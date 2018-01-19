<?php

use \Werlich\Page;

$app->get('/', function() {
    $page = new Page();
    $page->setTpl("index");
});

$app->get('/categories/:idcategory', function($idcategory) {
    $repositoryCategory = new RepositoryCategory();
    $page = new Page();
    $page->setTpl("category", array(
        'category' => $repositoryCategory->find((int) $idcategory),
        'products' => []
    ));
});
