<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

final class MeetupForList
{
  private string $meetupId;
  private string $name;
  private string $scheduledFor;

  public function __construct(
    string $meetupId,
    string $name,
    string $scheduledFor
  ) {
    $this->meetupId = $meetupId;
    $this->name = $name;
    $this->scheduledFor = $scheduledFor;
  }

  public static function existing(
    string $meetupId,
    string $name,
    string $scheduledFor
  ): MeetupForList {
    return new self(
      $meetupId,
      $name,
      $scheduledFor
    );
  }

  public function meetupId()
  {
    return $this->meetupId;
  }

  public function name()
  {
    return $this->name;
  }

  public function scheduledFor()
  {
    return $this->scheduledFor;
  }
}
