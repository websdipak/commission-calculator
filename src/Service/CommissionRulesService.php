<?php

namespace App\Service;

use App\DTO\Operation;

class CommissionRulesService
{
    
    public function applyRules(Operation $operation, float $amountInEUR): float
    {
        $commission = 0.0;

        // Apply deposit rule: 0.03% commission on deposit amount
        if ($operation->getType() === 'deposit') {
            $commission = $amountInEUR * 0.0003; // 0.03% commission
        }

        // Apply withdraw rules: different logic for private and business users
        if ($operation->getType() === 'withdraw') {
            if ($operation->getUserType() === 'private') {
                // Example: Private user withdraws with a 0.3% commission
                $commission = $amountInEUR * 0.003; // 0.3% commission for private users
            } elseif ($operation->getUserType() === 'business') {
                // Example: Business user withdraws with a 0.5% commission
                $commission = $amountInEUR * 0.005; // 0.5% commission for business users
            }
        }

        return (float) $commission;
    }
}
