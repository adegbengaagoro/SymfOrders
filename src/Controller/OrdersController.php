<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[Route('/api')]
class OrdersController extends AbstractController
{

    #[Route('/orders', name: 'fetch_all_orders', methods: ['GET'])]
    #[OA\Get(
        path: "/api/orders",
        summary: "Retrieve the details of a specific order",
        responses: [
            new OA\Response(
                response: 200,
                description: "Order retrieved successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "status_code", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Order retrieved successfully."),
                        new OA\Property(
                            property: "results",
                            type: "array",
                            items: new OA\Items(
                                type: "object",
                                properties: [
                                    new OA\Property(property: "name", type: "string", example: "Franklin West"),
                                    new OA\Property(property: "delivery_address", type: "string", example: "128 Leigh Lane, DY1 3TG"),
                                    new OA\Property(
                                        property: "order_items",
                                        type: "array",
                                        items: new OA\Items(
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "id", type: "string", example: "4521"),
                                                new OA\Property(property: "quantity", type: "integer", example: 2),
                                            ]
                                        )
                                    ),
                                    new OA\Property(property: "delivery_option", type: "string", example: "Royal Mail Service"),
                                    new OA\Property(
                                        property: "estimated_delivery_date_time",
                                        type: "string",
                                        format: "date-time",
                                        example: "2024-08-17T14:30:00Z"
                                    ),
                                    new OA\Property(property: "order_status", type: "string", example: "Shipped")
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
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
            'message' => 'Order retrieved successfully',
            'results' => $order_data
        ]);
    }
}
