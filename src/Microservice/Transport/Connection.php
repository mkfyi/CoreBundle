<?php

declare(strict_types=1);

namespace HighMinded\CoreBundle\Microservice\Transport;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class Connection
{
    private readonly DataSource $dataSource;
    private AMQPStreamConnection $driver;

    public function __construct(string $dataSourceName)
    {
        $this->dataSource = DataSource::fromName($dataSourceName);

        $this->driver = new AMQPStreamConnection(
            $this->dataSource->getHostname(),
            $this->dataSource->getPort(),
            $this->dataSource->getUsername(),
            $this->dataSource->getPassword(),
        );
    }

    public function __destruct()
    {
        $this->driver->close();

        unset($this->driver);
    }

    public function getDriver(): AMQPStreamConnection
    {
        return $this->driver;
    }

    public function createChannel(): AMQPChannel
    {
        return $this->driver->channel();
    }
}
