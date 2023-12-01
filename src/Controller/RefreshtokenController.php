<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Jwt\JwtValidator;
use App\Security\Jwt\JWTBuilder;
use Exception;

class RefreshtokenController extends AbstractController
{
    /* ROUTE TO REFRESH THE SESSIONTOKEN WITH THE REFRESHTOKEN */
    #[Route('/refresh', name: 'app_refreshtoken', methods: ["post"])]
    public function index(Request $request,): JsonResponse
    {

        /* Try to Validad the RefreshToken */
        try {
            $JwtValidator = new JwtValidator($_ENV["REFRESHTOKEN_KEY"]);
            $verifyToken = $JwtValidator->validate($request->request->get("token"));
        } catch (Exception) {
            $verifyToken = false;
        }

        /* If RefreshToken is Valid -> JwtValidator gives the unique email back and a new sessiontoken get build */
        if ($verifyToken) {
            try {
                $jwtBuilder = new JWTBuilder($_ENV["SESSIONTOKEN_KEY"]);
                $sessionToken = $jwtBuilder->generateToken($verifyToken);
            } catch (Exception) {
                $verifyToken = false;
            }
            /* Return sessionToken and 200 */
            return $this->json([
                'sessionToken' => $sessionToken,
                'status' => '200',
            ]);
        } else {
            /* return 401 -> frontend axios.interceptor should log the user out */
            return $this->json([
                'message' => 'RefreshToken is Invalid',
                'status' => '401',
            ]);
        }
    }
}
