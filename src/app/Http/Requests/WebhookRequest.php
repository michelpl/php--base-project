<?php

namespace App\Http\Requests;
use Carbon\Traits\Timestamp;

class WebhookRequest
{
    private int $debtId;
    private string $paidAt;
    private float $paidAmount;
    private string $paidBy;

    public function getDebtId(): int
    {
        return $this->debtId;
    }

    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    public function getPaidAmount(): float
    {
        return $this->paidAmount;
    }

    public function getPaidBy(): string
    {
        return $this->paidBy;
    }

    public function setDebtId(int $debtId):self
    {
        $this->debtId = $debtId;
        return $this;
    }

    public function setPaidAt(string $paidAt):self
    {
        $this->paidAt = date($paidAt);
        return $this;
    }

    public function setPaidAmount(float $paidAmount):self
    {
        $this->paidAmount = $paidAmount;
        return $this;
    }

    public function setPaidBy(string $paidBy):self
    {
        $this->paidBy = $paidBy;
        return $this;
    }
}