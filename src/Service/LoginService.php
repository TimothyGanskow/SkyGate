<?php

namespace App\Service;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginService
{
    public function loginUser(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): array | bool
    {
        /* getManager for managing entity -> User -> find One User By email */
        $body = $request->getContent();
        $data = json_decode($body, true);
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $data["email"]]);

        /* If no User found -> Error 404 */
        if (!$user) {
            return [
                "message" => $data["email"],
                "statuscode" => 404
            ];
        }

        if (!$passwordHasher->isPasswordValid($user, $data["passwort"])) {
            return [
                "message" => "Login Failed",
                "statuscode" => 401
            ];
        } else {
            return [
                "message" => "Login Succes",
                "userID" => $user->getid(),
                "email" => $user->getEmail(),
                "isConfirmed" => $user->getMailToken() === "verify" ? true : false,
                "permission" => $user->getRoles(),
                "statuscode" => 200
            ];
        }
    }
}
