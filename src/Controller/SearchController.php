<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SearchService;

class SearchController extends AbstractController
{

    /* SEARCH USER WITH QUERYBUILDER */
    #[Route('/search', name: 'app_search', methods: ["post"])]
    public function show(ManagerRegistry $doctrine, Request $request, SearchService $searchService): JsonResponse
    {
        $data = $searchService->SearchUser($doctrine, $request);

            /* return founded data */
            return $this->json($data);
        
    }


    /* HELPERFUNCTION SEARCHCOUNT -> COUNT ALL POSSIBLE ENTRIES */
    #[Route('/searchcounter', name: 'app_search_Counter', methods: ["post"])]
    public function showCounter(ManagerRegistry $doctrine, Request $request, SearchService $searchService): JsonResponse
    {

        $data = $searchService->CountUser($doctrine, $request);

        /* return the count of the users with limit 1000 */
        return $this->json([
            "result" => count($data),
            "code" => 200
        ]);
    }
}
