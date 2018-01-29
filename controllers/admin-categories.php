<?php

use \Werlich\Model\Entities\Category;
use \Werlich\Model\Repository\RepositoryCategory;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\PageAdmin;

$app->get('/admin/categories', function() {
    RepositoryUser::isAdmin();

    $repositoryCategory = new RepositoryCategory();
    $categories = $repositoryCategory->listAll();

    $page = new PageAdmin();
    $page->setTpl("categories", array(
        "categories" => $categories
    ));
});

$app->get('/admin/categories/create', function() {
    RepositoryUser::isAdmin();
    $page = new PageAdmin();
    $page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function() {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $category = new Category();
    $category->setCategory($_POST['category']);
    $repositoryCategory->insert($category);
    header("Location: /ecommerce/admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory/delete', function($idcategory) {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $repositoryCategory->delete($idcategory);
    header("Location: /ecommerce/admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory/products', function($idcategory) {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $page = new PageAdmin();
    $page->setTpl("categories-products", array(
        "category" => $repositoryCategory->find((int) $idcategory),
        "productsRelated" => $repositoryCategory->getProducts(true, (int) $idcategory),
        "productsNotRelated" => $repositoryCategory->getProducts(false, (int) $idcategory)
    ));
});

$app->get('/admin/categories/:idcategory/products/:idproduct/add', function($idcategory, $idproduct) {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $repositoryCategory->insertProduct($idcategory, $idproduct);
    header("Location: /ecommerce/admin/categories/{$idcategory}/products");
    exit;    
});

$app->get('/admin/categories/:idcategory/products/:idproduct/remove', function($idcategory, $idproduct) {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $repositoryCategory->deleteProduct($idcategory, $idproduct);
    header("Location: /ecommerce/admin/categories/{$idcategory}/products");
    exit;    
});

$app->get('/admin/categories/:idcategory', function($idcategory) {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $page = new PageAdmin();
    $page->setTpl("categories-update", array(
        "category" => $repositoryCategory->find((int) $idcategory)
    ));
});

$app->post('/admin/categories/:idcategory', function($idcategory) {
    RepositoryUser::isAdmin();
    $repositoryCategory = new RepositoryCategory();
    $category = new Category();
    $category->setCategory($_POST['category']);
    $category->setIdcategory($idcategory);
    $repositoryCategory->update($category);
    header("Location: /ecommerce/admin/categories");
    exit;
});


