<?php

use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\PageAdmin;


$app->get('/admin/login', function() {
    $page = new PageAdmin(["header" => false, "footer" => false]);
    $page->setTpl("login");
});

$app->post('/admin/login', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->login($_POST["deslogin"], $_POST["despassword"]);
    header("Location: /ecommerce/admin");
    exit;
});

$app->get('/admin/logout', function() {
    $_SESSION['user'] = NULL;
    header("Location: /ecommerce/admin/login");
    exit;
});

$app->get('/admin', function() {    
    RepositoryUser::isAdmin();
    $page = new PageAdmin();
    $page->setTpl("index");
});





