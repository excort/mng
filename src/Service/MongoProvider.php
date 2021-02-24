<?php
namespace App\Service;

use MongoDB\Client;
use MongoDB\Collection;

class MongoProvider
{
    private Client $client;

    public function __construct(
        private string $dbName,
        private string $dbUser,
        private string $dbPass,
        private string $dbPort,
        private string $dbHost,
    ) {
        $this->client = new Client('mongodb://' . $dbUser . ':' . $dbPass . '@'. $dbHost . ':' . $dbPort);
    }

    public function getManufacturerCollection(): Collection
    {
        return $this->client->{$this->dbName}->manufacturer;
    }

}
