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

    public function getUserCollection(): Collection
    {
        return $this->client->{$this->dbName}->user;
    }

    public function getRegistrationCollection(): Collection
    {
        return $this->client->{$this->dbName}->registration;
    }

    public function getVehicleCollection(): Collection
    {
        return $this->client->{$this->dbName}->vehicle;
    }

    public function getAuthCollection(): Collection
    {
        return $this->client->{$this->dbName}->auth;
    }

    public function deleteCollection(string $collectionName)
    {
        return $this->client->{$this->dbName}->dropCollection($collectionName);
    }
}
