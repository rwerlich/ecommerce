<?php

use \Werlich\Page;
use \Werlich\Model\Repository\RepositoryProduct;
use \Werlich\Model\Repository\RepositoryCategory;

$app->get('/', function() {
    $repositoryProduct = new RepositoryProduct();
    $products = $repositoryProduct->listAll();
    
    $page = new Page();
    $page->setTpl("index", array(
        'products' => $products
    ));
});

$app->get('/categories/:idcategory', function($idcategory) {
    
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    
    $repositoryCategory = new RepositoryCategory();
    $pagination = $repositoryCategory->getProductsPage($page, 3, $idcategory);
    #var_dump($pagination);
    #die();
    $pages = [];
    for($i = 1; $i <= $pagination['pages']; $i++){
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
