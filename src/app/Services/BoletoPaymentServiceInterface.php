<?php

namespace App\Services;
use App\Models\Boleto;
use App\Models\Charge;

interface BoletoPaymentServiceInterface
{
    public function createPaymentMethod(Charge $charge): Boleto;
}