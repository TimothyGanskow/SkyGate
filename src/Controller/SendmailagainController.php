<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;
use Exception;

class SendmailagainController extends AbstractController
{

    /* SEND AN EMAIL AGAIN WHEN USER FORGET OR HAS NOT GOT AN VERIFY EMAIL */
    #[Route('/mailagain', name: 'app_sendmailagain', methods: ["post"])]
    public function index(ManagerRegistry $doctrine, Request $request): JsonResponse
    {

        /* Check for user by Email */
        try {
            $user = $doctrine->getRepository(Users::class)->findOneBy(['email' => $request->request->get("email")]);

            /* No User -> Error 404 */
            if (!$user) {
                return $this->json([
                    'message' => "No User found",
                    'code' => '404',
                ]);
            } else {
                /* User -> helperfunction sendMail from UsersRepository */
                $entityManager = $doctrine->getManager();
                $mail = $entityManager->getRepository(Users::class)->sendMail($user->getEmail(), $user->getMailToken());
            }
        } catch (Exception) {
            $mail = false;
            return $this->json([
                'message' => "mail failed",
                'code' => '404',
            ]);
        }


        /* Mail no error -> code 201 and email has been sended succesfully - else error 404 */
        if ($mail !== false) {
            return $this->json([
                'message' => "mail send",
                'code' => '201',
            ]);
        } else {
            return $this->json([
                'message' => "mail failed",
                'code' => '404',
            ]);
        }
    }
}
