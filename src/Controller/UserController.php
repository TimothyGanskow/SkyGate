<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use App\Entity\Registry;
use App\Entity\Userspermission;
use App\Security\Jwt\JWTBuilder;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

use App\Security\Jwt\JwtValidator;
use Exception;

class UserController extends AbstractController
{


    /* GET ALL USERS */
    #[Route('/user', name: 'getAll_user', methods: ["get"])]
    public function index(ManagerRegistry $doctrine, Request $request): JsonResponse
    {

        /* Check Header for the Token */
        $headers = $request->headers->all();
        $token = explode(' ', $headers['authorization'][0])[1];

        /* Check Token is Valid*/
        try {
            $JwtValidator = new JwtValidator($_ENV["SESSIONTOKEN_KEY"]);
            $verifyToken = $JwtValidator->validate($token);
        } catch (Exception) {
            $verifyToken = false;
        }

        if ($verifyToken) {
            /* Use the findAll() function to get every entry */
            $users = $doctrine->getRepository(Users::class)->findAll();
            $data = [];

            /* loop through the found $users object and create a separate field in $data for each item in it*/
            foreach ($users as $user) {
                $data[] = [
                    "id" => $user->getId(),
                    "email" => $user->getEmail(),
                    "name" => $user->getRegistry()->getName(),
                    "telefon" => $user->getRegistry()->getTelefon(),
                    "postcode" => $user->getRegistry()->getPostcode(),
                    "place" => $user->getRegistry()->getPlace(),
                    "permission" => $user->getPermission()->getPermission(),
                    "status" => 201
                ];
            }

            /* return all entrys */
            return $this->json($data);
        } else {
            /* return error */
            return $this->json([
                "message" => "Token is invalid",
                "status" => 401
            ]);
        }
    }


    /* GET USER BY ID */
    #[Route('/user/{id}', name: 'get_user_by_ID', methods: ["get"])]
    public function show(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        /* Check and Validad Token */
        try {
            $headers = $request->headers->all();
            $token = explode(' ', $headers['authorization'][0])[1];
            $JwtValidator = new JwtValidator($_ENV["SESSIONTOKEN_KEY"]);
            $verifyToken = $JwtValidator->validate($token);
        } catch (Exception) {
            $verifyToken = false;
        }

        /* !Token -> error 401 (Token is invalid)
            Token -> Check for User by id
            !User -> error 404 (User not found)
            User -> get id, email, name, telefon, postcode, place, permission as an array
        */
        if ($verifyToken) {
            $user = $doctrine->getRepository(Users::class)->find($id);
            if (!$user) {
                return $this->json([
                    "message" => "user$user not found",
                    "code" => 404
                ]);
            }

            $data[] = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getRegistry()->getName(),
                "telefon" => $user->getRegistry()->getTelefon(),
                "postcode" => $user->getRegistry()->getPostcode(),
                "place" => $user->getRegistry()->getPlace(),
                "permission" => $user->getPermission()->getPermission(),
            ];

            /* return an jsonobject with the datas */
            return $this->json([
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getRegistry()->getName(),
                "telefon" => $user->getRegistry()->getTelefon(),
                "postcode" => $user->getRegistry()->getPostcode(),
                "place" => $user->getRegistry()->getPlace(),
                "permission" => $user->getPermission()->getPermission(),
                "status" => 200
            ]);
        } else {
            return $this->json([
                "message" => "Token is invalid",
                "status" => 401
            ]);
        }
    }


    /* CREATE USER */
    #[Route('/user', name: 'create_user', methods: ["post"])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {

        /* Call the entityManger and call a helperfunction from the repository from class User (Validate)
       Validate with Param true -> Create User and false -> Update User */
        $entityManager = $doctrine->getManager();
        $errors = $entityManager->getRepository(Users::class)->Validate(
            $request->request->get("email"),
            $request->request->get("name"),
            $request->request->get("telefon"),
            $request->request->get("postcode"),
            $request->request->get("place"),
            $request->request->get("passwort"),
            $request->request->get("terms"),
            true
        );

        /* error -> return errors */
        if (!empty($errors)) {
            return $this->json([
                "errors" => $errors,
                "status" => 422
            ]);
        } else {

            try {
                /* Create Data for UserTable */
                $user = new Users();
                $user->setEmail($request->request->get("email"));

                /* HashPW */
                $factory = new PasswordHasherFactory([
                    'common' => ['algorithm' => 'bcrypt'],
                    'memory-hard' => ['algorithm' => 'sodium'],
                ]);
                $passwordHasher = $factory->getPasswordHasher('common');
                $hashedPassword = $passwordHasher->hash($request->request->get("passwort"));
                $user->setPasswort($hashedPassword);
                /* ENDHASH */

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
                    return $this->json(" Error");
                }

                $date = new \DateTimeImmutable();
                $user->setCreatedAt($date);

                /* Create Data for RegistryTable */
                $registry = new Registry();
                $registry->setName($request->request->get("name"));
                $registry->setTelefon($request->request->get("telefon"));
                $registry->setPostcode($request->request->get("postcode"));
                $registry->setPlace($request->request->get("place"));
                $registry->setTerms($request->request->get("terms"));
                $registry->setMailConfirmed(false);

                /* Create Data for UserspermissionTable */
                $permission = new Userspermission();
                $permission->setPermission(1);
                $permission->setUpdatedAt($date);

                /* Set Relations beetwen Registry and User && Permission and User */
                if ($registry && $permission) {
                    $user->setRegistry($registry);
                    $user->setPermission($permission);
                }

                /* Get the EntityManger and safe all */
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                /* Send the verify email to the new Created User */
                $entityManager = $doctrine->getManager();
                $entityManager->getRepository(Users::class)->sendMail($request->request->get("email"), $mailToken);

                $data = [
                    "id" => $user->getId(),
                    "email" => $user->getEmail()
                ];

                /* check if user is created with $user->get functions -> return data, else  return error */
                if ($data) {
                    return $this->json([
                        $data,
                        "statuscode" => 201,
                        "message" => "User created"
                    ]);
                } else {
                    return $this->json("Error");
                }
            } catch (Exception) {
                return $this->json([
                    "message" => "Error",
                    "statuscode" => 500
                ]);
            }
        }
    }


    /* UPDATE USER */
    #[Route('/user/{id}', name: 'update_user', methods: ["patch"])]
    public function update(ManagerRegistry $doctrine, Request $request, string $id): JsonResponse
    {

        /* Check and Validade Token */
        $headers = $request->headers->all();
        $token = explode(' ', $headers['authorization'][0])[1];

        try {
            $JwtValidator = new JwtValidator($_ENV["SESSIONTOKEN_KEY"]);
            $verifyToken = $JwtValidator->validate($token);
        } catch (Exception) {
            $verifyToken = false;
        }

        if ($verifyToken) {
            $user = $doctrine->getRepository(Users::class)->findOneBy(['email' => $verifyToken]);
            $permission = $user->getPermission()->getPermission();
            $selfID = $user->getid();
        } else {
            return $this->json([
                "message" => "Token is invalid",
                "status" => 401
            ]);
        }

        if ($selfID == $id) {
            $permission = 2;
        }
        echo $permission;
        /* Check if request has permission -> else 422 permission denied */
        if ($permission != 2 && $permission != 3) {
            return $this->json([
                "message" => "permission denied",
                "status" => 422
            ]);
        } else {

            /* Validate Form  (flase because empty Values are no problem)*/
            $entityManager = $doctrine->getManager();
            $errors = $entityManager->getRepository(Users::class)->Validate(
                $request->request->get("email"),
                $request->request->get("name"),
                $request->request->get("telefon"),
                $request->request->get("postcode"),
                $request->request->get("place"),
                $request->request->get("passwort"),
                $request->request->get("terms"),
                false
            );

            /* error -> return errors */
            if (!empty($errors)) {
                return $this->json([
                    "errors" => $errors,
                    "status" => 422
                ]);
            }



            /* Token-> Search User 
                User -> Update User
                !Token -> Error 401
                !User -> Error 404  */

            $entityManager = $doctrine->getManager();
            $user = $doctrine->getRepository(Users::class)->find($id);

            if (!$user) {
                return $this->json([
                    "message" => "No User found",
                    "status" => 404
                ]);
            }
            $request->request->get("email") ? $user->setEmail($request->request->get("email")) : null;

            if ($request->request->get("passwort")) {
                /* HashPW */
                $factory = new PasswordHasherFactory([
                    'common' => ['algorithm' => 'bcrypt'],
                    'memory-hard' => ['algorithm' => 'sodium'],
                ]);
                $passwordHasher = $factory->getPasswordHasher('common');
                $hashedPassword = $passwordHasher->hash($request->request->get("passwort"));
                /* ENDHASH */
                $user->setPasswort($hashedPassword);
            }


            /* Permission */
            $request->request->get("permission") ? $user->getPermission()->setPermission($request->request->get("permission")) : null;

            /* Registry */
            $request->request->get("name") ? $user->getRegistry()->setName($request->request->get("name")) : null;
            $request->request->get("telefon") ? $user->getRegistry()->setTelefon($request->request->get("telefon")) : null;
            $request->request->get("postcode") ? $user->getRegistry()->setPostcode($request->request->get("postcode")) : null;
            $request->request->get("place") ? $user->getRegistry()->setPlace($request->request->get("place")) : null;
            $request->request->get("mailConfirmed") ? $user->getRegistry()->setMailConfirmed($request->request->get("mailConfirmed")) : null;
            $entityManager->flush();

            /* User update succes 200 */
            $data = [
                "id" => $user->getid(),
                "message" => "user " . $user->getid() . " updated",
                "code" => 200
            ];

            /* return id message and code */
            return $this->json($data);
        }
    }


    /* DELETE USER */
    #[Route('/user/{id}', name: 'delete_user', methods: ["delete"])]
    public function delete(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        /* Check and Validade Token */
        try {
            $headers = $request->headers->all();
            $token = explode(' ', $headers['authorization'][0])[1];
            $JwtValidator = new JwtValidator($_ENV["SESSIONTOKEN_KEY"]);
            $verifyToken = $JwtValidator->validate($token);
        } catch (Exception) {
            $verifyToken = false;
        }

        $user = $doctrine->getRepository(Users::class)->findOneBy(['email' => $verifyToken]);
        $permission = $user->getPermission()->getPermission();

        /* Check Permission */


        /* Token-> Search User 
            User -> Delete User
            !Token -> Error 401
            !User -> Error 404  */
        if ($verifyToken) {
            if ($permission !== 3) {
                return $this->json([
                    "message" => "permission denied",
                    "status" => 422
                ]);
            } else {
                $entityManager = $doctrine->getManager();
                $user = $doctrine->getRepository(Users::class)->find($id);

                if (!$user) {
                    return $this->json("No User found", 404);
                }

                /* remove founded User */
                $entityManager->remove($user);
                $entityManager->flush();

                /* return succes 202 */
                return $this->json([
                    "messge" => "User succesfully deleted",
                    "id" => $id,
                    "code" => 202
                ]);
            }
        }
    }
}
