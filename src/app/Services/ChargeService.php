<?php

namespace App\Services;

use App\Factories\ChargeMailFactory;
use App\Http\Requests\WebhookRequest;
use App\Models\Charge;
use App\Models\ChargeMail;
use App\Repositories\LogRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ChargeService
{
    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_PAID = 'paid';
    const STATUS_OVERPAID = 'overpaid';
    const STATUS_UNDERPAID = 'underpaid';
    const CHARGES_TABLE = 'charges';
    private BoletoService $paymentService;
    private Charge $charge;
    private EmailService $emailService;
    private ChargeMail $chargeMail;
    private ChargeMailFactory $chargeMailFactory;

    public function __construct(
        BoletoService $boletoService, 
        Charge $charge, 
        EmailService $emailService,
        ChargeMail $chargeMail,
        ChargeMailFactory $chargeMailFactory
    ) {
        $this->charge = $charge;
        $this->paymentService = $boletoService;
        $this->emailService = $emailService;
        $this->chargeMail = $chargeMail;
        $this->chargeMailFactory = $chargeMailFactory;
    }

    public function paginatedChargeList(Int $rowsPerPage = 1)
    {
        LogRepository::info('Returning charge list...');
        return Charge::simplePaginate($rowsPerPage);
    }

    public function createCharge(Charge $charge): Charge | null 
    {
        try {
            DB::beginTransaction();

            $createdCharge = $charge->firstOrCreate(
                ['debt_id' => $charge->debt_id],
                [
                    'name' => $charge->name,
                    'government_id' => $charge->government_id,
                    'email' => $charge->email,
                    'debt_amount' => $charge->debt_amount,
                    'debt_due_date' => $charge->debt_due_date,
                    'debt_id' => $charge->debt_id,
                    'paid_at' => $charge->paid_at,
                    'paid_amount' => $charge->paid_amount,
                    'paid_by' => $charge->paid_by
                ]
            );
            $this->paymentService->createChargePaymentMethod($createdCharge);

            DB::commit();

            LogRepository::info('Charge created for debt_id: ' . $createdCharge->debt_id);

            return $createdCharge;

        } catch (Exception $e) {
            DB::rollBack();

            LogRepository::warning(
                'Could not create charge: ' . 
                serialize($charge) . ' | ' . 
                $e->getMessage()
            );
            return null;
        }
    }

    public function sendChargeToCustomers()
    {
        try{
            LogRepository::info('Starting e-mail sending...');

            $chargeList =  
                Charge::with(
                    'boleto:charge_id,barcode,government_id,amount,debt_due_date'
                )
                ->where('status', self::STATUS_CREATED)
                ->get();

            $chargeMails = [];
            
            foreach($chargeList as $charge) {
                $chargeMails[] = $this->chargeMailFactory->createFromCharge($charge);
            }
            $successfulIds = $this->emailService->sendEmailList($chargeMails);

            Charge::whereIn("id", $successfulIds)
            ->update(['status' => self::STATUS_SENT]);

            LogRepository::info('...Finish e-mail sending');
        }catch(Exception $e){
            DB::rollBack();

            LogRepository::warning(
                'Could not send charge e-mails ' . 
                $e->getMessage()
            );
        }
    }

    public function getChargeFromDebtId(int $debtId):Charge
    {
        return Charge::where('debt_id', $debtId)->first();
    }

    public function payCharge(WebhookRequest $webhookRequest): string
    {
        try {
            $charge = Charge::where('debt_id', $webhookRequest->getDebtId())->first();
        
            $message = '';
    
            if (empty($charge)) {
                LogRepository::info('Charge not found. debtId: ' . $webhookRequest->getDebtId());
                throw new Exception('', Response::HTTP_NOT_FOUND);
            }
            
            if (
                $charge->status == self::STATUS_PAID ||
                $charge->status == self::STATUS_OVERPAID
            ) {
                LogRepository::info('Charge already paid. debtId: ' . $webhookRequest->getDebtId());
                throw new Exception('Charge already paid', Response::HTTP_OK);
            }
    
            $totalPaidAmount = $charge->paid_amount + $webhookRequest->getPaidAmount();
    
            if($totalPaidAmount > $charge->debt_amount) {
                LogRepository::info('Charge overpaid. debtId: ' . $webhookRequest->getDebtId());
                
                $charge->status = self::STATUS_OVERPAID;
                $message = 'Charge overpaid. Total paid amount: ' . $totalPaidAmount;
            }
    
            if($totalPaidAmount < $charge->debt_amount) {
                LogRepository::info('Charge underpaid. debtId: ' . $webhookRequest->getDebtId());
                
                $charge->status = self::STATUS_UNDERPAID;
                $message = 'Charge underpaid. Total paid amount: ' . $totalPaidAmount;
            }
    
            if($totalPaidAmount == $charge->debt_amount) {
                LogRepository::info('Charge paid. debtId: ' . $webhookRequest->getDebtId());
                $charge->status = self::STATUS_PAID;
            }
    
            $charge->paid_amount = $totalPaidAmount;
            $charge->paid_at = $webhookRequest->getPaidAt();
            $charge->paid_by = $webhookRequest->getPaidBy();
            $charge->save();

            return $message;
    
        } catch(Exception $e){   
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}