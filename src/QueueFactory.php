<?php

declare(strict_types=1);

namespace BurakSevinc\Pueue;

use Aws\Sqs\SqsClient;
use RuntimeException;

class QueueFactory
{
    public static function create(string|null $queueUrl = null): Queue
    {
        $queueDriver = Config::get('QUEUE_DRIVER');
        if ($queueDriver === null) {
            throw new RuntimeException('Queue driver not supported');
        }

        if ($queueDriver === 'sqs') {
            if ($queueUrl === null) {
                throw new RuntimeException('Queue URL is required for SQS');
            }

            return self::createSqsQueue($queueUrl);
        }
    }

    private static function createSqsQueue(string $queueUrl): Queue
    {
        $region = Config::get('AWS_REGION');
        $key    = Config::get('AWS_ACCESS_KEY_ID');
        $secret = Config::get('AWS_SECRET_ACCESS_KEY');

        $client = new SqsClient([
            'region' => $region,
            'version' => 'latest',
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
        ]);

        return new SqsQueue($client, $queueUrl);
    }
}
