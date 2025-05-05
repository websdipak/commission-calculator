<?php

namespace App\Service;

use App\DTO\Operation;

class CommissionCalculator
{
    private ExchangeRateService $exchangeRateService;
    private CommissionRulesService $commissionRulesService;

    public function __construct(ExchangeRateService $exchangeRateService, CommissionRulesService $commissionRulesService)
    {
        $this->exchangeRateService = $exchangeRateService;
        $this->commissionRulesService = $commissionRulesService;
    }

    public function calculate(Operation $operation): float
    {
        // Convert amount to EUR if necessary
        if ($operation->getCurrency() !== 'EUR') {
            // Get the exchange rate for the given currency to EUR
            $exchangeRate = $this->exchangeRateService->getExchangeRate($operation->getCurrency(), 'EUR');
            // Convert the amount to EUR
            $amountInEUR = $operation->getAmount() * $exchangeRate;
        } else {
            // If already in EUR, no conversion needed
            $amountInEUR = $operation->getAmount();
        }

        // Apply commission rules (now using the EUR amount)
        $commission = $this->commissionRulesService->applyRules($operation, $amountInEUR);

        // Round commission to 2 decimal places
        return round($commission, 2);
    }
}
