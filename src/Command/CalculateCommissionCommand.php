<?php
namespace App\Command;

use App\Service\CommissionCalculator;
use App\DTO\Operation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCommissionCommand extends Command
{
    private CommissionCalculator $calculator;

    public function __construct(CommissionCalculator $calculator)
    {
        parent::__construct();
        $this->calculator = $calculator;
    }

    protected function configure(): void
    {
        $this->setName('app:calculate-commission')
             ->setDescription('Calculates commission fees from a CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvFile = 'input.csv';
        $handle = fopen($csvFile, 'r');

        if ($handle === false) {
            $output->writeln('<error>Failed to open CSV file.</error>');
            return Command::FAILURE;
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $operation = new Operation(...$data);
            $commission = $this->calculator->calculate($operation);
            $output->writeln($commission);
        }

        fclose($handle);
        return Command::SUCCESS;
    }
}
