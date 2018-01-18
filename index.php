<?php

require_once("vendor/autoload.php");


session_start();

use \Slim\Slim;
use \Werlich\Page;
use \Werlich\PageAdmin;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    $page = new Page();
    $page->setTpl("index");
});

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
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("index");
});

$app->get('/admin/users', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $users = $repositoryUser->listAll();
    $page = new PageAdmin();
    $page->setTpl("users", array(
        "users" => $users
    ));
});

$app->get('/admin/users/create', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("users-create");
});

$app->post('/admin/users/create', function() {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $admin = (isset($_POST["admin"])) ? 1 : 0;
    $user = new User();
    $user->setAtributes('', $_POST['login'], md5($_POST['password']), $admin, $_POST['nome'], $_POST['email'], $_POST['phone']);    
    $repositoryUser->insert($user);
    header("Location: /ecommerce/admin/users");
    exit;
});

$app->get('/admin/users/:iduser/delete', function($iduser) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();  
    $repositoryUser->delete($iduser);  
    header("Location: /ecommerce/admin/users");
    exit;
});

$app->get('/admin/users/:iduser', function($iduser) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("users-update", array(
        "user" => $repositoryUser->find((int) $iduser)
    ));
});


$app->post('/admin/users/:iduser', function($iduser) {
    $repositoryUser = new RepositoryUser();
    $repositoryUser->verifyLogin();

    $admin = (isset($_POST["admin"])) ? 1 : 0;
    $user = new User();
    $user->setAtributes($iduser, $_POST['login'], md5($_POST['password']), $admin, $_POST['nome'], $_POST['email'], $_POST['phone']);
    $repositoryUser->update($user);

    header("Location: /ecommerce/admin/users");
    exit;
});




$app->run();
?> 