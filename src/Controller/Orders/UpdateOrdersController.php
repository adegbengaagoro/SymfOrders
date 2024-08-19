<?php

namespace App\Controller\Orders;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;


class UpdateOrdersController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/api/orders', name: 'update_order_status', methods: ['PATCH'])]
    #[OA\Patch(
        path: "/api/orders",
        summary: "Update the status of an existing order",
        description: "Endpoint to modify the status of an existing order based on its record identifier.",
        requestBody: new OA\RequestBody(
            description: "Update Order Request Payload",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "order_identifier", type: "string", example: "abc123xyz456"),
                    new OA\Property(property: "new_order_status", type: "string", example: "shipped"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "status_code", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Order status updated successfully"),
                        new OA\Property(
                            property: "results",
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
                                new OA\Property(property: "estimated_delivery_date", type: "string", example: "2024-08-19"),
                                new OA\Property(
                                    property: "order_status",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "previous_order_status", type: "string", example: "processing"),
                                        new OA\Property(property: "new_order_status", type: "string", example: "shipped")
                                    ]
                                ),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-08-18 00:56:45")
                            ]
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
                        new OA\Property(property: "message", type: "string", example: "Invalid order identifier provided")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Error creating the order",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "status_code", type: "integer", example: 500),
                        new OA\Property(property: "message", type: "string", example: "Internal Server Error")
                    ]
                )
            )
        ]
    )]
    public function handle(Request $request): JsonResponse
    {
        try {
            $requestPayload = json_decode($request->getContent(), true);

            $expectedFields = ['order_identifier', 'new_order_status'];
            $unexpectedFields = array_diff(array_keys($requestPayload), $expectedFields);

            if (!empty($unexpectedFields)) {
                $this->logger->warning('Unexpected fields in request payload', [
                    'unexpected_fields' => $unexpectedFields,
                    'payload' => $requestPayload
                ]);

                return $this->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Invalid request payload',
                ], 400);
            }

            $orderIdentifier = $requestPayload['order_identifier'];
            $newOrderStatus = $requestPayload['new_order_status'];
            $repositoryHandler = $this->entityManager->getRepository(Order::class);

            $orderData = $repositoryHandler->findOneBy(['identifier' => $orderIdentifier], []);

            if (!$orderData) {
                return $this->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Invalid order identifier provided',
                ], 400);
            }

            $previousOrderStatus = $orderData->getOrderStatus();

            $orderData->setOrderStatus($newOrderStatus);
            $orderData->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($orderData);
            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Order status updated successfully',
                'results' => [
                    'identifier' => $orderData->getIdentifier(),
                    'name' => $orderData->getName(),
                    'delivery_address' => $orderData->getDeliveryAddress(),
                    'order_items' => $orderData->getOrderItems(),
                    'delivery_option' => $orderData->getDeliveryOption(),
                    'estimated_delivery_date' => $orderData->getEstimatedDeliveryDate(),
                    'order_status' => [
                        'previous_order_status' => $previousOrderStatus,
                        'new_order_status' => $orderData->getOrderStatus()
                    ],
                    'updated_at' => $orderData->getUpdatedAt()->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $updateOrderError) {
            return $this->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $updateOrderError->getMessage()
            ], 500);
        }
    }
}
