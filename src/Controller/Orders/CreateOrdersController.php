<?php

namespace App\Controller\Orders;

use App\Entity\Order;
use App\Helpers\DateGenerator;
use App\Helpers\RandomOrderDetailsGenerator;
use App\Helpers\RandomStringGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;


class CreateOrdersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/orders', name: 'create_new_order', methods: ['POST'])]
    #[OA\Post(
        path: "/api/orders",
        summary: "Create a new order",
        description: "Endpoint to create a new order with the specified details.",
        requestBody: new OA\RequestBody(
            description: "Create Order Request Payload",
            content: new OA\JsonContent(
                type: "object",
                properties: [
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
                    new OA\Property(property: "delivery_option", type: "string", example: "standard")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "created"),
                        new OA\Property(property: "status_code", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Order created successfully"),
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
                                new OA\Property(
                                    property: "estimated_delivery_date_and_time",
                                    type: "string",
                                    format: "date-time",
                                    example: "2024-08-19 00:56:45"
                                ),
                                new OA\Property(property: "order_status", type: "string", example: "processing"),
                                new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-08-18 00:56:45"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-08-18 00:56:45")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
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
            $requestPayload = json_decode($request->getContent(), true);

            $customerName = $requestPayload['name'];
            $deliveryAddress = $requestPayload['delivery_address'];
            $orderItems = $requestPayload['order_items'];
            $deliveryOption = $requestPayload['delivery_option'];
            $estimatedDeliveryDateAndTime = DateGenerator::generateDateInFuture(DateGenerator::generateRandomDayCount());
            $orderStatus = RandomOrderDetailsGenerator::getRandomOrderStatus();
            $timestamps = DateGenerator::currentDateTime();

            $order = new Order();
            $order->setIdentifier(RandomStringGenerator::generateSecureRandomString(32));
            $order->setName($customerName);
            $order->setDeliveryAddress($deliveryAddress);
            $order->setOrderItems($orderItems);
            $order->setDeliveryOption($deliveryOption);
            $order->setEstimatedDeliveryDateAndTime($estimatedDeliveryDateAndTime);
            $order->setOrderStatus($orderStatus);
            $order->setCreatedAt($timestamps);
            $order->setUpdatedAt($timestamps);

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $this->json([
                'status' => 'created',
                'status_code' => 201,
                'message' => 'Order created successfully',
                'results' => [
                    'identifier' => $order->getIdentifier(),
                    'name' => $order->getName(),
                    'delivery_address' => $order->getDeliveryAddress(),
                    'order_items' => $order->getOrderItems(),
                    'delivery_option' => $order->getDeliveryOption(),
                    'estimated_delivery_date_and_time' => $order->getEstimatedDeliveryDateAndTime()->format('Y-m-d H:i:s'),
                    'order_status' => $order->getOrderStatus(),
                    'created_at' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $order->getUpdatedAt()->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $createNewOrderError) {
            return $this->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $createNewOrderError->getMessage()
            ], 500);
        }
    }
}
