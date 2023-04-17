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

if(!isset($_SESSION["afhaalDatum"])){
    $_SESSION["afhaalDatum"] = "";
}

$gebruiker = unserialize($_SESSION["gebruiker"], ["User"]);

$userService = new UserService();
$productService = new ProductService();

$producten = $productService->getAll();
$keuzeGemaakt = false;
$errorKeuze = "";

$Date = date('Y-m-d');
$DateMin = date('Y-m-d', strtotime($Date . ' + 1 days'));
$DateMax = date('Y-m-d', strtotime($Date . ' + 3 days'));

$_SESSION["bestelLijnen"] = [];

if (!isset($_SESSION["bestelling"])) {
    $_SESSION["bestelling"] = new Bestelling();
}

if (!isset($_SESSION["winkelmand"])) {
    $_SESSION["winkelmand"] = [];
}


$bs = new BestelService();

if (isset($_POST["btnBestellen"])) {
    $_SESSION["afhaalDatum"] = $_POST["datumPicker"];
    $check = $userService->checkBestellingPerDag($gebruiker, $_SESSION["afhaalDatum"]);
    if ($check) {
        $error = "U hebt reeds een bestelling geplaats voor deze datum.";
        print $twig->render(
            "shop.twig",
            array(
                "producten" => $producten,
                "error" => $error
            )
        );
        exit();
    }
    $_SESSION["bestelling"]->setKlantId($gebruiker->getId());
    $_SESSION["bestelling"]->setBestelDatum($Date);
    $_SESSION["bestelling"]->setAfhaalDatum($_SESSION["afhaalDatum"]);

    $totaalPrijs = 0;

    $bestelId = $bs->getBestellingId();
    $aankoopTeller = 0;

    $_SESSION["winkelmand"] = [];

    foreach ($producten as $product) {
        if (isset($_POST[$product->getId()]) && $_POST[$product->getId()] > 0) {
            $aankoopTeller++;
            $_SESSION["winkelmand"][$product->getId()] = $_POST[$product->getId()];
            $bestelLijn = new BestelLijn();
            $bestelLijn->setBestelId((int) $bestelId);
            $bestelLijn->setProductId((int) $product->getId());
            $bestelLijn->setAantal((int) $_POST[$product->getId()]);
            $bestelLijn->setPrijsPerEenheid($product->getPrijsPerEenheid());
            $totaalPrijs += $bestelLijn->getAantal() * $bestelLijn->getPrijsPerEenheid();
            array_push($_SESSION["bestelLijnen"], $bestelLijn);
        }
    }
    if ($aankoopTeller === 0) {
        $errorKeuze = "Je hebt geen product geselecteerd. Maak uw keuze.";
        $keuzeGemaakt = false;
    } else {
        $keuzeGemaakt = true;
        print $twig->render(
            "afrekening.twig",
            array(
                "bestelLijnen" => $_SESSION["bestelLijnen"],
                "bestelling" => $_SESSION["bestelling"],
                "bestelService" => $bs,
                "productService" => $productService,
                "totaal" => $totaalPrijs,
            )
        );
    }
}
if (isset($_POST["btnAfrekenen"])) {
    $bs->sendBestelling($_SESSION["bestelling"]);
    foreach ($_SESSION["bestelLijnen"] as $bestelLijn) {
        $bs->sendBestelLijn($bestelLijn);
    }
    unset($_SESSION["bestelling"]);
    unset($_SESSION["bestelLijnen"]);
    unset($_SESSION["winkelmand"]);
    unset($_SESSION["afhaalDatum"]);
    print $twig->render(
        "result.twig",
        array()
    );
    exit();
}

if (!$keuzeGemaakt) {
    print $twig->render(
        "shop.twig",
        array(
            "producten" => $producten,
            "datumMin" => $DateMin,
            "datumMax" => $DateMax,
            "errorKeuze" => $errorKeuze,
            "winkelmand" => $_SESSION["winkelmand"],
            "afhaalDatum" => $_SESSION["afhaalDatum"]
        )
    );
}
