<?php

namespace App\Manager;

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
        $cursor = $this->collection->find($filter, $options);

        return $cursor->toArray();
    }

    public function findUser($filter = [], array $options = []): ?User
    {
        if (!$filter) {
            return null;
        }

        /** @var User|null $user */
        $user = $this->collection->findOne($filter, $options);

        return $user;
    }
}
