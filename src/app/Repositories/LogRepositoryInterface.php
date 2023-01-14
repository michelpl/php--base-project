<?php

namespace App\Repositories;

interface LogRepositoryInterface
{
    public static function alert(string|\Stringable $message, array $context = []): void;
    public static function critical(string|\Stringable $message, array $context = []): void;

    public static function error(string|\Stringable $message, array $context = []): void;

    public static function warning(string|\Stringable $message, array $context = []): void;

    public static function notice(string|\Stringable $message, array $context = []): void;

    public static function info(string|\Stringable $message, array $context = []): void;

    public static function debug(string|\Stringable $message, array $context = []): void;

    public static function log($level, string|\Stringable $message, array $context = []): void;
}