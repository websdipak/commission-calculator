<?php

namespace App\DTO;

class Operation
{
    private string $type;
    private string $userType;
    private float $amount;
    private string $currency;

    public function __construct(string $date, string $userId, string $userType, string $type, string $amount, string $currency)
    {
        $this->date = new \DateTime($date);
        $this->userId = (int)$userId;
        $this->userType = $userType;
        $this->type = $type;
        $this->amount = (float)$amount;
        $this->currency = $currency;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
