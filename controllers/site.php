<?php

use \Werlich\Page;
use \Werlich\Model\Repository\RepositoryProduct;

$app->get('/', function() {
    $repositoryProduct = new RepositoryProduct();
    $products = $repositoryProduct->listAll();
    
    $page = new Page();
    $page->setTpl("index", array(
        'products' => $products
    ));
});

$app->get('/categories/:idcategory', function($idcategory) {
    $repositoryCategory = new RepositoryCategory();
    $page = new Page();
    $page->setTpl("category", array(
        'category' => $repositoryCategory->find((int) $idcategory),
        'products' => []
    ));
});
