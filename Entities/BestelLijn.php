<?php

declare(strict_types=1);



namespace Entities;



class BestelLijn
{
    private $id;
    private $bestelId;
    private $productId;
    private $aantal;
    private $prijsPerEenheid;


    public function __construct(
        $cid = null,
        $cbestelId = null,
        $cproductId = null,
        $caantal = null,
        $cprijsPerEenheid = null

    ) {
        $this->id = $cid;
        $this->bestelId = $cbestelId;
        $this->productId = $cproductId;
        $this->aantal = $caantal;
        $this->prijsPerEenheid = $cprijsPerEenheid;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getBestelId(): int
    {
        return $this->bestelId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAantal(): int
    {
        return $this->aantal;
    }

    public function getPrijsPerEenheid(): float
    {
        return $this->prijsPerEenheid;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setBestelId(int $bestelId)
    {
       $this->bestelId = $bestelId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function setAantal(int $aantal)
    {
        $this->aantal = $aantal;
    }

    public function setPrijsPerEenheid(float $prijsPerEenheid)
    {
        $this->prijsPerEenheid = $prijsPerEenheid;
    }
}
