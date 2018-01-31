<?php

use \Werlich\Page;
use \Werlich\Model\Repository\RepositoryProduct;
use \Werlich\Model\Repository\RepositoryCategory;
use \Werlich\Model\Repository\RepositoryCart;

$app->get('/', function() {
    $repositoryProduct = new RepositoryProduct();
    $products = $repositoryProduct->listAll();

    $page = new Page();
    $page->setTpl("index", array(
        'products' => $products
    ));
});

$app->get('/categories/:idcategory', function($idcategory) {

    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

    $repositoryCategory = new RepositoryCategory();
    $pagination = $repositoryCategory->getProductsPage($page, 3, $idcategory);
    $pages = [];
    for ($i = 1; $i <= $pagination['pages']; $i++) {
        array_push($pages, [
            'link' => "/categories/{$idcategory}?page={$i}",
            'page' => $i
        ]);
    }

    $page = new Page();
    $page->setTpl("category", array(
        'category' => $repositoryCategory->find((int) $idcategory),
        'products' => $pagination['data'],
        'pages' => $pages
    ));
});

$app->get('/products/:url', function($url) {

    $repositoryProduct = new RepositoryProduct();
    $product = $repositoryProduct->getFromUrl($url);

    $page = new Page();
    $page->setTpl("product-detail", array(
        'product' => $product,
        'categories' => $repositoryProduct->getCategories($product['idproduct'])
    ));
});

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
    $qtd = (isset($_GET['qtd'])) ? (int)$_GET['qtd'] : 1;
    for($i = 0; $i < $qtd; $i++){
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