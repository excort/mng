<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Uid\Uuid;
use MongoDB\BSON\Persistable;

class Manufacturer implements Persistable
{
    #[Assert\Uuid]
    private Uuid $id;

    private string $name;

    #[Assert\Url(
        message: 'The url {{ value }} is not a valid url',
    )]
    private string $site;

    public function __construct(
        Uuid $id,
        string $name,
        string $site,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->site = $site;
    }

    public function getId(): string
    {
        return (string) $this->id;
    }

    public function setId(Uuid $id): Manufacturer
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Manufacturer
    {
        $this->name = $name;
        return $this;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function setSite(string $site): Manufacturer
    {
        $this->site = $site;
        return $this;
    }

    function bsonSerialize()
    {
        return [
            '_id' => (string) $this->id,
            'name' => $this->name,
            'site' => $this->site,
        ];
    }

    function bsonUnserialize(array $data)
    {
        $this
            ->setId(Uuid::fromString($data['_id']))
            ->setName($data['name'])
            ->setSite($data['site'])
        ;
    }
}
