# Commission Fee Calculator

This is a PHP-based CLI application that reads operations from a CSV file and calculates commission fees according to specific business rules for private and business clients.

 Features
- Handles deposit and withdraw operations
- Calculates commission fees using defined business logic
- Supports multiple currencies with live exchange rate conversion
- Follows PSR-4 autoloading and PSR-12 coding standards
- Designed for maintainability and extensibility
- Includes a simple automation test

-------------------------
 Project Structure
-------------------------

src/
│
├── Command/          - Console command to run the app
├── DTO/              - Data Transfer Object (Operation)
├── Service/          - Business logic for commissions
├── Utils/            - Currency conversion logic
|
input.csv             - Input file with operations
tests/                - Automation test
composer.json         - Autoload configuration
script.php            - Optional entry point
README.md             - This file

-------------------------
 Getting Started
-------------------------

1. Install dependencies

   composer install

3. Run the application

   php bin/console app:calculate-commission

   Note: The command reads from 'input.csv' located in the root directory.

-------------------------
 Running Tests
-------------------------

Run the test script to validate calculations:

   php tests/Service/CommissionCalculatorTest.php

This script compares expected and actual output using provided CSV input.

-------------------------
 Commission Rules
-------------------------

Deposit (All Clients)
- 0.03% commission fee on deposit amount

Withdraw - Private Clients
- 0.3% commission fee
- First 3 withdrawals per week are free up to 1000.00 EUR
- Only the exceeded amount is charged
- Non-EUR operations are converted using: https://api.exchangeratesapi.io/latest

Withdraw - Business Clients
- 0.5% commission fee
- No free limit

-------------------------
 Currency Conversion
-------------------------

- Uses Exchange Rates API (https://api.exchangeratesapi.io/latest)
- Fees are always calculated in the currency of the operation
- Rounding is done upwards based on currency decimal precision
  Example: 0.023 EUR becomes 0.03 EUR

-------------------------
 Requirements
-------------------------

- PHP 8.0+
- Composer
- Internet connection for exchange rate fetching

-------------------------
 Extensibility Notes
-------------------------

- To support new currencies, extend or enhance the converter logic
- To add new rules or user types, modify CommissionRulesService
- Follows SOLID and modular principles for easy changes

-------------------------
 Coding Standards
-------------------------

- PSR-4 for autoloading (via composer.json)
- PSR-12 code formatting
- No database or temp files used
- Pure in-memory processing

-------------------------
 Author Notes
-------------------------

- (Comp. name) name is not used in any code, class, or description
- Frameworks are avoided unless needed
- System is self-contained and runs via CLI
- All features are implemented per task specification

