<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Security\Jwt\JwtValidator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class VerifyController extends AbstractController
{
    /* VERIFY AN EMAIL AND UPDATE ISMAILCONFIRMED */
    #[Route('/verify/{mailToken}', name: 'app_verify', methods: ["get"])]
    public function update(ManagerRegistry $doctrine, string $mailToken)
    {

        /* Check and Validade Token */
        try {
            $JwtValidator = new JwtValidator($_ENV["EMAIL_TOKEN"]);
            $verifyToken = $JwtValidator->validate($mailToken);
        } catch (Exception) {
            $verifyToken = false;
        }


        /* Token-> Search User 
            User -> return redirectionsucces and confirm email
            !Token -> return redirectionerror
            !User -> return redirectionerror  */
        if ($verifyToken) {
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $verifyToken]);
            if (!$user) {
                return $this->redirect("http://localhost:5173/emailconfirmerror");
            } else {
                $user->setMailToken("verify");
                $entityManager = $doctrine->getManager();
                $entityManager->flush();
                return $this->redirect("http://localhost:5173/emailconfirmsucces");
            }
        } else {
            return $this->redirect("http://localhost:5173/emailconfirmerror");
        }
    }
}
