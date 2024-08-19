<?php

namespace App\Helpers;

class DateHelpers
{
  /**
   * Generates a future date by adding a specified number of days to the current date.
   *
   * @param int $numberOfDaysInFuture The number of days to add to the current date
   * @param string $dateFormat The format of the returned date
   * @return string The computed date in the future
   * @throws \InvalidArgumentException if the number of days is zero or negative
   */
  public static function generateDateInFuture(int $numberOfDaysInFuture, string $dateFormat): string
  {
    if ($numberOfDaysInFuture <= 0) {
      throw new \InvalidArgumentException('Number of days in the future must be greater than 0.');
    }
    $currentDate = new \DateTime();
    $currentDate->modify("+ $numberOfDaysInFuture days");

    return $currentDate->format($dateFormat);
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
    return mt_rand(1, 7);
  }

  /**
   * Returns the current datetime in London time.
   *
   * @return \DateTime The current datetime in the Europe/London timezone.
   */
  public static function currentDateTime(): \DateTime
  {
    return new \DateTime('now', new \DateTimeZone('Europe/London'));
  }

  /**
   * Checks if a given string is a valid date in the YYYY-MM-DD format.
   *
   * @param string $dateString The date string to be validated.
   * @return bool True if the string is a valid date in the specified format, false otherwise.
   */
  public static function checkDateFormat(string $dateString): bool
  {
    $dateFormat = 'Y-m-d';
    $generateDateInExpectedFormat = \DateTime::createFromFormat($dateFormat, $dateString);
    return $generateDateInExpectedFormat && $generateDateInExpectedFormat->format($dateFormat) === $dateString;
  }
}
