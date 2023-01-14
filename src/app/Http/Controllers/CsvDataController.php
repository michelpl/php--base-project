<?php

namespace App\Http\Controllers;

use App\Factories\CsvDataFactory;
use App\Models\CsvData;
use App\Repositories\LogRepository;
use App\Services\CsvDataService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CsvDataController extends Controller
{
    private CsvData $csvData;
    private CsvDataFactory $csvDataFactory;
    private CsvDataService $csvDataService;

    public function __construct(CsvDataFactory $csvDataFactory, CsvData $csvData, CsvDataService $csvDataService)
    {
        $this->csvData = $csvData;
        $this->csvDataFactory = $csvDataFactory;
        $this->csvDataService = $csvDataService;
    }

    public function store(Request $request)
    {
        try {
            $this->csvDataService->validateHttpRequest($request);
            $this->csvDataService->createFromRequestData($request);

            return response(null, Response::HTTP_CREATED);

        } catch(Exception $e) {
            LogRepository::warning('Could not save csv file on database: ' . $e->getMessage());
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function createChargeFromCSVDatabase()
    {
        try {
            $this->csvDataService->createChargeFromDatabase();
            
            return response('', Response::HTTP_CREATED);
            
        } catch(Exception $e) {
            LogRepository::warning('Could not create charges from csv file saved on database: ' . $e->getMessage());
            return response('Could not create charges from csv_file saved on database', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
