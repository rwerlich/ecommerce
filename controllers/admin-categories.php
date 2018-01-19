<?php

use \Werlich\Model\Entities\Category;
use \Werlich\Model\Repository\RepositoryCategory;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\PageAdmin;

$app->get('/admin/categories', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();

    $repositoryCategory = new RepositoryCategory();
    $categories = $repositoryCategory->listAll();

    $page = new PageAdmin();
    $page->setTpl("categories", array(
        "categories" => $categories
    ));
});

$app->get('/admin/categories/create', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryCategory = new RepositoryCategory();
    $category = new Category();
    $category->setCategory($_POST['category']);
    $repositoryCategory->insert($category);
    header("Location: /ecommerce/admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory/delete', function($idcategory) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryCategory = new RepositoryCategory();
    $repositoryCategory->delete($idcategory);
    header("Location: /ecommerce/admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory', function($idcategory) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryCategory = new RepositoryCategory();
    $page = new PageAdmin();
    $page->setTpl("categories-update", array(
        "category" => $repositoryCategory->find((int) $idcategory)
    ));
});

$app->post('/admin/categories/:idcategory', function($idcategory) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $repositoryCategory = new RepositoryCategory();
    $category = new Category();
    $category->setCategory($_POST['category']);
    $category->setIdcategory($idcategory);
    $repositoryCategory->update($category);
    header("Location: /ecommerce/admin/categories");
    exit;
});
