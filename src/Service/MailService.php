<?php

namespace App\Service;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Exception;

class MailService
{

    public function sendMail($sendTo, $mailToken)
    {

        $text = <<<Body
        Please click this email to confirm your email: http://localhost:8000/verify/$mailToken;
        Body;

        $email = (new Email())->from($_ENV["MAIL_USERNAME"])->to($sendTo)->subject("Please verify your email")->text($text);
        $dsn = "smtp://" . $_ENV["MAIL_USERNAME"] . ":*dipolmat.insure6570!@" . $_ENV["MAIL_HOST"];

        /* $dsn = "smtp://service@diplomat.insure:*dipolmat.insure6570!@smtp.strato.de:587"; */

        $transporter = Transport::fromDsn($dsn);

        $mailer = new Mailer($transporter);
        $mailer->send($email);
    }


    public function sendMailAgain(ManagerRegistry $doctrine, Request $request): array
    {

        $body = $request->getContent();
        $data = json_decode($body, true);
        /* Check for user by Email */
        try {
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $data["email"]]);

            /* No User -> Error 404 */
            if (!$user) {
                return [
                    'message' => "No User found",
                    'code' => '404',
                ];
            } else {
                /* User -> helperfunction sendMail from UsersRepository */
                $this->sendMail($user->getEmail(), $user->getMailToken());
                return [
                    'message' => "Mail sended",
                    'code' => '202',
                ];
            }
        } catch (Exception) {
            return [
                'message' => "mail failed",
                'code' => '404',
            ];
        }
    }
}
