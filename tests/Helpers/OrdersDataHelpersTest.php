<?php

namespace App\Tests\Helpers;

use App\Helpers\OrdersDataHelpers;
use PHPUnit\Framework\TestCase;

class OrdersDataHelpersTest extends TestCase
{
    /**
     * Test if getRandomOrderStatus returns a valid status from the predefined list.
     */
    public function testGetRandomOrderStatusReturnsValidStatus()
    {
        $validStatuses = ['processing', 'shipped', 'delivered', 'returned', 'cancelled'];

        // Run the method multiple times to ensure that all statuses can be returned
        for ($i = 0; $i < 100; $i++) {
            $status = OrdersDataHelpers::getRandomOrderStatus();
            $this->assertContains($status, $validStatuses, "Returned status '$status' is not in the valid statuses list.");
        }
    }

    /**
     * Test if getRandomOrderStatus always returns a string.
     */
    public function testGetRandomOrderStatusReturnsString()
    {
        $status = OrdersDataHelpers::getRandomOrderStatus();
        $this->assertIsString($status, "Returned status is not a string.");
    }

    /**
     * Test if getRandomOrderStatus returns one of the predefined statuses.
     */
    public function testGetRandomOrderStatusHasValidValue()
    {
        $validStatuses = ['processing', 'shipped', 'delivered', 'returned', 'cancelled'];

        $status = OrdersDataHelpers::getRandomOrderStatus();

        $this->assertTrue(in_array($status, $validStatuses), "Returned status '$status' is not a valid order status.");
    }

    /**
     * Test the randomness of getRandomOrderStatus over multiple calls.
     */
    public function testGetRandomOrderStatusRandomness()
    {
        $samples = [];
        $numSamples = 100;

        for ($i = 0; $i < $numSamples; $i++) {
            $samples[] = OrdersDataHelpers::getRandomOrderStatus();
        }

        // Check if we got all possible statuses in the sample
        $uniqueStatuses = array_unique($samples);
        $validStatuses = ['processing', 'shipped', 'delivered', 'returned', 'cancelled'];

        $this->assertGreaterThanOrEqual(count($validStatuses), count($uniqueStatuses), "Not all statuses were returned in the sample.");
    }

    /**
     * Test if getRandomDeliveryOption returns a valid delivery option from the predefined list.
     */
    public function testGetRandomDeliveryOptionReturnsValidOption()
    {
        $validOptions = ['Standard Delivery', 'Express Delivery', 'Amazon Prime', 'Royal Mail'];

        // Run the method multiple times to ensure that all options can be returned
        for ($i = 0; $i < 100; $i++) {
            $option = OrdersDataHelpers::getRandomDeliveryOption();
            $this->assertContains($option, $validOptions, "Returned option '$option' is not in the valid delivery options list.");
        }
    }

    /**
     * Test if getRandomDeliveryOption always returns a string.
     */
    public function testGetRandomDeliveryOptionReturnsString()
    {
        $option = OrdersDataHelpers::getRandomDeliveryOption();
        $this->assertIsString($option, "Returned option is not a string.");
    }

    /**
     * Test if getRandomDeliveryOption returns one of the predefined delivery options.
     */
    public function testGetRandomDeliveryOptionHasValidValue()
    {
        $validOptions = ['Standard Delivery', 'Express Delivery', 'Amazon Prime', 'Royal Mail'];

        $option = OrdersDataHelpers::getRandomDeliveryOption();
        $this->assertTrue(in_array($option, $validOptions), "Returned option '$option' is not a valid delivery option.");
    }

    /**
     * Test the randomness of getRandomDeliveryOption over multiple calls.
     */
    public function testGetRandomDeliveryOptionRandomness()
    {
        $samples = [];
        $numSamples = 100;

        for ($i = 0; $i < $numSamples; $i++) {
            $samples[] = OrdersDataHelpers::getRandomDeliveryOption();
        }

        $uniqueOptions = array_unique($samples);
        $validOptions = ['Standard Delivery', 'Express Delivery', 'Amazon Prime', 'Royal Mail'];

        $this->assertGreaterThanOrEqual(count($validOptions), count($uniqueOptions), "Not all delivery options were returned in the sample.");
    }
}
