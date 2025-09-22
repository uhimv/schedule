<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\Schedule\GenerateHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScheduleController extends AbstractController
{
    public function __construct(private GenerateHandler $generateHandler)
    {
    }

    #[Route('/schedule/generate', name: 'app_schedule_generate')]
    public function generateAction(): Response
    {
        $schedule = $this->generateHandler->handle();

        return new JsonResponse(
            $schedule
        );
    }
}
