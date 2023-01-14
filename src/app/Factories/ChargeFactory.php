<?php

namespace App\Factories;

use App\Models\Charge;
use App\Repositories\LogRepository;
use Illuminate\Http\Request;
use App\Factories\FactoryInterface;

class ChargeFactory implements FactoryInterface
{
    const CHARGE_FIELDS = [
        'name',
        'government_id',
        'email',
        'debt_amount',
        'debt_due_date',
        'debt_id',
        'paid_at',
        'paid_amount',
        'paid_by'
    ];

    private Charge $charge;
    public function __construct(Charge $charge)
    {
        $this->charge = $charge;
    }

    public function createFromRequestData(Request $requestData): Charge
    {
        $this->charge->debt_id = $requestData->debtId;
        $this->charge->name = $requestData->name;
        $this->charge->government_id = preg_replace('/[^0-9]/', '', $requestData->governmentId);
        $this->charge->email = $requestData->email;
        $this->charge->debt_amount = $requestData->debtAmount;
        $this->charge->debt_due_date = $requestData->debtDueDate;

        return $this->charge;
    }

    public function createFromArray(array $csvRow): Charge
    {
        $charge = new Charge;
        $charge->debt_id = (int) $csvRow['debtId'];
        $charge->name = $csvRow['name'];
        $charge->government_id = preg_replace('/[^0-9]/', '', $csvRow['governmentId']);
        $charge->email = $csvRow['email'];
        $charge->debt_amount = $csvRow['debtAmount'];
        $charge->debt_due_date = $csvRow['debtDueDate'];

        return $charge;
    }
}