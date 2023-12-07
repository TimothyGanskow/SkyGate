<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailService;
use App\Service\UserService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{

    /* GET ALL USERS */
    #[Route('/user/getall', name: 'getAll_user', methods: ["get"])]
    public function index(ManagerRegistry $doctrine, Request $request, UserService $userService): JsonResponse
    {
        $data = $userService->findAllUsers($doctrine);
        return $this->json($data);
    }


    /* GET USER BY ID */
    #[Route('/user/getone/{id}', name: 'get_user_by_ID', methods: ["get"])]
    public function show(ManagerRegistry $doctrine, int $id, Request $request, UserService $userService): JsonResponse
    {
        $data = $userService->findOneUserByID($doctrine, $id);
        return $this->json($data);
    }


    /* CREATE USER */
    #[Route('/user/create', name: 'create_user', methods: ["post"])]
    public function create(ManagerRegistry $doctrine, Request $request, MailService $mailService, UserService $userService, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = $userService->CreateUser($doctrine, $request, $mailService, $passwordHasher);
        return $this->json($data);
    }



    /* UPDATE USER */
    #[Route('/user/patch/{id}', name: 'update_user', methods: ["patch"])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id, UserService $userService, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = $userService->UpdateUser($doctrine, $request, $id, $passwordHasher);
        return $this->json($data);
    }


    /* DELETE USER */
    #[Route('/user/delete/{id}', name: 'delete_user', methods: ["delete"])]
    public function delete(ManagerRegistry $doctrine, int $id, UserService $userService): JsonResponse
    {
        $data = $userService->DeleteUser($doctrine, $id);
        return $this->json($data);
    }
}
