<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Controller;

use Assert\Assert;
use Exception;
use MeetupOrganizing\Domain\Entity\ScheduledDate;
use MeetupOrganizing\Session;
use MeetupOrganizing\Application\UseCase\MeetupUseCase;
use MeetupOrganizing\Application\UseCase\ScheduleMeetupCommand;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ScheduleMeetupController
{
    private Session $session;

    private TemplateRendererInterface $renderer;

    private RouterInterface $router;

    private MeetupUseCase $meetupUseCase;

    public function __construct(
        Session $session,
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        MeetupUseCase $meetupUseCase
    ) {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->meetupUseCase = $meetupUseCase;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $formErrors = [];
        $formData = [
            // This is a nice place to set some defaults
            'scheduleForTime' => '20:00'
        ];

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();
            Assert::that($formData)->isArray();

            if (empty($formData['name'])) {
                $formErrors['name'][] = 'Provide a name';
            }
            if (empty($formData['description'])) {
                $formErrors['description'][] = 'Provide a description';
            }
            try {
                ScheduledDate::fromString(
                    $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
                );
            } catch (Exception $exception) {
                $formErrors['scheduleFor'][] = 'Invalid date/time';
            }

            if (empty($formErrors)) {
                // $record = [
                //     'organizerId' => $this->session->getLoggedInUser()->userId()->asInt(),
                //     'name' => $formData['name'],
                //     'description' => $formData['description'],
                //     'scheduledFor' => $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime'],
                //     'wasCancelled' => 0
                // ];
                // $this->connection->insert('meetups', $record);

                // $meetup = Meetup::create(
                //     $this->session->getLoggedInUser()->userId(),
                //     $formData['name'],
                //     $formData['description'],
                //     ScheduledDate::fromString($formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']),
                //     0
                // );

                // $this->meetupRepository->save($meetup);

                $scheduleMeetupCommand = new ScheduleMeetupCommand(
                    $this->session->getLoggedInUser()->userId()->asInt(),
                    $formData['name'],
                    $formData['description'],
                    $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
                );

                $meetupId = $this->meetupUseCase->schedule(
                    $scheduleMeetupCommand
                );

                $this->session->addSuccessFlash('Your meetup was scheduled successfully');

                return new RedirectResponse(
                    $this->router->generateUri(
                        'meetup_details',
                        [
                            'id' => $meetupId
                        ]
                    )
                );
            }
        }

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                [
                    'formData' => $formData,
                    'formErrors' => $formErrors
                ]
            )
        );

        return $response;
    }
}
