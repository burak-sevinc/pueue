# Pueue - PHP Simple Queue

Pueue, is a flexible PHP library for managing queues. It currently supports AWS SQS and plans to extend
support to Database and Redis queues. It provides an easy-to-use API for sending, receiving, and deleting messages.

## Installation

with Composer:

    composer require burak-sevinc/pueue  

## Usage

First, you need to create a Queue instance. You can use the QueueFactory for this:

```php
    use BurakSevinc\Pueue\QueueFactory;
    $queue = QueueFactory::create($queueUrl);  
```

Then, you can create a QueueService instance with the Queue instance:

```php
    use BurakSevinc\Pueue\QueueService;
    $queueService = new QueueService($queue);  
```

Now you can use the QueueService to interact with the queue:

```php
    // Send a message  
    $queueService->send('Hello, world!');  
      
    // Receive a message  
    $message = $queueService->receive();  
      
    // Receive all messages  
    $messages = $queueService->receiveAll();  
      
    // Delete a message  
    $receiptHandle = $message['receiptHandle'];
    $queueService->delete($receiptHandle);  
      
    // Delete all messages  
    $queueService->deleteAll();  
```

## Configuration

The QueueFactory uses the Config class to get configuration values. You need to set the following configuration values:

```
    QUEUE_DRIVER: The type of queue to use. Currently, only 'sqs' is supported.  
    AWS_REGION: The AWS region for SqsQueue.  
    AWS_ACCESS_KEY_ID: The AWS access key ID for SqsQueue.  
    AWS_SECRET_ACCESS_KEY: The AWS secret access key for SqsQueue.  
```

## Testing

You can run the tests with:

```php
    composer test
```

## License

**Pueue** is open-sourced software licensed under the **MIT** license.
