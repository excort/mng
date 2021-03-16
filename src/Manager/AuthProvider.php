<?php
namespace App\Manager;

use App\Entity\Auth;
use App\Entity\User;
use App\Service\MongoProvider;
use MongoDB\Collection;

class AuthProvider
{
    private Collection $collection;

    public function __construct(
        private MongoProvider $mongoProvider
    ) {
        $this->collection = $this->mongoProvider->getAuthCollection();
    }

    public function createAuth(Auth $auth): mixed
    {
        $res = $this->collection->insertOne($auth);

        return $res->getInsertedId();
    }

    public function findAuth($filter = [], array $options = []): ?Auth
    {
        return $this->collection->findOne($filter, $options);
    }

    public function getLastAuthByUser(User $user): ?Auth
    {
        $auth = $this->collection->find(
            ['userId' => $user->getId()],
            [
                'sort' => ['createdAt' => -1],
                'limit' => 1,
            ]
        );

        return $auth->toArray()[0] ?? null;
    }


}
