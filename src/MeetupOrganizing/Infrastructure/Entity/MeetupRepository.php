<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Entity;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Domain\Entity\Meetup;
use MeetupOrganizing\Domain\Entity\MeetupRepository as EntityMeetupRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class MeetupRepository implements EntityMeetupRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Meetup $meetup): void
    {
        $record = $meetup->getData();

        $this->connection->insert('meetups', $record);
    }

    public function getNextId(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
