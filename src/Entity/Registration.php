<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Uid\Uuid;

class Registration
{
    #[Assert\Uuid]
    private Uuid $id;

    private User|null $owner;

    private string $ownerName;

    #[Assert\Length(
        min: 8,
        max: 8,
    )]
    private string $registrationNumber;

    private DateTime $registrationDate;

    public function __construct(
        Uuid $id,
        ?User $owner,
        string $ownerName,
        string $registrationNumber,
        DateTime $registrationDate
    ) {
        $this->id = Uuid::v4();;
        $this->owner = $owner;
        $this->ownerName = $ownerName;
        $this->registrationNumber = $registrationNumber;
        $this->registrationDate = $registrationDate;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): Registration
    {
        $this->owner = $owner;
        return $this;
    }

    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    public function setOwnerName(string $ownerName): Registration
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(string $registrationNumber): Registration
    {
        $this->registrationNumber = $registrationNumber;
        return $this;
    }

    public function getRegistrationDate(): DateTime
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(DateTime $registrationDate): Registration
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }
}
