<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Uid\Uuid;

class Manufacturer
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

    public function getId(): Uuid
    {
        return $this->id;
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

    public function getSite(): Url
    {
        return $this->site;
    }

    public function setSite(Url $site): Manufacturer
    {
        $this->site = $site;
        return $this;
    }
}
