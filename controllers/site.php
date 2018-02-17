<?php

use \Werlich\Page;
use \Werlich\Model\Repository\RepositoryProduct;
use \Werlich\Model\Repository\RepositoryUser;

$app->get('/', function() {
    $repositoryProduct = new RepositoryProduct();
    $products = $repositoryProduct->listAll();
    $page = new Page();
    $page->setTpl("index", array(
        'products' => $products
    ));
});

$app->get('/logout', function() {
    RepositoryUser::logout();
    header("Location: /ecommerce/login");
    exit;
});

$app->get("/login", function() {
    $page = new Page();
    $page->setTpl("login", [
        'error' => RepositoryUser::getMsgError(),
        'errorRegister' => RepositoryUser::getMsgError(),
        'registerValues' => (isset($_SESSION['registerValues'])) ? $_SESSION['registerValues'] : ['nome' => '', 'email' => '', 'phone' => '']
    ]);
});

$app->post('/login', function() {
    try {
        RepositoryUser::login($_POST["login"], $_POST["password"]);
    } catch (Exception $ex) {
        RepositoryUser::setMsgError($ex->getMessage());
    }
    header("Location: /ecommerce/checkout");
    exit;
});
