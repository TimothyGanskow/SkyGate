<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use App\Security\Jwt\JWTBuilder;
use App\Entity\User;
use App\Form\RegistryType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserService extends AbstractController
{
    public function findAllUsers(ManagerRegistry $doctrine): array
    {

        /* Use the findAll() function to get every entry */
        $users = $doctrine->getRepository(User::class)->findAll();
        $data = [];

        /* loop through the found $users object and create a separate field in $data for each item in it*/
        foreach ($users as $user) {
            $data[] = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getName(),
                "telefon" => $user->getTelefon(),
                "postcode" => $user->getPostcode(),
                "place" => $user->getPlace(),
                "permission" => $user->getRoles(),
                "status" => 201
            ];
        }

        return $data;
    }


    /* Find User by ID */
    public function findOneUserByID(ManagerRegistry $doctrine, int $id): array | bool
    {
        /* Use the findAll() function to get every entry */
        $user = $doctrine->getRepository(User::class)->find($id);
        if ($user) {
            $data = [];
            /* loop through the found $users object and create a separate field in $data for each item in it*/
            $data = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getName(),
                "telefon" => $user->getTelefon(),
                "postcode" => $user->getPostcode(),
                "place" => $user->getPlace(),
                "permission" => $user->getRoles(),
                "code" => 201
            ];

            return $data;
        } else {
            return [
                "message" => "user $id not found",
                "code" => 404
            ];
        }
    }


    /* Create a new User*/
    public function CreateUser(ManagerRegistry $doctrine, Request $request, MailService $mailService, UserPasswordHasherInterface $passwordHasher): array | bool
    {
        $entityManager = $doctrine->getManager();
        $body = $request->getContent();
        $data = json_decode($body, true);
        $user = new User();
        $form = $this->createForm(RegistryType::class, $user);
        $plaintextPassword = $data["passwort"];
        $form->submit($data);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPasswort($hashedPassword);
        $roles[] = 'ROLE_USER';
        $user->setRoles($roles);
        /* Create MailToken */

        try {
            $jwtBuilder = new JWTBuilder($_ENV["EMAIL_TOKEN"]);
            $mailToken = $jwtBuilder->generateToken($user->getEmail());
        } catch (Exception) {
            $mailToken = false;
        }

        /* If $mailToken -> setMailToken for DB and for the verifyemail */
        if ($mailToken) {
            $user->setMailToken($mailToken);
        } else {
            $data = [
                "statuscode" => 404,
                "message" => "Error"
            ];
        }

        $errors = $this->validateUser($form);
        if (!$errors) {
            $entityManager = $doctrine->getManager();
            /*  $mailService->sendMail($user->getEmail(), $mailToken); */
            $entityManager->persist($user);
            $entityManager->flush();
            $data = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "statuscode" => 201,
                "message" => "User created"
            ];
        } else {
            $data = [
                "errors" => $errors,
                "statuscode" => 404,
                "message" => "Error"
            ];
        }

        return $data;
    }


    /* Update a User */
    /* Find User by ID */
    public function UpdateUser(ManagerRegistry $doctrine, Request $request, int $id, UserPasswordHasherInterface $passwordHasher): array | bool
    {

        $data = [];

        $entityManager = $doctrine->getManager();
        $body = $request->getContent();
        $data = json_decode($body, true);
        $user = $doctrine->getRepository(User::class)->find($id);

        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $hasAccessSwitchPermission = $this->isGranted('ROLE_SUPER_ADMIN');

        if (!$hasAccess) {
            if ($user === $this->getUser()) {
                $hasAccess = true;
            }
            /* throw $this->createAccessDeniedException(); */
        }

        if ($hasAccess) {
            $form = $this->createForm(RegistryType::class, $doctrine->getRepository(User::class)->find($id));

            if ($data["passwort"] !== "") {
                $plaintextPassword = $data["passwort"];
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPasswort($hashedPassword);
            }
            if ($data["permission"] !== "") {
                if ($hasAccessSwitchPermission) {
                    $roles[] = $data["permission"];
                    $user->setRoles($roles);
                }
            }
            $data["name"] !== "" ? null : $data["name"] = $user->getName();
            $data["telefon"] !== "" ? null : $data["telefon"] = $user->getTelefon();
            $data["postcode"] !== "" ? null : $data["postcode"] = $user->getPostcode();
            $data["place"] !== "" ? null : $data["place"] = $user->getPlace();
            $data["email"] !== "" ? null : $data["email"] = $user->getEmail();
            $data["terms"] !== "" ? null : $data["terms"] = $user->isTerms();
            $form->submit($data);
            $errors = $this->validateUser($form);

            if (!$errors) {
                $entityManager->flush();
                $data = [
                    "id" => $user->getid(),
                    "message" => "user " . $user->getid() . " updated",
                    "code" => 202
                ];
            } else {
                $data = [
                    "id" => $user->getid(),
                    "message" => "Error",
                    "code" => 404
                ];
            }
        } else {
            $data = [
                "id" => $user->getid(),
                "message" => "Error",
                "code" => 404
            ];
        }
        return $data;
    }


    public function DeleteUser(ManagerRegistry $doctrine, int $id): array | bool
    {
        $data = [];
        $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);

        $hasAccess = $this->isGranted('ROLE_SUPER_ADMIN');
        if (!$hasAccess) {
            if ($user === $this->getUser()) {
                $hasAccess = true;
            }
            /* throw $this->createAccessDeniedException(); */
        }

        if ($hasAccess) {

            if (!$user) {
                $data = [
                    "messge" => "Error deleted",
                    "id" => $id,
                    "code" => 404
                ];
            } else {
                /* remove founded User */
                $entityManager->remove($user);
                $entityManager->flush();

                $data = [
                    "messge" => "User succesfully deleted",
                    "id" => $id,
                    "code" => 202
                ];
            }
        } else {
            $data = [
                "messge" => "Error deleted",
                "id" => $id,
                "code" => 404
            ];
        }

        return $data;
    }


    public function validateUser(Form $form): array | bool
    {
        $errors = [];
        if (count($form['name']->getErrors()) !== 0) {
            $errors["name"] = $form['name']->getErrors();
        };
        if (count($form['telefon']->getErrors()) !== 0) {
            $errors["telefon"] = $form['telefon']->getErrors();
        };
        if (count($form['postcode']->getErrors()) !== 0) {
            $errors["postcode"] = $form['postcode']->getErrors();
        };
        if (count($form['place']->getErrors()) !== 0) {
            $errors["place"] = $form['place']->getErrors();
        };
        if (count($form['email']->getErrors()) !== 0) {
            $errors["email"] = $form['email']->getErrors();
        };
        if (count($form['terms']->getErrors()) !== 0) {
            $errors["terms"] = $form['terms']->getErrors();
        };

        if (count($errors) > 0) {
            return $errors;
        }

        return false;
    }
}
