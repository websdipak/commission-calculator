<?php
// src/Service/ExchangeRateService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        // If both currencies are EUR, we return 1.0 directly
        if ($fromCurrency === 'EUR' && $toCurrency === 'EUR') {
            return 1.0;
        }

        // If fromCurrency is EUR, we return 1.0 since EUR to EUR is no conversion
        if ($fromCurrency === 'EUR') {
            return 1.0;
        }

        // If the target currency is EUR, we need to reverse the conversion
        if ($toCurrency === 'EUR') {
            return $this->getReverseExchangeRate($fromCurrency);
        }

        // Standard conversion: Get rate from fromCurrency to toCurrency
        return $this->getDirectExchangeRate($fromCurrency, $toCurrency);
    }

    // Fetches exchange rate for non-EUR to EUR conversion
    private function getReverseExchangeRate(string $fromCurrency): float
    {
        try {
            $response = $this->httpClient->request('GET', 'https://api.exchangeratesapi.io/latest', [
                'query' => [
                    'base' => $fromCurrency,
                    'symbols' => 'EUR',
                ],
            ]);

            $data = $response->toArray();
            if (!isset($data['rates']['EUR'])) {
                // Log a warning and return a fallback rate if EUR conversion is unavailable
                // You could also set a default exchange rate here if you want to continue operations
                throw new \Exception("Exchange rate for EUR from {$fromCurrency} not found.");
            }

            // Reverse the exchange rate to get from another currency to EUR
            return 1 / $data['rates']['EUR'];
        } catch (\Exception $e) {
            // Log the error and provide a fallback rate (e.g., 0.007, this depends on your application logic)
            // Alternatively, you can set up a better default fallback rate or exit gracefully.
            // For now, we'll log the error and return a fallback rate (you can adjust this logic).
            $this->logError("Failed to fetch exchange rate for {$fromCurrency} to EUR: " . $e->getMessage());
            return 0.007; // A fallback rate (e.g., USD -> EUR rate, you can replace it with another default value)
        }
    }

    // Fetches exchange rate for fromCurrency to toCurrency
    private function getDirectExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        try {
            $response = $this->httpClient->request('GET', 'https://api.exchangeratesapi.io/latest', [
                'query' => [
                    'base' => $fromCurrency,
                    'symbols' => $toCurrency,
                ],
            ]);

            $data = $response->toArray();
            if (!isset($data['rates'][$toCurrency])) {
                throw new \Exception("Exchange rate for {$toCurrency} from {$fromCurrency} not found.");
            }

            return $data['rates'][$toCurrency];
        } catch (\Exception $e) {
            // Log the error and provide a fallback rate if the direct conversion fails
            $this->logError("Failed to fetch exchange rate for {$fromCurrency} to {$toCurrency}: " . $e->getMessage());
            return 0.007; // Fallback rate here
        }
    }

    // Converts the amount from any currency to EUR
    public function convertToEUR(float $amount, string $fromCurrency): float
    {
        // Call getExchangeRate to get the conversion rate from $fromCurrency to EUR
        $exchangeRate = $this->getExchangeRate($fromCurrency, 'EUR');
        return $amount * $exchangeRate;
    }

    private function logError(string $message)
    {
        // In a real application, you'd want to log this to a file or monitoring system
        // For now, we'll just print it for debugging
        echo "[ERROR] " . $message . "\n";
    }
}
