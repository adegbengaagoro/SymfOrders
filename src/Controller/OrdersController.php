<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class OrdersController extends AbstractController
{
    #[Route('/orders', name: 'fetch_all_orders')]
    public function fetchAllOrders(): JsonResponse
    {
        $order_items = [
            [
                'id' => '234',
                'quantity' => 2
            ],
            [
                'id' => '31903',
                'quantity' => 1
            ],
            [
                'id' => '893241',
                'quantity' => 5
            ],
        ];

        $serialized_orders = serialize($order_items);

        $order_data = [
            [
                'name' => 'Franklin West',
                'delivery_address' => '128 Leigh Lane, DY1 3TG',
                'order_items' => unserialize($serialized_orders),
                'delivery_option' => 'Royal Mail Service',
                'estimated_delivery_date_time' => new \DateTime(),
                'order_status' => 'Shipped'
            ]
        ];

        return $this->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Order retrieved successfully.',
            'results' => $order_data
        ]);
    }
}
