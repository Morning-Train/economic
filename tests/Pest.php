<?php

use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Services\EconomicLoggerService;
use Morningtrain\Economic\Tests\DummyEconomicDriver;
use Psr\Log\AbstractLogger;

uses()->beforeEach(function () {
    $driverInstance = new DummyEconomicDriver('secret-token', 'grant-token');
    $this->driver = Mockery::mock($driverInstance)->makePartial();

    EconomicApiService::setDriver($this->driver);
})->in('Unit');

function fixture(string $fixtureName, string $extension = 'json'): bool|string|array
{
    $filePath = __DIR__."/Fixtures/$fixtureName.$extension";

    if (! file_exists($filePath)) {
        throw new Exception("Fixture file not found: $filePath");
    }

    if ($extension === 'json') {
        return json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);
    }

    return file_get_contents($filePath);
}

/**
 * Run $callback with a logger attached to EconomicLoggerService and return everything it logged.
 *
 * @return array<int, array{level: mixed, message: string}>
 */
function economicLogs(callable $callback): array
{
    $logs = [];

    $logger = new class($logs) extends AbstractLogger
    {
        public function __construct(private array &$logs) {}

        public function log($level, \Stringable|string $message, array $context = []): void
        {
            $this->logs[] = ['level' => $level, 'message' => (string) $message];
        }
    };

    $loggers = new ReflectionProperty(EconomicLoggerService::class, 'loggers');
    $previous = $loggers->getValue();

    EconomicLoggerService::registerLogger($logger);

    try {
        $callback();
    } finally {
        $loggers->setValue(null, $previous);
    }

    return $logs;
}
