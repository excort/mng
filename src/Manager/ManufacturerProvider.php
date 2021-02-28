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

    public function createManufacturer(Manufacturer $manufacturer)
    {
        // https://docs.mongodb.com/php-library/v1.5/reference/bson/
        $res = $this->collection->insertOne($manufacturer);

        return $res->getInsertedId();
    }

    public function deleteManufacturer(Manufacturer $manufacturer)
    {
//        $collection = $this->mongoProvider->getManufacturerCollection();
//
//        $collection->insertOne([ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ]);
//
//        dump($collection);die();
    }

    public function clearManufacturer()
    {
        $this->mongoProvider->deleteCollection($this->collection->getCollectionName());
    }
}
