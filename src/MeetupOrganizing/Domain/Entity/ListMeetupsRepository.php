<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

interface ListMeetupsRepository
{
    /**
     * @return MeetupForList[]
     */
    public function listUpcoming(): array;

    /**
     * @return MeetupForList[]
     */
    public function listPast(): array;
}
