<?php
namespace App\Manager;

use App\Entity\Manufacturer;
use App\Entity\User;
use App\Service\MongoProvider;
use MongoDB\Collection;

class UserProvider
{
    private Collection $collection;

    public function __construct(
        private MongoProvider $mongoProvider
    ) {
        $this->collection = $this->mongoProvider->getUserCollection();
    }

    public function createUser(User $user): mixed
    {
        $res = $this->collection->insertOne($user);

        return $res->getInsertedId();
    }

    public function clearUsers()
    {
        $this->mongoProvider->deleteCollection($this->collection->getCollectionName());
    }

    public function findUsers($filter = [], array $options = []): array
    {
        $cursor = $this->collection->find();

        return $cursor->toArray();
    }
}
