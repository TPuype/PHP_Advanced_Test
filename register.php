<?php

declare(strict_types=1);

spl_autoload_register();

use Business\UserService;
use Data\UserDAO;
use Entities\User;
use Exceptions\OngeldigePostcodeException;

require_once("vendor/autoload.php");

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);

$userService = new UserService();
$woonplaatsen = $userService->getWoonplaatsen();

$registered = false;

$errorTeller = 0;
$errorNaam = "";
$errorVoornaam = "";
$errorEmail = "";
$errorStraatNaam = "";
$errorHuisnummer = "";
$errorPostcode = "";
$errorWoonplaats = "";
$errorWachtwoord = "";

$naam = "";
$voornaam = "";
$straat = "";
$huisnummer = "";
$plaatsId = "";
$woonplaats = "";
$postcode = "";
$email = "";
$plaatsenByPostcode = [];

if (isset($_POST["btnZoekPostcode"])) {
    $naam = $_POST["txtNaam"];
    $voornaam = $_POST["txtVoornaam"];
    $email = $_POST["txtEmail"];
    $straat = $_POST["txtStraatNaam"];
    $huisnummer = $_POST["txtHuisnummer"];
    $postcode = $_POST["txtPostcode"];
    try {
        $plaatsenByPostcode = $userService->getPlaatsenByPostcode($_POST["txtPostcode"]);
        $plaatsenByPostcode = $userService->getPlaatsenByPostcode($_POST["txtPostcode"]);
    } catch (OngeldigePostcodeException $ex) {
        $errorPostcode = "Vul een correcte postcode in. ";
    }
}

if (isset($_POST["btnRegistreer"])) {

    if (!empty($_POST["txtNaam"])) {
        $naam = $_POST["txtNaam"];
    } else {
        $errorNaam = "Vul een naam in. ";
        $errorTeller++;
    }

    if (!empty($_POST["txtVoornaam"])) {
        $voornaam = $_POST["txtVoornaam"];
    } else {
        $errorVoornaam = "Vul een voornaam in. ";
        $errorTeller++;
    }

    if (!empty($_POST["txtEmail"])) {
        $email = $_POST["txtEmail"];
    } else {
        $errorEmail = "Voer een correct e-mail adres in. ";
        $errorTeller++;
    }

    if (!empty($_POST["txtStraatNaam"])) {
        $straat = $_POST["txtStraatNaam"];
    } else {
        $errorStraatNaam = "Vul een straatnaam in. ";
        $errorTeller++;
    }

    if (!empty($_POST["txtHuisnummer"])) {
        if (is_numeric((int)$huisnummer)) {
            $huisnummer = $_POST["txtHuisnummer"];
        } else {
            $errorHuisnummer = "Vul een correct huisnummer in. ";
            $errorTeller++;
        }
    } else {
        $errorHuisnummer = "Vul een huisnummer in. ";
        $errorTeller++;
    }

    if (!empty($_POST["selectWoonplaats"])) {
        $plaatsId = (int) $_POST["selectWoonplaats"];
    } else {
        $errorWoonplaats = "Kies een woonplaats. ";
        $errorTeller++;
    }

    if (!empty($_POST["txtPostcode"])) {
        try {
            $postcode = $_POST["txtPostcode"];
            $userService->checkPostcode($postcode);
        } catch (OngeldigePostcodeException $ex) {
            $errorPostcode = "Vul een correcte postcode in. ";
        }
    } else {
        $errorPostcode = "Vul een postcode in. ";
        $errorTeller++;
    }

    $wachtwoord = $userService->createWachtwoord(6);

    if ($errorTeller === 0) {
        $registered = true;
        $user = new User();
        $user->setNaam($naam);
        $user->setVoornaam($voornaam);
        $user->setStraat($straat);
        $user->setHuisnummer((int) $huisnummer);
        $user->setWoonplaatsId($plaatsId);
        $user->setWachtwoord($wachtwoord);
        $user->setEmail($email);
        $user->setBestelStatus(false);
        $userService = new UserService();
        $userService->register($user);
        print $twig->render(
            "registered.twig",
            array("wachtwoord" => $wachtwoord)
        );
    }
}

if (!$registered) {
    print $twig->render(
        "register.twig",
        array(
            "errorNaam" => $errorNaam,
            "errorVoornaam" => $errorVoornaam,
            "errorMail" => $errorEmail,
            "errorStraatnaam" => $errorStraatNaam,
            "errorHuisnummer" => $errorHuisnummer,
            "errorPostcode" => $errorPostcode,
            "errorWoonplaats" => $errorWoonplaats,
            "errorWachtwoord" => $errorWachtwoord,
            "naam" => $naam,
            "voornaam" => $voornaam,
            "email" => $email,
            "straat" => $straat,
            "huisnummer" => $huisnummer,
            "postcode" => $postcode,
            "woonplaatsen" => $woonplaatsen,
            "plaatsenByPostcode" => $plaatsenByPostcode,
        )
    );
}
