<?php
namespace App\Manager;

use App\Entity\Registration;
use App\Service\MongoProvider;
use MongoDB\Collection;

class RegistrationProvider
{
    private Collection $collection;

    public function __construct(
        private MongoProvider $mongoProvider
    ) {
        $this->collection = $this->mongoProvider->getRegistrationCollection();
    }

    public function createRegistration(Registration $registration)
    {
        $res = $this->collection->insertOne($registration);

        return $res->getInsertedId();
    }

    public function clearRegistration()
    {
        $this->mongoProvider->deleteCollection($this->collection->getCollectionName());
    }

    public function findRegistrations($filter = [], array $options = []): array
    {
        $cursor = $this->collection->find($filter, $options);

        return $cursor->toArray();
    }
}
