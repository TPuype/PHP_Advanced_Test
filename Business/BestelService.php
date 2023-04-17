<?php

declare(strict_types=1);

namespace Business;

use Data\BestelDAO;
use Entities\BestelLijn;
use Entities\Bestelling;
use Entities\User;

class BestelService
{
    public function getBestellingId(): int
    {
        $bestelDAO = new BestelDAO();
        $id = $bestelDAO->getLaatseBestellingId();
        return $id;
    }

    public function sendBestelLijn(BestelLijn $bestelLijn){
        $bestelDAO = new BestelDAO();
        $bestelDAO->sendBestelLijn($bestelLijn);
    }

    public function sendBestelling(Bestelling $bestelling){
        $bestelDAO = new BestelDAO();
        $bestelDAO->sendBestelling($bestelling);
    }

    public function bestellingAnnuleren(Bestelling $bestelling){
        $bestelDAO = new BestelDAO();
        $bestelDAO->bestellingAnnuleren($bestelling);
    }

    public function getBestellingById(int $id): Bestelling{
        $bestelDAO = new BestelDAO();
        $bestelling = $bestelDAO->getBestellingById($id);
        return $bestelling;
    }
}
