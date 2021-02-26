<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Command;

use Assert\Assert;
use MeetupOrganizing\Application\UseCase\MeetupUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MeetupOrganizing\Application\UseCase\ScheduleMeetupCommand as ScheduleMeetupCommandUseCase;

final class ScheduleMeetupCommand extends Command
{
    private MeetupUseCase $meetupUseCase;

    public function __construct(
        MeetupUseCase $meetupUseCase
    ) {
        parent::__construct();

        $this->meetupUseCase = $meetupUseCase;
    }

    protected function configure(): void
    {
        $this->setName('schedule')
            ->setDescription('Schedule a meetup')
            ->addArgument('organizerId', InputArgument::REQUIRED, 'Organizer ID')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the meetup')
            ->addArgument('description', InputArgument::REQUIRED, 'Description of the meetup')
            ->addArgument('scheduledFor', InputArgument::REQUIRED, 'Scheduled for');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // InputInterface isn't particularly well-typed, so we need to add some checks
        $organizerId = $input->getArgument('organizerId');
        Assert::that($organizerId)->string();


        $name = $input->getArgument('name');
        Assert::that($name)->string();

        $description = $input->getArgument('description');
        Assert::that($description)->string();

        $scheduledFor = $input->getArgument('scheduledFor');
        Assert::that($scheduledFor)->string();

        // $record = [
        //     'organizerId' => (int)$organizerId,
        //     'name' => $name,
        //     'description' => $description,
        //     'scheduledFor' => $scheduledFor
        // ];

        // $this->connection->insert('meetups', $record);

        $scheduleMeetupCommand = new ScheduleMeetupCommandUseCase(
            (int)$organizerId,
            $name,
            $description,
            $scheduledFor
        );

        $this->meetupUseCase->schedule(
            $scheduleMeetupCommand
        );

        $output->writeln('<info>Scheduled the meetup successfully</info>');

        return 0;
    }
}
