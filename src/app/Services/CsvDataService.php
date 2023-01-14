<?php

namespace App\Services;

use App\Factories\CsvDataFactory;
use App\Models\Charge;
use App\Models\CsvData;
use App\Repositories\LogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use App\Factories\ChargeFactory;
use App\Services\BoletoService;
use App\Services\ChargeService;
use Exception;
                  
class CsvDataService
{
    const CSV_FILE_TABLE = 'csv_data';
    private CsvDataFactory $csvDataFactory;
    private ChargeFactory $chargeFactory;
    private CsvData $csvData;
    private Charge $charge;
    private BoletoService $boletoService;
    private ChargeService $chargeService;

    public function __construct(
        CsvDataFactory $csvDataFactory, 
        ChargeFactory $chargeFactory,
        CsvData $csvData, 
        Charge $charge,
        BoletoService $boletoService,
        ChargeService $chargeService
    ) {
        $this->csvDataFactory = $csvDataFactory;
        $this->chargeFactory = $chargeFactory;
        $this->csvData = $csvData;
        $this->charge = $charge;
        $this->boletoService = $boletoService;
        $this->chargeService = $chargeService;
    }
    
    public function createFromRequestData(Request $request)
    {
        try {
            LogRepository::info('Saving CSV file on database...');

            $csvData = $this->csvDataFactory->createFromRequestData($request);
            DB::beginTransaction();
            $csvData->upsert(
                [
                'csv_file_hash' => $csvData->csv_file_hash,
                'csv_filename' => $csvData->csv_filename,
                'csv_header' => $csvData->csv_header,
                'csv_data' => $csvData->csv_data
                ], 'csv_file_hash'
            );

            DB::commit();
            LogRepository::info('CSV file saved:' . $csvData->csv_filename);

        } catch (Exception $e) {
            DB::rollBack();
            LogRepository::warning('Could not save CSV file on database: ' . $e->getMessage());
            throw new Exception('Could not save CSV file on database', 422);
        }
    }
    
    public function createChargeFromDatabase():void
    {
        try {
            $csvFiles = $this->getCsvFilesFromDatabase();

            if($csvFiles->count() == 0) {
                LogRepository::info("No files for migration");
            }
            LogRepository::info("Starting CSV igration...");

            foreach ($csvFiles as $csvFile) {
                LogRepository::info("CSV resource id: " . $csvFile->id);

                if($this->createCharges($csvFile)) {
                    $this->updateCsvStatus($csvFile->id);
                }
            }

            LogRepository::info("CSV migration finished...");
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function createCharges(stdClass $csvFile): bool
    {
        if (empty($csvFile->csv_header) || empty($csvFile->csv_data)) {
            LogRepository::warning("Missing csv_header or csv_data on csv saved on database: " . serialize($csvFile));
            throw new Exception("Missing csv_header or csv_data on csv saved on database");
        }
        $data = json_decode($csvFile->csv_data);
        $header = json_decode($csvFile->csv_header);

        LogRepository::info("Creating charges from CSV data...");

        $success = true;

        foreach($data as $chargeArray){
            $charge = $this->chargeFactory->createFromArray(
                array_combine($header, $chargeArray)
            );
            if(!$this->chargeService->createCharge($charge)) {
                $success = false;
            }
        }

        return $success;
    }

    private function updateCsvStatus(int $csvFileId): void
    {
        if(!DB::table(self::CSV_FILE_TABLE)->where('id', $csvFileId)->update(['status' => 'migrated'])
        ) {
            LogRepository::info("Could not update the csv file status for csv file id: " . $csvFileId);
        }
    }

    private function getCsvFilesFromDatabase(): Collection
    {
        return DB::table(self::CSV_FILE_TABLE)
            ->oldest()
            ->where('status', 'pending')
            ->limit(env('CSV_PROCESSING_LIMIT', 1))
            ->get();
    }

    public function validateHttpRequest(Request $request): bool
    {
        if (empty($request->file('csv_file'))) {
            throw new Exception('Empty csv_file field', Response::HTTP_BAD_REQUEST);
        }

        if ($request->file('csv_file')->getClientOriginalExtension() != env('DATA_FILE_EXTENSION')
        ) {
            throw new Exception(
                'Not supported file extension: ' .
                $request->file('csv_file')->getClientOriginalExtension(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($request->file('csv_file')->getSize() > env('MAX_DATA_FILE_SIZE_IN_BYTES')) {
            throw new Exception(
                env('DATA_FILE_EXTENSION') . 
                        " file size should be shorter than" . 
                        env('MAX_DATA_FILE_SIZE_IN_BYTES') . 
                        ' bytes', 
                Response::HTTP_UNPROCESSABLE_ENTITY
            );     
        }

        return true;
    }
}