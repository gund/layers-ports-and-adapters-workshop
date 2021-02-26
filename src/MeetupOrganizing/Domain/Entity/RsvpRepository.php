<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

interface RsvpRepository
{
    public function save(Rsvp $rsvp): void;

    /**
     * @return Rsvp[]
     */
    public function getByMeetupId(int $meetupId): array;
}
