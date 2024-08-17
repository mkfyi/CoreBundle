<?php

declare(strict_types=1);

namespace HighMinded\CoreBundle\Microservice\Transport;

use function substr;

final class DataSource
{
    private const string DATA_SOURCE_NAME_PATTERN = '/^amqp:\/{2}([A-Za-z0-9]+):(.+)@([A-Za-z0-9-_.]+)(:\d+)?$/';
    private string $hostname = '';
    private string $username = '';
    private string $password = '';
    private ?int $port = null;

    public static function fromName(string $dsn): self
    {
        $dataSource = new self();

        if (!preg_match(self::DATA_SOURCE_NAME_PATTERN, $dsn, $matches)) {
            throw new \RuntimeException();
        }

        return (new self())
            ->setHostname($matches[3])
            ->setUsername($matches[1])
            ->setPassword($matches[2])
            ->setPort(isset($matches[4]) ? (int)substr($matches[4], 1) : 5672);
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPort(): int
    {
        return $this->port ?? 15672;
    }

    public function setPort(?int $port): self
    {
        $this->port = $port;

        return $this;
    }
}
