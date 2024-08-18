<?php

namespace App\Helpers;

class RandomStringGenerator
{
  /**
   * Generates a secure random string of a specified length.
   *
   * This method generates a random string of the desired length using
   * cryptographically secure random bytes. The string is encoded in base64 and
   * then modified to be URL-safe by replacing `+` with `-` and `/` with `_`.
   *
   * @param int $stringLength The desired length of the generated string.
   *
   * @return string A secure random string of the specified length.
   */
  public static function generateSecureRandomString(int $stringLength): string
  {
    $randomBytes = random_bytes(ceil($stringLength * 3 / 4));

    $base64String = rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');

    return substr($base64String, 0, $stringLength);
  }
}
