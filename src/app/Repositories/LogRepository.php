<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Repositories\LogRepositoryInterface;

class LogRepository implements LogRepositoryInterface
{
    public function __construct()
    {
    }

    public static function alert(string|\Stringable $message, array $context = []): void
    {
        Log::alert($message, $context);
    }

    public static function critical(string|\Stringable $message, array $context = []): void
    {
        Log::critical($message, $context);
    }

    public static function error(string|\Stringable $message, array $context = []): void
    {
        Log::error($message, $context);
    }

    public static function warning(string|\Stringable $message, array $context = []): void
    {
        Log::warning($message, $context);
    }

    public static function notice(string|\Stringable $message, array $context = []): void
    {
        Log::notice($message, $context);
    }

    public static function info(string|\Stringable $message, array $context = []): void
    {
        Log::info($message, $context);
    }

    public static function debug(string|\Stringable $message, array $context = []): void
    {
        Log::debug($message, $context);
    }

    public static function log($level, string|\Stringable $message, array $context = []): void
    {
        Log::log($level, $message, $context);
    }
}