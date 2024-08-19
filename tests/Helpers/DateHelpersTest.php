<?php

namespace App\Tests\Helpers;

use App\Helpers\DateHelpers;
use PHPUnit\Framework\TestCase;

class DateHelpersTest extends TestCase
{
    /**
     * Tests generateDateInFuture() with default format
     */
    public function testGenerateDateInFutureWithDefaultFormat()
    {
        $numberOfDays = 10;
        $expectedDate = (new \DateTime())->modify("+$numberOfDays days")->format('Y-m-d');
        $actualDate = DateHelpers::generateDateInFuture($numberOfDays, 'Y-m-d');

        $this->assertEquals($expectedDate, $actualDate);
    }

    /**
     * Tests generateDateInFuture() with custom format
     */
    public function testGenerateDateInFutureWithCustomFormat()
    {
        $numberOfDays = 10;
        $expectedDate = (new \DateTime())->modify("+$numberOfDays days")->format('d/m/Y');
        $actualDate = DateHelpers::generateDateInFuture($numberOfDays, 'd/m/Y');

        $this->assertEquals($expectedDate, $actualDate);
    }

    /**
     * Tests generateDateInFuture() when 0 days are passed in
     */
    public function testGenerateDateInFutureWithZeroDays()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of days in the future must be greater than 0.");
        DateHelpers::generateDateInFuture(0, 'Y-m-d');
    }

    /**
     * Tests generateDateInFuture() when negative days are passed in
     */
    public function testGenerateDateInFutureWithNegativeDays()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of days in the future must be greater than 0.");
        DateHelpers::generateDateInFuture(-5, 'Y-m-d');
    }

    /**
     * Tests generateRandomDayCount() to ensure values are within 1 to 7.
     */
    public function testGenerateRandomDayCount()
    {
        for ($i = 0; $i < 100; $i++) {
            $randomDayCount = DateHelpers::generateRandomDayCount();
            $this->assertGreaterThanOrEqual(1, $randomDayCount);
            $this->assertLessThanOrEqual(7, $randomDayCount);
        }
    }

    /**
     * Tests currentDateTime() to verify correct timezone
     */
    public function testCurrentDateTimeTimezone()
    {
        $currentDateTime = DateHelpers::currentDateTime();
        $expectedTimezone = new \DateTimeZone('Europe/London');

        // Check if the timezone is correct
        $this->assertEquals($expectedTimezone->getName(), $currentDateTime->getTimezone()->getName());
    }

    /**
     * Tests currentDateTime() to verify proximity to current time.
     */
    public function testCurrentDateTimeWithinMargin()
    {
        $currentDateTime = DateHelpers::currentDateTime();
        $now = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $diffInSeconds = abs($now->getTimestamp() - $currentDateTime->getTimestamp());

        $this->assertLessThan(5, $diffInSeconds, 'The current datetime should be within 5 seconds of now');
    }

    /**
     * Test checkDateFormat() with valid date formats for a normal and leap year
     */
    public function testCheckDateFormatWithValidDates()
    {
        $this->assertTrue(DateHelpers::checkDateFormat('2024-08-19'));
        $this->assertTrue(DateHelpers::checkDateFormat('2000-02-29'));
    }

    /**
     * Test checkDateFormat() with invalid date formats
     */
    public function testCheckDateFormatWithInvalidDates()
    {
        $this->assertFalse(DateHelpers::checkDateFormat('2024-13-19')); // Invalid month
        $this->assertFalse(DateHelpers::checkDateFormat('2024-02-30')); // Invalid day
        $this->assertFalse(DateHelpers::checkDateFormat('19-08-2024')); // Incorrect format
        $this->assertFalse(DateHelpers::checkDateFormat('2024/08/19')); // Incorrect format
    }
}
