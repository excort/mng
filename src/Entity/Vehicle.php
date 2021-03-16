<?php

namespace App\Entity;

use App\Manager\ManufacturerProvider;
use Doctrine\Common\Collections\ArrayCollection;
use MongoDB\BSON\Persistable;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use \Symfony\Component\Uid\Uuid;

class Vehicle implements Persistable
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
        private ManufacturerProvider $manufacturerProvider,
    ) {
        $this->id = Uuid::v4();
        $this->name = $name;
        $this->model = $model;
        $this->productionDate = $productionDate;
        $this->vin = $vin;
        $this->manufacturer = $manufacturer;
        $this->registration = new ArrayCollection();
    }

    public function getId(): Uuid
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

    public function getRegistration(): ArrayCollection
    {
        return $this->registration;
    }

    public function addRegistration(Registration $registration): Vehicle
    {
        if (!$this->registration->contains($registration)) {
            $this->registration[] = $registration;
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): Vehicle
    {
        $this->registration->removeElement($registration);

        return $this;
    }

    function bsonSerialize()
    {
        return [
            '_id' => (string) $this->id,
            'name' => $this->name,
            'model' => $this->model,
            'productionDate' => $this->productionDate->format('d.m.Y H:i:s'),
            'vin' => $this->vin,
            'manufacturer' => $this->manufacturer->getId(),
        ];
    }

    // TODO вынести из сущностей в отдельный слой и работа через рефлексию
    function bsonUnserialize(array $data)
    {
        $manufacturer = $this->manufacturerProvider->getManufacturer(['_id' => $data['manufacturer']]);

        $this->id = $data['_id'];
        $this
            ->setName($data['name'])
            ->setModel($data['model'])
            ->setProductionDate(DateTime::createFromFormat('d.m.Y H:i:s',$data['productionDate']))
            ->setVin($data['vin'])
            ->setManufacturer($manufacturer)
        ;
    }
}
