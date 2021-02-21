<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Uid\Uuid;

class User
{
    #[Assert\Uuid]
    private Uuid $id;

    #[Assert\Unique]
    private string $login;

    #[SecurityAssert\UserPassword(
        message: 'Wrong value for your password',
    )]
    private string $pass;

    private string $fullName;

    private bool $admin;

    public function __construct(string $login, string $pass, string $fullName, bool $admin)
    {
        $this->id = Uuid::v4();
        $this->login = $login;
        $this->pass = $pass;
        $this->fullName = $fullName;
        $this->admin = $admin;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
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
}
