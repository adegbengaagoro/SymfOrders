<?php

namespace App\Helpers;

class OrdersDataHelpers
{
  /**
   * Retrieves a random order status from a predefined list.
   *
   * This method selects a random status from an array of possible order statuses,
   * which includes 'processing', 'shipped', 'delayed', and 'delivered'.
   *
   * @return string A randomly selected order status from the list.
   */
  public static function getRandomOrderStatus(): string
  {
    $orderStatuses = ['processing', 'shipped', 'delayed', 'delivered'];
    $randomIndex = array_rand($orderStatuses);
    return $orderStatuses[$randomIndex];
  }

  /**
   * Retrieves a random delivery option from a predefined list.
   *
   * This method selects a random delivery option from an array of possible options,
   * which includes 'Standard Delivery', 'Express Delivery', 'Same Day Delivery',
   * and 'Amazon Prime'.
   *
   * @return string A randomly selected delivery option from the list.
   */
  public static function getRandomDeliveryOption(): string
  {
    $deliveryOptions = ['Standard Delivery', 'Express Delivery', 'Same Day Delivery', 'Amazon Prime'];
    $randomIndex = array_rand($deliveryOptions);
    return $deliveryOptions[$randomIndex];
  }

  // public static function findOrdersByDateAndStatus(array $orders, $targetDate)
  // {
  //   $matchingOrders = [];

  //   foreach ($orders as $order) {
  //     if ($order->order_status === 'processing') {
  //       $estimatedDeliveryDate = \DateTime::createFromFormat('Y-m-d\TH:i:sP', $order->estimatedDeliveryDateAndTime);
  //       if ($estimatedDeliveryDate && $estimatedDeliveryDate->format('Y-m-d') === $targetDate) {
  //         $matchingOrders[] = $order;
  //       }
  //     }
  //   }

  //   return $matchingOrders;
  // }
}
