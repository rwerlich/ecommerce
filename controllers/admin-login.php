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

$app->get('/admin/forgot', function() {
    $page = new PageAdmin(["header" => false, "footer" => false]);
    $page->setTpl("forgot");
});

$app->post('/admin/forgot', function() {
    RepositoryUser::createForgot($_POST['email']);    
    header("Location: /ecommerce/admin/forgot/sent");
    exit;
});

$app->get('/admin/forgot/sent', function() {
    $page = new PageAdmin(["header" => false, "footer" => false]);
    $page->setTpl("forgot-sent");
});

$app->get('/admin/forgot/reset', function() {
    $valido = RepositoryUser::validToken($_GET['code']);    
    $page = new PageAdmin(["header" => false, "footer" => false]);
    $page->setTpl("forgot-reset", ["valido" => $valido, "code" => $_GET['code']]);
});

$app->post('/admin/forgot/reset', function() {
    $valido = RepositoryUser::validToken($_POST['code']);
    if(!$valido > 0){
        header("Location: /ecommerce/admin/forgot/reset");
        exit;
    }    
    #RepositoryUser::updatePass($_POST['code'], $_POST['password'], $valido);        
});
