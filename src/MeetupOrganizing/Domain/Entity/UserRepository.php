<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

interface UserRepository
{
    public function getById(UserId $id): User;

    /**
     * @return User[]
     */
    public function findAll(): array;

    public function getOrganizerId(): UserId;
}
