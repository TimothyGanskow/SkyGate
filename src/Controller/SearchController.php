<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Jwt\JwtValidator;
use Exception;

class SearchController extends AbstractController
{

    /* SEARCH USER WITH QUERYBUILDER */
    #[Route('/search', name: 'app_search', methods: ["post"])]
    public function show(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        /* Check Header for token */
        $headers = $request->headers->all();
        $token = explode(' ', $headers['authorization'][0])[1];

        /* Check Token if Valid */
        try {
            $JwtValidator = new JwtValidator($_ENV["SESSIONTOKEN_KEY"]);
            $verifyToken = $JwtValidator->validate($token);
        } catch (Exception) {
            $verifyToken = false;
        }


        if ($verifyToken) {

            /* Collect form Data and Check if there are !empty ->  if empty set default Value */
            $request->request->get("offset") ? $offset = $request->request->get("offset") : $offset = 0;
            $request->request->get("orderBy") ? $orderBy = $request->request->get("orderBy") : $orderBy = "email";
            $request->request->get("sc") ? $as = $request->request->get("sc") : $as = "ASC";
            $request->request->get("email") ? $email = $request->request->get("email") : $email = "";
            $request->request->get("name") ? $name = $request->request->get("name") : $name = "";
            $request->request->get("telefon") ? $telefon = $request->request->get("telefon") : $telefon = "";
            $request->request->get("postcode") ? $postcode = $request->request->get("postcode") : $postcode = "";
            $request->request->get("place") ? $place = $request->request->get("place") : $place = "";
            $limit = 10;

            /* Call the entityManger and call a helperfunction from the repository from class User (findBySearch) */
            $entityManager = $doctrine->getManager();
            $users = $entityManager->getRepository(Users::class)->findBySearch(
                $offset,
                $orderBy,
                $as,
                $email,
                $name,
                $telefon,
                $postcode,
                $place,
                $limit
            );


            $data = [];

            /* loop through the found $users object and create a separate field in $data for each item in it*/
            foreach ($users as $user) {
                $data[] = [
                    "id" => $user->getId(),
                    "email" => $user->getEmail(),
                    "passwort" => $user->getPasswort(),
                    "name" => $user->getRegistry()->getName(),
                    "telefon" => $user->getRegistry()->getTelefon(),
                    "postcode" => $user->getRegistry()->getPostcode(),
                    "place" => $user->getRegistry()->getPlace(),
                    "permission" => $user->getPermission()->getPermission(),
                    "mailConfirmed" => $user->getRegistry()->isMailConfirmed()
                ];
            }

            /* return founded data */
            return $this->json([
                "result" => $data,
                "code" => 200
            ]);
        } else {
            return $this->json([
                "message" => "Token is invalid",
                "status" => 401
            ]);
        }
    }


    /* HELPERFUNCTION SEARCHCOUNT -> COUNT ALL POSSIBLE ENTRIES */
    #[Route('/searchcounter', name: 'app_search_Counter', methods: ["post"])]
    public function showCounter(ManagerRegistry $doctrine, Request $request): JsonResponse
    {

        /* Collect form Data */
        $request->request->get("offset") ? $offset = $request->request->get("offset") : $offset = 0;
        $request->request->get("orderby") ? $orderBy = $request->request->get("orderby") : $orderBy = "email";
        $request->request->get("as") ? $as = $request->request->get("as") : $as = "asc";
        $request->request->get("email") ? $email = $request->request->get("email") : $email = "";
        $request->request->get("name") ? $name = $request->request->get("name") : $name = "";
        $request->request->get("telefon") ? $telefon = $request->request->get("telefon") : $telefon = "";
        $request->request->get("postcode") ? $postcode = $request->request->get("postcode") : $postcode = "";
        $request->request->get("place") ? $place = $request->request->get("place") : $place = "";
        $limit = 1000;

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(Users::class)->findBySearch(
            $offset,
            $orderBy,
            $as,
            $email,
            $name,
            $telefon,
            $postcode,
            $place,
            $limit
        );

        /* return the count of the users with limit 1000 */
        return $this->json([
            "result" => count($users),
            "code" => 200
        ]);
    }
}
