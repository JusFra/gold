<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use GoldPriceCalculator;

class GoldController extends AbstractController
{
    #[Route('/api/gold', name: 'app_gold', methods: [ 'POST' ])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $start = $data['from'];
        $stop = $data['to'];

        $avgPriceArray = GoldPriceCalculator::calculateAveragePrice($start,$stop);

        if($avgPriceArray){
            return $this->json($avgPriceArray);
        }
        else{
            return http_response_code(400);
        }
    }
}
