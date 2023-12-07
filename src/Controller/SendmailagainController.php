<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Service\MailService;
use Exception;

class SendmailagainController extends AbstractController
{

    /* SEND AN EMAIL AGAIN WHEN USER FORGET OR HAS NOT GOT AN VERIFY EMAIL */
    #[Route('/mailagain', name: 'app_sendmailagain', methods: ["post"])]
    public function index(ManagerRegistry $doctrine, Request $request, MailService $mailService): JsonResponse
    {
        return $this->json($mailService->sendMailAgain($doctrine, $request));
    }
}
