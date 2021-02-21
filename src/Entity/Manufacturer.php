<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints as Assert;

class Manufacturer
{
    #[Assert\Uuid]
    private Uuid $id;

    private string $name;

    #[Assert\Url(
        message: 'The url {{ value }} is not a valid url',
    )]
    private Url $site;

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
