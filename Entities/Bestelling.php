<?php

declare(strict_types=1);



namespace Entities;

use DateTime;

class Bestelling
{
    private $id;
    private $klantId;
    private $bestelDatum;
    private $afhaalDatum;



    public function __construct($cid = null, $cklantId = null, $cbestelDatum = null, $cafhaalDatum = null)
    {
        $this->id = $cid;
        $this->klantId = $cklantId;
        $this->bestelDatum = $cbestelDatum;
        $this->afhaalDatum = $cafhaalDatum;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKlantId(): int
    {
        return $this->klantId;
    }

    public function getBestelDatum(): string
    {
        return $this->bestelDatum;
    }

    public function getAfhaalDatum(): string
    {
        return $this->afhaalDatum;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setKlantId(int $klantId)
    {
        $this->klantId = $klantId;
    }

    public function setBestelDatum(string $bestelDatum)
    {
        $this->bestelDatum = $bestelDatum;
    }

    public function setAfhaalDatum(string $afhaalDatum)
    {
        $this->afhaalDatum = $afhaalDatum;
    }
}
