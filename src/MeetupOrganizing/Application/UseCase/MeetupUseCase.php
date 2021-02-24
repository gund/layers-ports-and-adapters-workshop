<?php

declare(strict_types=1);

namespace MeetupOrganizing\Application\UseCase;

use DateTimeImmutable;
use MeetupOrganizing\Domain\Entity\Meetup;
use MeetupOrganizing\Domain\Entity\MeetupRepository;

final class MeetupUseCase
{

  private MeetupRepository $meetupRepository;

  public function __construct(
    MeetupRepository $meetupRepository
  ) {
    $this->meetupRepository = $meetupRepository;
  }

  public function schedule(
    ScheduleMeetupCommand $scheduleMeetupCommand
  ) {
    $currentDate = new DateTimeImmutable();
    $meetupId = $this->meetupRepository->getNextId();

    $meetup = Meetup::create(
      $meetupId,
      $scheduleMeetupCommand->organizerId(),
      $scheduleMeetupCommand->name(),
      $scheduleMeetupCommand->description(),
      $scheduleMeetupCommand->scheduledFor(),
      false,
      $currentDate
    );

    $this->meetupRepository->save($meetup);

    return $meetupId->toString();
  }
}
