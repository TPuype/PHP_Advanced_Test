<?php

declare(strict_types=1);

namespace Entities;

class Product
{
    private int $id;
    private string $naam;
    private float $prijsPerEenheid;
    private string $imgUrl;


    public function __construct(int $id, string $naam, float $prijsPerEenheid, string $imgUrl)
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->prijsPerEenheid = $prijsPerEenheid;
        $this->imgUrl = $imgUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getPrijsPerEenheid(): float
    {
        return $this->prijsPerEenheid;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /*public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setNaam(string $naam)
    {
        $this->naam = $naam;
    }

    public function setPrijsPerEenheid(float $prijsPerEenheid)
    {
        $this->prijsPerEenheid = $prijsPerEenheid;
    }

    public function setImgUrl(string)
    {
        return $this->imgUrl;
    }*/


}
