<?php

declare(strict_types=1);

spl_autoload_register();

use Business\UserService;
use Entities\User;
use Exceptions\GebruikerBestaatNietException;
use Exceptions\WachtwoordenKomenNietOvereenException;
use Exceptions\WachtwoordIncorrectException;

session_start();

require_once("vendor/autoload.php");

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);

$error = "";

$userService = new UserService();


if (isset($_POST["btnLogin"])) {

    $email = "";
    $wachtwoord = "";

    if (!empty($_POST["txtEmail"])) {
        $email = $_POST["txtEmail"];
    } else {
        $error .= "Het e-mailadres moet ingevuld worden.";
    }
    if (!empty($_POST["txtWachtwoord"])) {
        $wachtwoord = $_POST["txtWachtwoord"];
    } else {
        $error .= "Het wachtwoord moet ingevuld worden.";
    }

    if ($error == "") {
        try {
            $gebruiker = $userService->login($email, $wachtwoord);
            $_SESSION["gebruiker"] = serialize($gebruiker);
            print $twig->render(
                "loggedIn.twig",
                array("voornaam" => $gebruiker->getVoornaam())
            );
            exit();
        } catch (GebruikerBestaatNietException $ex) {
            $error .= "Er bestaat geen gebruiker met dit e-mailadres. ";
            print $twig->render(
                "login.twig",
                array("error" => $error)
            );
            exit();
        } catch (WachtwoordIncorrectException $ex) {
            $error .= "Het wachtwoord is niet correct. ";
            print $twig->render(
                "login.twig",
                array("error" => $error)
            );
            exit();
        }
    }
}

if (isset($_GET["action"]) && $_GET["action"] === "logout") {
    session_unset();
    header("location: login.php");
}

if (!isset($_SESSION["gebruiker"])) {
    print $twig->render(
        "login.twig",
        array("error" => $error)
    );
} elseif (!isset($_POST["btnLogin"]) && isset($_SESSION["gebruiker"])) {
    $gebruiker = unserialize($_SESSION["gebruiker"], ["User"]);
    print $twig->render(
        "loggedIn.twig",
        array("voornaam" => $gebruiker->getVoornaam())
    );
}
