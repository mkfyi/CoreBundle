<?php

declare(strict_types=1);

namespace HighMinded\CoreBundle\Microservice\Exception;

use HighMinded\CoreBundle\Microservice\Transport\DataSource;
use RuntimeException;

final class ConnectionFailedException extends RuntimeException
{
    public function __construct(DataSource $dataSource)
    {
        parent::__construct("Failed to connect to {$dataSource->getHostname()}");
    }
}
