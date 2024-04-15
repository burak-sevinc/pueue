<?php

declare(strict_types=1);

namespace BurakSevinc\Pueue;

use Dotenv\Dotenv;
use RuntimeException;
use Throwable;

use function str_repeat;

class Config
{
    public function __construct()
    {
    }

    private static function load(): void
    {
        $maxAttempts = 5;
        for ($attempt = 0; $attempt <= $maxAttempts; $attempt++) {
            try {
                $dotenv = Dotenv::createImmutable(__DIR__ . str_repeat('/..', $attempt));
                $dotenv->load();

                return;
            } catch (Throwable) {
            }
        }

        throw new RuntimeException('Could not find .env file');
    }

    public static function get(string $key): mixed
    {
        self::load();

        return $_ENV[$key] ?? null;
    }
}
