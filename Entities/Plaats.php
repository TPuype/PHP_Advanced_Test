<?php

declare(strict_types=1);



namespace Entities;



class Plaats {
   private int $id;
   private string $naam;
   private string $postcode;

   public function __construct(int $id, string $naam, string $postcode)
   {
    $this->id = $id;
    $this->naam = $naam;
    $this->postcode = $postcode;
   }

   public function getId() : int{
    return $this->id;
   }

   public function getNaam() : string{
    return $this->naam;
   }

   public function getPostcode() : string{
    return $this->postcode;
   }

}
