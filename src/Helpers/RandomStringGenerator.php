<?php

namespace App\Helpers;

class RandomStringGenerator
{
  /**
   * Generates a secure random string of a specified length.
   *
   * This method creates a cryptographically secure random string using
   * random bytes encoded in base64. The generated string is URL-safe by replacing
   * `+` with `-` and `/` with `_`. The method enforces a minimum string length of 22 characters.
   *
   * @param int $stringLength The desired length of the generated string. Must be greater than 22.
   *
   * @return string A secure random string of the specified length.
   *
   * @throws \InvalidArgumentException If the string length is less than or equal to zero, or less than 22 characters.
   */
  public static function generateSecureRandomString(int $stringLength): string
  {
    if ($stringLength <= 0) {
      throw new \InvalidArgumentException('String length must be greater than zero.');
    }

    if ($stringLength < 22) {
      throw new \InvalidArgumentException('Generated string length should have a minimum of 22 characters.');
    }

    $randomBytes = random_bytes(ceil($stringLength * 3 / 4));

    $base64String = rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');

    return substr($base64String, 0, $stringLength);
  }
}
