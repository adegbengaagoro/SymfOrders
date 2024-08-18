<?php

namespace App\Controller\Orders;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FetchOrdersController extends AbstractController
{
    #[Route('/api/orders', name: 'fetch_orders', methods: ['GET'])]
    public function handle(): JsonResponse
    {
        try {
            return $this->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Order retrieved successfully',
            ], 200);
        } catch (\Exception $fetchOrderError) {
            return $this->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $fetchOrderError->getMessage()
            ], 500);
        }
    }
}
