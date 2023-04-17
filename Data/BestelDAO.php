<?php

declare(strict_types=1);

namespace Data;

use \PDO;
use Data\DBConfig;
use Entities\BestelLijn;
use Entities\Product;
use Entities\Bestelling;
use Exceptions\AnnulatieTeLaatException;

class BestelDAO
{
    public function getLaatseBestellingId()
    {

        $sql = "select auto_increment from information_schema.TABLES where TABLE_NAME ='bestellingen' and TABLE_SCHEMA='eindoefening_php'";

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $rij["auto_increment"];
        $dbh = null;
        return $id;
    }

    public function sendBestelLijn(BestelLijn $bestelLijn)
    {

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("INSERT INTO bestellijnen (bestelId, productId, aantal, prijsPerEenheid) 
        VALUES (:bestelId, :productId, :aantal, :prijsPerEenheid)");
        $stmt->bindValue(":bestelId", $bestelLijn->getBestelId());
        $stmt->bindValue(":productId", $bestelLijn->getProductId());
        $stmt->bindValue(":aantal", $bestelLijn->getAantal());
        $stmt->bindValue(":prijsPerEenheid", $bestelLijn->getPrijsPerEenheid());

        $stmt->execute();

        $dbh = null;
    }

    public function sendBestelling(Bestelling $bestelling)
    {

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("INSERT INTO bestellingen (klantId, bestelDatum, afhaalDatum) 
        VALUES (:klantId, :bestelDatum, :afhaalDatum)");
        $stmt->bindValue(":klantId", $bestelling->getKlantId());
        $stmt->bindValue(":bestelDatum", $bestelling->getBestelDatum());
        $stmt->bindValue(":afhaalDatum", $bestelling->getAfhaalDatum());

        $stmt->execute();

        $dbh = null;
    }

    public function bestellingAnnuleren(bestelling $bestelling)
    {
        $sql = "delete from bestellingen where id = :id";

        $today = date("Y-m-d");
        $date = $bestelling->getAfhaalDatum();

        if (date('Y-m-d', strtotime($date . ' -1  days')) >= $today) {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $sql = "delete from bestelLijnen where bestelId = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":id", $bestelling->getId());
            $stmt->execute();
            $sql = "delete from bestellingen where id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":id", $bestelling->getId());
            $stmt->execute();
            $dbh = null;
        } else{
            throw new AnnulatieTeLaatException();
        }
    }

    public function getBestellingById(int $id): Bestelling
    {
        $sql = "select * from bestellingen where id = :id";

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $bestelling = new Bestelling((int) $rij["id"], (int) $rij["klantId"], $rij["bestelDatum"], $rij["afhaalDatum"]);
        return $bestelling;
    }
}
