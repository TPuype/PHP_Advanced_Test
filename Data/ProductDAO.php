<?php
declare(strict_types=1);

namespace Data;

use \PDO;
use Data\DBConfig;
use Entities\BestelLijn;
use Entities\Product;
use Exceptions\OngeldigProductIdException;


class ProductDAO
{
    public function getAll(): array
    {
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $resultSet = $dbh->query("select * from producten");

        $lijst = array();
        foreach ($resultSet as $rij) {
            $product = new Product((int) $rij["id"], $rij["naam"], (float) $rij["prijs"], $rij["img"]);
            array_push($lijst, $product);
        }
        $dbh = null;
        return $lijst;
    }

    public function getNaamById(BestelLijn $bestelLijn): string
    {
        $sql = "select * from producten where id = :productId";

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':productId' => $bestelLijn->getProductId()));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        if($rij){
            $naam = $rij["naam"];
            $dbh = null;
            return $naam;
        } else{
            throw new OngeldigProductIdException();
        } 
    }

}
