<?php
namespace App\Manager;

use App\Entity\Manufacturer;
use App\Service\MongoProvider;
use MongoDB\Collection;

class ManufacturerProvider
{
    private Collection $collection;

    public function __construct(
        private MongoProvider $mongoProvider
    ) {
        $this->collection = $this->mongoProvider->getManufacturerCollection();
    }

    public function createManufacturer(Manufacturer $manufacturer): mixed
    {
        $res = $this->collection->insertOne($manufacturer);

        return $res->getInsertedId();
    }

    public function clearManufacturer(): void
    {
        $this->mongoProvider->deleteCollection($this->collection->getCollectionName());
    }

    public function findManufacturers($filter = [], array $options = []): array
    {
        $cursor = $this->collection->find();

        return $cursor->toArray();
    }

    public function getManufacturer(array $params = [])
    {
        if (!$params) {
            return null;
        }

        $cursor = $this->collection->find(['_id' => '94301']);
        dump($cursor);die();
    }
}
