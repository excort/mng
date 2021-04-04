<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Uid\Uuid;
use MongoDB\BSON\Persistable;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\Exclude;

class User implements Persistable, UserInterface
{
    #[Assert\Uuid]
    private Uuid $id;

    #[Assert\Unique]
    private string $login;

    #[SecurityAssert\UserPassword(
        message: 'Wrong value for your password',
    )]
    /**
     * @Exclude
    */
    private string $pass;

    private string $fullName;

    private bool $admin;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): string
    {
        return (string) $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): User
    {
        $this->login = $login;

        return $this;
    }

    public function setPass(string $pass): User
    {
        $this->pass = $pass;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): User
    {
        $this->admin = $admin;
        return $this;
    }

    public function bsonSerialize()
    {
        return [
            '_id' => (string) $this->id,
            'login' => $this->login,
            'pass' => $this->pass,
            'fullName' => $this->fullName,
            'admin' => $this->admin,
        ];
    }

    public function bsonUnserialize(array $data)
    {
        $this->id = Uuid::fromString($data['_id']);
        $this->login = $data['login'];
        $this->pass = $data['pass'];
        $this->fullName = $data['fullName'];
        $this->admin = $data['admin'];
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        $roles[] = 'ROLE_USER';

        if ($this->admin) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->pass;
    }

    /**
     * @inheritDoc
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->login;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        $this->setPass('');
    }
}
