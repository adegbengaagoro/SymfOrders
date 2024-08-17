<?php

namespace App\Helpers;

class DateGenerator
{
  /**
   * Generates a future date by adding a specified number of days to the current date.
   *
   * @param int $numberOfDaysInFuture The number of days to add to the current date
   * @return \DateTime The computed date in the future
   */
  public static function generateDateInFuture(int $numberOfDaysInFuture): \DateTime
  {
    $currentDate = new \DateTime();
    $currentDate->modify('+' . $numberOfDaysInFuture . ' days');

    return $currentDate;
  }

  /**
   * Generates a random number of days within a year.
   *
   * This returns a random integer representing the number of days.
   * The random number is between 1 and 365, inclusive.
   *
   * @return int The random number of days between 1 and 365.
   */
  public static function generateRandomDayCount(): int
  {
    return mt_rand(1, 365);
  }
}
