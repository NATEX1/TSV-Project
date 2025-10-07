<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\WebProcessor;

class UserActivityFormatter
{
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $formatter = new LineFormatter(
                "%datetime% | %context.ip% | %context.username% | %message%\n",
                "Y-m-d H:i:s",
                true,
                true
            );
            $handler->setFormatter($formatter);
        }

        $logger->pushProcessor(function ($record) {
            $record['extra']['username'] = session('user.username') ?? (session('user.username') ?? 'guest');
            $record['extra']['ip'] = request()->ip() ?? 'unknown';
            return $record;
        });
    }
}
