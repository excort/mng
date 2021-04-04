<?php

namespace App\Entity;

use DateTime;
use Exception;
use MongoDB\BSON\Persistable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

class Registration implements Persistable
{
    #[Assert\Uuid]
    private Uuid $id;

    private User | null $owner;

    private string $ownerName;

    #[Assert\Length(
        min: 8,
        max: 8,
    )]
    private string $registrationNumber;

    private DateTime $registrationDate;

    private Uuid $vehicleId;

    public function __construct(
        Uuid $id,
        ?User $owner,
        string $ownerName,
        string $registrationNumber,
        DateTime $registrationDate,
    ) {
        $this->id = Uuid::v4();
        ;
        $this->owner = $owner;
        $this->ownerName = $ownerName;
        $this->registrationNumber = $registrationNumber;
        $this->registrationDate = $registrationDate;
    }

    private function setId(Uuid $uuid): Registration
    {
        $this->id = $uuid;

        return $this;
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

    public function getVehicleId(): Uuid
    {
        return $this->vehicleId;
    }

    public function setVehicleId(Uuid $vehicleId): Registration
    {
        $this->vehicleId = $vehicleId;

        return $this;
    }

    function bsonSerialize()
    {
        return [
            '_id' => (string) $this->id,
            'owner' => $this->owner?->getId(), // https://www.php.net/releases/8.0/ru.php#nullsafe-operator
            'ownerName' => $this->ownerName,
            'registrationNumber' => $this->registrationNumber,
            'registrationDate' => $this->registrationDate->format('d.m.Y H:i:s'),
            'vehicleId' => (string) $this->vehicleId,
        ];
    }

    function bsonUnserialize(array $data)
    {
        if (!$userProvider = $GLOBALS['kernel']->getContainer()->get('App\Manager\UserProvider')) {
            throw new Exception('Error creating user token');
        };

        if (!$user = $userProvider->findUser(['_id' => $data['owner']])) {
            throw new Exception('Error getting vihicle');
        }

        $this
            ->setId(Uuid::fromString($data['_id']))
            ->setOwner($user)
            ->setOwnerName($data['ownerName'])
            ->setRegistrationNumber($data['registrationNumber'])
            ->setRegistrationDate(DateTime::createFromFormat('d.m.Y H:i:s', $data['registrationDate']))
            ->setVehicleId(Uuid::fromString($data['vehicleId']))
        ;
    }
}
