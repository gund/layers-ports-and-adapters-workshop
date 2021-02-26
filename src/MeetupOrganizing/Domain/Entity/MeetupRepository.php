<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

interface MeetupRepository
{
    public function save(Meetup $meetup): void;
    public function getNextId(): UuidInterface;
}
