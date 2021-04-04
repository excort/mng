<?php

namespace App\Manager;

use App\Entity\Vehicle;
use App\Service\MongoProvider;
use JetBrains\PhpStorm\Pure;
use MongoDB\Collection;

class VehicleProvider
{
    private Collection $collection;

    #[Pure] public function __construct(
        private MongoProvider $mongoProvider
    ) {
        $this->collection = $this->mongoProvider->getVehicleCollection();
    }

    public function createVehicle(Vehicle $vehicle)
    {
        $res = $this->collection->insertOne($vehicle);

        return $res->getInsertedId();
    }

    public function clearVehicle()
    {
        $this->mongoProvider->deleteCollection($this->collection->getCollectionName());
    }

    public function findVehicle($filter = [], array $options = []): array
    {
        $cursor = $this->collection->find($filter, $options);

        return $cursor->toArray();
    }
}
