<?php

use \Werlich\PageAdmin;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;

$app->get('/admin/users', function() {
    RepositoryUser::isAdmin();
    $repositoryUser = new RepositoryUser();
    $search = (isset($_GET['search'])) ? $_GET['search'] : "";
    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
    if ($search != '') {
        $pagination = $repositoryUser->getPageSearch($search, $page);
    } else {
        $pagination = $repositoryUser->getPage($page);
    }
    $pages = [];
    for ($x = 0; $x < $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/ecommerce/admin/users?' . http_build_query([
                'page' => $x + 1,
                'search' => $search
            ]),
            'text' => $x + 1
        ]);
    }
    $page = new PageAdmin();
    $page->setTpl("users", array(
        "users" => $pagination['data'],
        "search" => $search,
        "pages" => $pages
    ));
});

$app->get('/admin/users/create', function() {
    RepositoryUser::isAdmin();
    $page = new PageAdmin();
    $page->setTpl("users-create");
});

$app->post('/admin/users/create', function() {
    RepositoryUser::isAdmin();
    $repositoryUser = new RepositoryUser();
    $admin = (isset($_POST["admin"])) ? 1 : 0;
    $user = new User();
    $user->setAtributes('', $_POST['login'], md5($_POST['password']), $admin, $_POST['nome'], $_POST['email'], $_POST['phone']);
    $repositoryUser->insert($user);
    header("Location: /ecommerce/admin/users");
    exit;
});

$app->get('/admin/users/:iduser/delete', function($iduser) {
    RepositoryUser::isAdmin();
    $repositoryUser = new RepositoryUser();
    $repositoryUser->delete($iduser);
    header("Location: /ecommerce/admin/users");
    exit;
});

$app->get('/admin/users/:iduser', function($iduser) {
    RepositoryUser::isAdmin();
    $repositoryUser = new RepositoryUser();
    $page = new PageAdmin();
    $page->setTpl("users-update", array(
        "user" => $repositoryUser->find((int) $iduser)
    ));
});


$app->post('/admin/users/:iduser', function($iduser) {
    RepositoryUser::isAdmin();
    $repositoryUser = new RepositoryUser();

    $admin = (isset($_POST["admin"])) ? 1 : 0;
    $user = new User();
    $user->setAtributes($iduser, $_POST['login'], md5($_POST['password']), $admin, $_POST['nome'], $_POST['email'], $_POST['phone']);
    $repositoryUser->update($user);

    header("Location: /ecommerce/admin/users");
    exit;
});
