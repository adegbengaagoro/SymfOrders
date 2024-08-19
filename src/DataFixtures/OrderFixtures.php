<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Helpers\DateHelpers;
use App\Helpers\OrdersDataHelpers;
use App\Helpers\RandomStringGenerator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class OrderFixtures extends Fixture
{
    // public function load(ObjectManager $manager): void
    // {
    //     // $orderItems = [];
    //     // $orderTimestamps = DateHelpers::currentDateTime();

    //     // $firstOrder = new Order();
    //     // $firstOrder->setIdentifier(RandomStringGenerator::generateSecureRandomString(32));
    //     // $firstOrder->setName('Angela Jax');
    //     // $firstOrder->setDeliveryAddress('53 Elm Street');
    //     // $firstOrder->setOrderItems($orderItems);
    //     // $firstOrder->setDeliveryOption(OrdersDataHelpers::getRandomDeliveryOption());
    //     // $firstOrder->setEstimatedDeliveryDate(DateHelpers::generateDateInFuture(DateHelpers::generateRandomDayCount(), 'Y-m-d'));
    //     // $firstOrder->setOrderStatus('processing');
    //     // $firstOrder->setCreatedAt($orderTimestamps);
    //     // $firstOrder->setUpdatedAt($orderTimestamps);
    //     // $manager->persist($firstOrder);

    //     $manager->flush();
    // }

    public function load(ObjectManager $manager): void
    {
        // Create 10 orders
        for ($i = 0; $i < 5; $i++) {
            $orderItems = $this->generateRandomOrderItems();
            $orderTimestamps = DateHelpers::currentDateTime();

            $order = new Order();
            $order->setIdentifier(RandomStringGenerator::generateSecureRandomString(32));
            $order->setName($this->generateRandomName());
            $order->setDeliveryAddress($this->generateRandomAddress());
            $order->setOrderItems($orderItems);
            $order->setDeliveryOption(OrdersDataHelpers::getRandomDeliveryOption());
            $order->setEstimatedDeliveryDate($this->generateRandomHistoricalDates());
            $order->setOrderStatus($this->generateProcessingOrShippedDeliveryOption());
            $order->setCreatedAt($orderTimestamps);
            $order->setUpdatedAt($orderTimestamps);

            $manager->persist($order);
        }

        $manager->flush();
    }

    private function generateRandomName(): string
    {
        $names = ['Angela Jax', 'John Doe', 'Jane Smith', 'Bob Johnson', 'Alice Brown', 'Charlie Davis', 'Emily Wilson', 'George King', 'Hannah White', 'Isla Green'];
        return $names[array_rand($names)];
    }

    private function generateProcessingOrShippedDeliveryOption(): string
    {
        $deliveryOptions = ['processing', 'shipped'];
        return $deliveryOptions[array_rand($deliveryOptions)];
    }

    private function generateRandomHistoricalDates(): string
    {
        $pastDeliveryDates = ['2024-07-15', '2024-08-02', '2024-07-07', '2024-08-11', '2024-08-10', '2024-08-05'];
        return $pastDeliveryDates[array_rand($pastDeliveryDates)];
    }

    private function generateRandomAddress(): string
    {
        $addresses = ['53 Elm Street', '22 Oak Road', '145 Maple Ave', '77 Pine Lane', '102 Cedar Blvd', '88 Birch Street', '69 Willow Drive', '24 Poplar Court', '35 Spruce Terrace', '50 Chestnut Ave'];
        return $addresses[array_rand($addresses)];
    }

    private function generateRandomOrderItems(): array
    {
        $items = [];
        $numberOfItems = rand(1, 5); // Generate between 1 and 5 items

        for ($i = 0; $i < $numberOfItems; $i++) {
            $items[] = [
                'id' => (string) rand(10000, 999999), // Random item ID as a string
                'quantity' => rand(1, 10), // Random quantity between 1 and 10
            ];
        }

        return $items;
    }
}
