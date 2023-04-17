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
$message ="";

if(isset($_GET["action"]) && $_GET["action"] == "logout"){
    session_start();
    unset($_SESSION["gebruiker"]);
    $message = "U bent uitgelogd";
}

print $twig->render(
    "index.twig",
    array("message" => $message)
);