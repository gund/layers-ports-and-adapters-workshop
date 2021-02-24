<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Controller;

use MeetupOrganizing\Domain\Entity\ListMeetupsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

final class ListMeetupsController implements MiddlewareInterface
{
    private ListMeetupsRepository $listMeetupsRepository;

    private TemplateRendererInterface $renderer;

    public function __construct(
        ListMeetupsRepository $listMeetupsRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->listMeetupsRepository = $listMeetupsRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        // $now = new DateTimeImmutable();

        // $statement = $this->connection->createQueryBuilder()
        //     ->select('*')
        //     ->from('meetups')
        //     ->where('scheduledFor >= :now')
        //     ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
        //     ->andWhere('wasCancelled = :wasNotCancelled')
        //     ->setParameter('wasNotCancelled', 0)
        //     ->execute();
        // Assert::that($statement)->isInstanceOf(Statement::class);

        // $upcomingMeetups = $statement->fetchAll(PDO::FETCH_ASSOC);

        // $statement = $this->connection->createQueryBuilder()
        //     ->select('*')
        //     ->from('meetups')
        //     ->where('scheduledFor < :now')
        //     ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
        //     ->andWhere('wasCancelled = :wasNotCancelled')
        //     ->setParameter('wasNotCancelled', 0)
        //     ->execute();
        // Assert::that($statement)->isInstanceOf(Statement::class);

        // $pastMeetups = $statement->fetchAll(PDO::FETCH_ASSOC);

        $upcomingMeetups = $this->listMeetupsRepository->listUpcoming();
        $pastMeetups = $this->listMeetupsRepository->listPast();

        $response->getBody()->write(
            $this->renderer->render(
                'list-meetups.html.twig',
                [
                    'upcomingMeetups' => $upcomingMeetups,
                    'pastMeetups' => $pastMeetups
                ]
            )
        );

        return $response;
    }
}
