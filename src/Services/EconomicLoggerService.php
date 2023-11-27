<?php

namespace MorningTrain\Economic\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class EconomicLoggerService
{
    protected static array $loggers = [];

    public static function registerLogger(LoggerInterface $logger, string|array $logLevels = null): void
    {
        if ($logLevels === null) {
            static::$loggers['all'][] = $logger;
        }

        foreach ((array) $logLevels as $logLevel) {
            static::$loggers[$logLevel][] = $logger;
        }

    }

    public static function log($level, $message, array $context = []): void
    {
        foreach (static::getLoggersForLevel($level) as $logger) {
            $logger->log($level, $message, $context);
        }
    }

    /**
     * System is unusable.
     *
     * @param  string|\Stringable  $message
     */
    public static function emergency($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::EMERGENCY) as $logger) {
            $logger->emergency($message, $context);
        }
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param  string|\Stringable  $message
     */
    public static function alert($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::ALERT) as $logger) {
            $logger->alert($message, $context);
        }
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param  string|\Stringable  $message
     */
    public static function critical($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::CRITICAL) as $logger) {
            $logger->critical($message, $context);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param  string|\Stringable  $message
     */
    public static function error($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::ERROR) as $logger) {
            $logger->error($message, $context);
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param  string|\Stringable  $message
     */
    public static function warning($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::WARNING) as $logger) {
            $logger->warning($message, $context);
        }
    }

    /**
     * Normal but significant events.
     *
     * @param  string|\Stringable  $message
     */
    public static function notice($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::NOTICE) as $logger) {
            $logger->notice($message, $context);
        }
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param  string|\Stringable  $message
     */
    public static function info($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::INFO) as $logger) {
            $logger->info($message, $context);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param  string|\Stringable  $message
     */
    public static function debug($message, array $context = []): void
    {
        foreach (static::getLoggersForLevel(LogLevel::DEBUG) as $logger) {
            $logger->debug($message, $context);
        }
    }

    protected static function getLoggersForLevel(string $level): array
    {
        $loggers = [];

        if (! empty(static::$loggers[$level])) {
            $loggers = array_merge($loggers, static::$loggers[$level]);
        }

        if (! empty(static::$loggers['all'])) {
            $loggers = array_merge($loggers, static::$loggers['all']);
        }

        return $loggers;
    }
}
