<?php

namespace App\Controller\Orders;

use App\Entity\Order;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FetchOrdersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/orders', name: 'fetch_orders', methods: ['GET'])]
    #[OA\Get(
        path: "/api/orders",
        summary: "Retrieve orders",
        description: "Endpoint fetches orders based on query parameters; retrieve either a specific order by its identifier, or retrieve orders based on their order status. The endpoint will return all orders when the query parameters are not provided.",
        parameters: [
            new OA\Parameter(
                name: "identifier",
                in: "query",
                description: "Unique identifier of the order to fetch.",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "order_status",
                in: "query",
                description: "Filter orders by their status.",
                required: false,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "status_code", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Orders retrieved successfully"),
                        new OA\Property(
                            property: "results",
                            type: "array",
                            items: new OA\Items(
                                type: "object",
                                properties: [
                                    new OA\Property(property: "identifier", type: "string", example: "abc123xyz456"),
                                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                                    new OA\Property(property: "delivery_address", type: "string", example: "1234 Elm Street"),
                                    new OA\Property(
                                        property: "order_items",
                                        type: "array",
                                        items: new OA\Items(
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "id", type: "string", example: "item123"),
                                                new OA\Property(property: "quantity", type: "integer", example: 3)
                                            ]
                                        )
                                    ),
                                    new OA\Property(property: "delivery_option", type: "string", example: "standard"),
                                    new OA\Property(property: "estimated_delivery_date", type: "string", example: "2024-08-30"),
                                    new OA\Property(property: "order_status", type: "string", example: "delivered"),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-08-18 00:56:45"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-08-18 00:56:45")
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad Request",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "status_code", type: "integer", example: 400),
                        new OA\Property(property: "message", type: "string", example: "Invalid order identifier or status provided")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "status_code", type: "integer", example: 500),
                        new OA\Property(property: "message", type: "string", example: "Something went wrong")
                    ]
                )
            )
        ]
    )]
    public function handle(Request $request): JsonResponse
    {
        try {

            $identifier = $request->query->get('identifier');
            $orderStatus = $request->query->get('order_status');
            $repositoryHandler = $this->entityManager->getRepository(Order::class);

            // Handle scenario when the query parameters are absent
            if (!$request->query->has('identifier') && !$request->query->has('order_status')) {
                $orderRecords = $repositoryHandler->findAll();

                $filteredOrderRecords = array_map(function ($order) {
                    return [
                        'identifier' => $order->getIdentifier(),
                        'name' => $order->getName(),
                        'delivery_address' => $order->getDeliveryAddress(),
                        'order_items' => $order->getOrderItems(),
                        'delivery_option' => $order->getDeliveryOption(),
                        'estimated_delivery_date' => $order->getEstimatedDeliveryDate(),
                        'order_status' => $order->getOrderStatus(),
                        'created_at' => $order->getCreatedAt(),
                        'updated_at' => $order->getUpdatedAt(),
                    ];
                }, $orderRecords);

                return $this->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'All Orders listed successfully',
                    'results' => $filteredOrderRecords,

                ], 200);
            }

            if ($identifier) {
                $identifierQueryData = $repositoryHandler->findOneBy(['identifier' => $identifier], []);

                if (!$identifierQueryData) {
                    return $this->json([
                        'status' => 'error',
                        'status_code' => 400,
                        'message' => 'Invalid order identifier provided',
                    ], 400);
                }

                return $this->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Order retrieved successfully',
                    'results' => [
                        'identifier' => $identifierQueryData->getIdentifier(),
                        'name' => $identifierQueryData->getName(),
                        'delivery_address' => $identifierQueryData->getDeliveryAddress(),
                        'order_items' => $identifierQueryData->getOrderItems(),
                        'delivery_option' => $identifierQueryData->getDeliveryOption(),
                        'estimated_delivery_date' => $identifierQueryData->getEstimatedDeliveryDate(),
                        'order_status' => $identifierQueryData->getOrderStatus(),
                        'created_at' => $identifierQueryData->getCreatedAt()->format('Y-m-d H:i:s'),
                        'updated_at' => $identifierQueryData->getUpdatedAt()->format('Y-m-d H:i:s')
                    ]
                ], 200);
            }

            $statusQueryData = $repositoryHandler->findBy(['orderStatus' => $orderStatus], []);

            if (!$statusQueryData) {
                return $this->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Invalid order status provided',
                ], 400);
            }

            $filteredStatusQueryData = array_map(function ($order) {
                return [
                    'identifier' => $order->getIdentifier(),
                    'name' => $order->getName(),
                    'delivery_address' => $order->getDeliveryAddress(),
                    'order_items' => $order->getOrderItems(),
                    'delivery_option' => $order->getDeliveryOption(),
                    'estimated_delivery_date' => $order->getEstimatedDeliveryDate(),
                    'order_status' => $order->getOrderStatus(),
                    'created_at' => $order->getCreatedAt(),
                    'updated_at' => $order->getUpdatedAt(),
                ];
            }, $statusQueryData);

            return $this->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Orders retrieved successfully',
                'results' => $filteredStatusQueryData
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
