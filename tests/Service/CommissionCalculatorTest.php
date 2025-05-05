<?php

namespace App\Tests\Service;

use App\DTO\Operation;
use App\Service\CommissionCalculator;
use App\Service\CommissionRulesService;
use App\Service\ExchangeRateService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CommissionCalculatorTest extends TestCase
{
    public function testCommissionCalculation(): void
    {
        // Mock the response to simulate the API call
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')
            ->willReturn([
                'rates' => ['EUR' => 1.0]
            ]);

        // Mock the HttpClient
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->method('request')
            ->willReturn($responseMock);

        // Create services with mocks
        $exchangeRateService = new ExchangeRateService($httpClientMock);
        $commissionRulesService = new CommissionRulesService();
        $calculator = new CommissionCalculator($exchangeRateService, $commissionRulesService);

        // Create a test operation
        $operation = new Operation(
            date: '2024-04-29',
            userId: '1',
            userType: 'private',
            type: 'withdraw',
            amount: '1000.00',
            currency: 'EUR'
        );

        // Run the calculation
        $result = $calculator->calculate($operation);

        // Assert result is as expected (based on your rules logic)
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }
}
