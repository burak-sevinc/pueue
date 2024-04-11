<?php

declare(strict_types=1);

namespace BurakSevinc\Pueue\Tests;

use BurakSevinc\Pueue\QueueFactory;
use BurakSevinc\Pueue\QueueService;
use BurakSevinc\Pueue\SqsQueue;
use PHPUnit\Framework\TestCase;

class SqsQueueTest extends TestCase
{
    private SqsQueue $queue;
    private QueueService $queueService;

    private const QUEUE_URL = ''; // Add your SQS queue URL here

    protected function setUp(): void
    {
        $this->queue        = QueueFactory::create(self::QUEUE_URL);
        $this->queueService = new QueueService($this->queue);
    }

    protected function tearDown(): void
    {
        $this->queueService->deleteAll();
    }

    /** @test  */
    public function deleteSendAndReceiveSuccessfully(): void
    {
        // Clean up the queue
        $this->queueService->deleteAll();

        // Send a message to the queue
        $this->queueService->send('Test message');

        // Receive the message from the queue
        $item          = $this->queue->receive();
        $message       = $item['message'];
        $receiptHandle = $item['receiptHandle'];

        // Check if the message is the same as the one sent
        $this->assertEquals('Test message', $message);

        // Delete the message from the queue
        $this->queueService->delete($receiptHandle);

        // Check if the queue is empty
        $this->assertEmpty($this->queue->receive());

        // Send another message to the queue
        $this->queue->send('Another test message');
        $item          = $this->queue->receive();
        $message       = $item['message'];
        $receiptHandle = $item['receiptHandle'];

        // Check if the message is the same as the one sent
        $this->assertEquals('Another test message', $message);

        // Delete the message from the queue
        $this->queueService->delete($receiptHandle);

        // Check if the queue is empty
        $this->assertEmpty($this->queue->receive());
    }
}
