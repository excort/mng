<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use MongoDB\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use \Symfony\Component\Uid\Uuid;
use \phpDocumentor\Reflection\Types\Collection;

class Vehicle
{
    #[Assert\Uuid]
    private Uuid $id;

    private string $name;

    private string $model;

    private DateTime $productionDate;

    private string $vin;

    private Manufacturer $manufacturer;

    /**
     * @var ArrayCollection<Registration>
    **/
    private ArrayCollection $registration;

    public function __construct(
        string $name,
        string $model,
        DateTime $productionDate,
        string $vin,
        Manufacturer $manufacturer,
        Registration $registration
    ) {
        $this->id = Uuid::v4();
        $this->name = $name;
        $this->model = $model;
        $this->productionDate = $productionDate;
        $this->vin = $vin;
        $this->manufacturer = $manufacturer;
        $this->registration = new ArrayCollection();
    }

    public function getId(): \Symfony\Component\Uid\UuidV4|Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Vehicle
    {
        $this->name = $name;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): Vehicle
    {
        $this->model = $model;
        return $this;
    }

    public function getProductionDate(): DateTime
    {
        return $this->productionDate;
    }

    public function setProductionDate(DateTime $productionDate): Vehicle
    {
        $this->productionDate = $productionDate;
        return $this;
    }

    public function getVin(): string
    {
        return $this->vin;
    }

    public function setVin(string $vin): Vehicle
    {
        $this->vin = $vin;
        return $this;
    }

    public function getManufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(Manufacturer $manufacturer): Vehicle
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }
}
