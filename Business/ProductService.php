<?php

declare(strict_types=1);

namespace Business;

use Data\ProductDAO;
use Entities\BestelLijn;


class ProductService
{

    public function getAll(): array
    {
        $productDAO = new ProductDAO();
        $lijst = $productDAO->getAll();
        return $lijst;
    }

    public function getNaamById(BestelLijn $bestelLijn) : string{
        $productDAO = new ProductDAO();
        $naam = $productDAO->getNaamById($bestelLijn);
        return $naam;
    }
}
