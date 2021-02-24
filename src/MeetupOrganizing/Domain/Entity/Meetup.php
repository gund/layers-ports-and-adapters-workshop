<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

use Assert\Assert;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

final class Meetup
{
    private UuidInterface $meetupId;
    private UserId $organizerId;
    private string $name;
    private string $description;
    private ScheduledDate $scheduledFor;
    private bool $wasCancelled;

    public function __construct(
        UuidInterface $meetupId,
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor,
        bool $wasCancelled
    ) {
        $this->meetupId = $meetupId;
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledFor = $scheduledFor;
        $this->wasCancelled = $wasCancelled;
    }

    public static function create(
        UuidInterface $meetupId,
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor,
        bool $wasCancelled,
        DateTimeInterface $currentDate
    ): Meetup {
        if (
            !$scheduledFor->isInTheFuture($currentDate)
        ) {
            throw new InvalidArgumentException("Meetup cannot be scheduled for future date");
        }

        return self::existing($meetupId, $organizerId, $name, $description, $scheduledFor, $wasCancelled);
    }

    public static function existing(
        UuidInterface $meetupId,
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor,
        bool $wasCancelled
    ) {
        Assert::that($name)->notBlank('Meetup name cannot be empty');
        Assert::that($description)->notBlank('Meetup description cannot be empty');

        return new self($meetupId, $organizerId, $name, $description, $scheduledFor, $wasCancelled);
    }

    public function getUuid(): UuidInterface
    {
        return $this->meetupId;
    }

    public function getData()
    {
        return [
            'meetupId' => $this->meetupId->toString(),
            'organizerId' => $this->organizerId->asInt(),
            'name' => $this->name,
            'description' => $this->description,
            'scheduledFor' => $this->scheduledFor->asString(),
            'wasCancelled' => (int)$this->wasCancelled,
        ];
    }
}
