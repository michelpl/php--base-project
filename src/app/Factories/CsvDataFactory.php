<?php

namespace App\Factories;

use App\Models\CsvData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use TheSeer\Tokenizer\Exception;
use App\Factories\FactoryInterface;

class CsvDataFactory implements FactoryInterface
{
    private CsvData $csvData;
    public function __construct(CsvData $csvData)
    {
        $this->csvData = $csvData;
    }

    public function createFromRequestData(Request $requestData): CsvData
    {
        $data = array_map('str_getcsv', file($requestData->file('csv_file')->getRealPath()));
        $header = array_shift($data);

        if (empty($data)) {
            throw new \Exception("Empty csv file");
        }
        $this->csvData->csv_file_hash = hash_file('md5', $requestData->file('csv_file')->getRealPath());
        $this->csvData->csv_filename = $requestData->file('csv_file')->getClientOriginalName();
        $this->csvData->csv_header = json_encode($header);
        $this->csvData->csv_data = json_encode($data);

        return $this->csvData;
    }
}