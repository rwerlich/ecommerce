<?php

use \Werlich\Model\Entities\Product;
use \Werlich\Model\Repository\RepositoryProduct;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\PageAdmin;

$app->get('/admin/products', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();

    $repositoryProduct = new RepositoryProduct();
    $products = $repositoryProduct->listAll();

    $page = new PageAdmin();
    $page->setTpl("products", array(
        "products" => $products
    ));
});

$app->get('/admin/products/create', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("products-create");
});

$app->post('/admin/products/create', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryProduct = new RepositoryProduct();
    $img = $repositoryProduct->setImg($_FILES['imgproduct']);
    $product = new Product();
    $product->setAtributes('', $_POST['product'], $_POST['vlprice'], $_POST['vlwidth'], $_POST['vlheight'], $_POST['vllength'], 
            $_POST['vlweight'], $_POST['url'], $img, '');
    $repositoryProduct->insert($product);
    header("Location: /ecommerce/admin/products");
    exit;
});

$app->get('/admin/products/:idproduct/delete', function($idproduct) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryProduct = new RepositoryProduct();
    $repositoryProduct->delete($idproduct);
    header("Location: /ecommerce/admin/products");
    exit;
});

$app->get('/admin/products/:idproduct', function($idproduct) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryProduct = new RepositoryProduct();
    $page = new PageAdmin();
    $page->setTpl("products-update", array(
        "product" => $repositoryProduct->find((int) $idproduct)
    ));
});

$app->post('/admin/products/:idproduct', function($idproduct) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryProduct = new RepositoryProduct();
    $img = $repositoryProduct->setImg($_FILES['imgproduct']);
    $product = new Product();
    $product->setAtributes($idproduct, $_POST['product'], $_POST['vlprice'], $_POST['vlwidth'], $_POST['vlheight'], $_POST['vllength'], 
            $_POST['vlweight'], $_POST['url'], $img, '');
    $repositoryProduct->update($product);    
    header("Location: /ecommerce/admin/products");
    exit;
});
