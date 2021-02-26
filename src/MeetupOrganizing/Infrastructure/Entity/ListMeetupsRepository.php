<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Domain\Entity\ListMeetupsRepository as EntityListMeetupsRepository;
use MeetupOrganizing\Domain\Entity\MeetupForList;
use MeetupOrganizing\Domain\Entity\ScheduledDate;
use PDO;

final class ListMeetupsRepository implements EntityListMeetupsRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return MeetupForList[]
     */
    public function listUpcoming(): array
    {
        $now = new DateTimeImmutable();

        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor >= :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute();
        // Assert::that($statement)->isInstanceOf(Statement::class);

        $upcomingMeetups = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return MeetupForList::existing(
                    $record['meetupId'],
                    $record['name'],
                    $record['scheduledFor']
                );
            },
            $upcomingMeetups
        );
    }

    /**
     * @return MeetupForList[]
     */
    public function listPast(): array
    {
        $now = new DateTimeImmutable();

        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor < :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute();
        // Assert::that($statement)->isInstanceOf(Statement::class);

        $upcomingMeetups = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return MeetupForList::existing(
                    $record['meetupId'],
                    $record['name'],
                    $record['scheduledFor']
                );
            },
            $upcomingMeetups
        );
    }
}
