<?php

declare(strict_types=1);



namespace Entities;

use Exceptions\OngeldigEmailadresException;
use Exceptions\WachtwoordenKomenNietOvereenException;

class User {
    private $id;
    private $naam;
    private $voornaam;
    private $straat;
    private $huisnummer;
    private $woonplaatsId;
    private $email;
    private $wachtwoord;
    private $bestelStatus;

    public function __construct(
        $cid = null,
        $cnaam = null,
        $cvoornaam = null,
        $cstraat = null,
        $chuisnummer = null,
        $cwoonplaatsId = null,
        $cemail = null,
        $cwachtwoord = null,
        $cbestelStatus = null
    ) {
        $this->id = $cid;
        $this->naam = $cnaam;
        $this->voornaam = $cvoornaam;
        $this->straat = $cstraat;
        $this->huisnummer = $chuisnummer;
        $this->woonplaatsId = $cwoonplaatsId;
        $this->email = $cemail;
        $this->wachtwoord = $cwachtwoord;
        $this->bestelStatus = $cbestelStatus;
    }

    function getId(): int {
        return $this->id;
    }

    function getNaam(): string {
        return $this->naam;
    }
    
    function getVoornaam(): string {
        return $this->voornaam;
    }

    function getStraat(): string {
        return $this->straat;
    }

    function getHuisnummer(): int {
        return $this->huisnummer;
    }
    
    function getWoonplaatsId(): int {
        return $this->woonplaatsId;
    }
    
    function getEmail(): string {
        return $this->email;
    }

    function getWachtwoord(): string {
        return $this->wachtwoord;
    }

    function getBestelStatus() : bool{
        return $this->bestelStatus;
    }

    function setNaam(string $naam) {
        $this->naam = $naam;
    }

    function setVoornaam(string $voornaam) {
        $this->voornaam = $voornaam;
    }

    function setStraat(string $straat) {
        $this->straat = $straat;
    }

    function setHuisnummer(int $huisnummer) {
        $this->huisnummer = $huisnummer;
    }

    function setWoonplaatsId(int $woonplaatsId) {
        $this->woonplaatsId = $woonplaatsId;
    }

    function setEmail(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new OngeldigEmailadresException();
        }
        $this->email = $email;
    }

    function setWachtwoord(string $wachtwoord) {
        $this->wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
    }

    function setBestelStatus(bool $bestelStatus) {
        $this->bestelStatus = $bestelStatus;
    }

}
