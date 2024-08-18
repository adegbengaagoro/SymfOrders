<?php

namespace App\Controller\Orders;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FetchOrdersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/orders', name: 'fetch_orders', methods: ['GET'])]
    public function handle(Request $request): JsonResponse
    {
        try {

            $identifier = $request->query->get('identifier');
            $orderStatus = $request->query->get('order_status');
            $repositoryHandler = $this->entityManager->getRepository(Order::class);

            // Handle scenario when the query parameters are absent
            if (!$request->query->has('identifier') && !$request->query->has('order_status')) {
                $orderRecords = $repositoryHandler->findAll();

                return $this->json([
                    'status' => 'error',
                    'status_code' => 200,
                    'message' => 'All Orders listed successfully',
                    'results' => $orderRecords,

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
                        'estimated_delivery_date_and_time' => $identifierQueryData->getEstimatedDeliveryDateAndTime()->format('Y-m-d H:i:s'),
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
                    'estimated_delivery_date_and_time' => $order->getEstimatedDeliveryDateAndTime(),
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
