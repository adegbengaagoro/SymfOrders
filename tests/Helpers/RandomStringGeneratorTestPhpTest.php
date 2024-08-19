<?php

namespace App\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use App\Helpers\RandomStringGenerator;

class RandomStringGeneratorTestPhpTest extends TestCase
{
    /**
     * Data provider for string length test.
     *
     * @return array
     */
    public function stringLengthProvider(): array
    {
        return [
            [22],
            [26],
            [32],
            [64],
            [128],
            [256],
        ];
    }

    /**
     * Test that the generated string has the correct length.
     *
     * @dataProvider stringLengthProvider
     */
    public function testGenerateSecureRandomStringHasCorrectLength(int $length)
    {
        $generatedString = RandomStringGenerator::generateSecureRandomString($length);

        $this->assertSame($length, strlen($generatedString), "Generated string length should be $length");
    }

    /**
     * Test that the generated string is URL-safe.
     */
    public function testGenerateSecureRandomStringIsUrlSafe()
    {
        $generatedString = RandomStringGenerator::generateSecureRandomString(32);

        $this->assertMatchesRegularExpression('/^[A-Za-z0-9\-_]+$/', $generatedString, "Generated string should be URL-safe");
    }

    /**
     * Test that the generated string is unique across multiple invocations.
     */
    public function testGeneratedSecureRandomStringIsUnique()
    {
        $generatedStrings = [];
        $length = 32;
        $iterations = 1000;

        for ($i = 0; $i < $iterations; $i++) {
            $generatedString = RandomStringGenerator::generateSecureRandomString($length);
            $this->assertNotContains($generatedString, $generatedStrings, "Generated string should be unique");
            $generatedStrings[] = $generatedString;
        }
    }

    /**
     * Test that the method handles a length of zero properly.
     */
    public function testGenerateSecureRandomStringWithZeroLengthThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('String length must be greater than zero.');

        RandomStringGenerator::generateSecureRandomString(0);
    }

    /**
     * Test that the method throws an exception for a negative length.
     */
    public function testGenerateSecureRandomStringWithNegativeLengthThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('String length must be greater than zero.');

        RandomStringGenerator::generateSecureRandomString(-1);
    }

    /**
     * Test that the method throws an exception when the provided string length is less than 22 character.
     */
    public function testGenerateSecureRandomStringWithSmallLengthsThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Generated string length should have a minimum of 22 characters.');

        RandomStringGenerator::generateSecureRandomString(1);
        RandomStringGenerator::generateSecureRandomString(2);
        RandomStringGenerator::generateSecureRandomString(16);
        RandomStringGenerator::generateSecureRandomString(21);
    }
}
