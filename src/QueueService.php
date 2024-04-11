<?php

declare(strict_types=1);

namespace BurakSevinc\Pueue;

class QueueService
{
    public function __construct(private readonly Queue $queue)
    {
    }

    /**
     * This method sends a message to the queue.
     */
    public function send(string $message): void
    {
        $this->queue->send($message);
    }

    /**
     * This method receives a last message from the queue.
     * Returns an array with the message and the receipt handle.
     *
     * @return array<string, string>
     */
    public function receive(): array
    {
        return $this->queue->receive();
    }

    /**
     * This method receives all messages from the queue.
     * Returns an array with the messages and the receipt handles.
     *
     * @return array<array<string, string>>
     */
    public function receiveAll(): array
    {
        return $this->queue->receiveAll();
    }

    /**
     * This method deletes all messages from the queue.
     */
    public function deleteAll(): void
    {
        $this->queue->deleteAll();
    }

    /**
     * This method deletes a message from the queue by its receipt handle.
     */
    public function delete(string $receiptHandle): void
    {
        $this->queue->delete($receiptHandle);
    }
}
