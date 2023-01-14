<?php

namespace App\Services;

use App\Models\ChargeMail;
use App\Models\Charge;
use App\Repositories\LogRepository;
use Illuminate\Support\Facades\Log;

class EmailService
{
    private ChargeMail $chargeMail;
    private Charge $charge;

    public function __construct(Charge $charge, ChargeMail $chargeMail)
    {
        $this->chargeMail = $chargeMail;
        $this->charge = $charge;
    }

    private function stmpService(array $emailList): array
    {
        $successfulIds = [];
        //Replace for an external service interface
        foreach($emailList as $email){
            $successfulIds[] = $email->charge_id;
            LogRepository::info("Sending e-mail to: " . $email->customerEmailAdress . PHP_EOL . ' Content: ' . $email->emailBody);
        }

        return $successfulIds;
    }

    /** 
     * @todo Replace for a queue consumer
     * */    
    public function sendEmailList(array $chargeMailList): array
    {
        return $this->stmpService($chargeMailList);
    }
}