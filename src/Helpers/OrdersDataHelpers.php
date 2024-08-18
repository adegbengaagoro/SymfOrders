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
    $orderStatuses = ['processing', 'shipped', 'delivered', 'returned', 'cancelled'];
    $randomIndex = array_rand($orderStatuses);
    return $orderStatuses[$randomIndex];
  }
}
