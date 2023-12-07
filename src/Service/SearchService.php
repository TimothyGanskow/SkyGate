<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class SearchService
{

    public function SearchUser(ManagerRegistry $doctrine, Request $request): array | bool
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        /* Collect form Data and Check if there are !empty ->  if empty set default Value */
        isset($data["offset"]) ? $offset = $data["offset"] : $offset = 0;
        isset($data["orderBy"]) ? $orderBy =  $data["orderBy"] : $orderBy = "email";
        isset($data["sc"]) ? $as = $data["sc"] : $as = "ASC";
        isset($data["email"]) ? $email = $data["email"] : $email = "";
        isset($data["name"]) ? $name = $data["name"] : $name = "";
        isset($data["telefon"]) ? $telefon = $data["telefon"] : $telefon = "";
        isset($data["postcode"]) ? $postcode = $data["postcode"] : $postcode = "";
        isset($data["place"]) ? $place = $data["place"] : $place = "";
        $limit = 10;

        /* Call the entityManger and call a helperfunction from the repository from class User (findBySearch) */
        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->findBySearch(
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
                "name" => $user->getName(),
                "telefon" => $user->getTelefon(),
                "postcode" => $user->getPostcode(),
                "place" => $user->getPlace(),
                "permission" => $user->getRoles(),
                "code" => 200
            ];
        }

        return $data;
    }


    public function CountUser(ManagerRegistry $doctrine, Request $request): array | bool
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        /* Collect form Data */
        isset($data["offset"]) ? $offset = $data["offset"] : $offset = 0;
        isset($data["orderBy"]) ? $orderBy =  $data["orderBy"] : $orderBy = "email";
        isset($data["sc"]) ? $as = $data["sc"] : $as = "ASC";
        isset($data["email"]) ? $email = $data["email"] : $email = "";
        isset($data["name"]) ? $name = $data["name"] : $name = "";
        isset($data["telefon"]) ? $telefon = $data["telefon"] : $telefon = "";
        isset($data["postcode"]) ? $postcode = $data["postcode"] : $postcode = "";
        isset($data["place"]) ? $place = $data["place"] : $place = "";
        $limit = 1000;

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->findBySearch(
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

        return $users;
    }
}
