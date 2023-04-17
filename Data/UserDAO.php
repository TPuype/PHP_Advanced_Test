<?php

declare(strict_types=1);

namespace Data;

use \PDO;
use Data\DBConfig;
use Entities\User;
use Entities\Plaats;
use Exceptions\GebruikerBestaatNietException;
use Exceptions\WachtwoordIncorrectException;
use Exceptions\OngeldigePostcodeException;
use Entities\Bestelling;

class UserDAO
{
    public function emailReedsInGebruikCheck(string $email)
    {
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("SELECT * FROM gebruikers WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $dbh = null;
        return $rowCount;
    }

    public function register(User $user)
    {

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("INSERT INTO gebruikers (naam, voornaam, straat, huisnummer, woonplaatsId,  email, wachtwoord, bestelStatus) 
        VALUES (:naam, :voornaam, :straat, :huisnummer, :woonplaatsId, :email, :wachtwoord, :bestelStatus )");
        $stmt->bindValue(":naam", $user->getNaam());
        $stmt->bindValue(":voornaam", $user->getVoornaam());
        $stmt->bindValue(":straat", $user->getStraat());
        $stmt->bindValue(":huisnummer", $user->getHuisnummer());
        $stmt->bindValue(":woonplaatsId", $user->getWoonplaatsId());
        $stmt->bindValue(":email", $user->getEmail());
        $stmt->bindValue(":wachtwoord", $user->getWachtwoord());
        $stmt->bindValue(":bestelStatus", $user->getBestelStatus());
        $stmt->execute();

        $dbh = null;
    }

    public function getNaamByEmail(string $email): string
    {
        $sql = "select voornaam from gebruikers where email = :email";

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':email' => $email));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $naam = $rij["voornaam"];
        $dbh = null;
        return $naam;
    }

    public function loginUser($email, $wachtwoord)
    {
        $rowCount = $this->emailReedsInGebruikCheck($email);
        if ($rowCount == 0) {
            throw new GebruikerBestaatNietException();
        }

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("SELECT * FROM gebruikers WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $isWachtwoordCorrect = password_verify(
            $wachtwoord,
            $resultSet["wachtwoord"]
        );
        if (!$isWachtwoordCorrect) {
            throw new WachtwoordIncorrectException();
        }
        $user = new User(
            (int) $resultSet["id"],
            $resultSet["naam"],
            $resultSet["voornaam"],
            $resultSet["straat"],
            (int) $resultSet["huisnummer"],
            $resultSet["woonplaatsId"],
            $resultSet["email"],
            $resultSet["wachtwoord"],
            (bool) $resultSet["bestelStatus"]
        );

        $dbh = null;
        return $user;
    }

    public function ophalenWoonplaatsen() : array{
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $resultSet = $dbh->query("select * from plaatsen order by naam asc");

        $lijst = array();
        foreach ($resultSet as $rij) {
            $plaats = new Plaats((int) $rij["id"], $rij["naam"], $rij["postcode"]);
            array_push($lijst, $plaats);
        }
        $dbh = null;
        return $lijst;
    }

    /*public function getPlaatsIdByPostcode(string $postcode): int
    {
        $sql = "select id from plaatsen where postcode = :postcode";

        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':postcode' => $postcode));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        if($rij){
            $plaatsId = $rij["id"];
            $dbh = null;
            return $plaatsId;
        } else{
            throw new OngeldigePostcodeException();
        } 
    }*/

    public function getPlaatsenByPostcode(string $postcode): array
    {
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("SELECT * FROM plaatsen where postcode = :postcode");
        $stmt->execute(['postcode' => $postcode]);
        $resultSet = $stmt->fetchAll();

        $lijst = array();
        foreach ($resultSet as $rij) {
            $plaats = new Plaats((int) $rij["id"], $rij["naam"], $rij["postcode"]);
            array_push($lijst, $plaats);
        }
        return $lijst;
    }

    public function checkBestellingDatum(User $user, $afhaalDatum){
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

    
        $stmt = $dbh->prepare("SELECT * FROM bestellingen WHERE klantId = :klantId and afhaalDatum = :afhaalDatum");
        $stmt->bindValue(':klantId', $user->getId());
        $stmt->bindValue( ':afhaalDatum', $afhaalDatum);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $dbh = null;
        return $rowCount;
    }
    
    public function getAlleBestellingenVanUser(User $user) : array{
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("SELECT * FROM bestellingen WHERE klantId = :klantId");
        $stmt->bindValue(':klantId', $user->getId());
        $stmt->execute();
        $resultSet = $stmt->fetchAll();

        $lijst = array();
        foreach ($resultSet as $rij) {
            $bestelling = new Bestelling((int) $rij["id"], (int) $rij["klantId"], $rij["bestelDatum"], $rij["afhaalDatum"]);
            array_push($lijst, $bestelling);
        }
        return $lijst;
    }

    
}

