<?php

use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\PageAdmin;


$app->get('/admin/login', function() {
    $page = new PageAdmin(["header" => false, "footer" => false]);
    $page->setTpl("login");
});

$app->post('/admin/login', function() {
    RepositoryUser::login($_POST["deslogin"], $_POST["despassword"]);
    header("Location: /ecommerce/admin");
    exit;
});

$app->get('/admin/logout', function() {
    RepositoryUser::logout();
    header("Location: /ecommerce/admin/login");
    exit;
});

$app->get('/admin', function() {    
    RepositoryUser::isAdmin();
    $page = new PageAdmin();
    $page->setTpl("index");
});





