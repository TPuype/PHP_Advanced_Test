<?php

declare(strict_types=1);

spl_autoload_register();

use Business\BestelService;
use Business\UserService;
use Data\UserDAO;
use Entities\User;
use Business\ProductService;
use Entities\BestelLijn;
use Entities\Bestelling;
use Exceptions\AnnulatieTeLaatException;

require_once("vendor/autoload.php");

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);

$test = 0;

session_start();

if (!isset($_SESSION["gebruiker"])) {
    header("Location: login.php");
    exit;
}

$gebruiker = unserialize($_SESSION["gebruiker"], ["User"]);

$userService = new UserService();
$bestelService = new BestelService();

$errorAnnul = "";


if (isset($_GET["action"]) && $_GET["action"] === "delete") {
    try {
        $id = $_GET["keuze"];
        $teAnnuleren = $bestelService->getBestellingById((int) $id);
        $bestelService->bestellingAnnuleren($teAnnuleren);
    } catch (AnnulatieTeLaatException $ex) {
        $errorAnnul = "Een bestelling kan ten laatste één dag voor de afhaaldatum worden geannuleerd.";
        $bestellingen = $userService->getBestellingenVanUser($gebruiker);
        print $twig->render(
            "overzicht.twig",
            array("bestellingen" => $bestellingen,
            "errorAnnulatie" => $errorAnnul)
        );
        exit();
    }
}


$bestellingen = $userService->getBestellingenVanUser($gebruiker);

print $twig->render(
    "overzicht.twig",
    array("bestellingen" => $bestellingen,
    "errorAnnulatie" => $errorAnnul)
);
