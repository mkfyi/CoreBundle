<?php

declare(strict_types=1);

namespace HighMinded\CoreBundle\Microservice\Transport;

use Exception;
use HighMinded\CoreBundle\Microservice\Exception\ConnectionFailedException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class Connection
{
    private readonly DataSource $dataSource;
    private ?AMQPStreamConnection $driver = null;

    public function __construct(?string $dataSourceName)
    {
        $this->dataSource = $dataSourceName !== null
            ? DataSource::fromName($dataSourceName)
            : 'amqp://rabbitmq:rabbitmq@rabbitmq:5672';
    }

    public function __destruct()
    {
        $this->driver->close();

        unset($this->driver);
    }

    /**
     * @throws ConnectionFailedException
     */
    public function getDriver(): AMQPStreamConnection
    {
        try {
            $this->driver ??= new AMQPStreamConnection(
                $this->dataSource->getHostname(),
                $this->dataSource->getPort(),
                $this->dataSource->getUsername(),
                $this->dataSource->getPassword(),
            );
        } catch (Exception) {
            throw new ConnectionFailedException($this->dataSource);
        }


        return $this->driver;
    }

    /**
     * @throws ConnectionFailedException
     */
    public function createChannel(): AMQPChannel
    {
        return $this->getDriver()->channel();
    }
}
