<?php

namespace App\Http\Controllers;

use App\Factories\ChargeFactory;
use App\Models\Charge;
use App\Repositories\LogRepository;
use App\Services\ChargeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChargeController extends Controller
{
    private ChargeService $chargeService;
    private Charge $charge;
    private ChargeFactory $chargeFactory;

    private array $chargeValidationRules = [
            'name' => 'required|max:255|string',
            'governmentId' => 'required|min:11|max:13|string',
            'email' => 'required|email',
            'debtAmount' => 'required|decimal:2|min:0.01|max:11',
            'debtDueDate' => 'required|date',
            'debtId' => 'required|integer|min:1|max:9999999999999'
    ];

    public function __construct(
        ChargeFactory $chargeFactory, 
        ChargeService $chargeService,
    ) {
        $this->chargeFactory = $chargeFactory;
        $this->chargeService = $chargeService;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        try {
            LogRepository::info('Get charge list..');
            return $this->chargeService->paginatedChargeList(env('CHARGE_PAGINATION'));
        } catch (Exception $e) {
            return $this->handleExceptionResponse($e);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate($this->chargeValidationRules);
            $charge = $this->chargeService->createCharge(
                $this->chargeFactory->createFromRequestData($request)
            );

            return response($charge, Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->handleExceptionResponse($e);
        }
    }
    
    /**
     * @return \Illuminate\Http\Response
     */
    public function sendChargeToCustomers()
    {
        try {
            $this->chargeService->sendChargeToCustomers();
            return response("", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->handleExceptionResponse($e);
        }
    }

    private function handleExceptionResponse(Exception $e)
    {
        $message = $e->getMessage();
        $code = $e->getCode() != 0? $e->getCode(): Response::HTTP_UNPROCESSABLE_ENTITY;

        LogRepository::warning('Could not create charge:' . $e->getMessage());

        if (isset($e->validator)) {
            $validator = $e->validator;
            $message = $validator->messages();
            $code = 400;
        }

        return response($message, $code);
    }
}
