<?php

use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\Model\Entities\User;

function formatPrice(float $price){
    return number_format($price, 2, ',', '.');
}

function checkLogin(){
    if(isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION]->getIduser() > 0){
        return $_SESSION[User::SESSION]->getIduser();
    } else {
        return 0;
    }
    
}

function getUserName(){
    return $_SESSION[User::SESSION]->getNome();
}
