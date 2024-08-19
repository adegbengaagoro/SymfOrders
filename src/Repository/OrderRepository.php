<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Order::class);
    }

    /**
     * Finds orders by a specific status and delivery date.
     *
     * This method retrieves orders from the database that match a given order status
     * and have an estimated delivery date earlier than the specified target date.
     *
     * @param string $targetDate The target date to compare against the estimated delivery dates.
     * @param string $orderStatus The order status to filter the orders by.
     *
     * @return array The list of orders that match the given criteria.
     */
    public function findOrdersByDateAndStatus(string $targetDate, string $orderStatus)
    {
        return $this->createQueryBuilder('o')
            ->where('o.orderStatus = :status')
            ->andWhere('o.estimatedDeliveryDate < :deliveryDate')
            ->setParameter('status', $orderStatus)
            ->setParameter('deliveryDate', $targetDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Updates the status of multiple orders to 'delayed' and returns a status message.
     *
     * This method iterates through an array of orders, sets each order's status to 'delayed',
     * and persists the changes to the database. It returns a message indicating the number of
     * orders that were updated. If no orders were updated, a corresponding message is returned.
     *
     * @param array $orders An array of order entities to update.
     *
     * @return string A message indicating the result of the update operation.
     */
    public function updateOrderStatusToDelayed(array $orders): string
    {
        $numberOfRecordsUpdated = 0;
        foreach ($orders as $order) {
            $order->setOrderStatus('delayed');
            $this->entityManager->persist($order);
            $numberOfRecordsUpdated++;
        }

        if ($numberOfRecordsUpdated > 0) {
            $this->entityManager->flush();
            return "$numberOfRecordsUpdated order deliveries have been updated successfully with a status of 'delayed'.";
        }

        return "No matching records. No orders were updated.";
    }

}
