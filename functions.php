<?php

use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryCart;

function formatPrice(float $price) {
    return number_format($price, 2, ',', '.');
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function checkLogin() {
    if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION]->getIduser() > 0) {
        return $_SESSION[User::SESSION]->getIduser();
    } else {
        return 0;
    }
}

function getUserName() {
    return $_SESSION[User::SESSION]->getNome();
}

function getCartNrQtd() {
    $repositorioCart = new RepositoryCart();
    $cart = RepositoryCart::getFromSession();
    $totals = $repositorioCart->getProducts($cart['idcart']);
    $total = 0;
    foreach ($totals as $t){
         $total += $t['nrqtd'];
    }
    return $total;
}

function getCartVlSubTotal() {
    if (getCartNrQtd() > 0) {
        $cart = RepositoryCart::getFromSession();
        return formatPrice($cart['vltotal']);
    }else{
        return "0,00";
    }
}
