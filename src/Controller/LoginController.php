<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\LoginService;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'login', methods: ["post"])]
    /* LOGIN USER */
    public function login(ManagerRegistry $doctrine, Request $request, LoginService $loginService,  UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        return $this->json($loginService->loginUser($doctrine, $request, $passwordHasher));
    }

}
