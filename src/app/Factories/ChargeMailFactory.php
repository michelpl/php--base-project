<?php
namespace App\Factories;

use App\Factories\ChargeMailFactoryInterface;
use App\Models\Charge;
use App\Models\ChargeMail;

class ChargeMailFactory implements ChargeMailFactoryInterface
{
    public function __construct()
    {
    }
    
    public function CreateFromCharge(Charge $charge): ChargeMail
    {
        $chargeMail = new ChargeMail;
        $chargeMail->charge_id = $charge->id;
        $chargeMail->customerEmailAdress = $charge->email;
        $chargeMail->emailBody = $this->buildMailBody($charge);

        return $chargeMail;
    }

    private function buildMailBody(Charge $charge): string
    {
        return
            "Boleto para pagamento " . PHP_EOL .
            "Documento: " . $charge->boleto->government_id . PHP_EOL .
            "Data de vencimento: " . date("d/m/Y", strtotime($charge->boleto->debt_due_date)) . PHP_EOL .
            "Código de barras para pagamento: " . $charge->boleto->barcode . PHP_EOL
        ;
    }
    
}