<?php

namespace App\Command;

use App\Helpers\DateHelpers;
use App\Repository\OrderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'orders:process-deliveries',
    description: 'Change the status of deliveries which are past their delivery date and stuck in the processing queue.',
)]
class OrdersProcessDeliveriesCommand extends Command
{
    private $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('process-delayed-deliveries', null, InputOption::VALUE_NONE, 'Find deliveries delayed after this date')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $processDelayedDeliveriesOption = $input->getOption('process-delayed-deliveries');

        if (!$processDelayedDeliveriesOption) {
            $io->warning('You must utilize one of the processor options');
        }


        if ($input->getOption('process-delayed-deliveries')) {
            $dateToCheck = $io->ask('Provide a date? (Format: YYYY-MM-DD)', '', function (string $dateToCheck) {
                if (empty($dateToCheck)) {
                    throw new \RuntimeException('Date can not be blank.');
                }

                if (!DateHelpers::checkDateFormat($dateToCheck)) {
                    throw new \RuntimeException('Date provided must match the defined format of YYYY-mm-dd');
                }

                return $dateToCheck;
            });

            $orderStatus = 'processing';

            $ordersFound = $this->orderRepository->findOrdersByDateAndStatus($dateToCheck, $orderStatus);

            $numberOfRecordsFound = count($ordersFound);

            $io->success($this->orderRepository->updateOrderStatusToDelayed($ordersFound));
        }

        return Command::SUCCESS;
    }
}
