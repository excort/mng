<?php

namespace App\Entity;

use App\Manager\UserProvider;
use DateTime;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use MongoDB\BSON\Persistable;

class Auth implements Persistable
{
    #[Assert\Uuid]
    private Uuid $id;

    #[Assert\DateTime]
    private DateTime $createdAt;

    private User $user;

    private string $token;

    public function __construct(
        User $user,
        string $token,
    ) {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTime();
        $this->token = $token;
        $this->user = $user;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    function bsonSerialize()
    {
        return [
            '_id' => (string) $this->id,
            'createdAt' => $this->createdAt->format('d.m.Y H:i:s'),
            'userId' => $this->user->getId(),
            'token' => $this->token,
        ];
    }

    function bsonUnserialize(array $data)
    {
        if (!$userProvider = $GLOBALS['kernel']->getContainer()->get('App\Manager\UserProvider')) {
            throw new Exception('Error creating user token');
        };

        if (!$user = $userProvider->findUser(['_id' => $data['userId']])) {
            throw new Exception('Error creating user token');
        }

        $this->id = Uuid::fromString($data['_id']);
        $this->createdAt = DateTime::createFromFormat('d.m.Y H:i:s',$data['createdAt']);
        $this->user = $user;
        $this->token = $data['token'];
    }
}
