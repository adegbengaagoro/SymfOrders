<?php

namespace App\Tests\Helpers;

use App\Helpers\RandomStringGenerator;
use PHPUnit\Framework\TestCase;

class RandomStringGeneratorTestPhpTest extends TestCase
{
    public function testGenerateSecureRandomStringReturnsCorrectLength()
    {
        $lengthArray = [8, 16, 32, 64, 128];

        foreach ($lengthArray as $length) {
            $outcome = RandomStringGenerator::generateSecureRandomString($length);

            $this->assertEquals($length, strlen($outcome), "Failed to assert that the string length is $length");
        }
    }
}
