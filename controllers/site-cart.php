<?php

use \Werlich\Page;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryCart;
use \Werlich\Model\Repository\RepositoryAddress;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\Model\Repository\RepositoryOrder;

$app->get('/cart', function() {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $page = new Page();
    $page->setTpl("cart", [
        'cart' => $cart,
        'products' => $repositoryCart->getProducts($cart['idcart']),
        'error' => RepositoryCart::getMsgError()
    ]);
});

$app->get('/cart/:idproduct/add', function(int $idproduct) {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $qtd = (isset($_GET['qtd'])) ? (int) $_GET['qtd'] : 1;
    for ($i = 0; $i < $qtd; $i++) {
        $repositoryCart->addProduct($idproduct, $cart['idcart']);
    }
    header("Location: /ecommerce/cart");
    exit;
});

$app->get('/cart/:idproduct/minus', function(int $idproduct) {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $repositoryCart->removeProduct($idproduct, $cart['idcart']);
    header("Location: /ecommerce/cart");
    exit;
});

$app->get('/cart/:idproduct/remove', function(int $idproduct) {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $repositoryCart->removeProduct($idproduct, $cart['idcart'], true);
    header("Location: /ecommerce/cart");
    exit;
});

$app->post('/cart/freight', function() {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $repositoryCart->calcFrete($cart['idcart'], $_POST['zipcode']);
    header("Location: /ecommerce/cart");
    exit;
});

$app->get('/checkout', function() {
    RepositoryUser::checkLogin(true);
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $cep = (isset($_GET['zipcode'])) ? $_GET['zipcode'] : $cart['zipcode'];
    if (preg_replace("/[^0-9]/", "", $cep) !== $cart['zipcode']) {
        $repositoryCart->updateZipcode($cep, $cart['idcart']);
        $cart = RepositoryCart::get($cart['idcart']);
    }
    $address = RepositoryAddress::getEnderecoAPI($cep);
    $page = new Page();
    $page->setTpl("checkout", [
        'cart' => $cart,
        'products' => $repositoryCart->getProducts($cart['idcart']),
        'address' => $address,
        'error' => RepositoryCart::getMsgError()
    ]);
});



$app->post("/checkout", function() {
    RepositoryUser::checkLogin(true);
    if (!isset($_POST['zipcode']) || $_POST['zipcode'] === '') {
        RepositoryCart::setMsgError("Informe o CEP.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['logradouro']) || $_POST['logradouro'] === '') {
        RepositoryCart::setMsgError("Informe o endereço.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['bairro']) || $_POST['bairro'] === '') {
        RepositoryCart::setMsgError("Informe o bairro.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['cidade']) || $_POST['cidade'] === '') {
        RepositoryCart::setMsgError("Informe a cidade.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['estado']) || $_POST['estado'] === '') {
        RepositoryCart::setMsgError("Informe o estado.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['pais']) || $_POST['pais'] === '') {
        RepositoryCart::setMsgError("Informe o país.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    $endereco = $_POST;
    $cart = RepositoryCart::getFromSession();
    if (preg_replace("/[^0-9]/", "", $endereco['zipcode']) !== $cart['zipcode']) {
        $repositoryCart->updateZipcode($cep, $cart['idcart']);
    }
    $idaddress = RepositoryAddress::save($endereco, $cart['idcart'], $_SESSION[User::SESSION]->getIduser());
    $order = RepositoryOrder::initializeOrder($cart['idcart'], $_SESSION[User::SESSION]->getIduser(), $cart['vltotal'], $idaddress);
    session_regenerate_id();
    header("Location: /ecommerce/order/{$order}");
    exit;
});

$app->get('/checkout', function() {
    RepositoryUser::checkLogin(true);
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $cep = (isset($_GET['zipcode'])) ? $_GET['zipcode'] : $cart['zipcode'];
    if (preg_replace("/[^0-9]/", "", $cep) !== $cart['zipcode']) {
        $repositoryCart->updateZipcode($cep, $cart['idcart']);
        $cart = RepositoryCart::get($cart['idcart']);
    }
    $address = RepositoryAddress::getEnderecoAPI($cep);
    $page = new Page();
    $page->setTpl("checkout", [
        'cart' => $cart,
        'products' => $repositoryCart->getProducts($cart['idcart']),
        'address' => $address,
        'error' => RepositoryCart::getMsgError()
    ]);
});

$app->get('/order/:idorder', function(int $idorder) {
    RepositoryUser::checkLogin(true);
    $order = RepositoryOrder::get($idorder);
    $page = new Page();
    $page->setTpl("payment", [
        'order' => $order
    ]);
});

$app->get('/boleto/:idorder', function(int $idorder) {
    RepositoryUser::checkLogin(true);
    $order = RepositoryOrder::get($idorder);    
    require_once("assets/boletophp/boleto_itau.php");
});
