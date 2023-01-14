<?php

namespace App\Services;

use App\Models\Boleto;
use App\Models\Charge;
use App\Repositories\LogRepository;
use App\Services\BoletoPaymentServiceInterface;
use Faker\Provider\sv_SE\Payment;
use TheSeer\Tokenizer\Exception;

class BoletoService implements BoletoPaymentServiceInterface
{
    private Boleto $boleto;
    public function __construct(Boleto $boleto)
    {
        $this->boleto = $boleto;
    }

    public function createPaymentMethod(Charge $charge): Boleto
    {
        //Replace for external service interface
        $boleto = new Boleto;
        $boleto->barcode = (string) random_int(999999999, 9999999999999);
        $boleto->government_id = $charge->government_id;
        $boleto->charge_id = $charge->id;
        $boleto->amount = $charge->debt_amount;
        $boleto->debt_due_date = $charge->debt_due_date; 
        
        return $boleto;
    }

    public function createChargePaymentMethod(Charge $charge)
    {
        try{
            $payment = $this->createPaymentMethod($charge);

            $payment->firstOrCreate(
                ['charge_id' => $payment->charge_id],
                [
                    'barcode' => $payment->barcode,
                    'government_id' => $payment->government_id,
                    'charge_id' => $payment->charge_id,
                    'amount' => $payment->amount,
                    'debt_due_date' => $payment->debt_due_date,
                ]
            );
        }catch(Exception $e){
            LogRepository::warning(
                'Could not create payment method for charge: ' . 
                serialize($charge) . ' | ' . 
                $e->getMessage()
            );

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}