<?php

declare(strict_types=1);

namespace BurakSevinc\Pueue\QueueDrivers\SqsQueue;

use Aws\Sqs\SqsClient;
use BurakSevinc\Pueue\Queue;
use Throwable;

class SqsQueue implements Queue
{
    public function __construct(
        private readonly SqsClient $client,
        private readonly string $queueUrl,
    ) {
    }

    public function send(string $message): void
    {
        $this->client->sendMessage([
            'QueueUrl'    => $this->queueUrl,
            'MessageBody' => $message,
        ]);
    }

    /** @return array<string, string> */
    public function receive(): array
    {
        $result = $this->client->receiveMessage(['QueueUrl' => $this->queueUrl]);
        if ($result->get('Messages') !== null) {
            return [
                'message'       => $result->get('Messages')[0]['Body'],
                'receiptHandle' => $result->get('Messages')[0]['ReceiptHandle'],
            ];
        }

        return [];
    }

    /** @return array<array<string, string>> */
    public function receiveAll(): array
    {
        $messages = [];
        $result   = $this->client->receiveMessage(['QueueUrl' => $this->queueUrl]);

        if ($result->get('Messages') !== null) {
            while ($result->get('Messages') !== null) {
                $messages[] = [
                    'message'       => $result->get('Messages')[0]['Body'],
                    'receiptHandle' => $result->get('Messages')[0]['ReceiptHandle'],
                ];

                $result = $this->client->receiveMessage([
                    'QueueUrl'        => $this->queueUrl,
                    'ReceiptHandle'   => $result->get('Messages')[0]['ReceiptHandle'],
                    'MaxNumberOfMessages' => 1,
                ]);
            }
        }

        return $messages;
    }

    public function delete(string $receiptHandle): void
    {
        try {
            $this->client->deleteMessage([
                'QueueUrl'      => $this->queueUrl,
                'ReceiptHandle' => $receiptHandle,
            ]);
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function deleteAll(): void
    {
        $messages = $this->receiveAll();

        foreach ($messages as $message) {
            $this->delete($message['receiptHandle']);
        }
    }
}
