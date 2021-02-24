<?php
namespace App\Manager;

use App\Entity\Manufacturer;
use App\Service\MongoProvider;

class ManufacturerManager
{
    public function __construct(
        private MongoProvider $mongoProvider
    ) {}

    public function createManufacturer(Manufacturer $manufacturer)
    {
        $collection = $this->mongoProvider->getManufacturerCollection();

        // https://docs.mongodb.com/php-library/v1.5/reference/bson/
        $res = $collection->insertOne($manufacturer);

        dump($res, $test, $collection);die();
    }

    public function deleteManufacturer(Manufacturer $manufacturer)
    {
//        $collection = $this->mongoProvider->getManufacturerCollection();
//
//        $collection->insertOne([ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ]);
//
//        dump($collection);die();
    }
}
