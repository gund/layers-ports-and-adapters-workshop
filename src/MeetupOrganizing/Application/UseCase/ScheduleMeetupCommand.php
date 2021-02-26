<?php

declare(strict_types=1);

namespace MeetupOrganizing\Application\UseCase;

use MeetupOrganizing\Domain\Entity\ScheduledDate;
use MeetupOrganizing\Domain\Entity\UserId;

final class ScheduleMeetupCommand
{
  private int $organizerId;
  private string $name;
  private string $description;
  private string $scheduledFor;

  public function __construct(
    int $organizerId,
    string $name,
    string $description,
    string $scheduledFor
  ) {
    $this->organizerId = $organizerId;
    $this->name = $name;
    $this->description = $description;
    $this->scheduledFor = $scheduledFor;
  }

  public function organizerId()
  {
    return UserId::fromInt($this->organizerId);
  }

  public function name()
  {
    return $this->name;
  }

  public function description()
  {
    return $this->description;
  }

  public function scheduledFor()
  {
    return ScheduledDate::fromString($this->scheduledFor);
  }
}
