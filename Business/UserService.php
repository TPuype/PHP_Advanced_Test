<?php

declare(strict_types=1);

namespace Business;

use Data\UserDAO;
use Entities\User;
use Exceptions\GebruikerBestaatAlException;
use Exceptions\OngeldigePostcodeException;

class UserService{
    public function checkEmailInTabel($email){
        $userDAO = new UserDAO();
        $check = $userDAO->emailReedsInGebruikCheck($email);
        return $check;
    }

    public function register(User $user){
        $check = $this->checkEmailInTabel($user->getEmail());
        $userDAO = new UserDAO();
        if($check > 0){
            throw new GebruikerBestaatAlException();
        } else{
            $userDAO->register($user);
        }
    }

    public function getVoornaamByEmail(string $email) : string{
        $userDAO = new UserDAO();
        $voornaam = $userDAO->getNaamByEmail(($email));
        return $voornaam;
    }

    public function login($email, $wachtwoord) : User{
        $userDAO = new UserDAO();
        $user = $userDAO->loginUser($email, $wachtwoord);
        return $user;
    }

    public function getWoonplaatsen() :array{
        $userDAO = new UserDAO();
        $woonplaatsen = $userDAO->OphalenWoonplaatsen();
        return $woonplaatsen;
    }

    public function checkPostcode(string $postcode){
        $userDAO = new UserDAO();
        $lijst = $userDAO->getPlaatsenByPostcode($postcode);
        if(count($lijst) == 0){
            throw new OngeldigePostcodeException();
        }
    }

    public function getPlaatsenByPostcode(string $postcode) : array{
        $userDAO = new UserDAO();
        $plaatsen = $userDAO->getPlaatsenByPostcode($postcode);
        if(count($plaatsen) > 0){
            return $plaatsen;
        } else{
            throw new OngeldigePostcodeException();
        }
    }

    public function checkBestellingPerDag(User $user, $afhaalDatum) : bool{
        $userDAO = new UserDAO();
        $check = $userDAO->checkBestellingDatum($user, $afhaalDatum);
        if($check > 0){
            $reedsBesteldDieDag = true;
        } else{
            $reedsBesteldDieDag = false;
        }
        return $reedsBesteldDieDag;
    }

    public function getBestellingenVanUser(user $user) : array{
        $userDAO = new UserDAO();
        $lijst = $userDAO->getAlleBestellingenVanUser($user);
        return $lijst;
    }

    public function createWachtwoord(int $length): string{

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $ww = substr(str_shuffle($chars),0,$length);
        return $ww;
    }

}