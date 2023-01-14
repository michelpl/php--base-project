<?php

namespace App\Factories;

use App\Models\ChargeMail;
use App\Models\Charge;

interface ChargeMailFactoryInterface
{
    public function CreateFromCharge(Charge $charge): ChargeMail;
}