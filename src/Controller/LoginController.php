<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use App\Security\Jwt\JWTBuilder;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'login', methods: ["post"])]
    /* LOGIN USER */
    public function login(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        /* getManager for managing entity -> Users -> find One User By email */
        $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(Users::class)->findOneBy(['email' => $request->request->get("email")]);

        /* If no User found -> Error 404 */
        if (!$user) {
            return $this->json([
                "message" => $request->request->get("email"),
                "statuscode" => 404
            ]);
        }

        /* Else get PasswordHaser helper function */
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);
        $passwordHasher = $factory->getPasswordHasher('common');

        /* Build Session & RefreshToken */
        $jwtBuilder = new JWTBuilder($_ENV["SESSIONTOKEN_KEY"]);
        $jwtBuilderRefresh = new JWTBuilder($_ENV["REFRESHTOKEN_KEY"]);
        $sessionToken = $jwtBuilder->generateToken($user->getEmail());
        $refreshToken = $jwtBuilderRefresh->generateToken($user->getEmail());

        /* Set Refreshtoken in DB */
        $user->setRefreshToken($refreshToken);

        /* Safe */
        $entityManager->flush();

        /* Check Passwort in DB with request pw -> If true response 200 and other else response 400 */
        if ($passwordHasher->verify($user->getpasswort(), $request->request->get("passwort"))) {
            return $this->json([
                "message" => "Login Succes",
                "sessionToken" => $sessionToken,
                "refreshToken" => $refreshToken,
                "userID" => $user->getid(),
                "email" => $user->getEmail(),
                "isConfirmed" => $user->getRegistry()->isMailConfirmed(),
                "statuscode" => 200
            ]);
        } else {
            return $this->json([
                "statuscode" => 401,
                "message" => "Bitte erstmal registrieren"
            ]);
        }
    }


    #[Route('/loggout/{id}', name: 'loggout', methods: ["post"])]
    /* LOGGOUT USER */
    public function loggout(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        /* getManager for managing entity -> Users -> find One User By id */
        $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(Users::class)->find($id);

        /* IF User found -> set RefreshToken to NULL -> Logout */
        if (!$user) {
            return $this->json("No User found", 404);
        } else {
            $user->setRefreshToken(NULL);
            $entityManager->flush();
            return $this->json("User Logged out", 200);
        }
    }
}
