<?php

declare(strict_types=1);

namespace BurakSevinc\Pueue;

interface Queue
{
    public function send(string $message): void;

    /** @return array<string, string> */
    public function receive(): array;

    /** @return array<array<string, string>> */
    public function receiveAll(): array;

    public function delete(string $receiptHandle): void;

    public function deleteAll(): void;
}
