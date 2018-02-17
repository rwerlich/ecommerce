<?php

use \Werlich\Page;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;

$app->post('/register', function() {
    $_SESSION['registerValues'] = $_POST;
    if (!isset($_POST['nome']) || strlen(trim($_POST['nome'])) == 0) {
        RepositoryUser::setMsgErrorRegister('Preencha o campo nome.');
        header("Location: /ecommerce/login");
        exit;
    }
    if (!isset($_POST['email']) || strlen(trim($_POST['email'])) == 0) {
        RepositoryUser::setMsgError('Preencha o campo e-mail.');
        header("Location: /ecommerce/login");
        exit;
    }
    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) == 0) {
        RepositoryUser::setMsgError('Preencha o campo senha.');
        header("Location: /ecommerce/login");
        exit;
    }
    if (RepositoryUser::checkLoginExist($_POST['email'])) {
        RepositoryUser::setMsgError('Este endereço de e-mail já esta sendo utilizado por outro usuário.');
        header("Location: /ecommerce/login");
        exit;
    }
    $repositoryUser = new RepositoryUser();
    $admin = 0;
    $user = new User();
    $user->setAtributes('', $_POST['email'], md5($_POST['password']), $admin, $_POST['nome'], $_POST['email'], $_POST['phone']);
    $repositoryUser->insert($user);
    RepositoryUser::login($_POST['email'], $_POST['password']);
    header("Location: /ecommerce/checkout");
    exit;
});

$app->get('/forgot', function() {
    $page = new Page();
    $page->setTpl("forgot");
});

$app->post('/forgot', function() {
    RepositoryUser::createForgot($_POST['email'], false);
    header("Location: /ecommerce/forgot/sent");
    exit;
});

$app->get('/forgot/sent', function() {
    $page = new Page();
    $page->setTpl("forgot-sent");
});

$app->get('/forgot/reset', function() {
    $userValido = RepositoryUser::validToken($_GET['code']);
    $page = new Page();
    $page->setTpl("forgot-reset", ["valido" => $userValido, "code" => $_GET['code']]);
});

$app->post('/forgot/reset', function() {
    $userValido = RepositoryUser::validToken($_POST['code']);
    if (!$userValido > 0) {
        header("Location: /ecommerce/forgot/reset");
        exit;
    }
    RepositoryUser::updatePass($_POST['password'], $_POST['code'], $userValido);
    $page = new Page();
    $page->setTpl("forgot-reset-success");
});

$app->get('/profile', function() {
    RepositoryUser::checkLogin(true);
    $user = RepositoryUser::getFromSession();
    $page = new Page();
    $page->setTpl("profile", [
        'profileMsg' => RepositoryUser::getMsgSuccess(),
        'profileError' => RepositoryUser::getMsgError(),
        'user' => User::userToArray($user)
    ]);
});

$app->post('/profile', function() {
    RepositoryUser::checkLogin(true);
    if (!isset($_POST['nome']) || $_POST['nome'] === '') {
        RepositoryUser::setMsgError("Preencha o seu nome.");
        header('Location: /ecommerce/profile');
        exit;
    }
    if (!isset($_POST['email']) || $_POST['email'] === '') {
        RepositoryUser::setMsgError("Preencha o seu e-mail.");
        header('Location: /ecommerce/profile');
        exit;
    }
    $user = RepositoryUser::getFromSession();
    if ($_POST['email'] !== $user->getEmail()) {
        if (RepositoryUser::checkLoginExist($_POST['email']) === true) {
            RepositoryUser::setMsgError("Este endereço de e-mail já está cadastrado.");
            header('Location: /ecommerce/profile');
            exit;
        }
    }
    $user->setAtributes($user->getIduser(), $_POST['email'], $user->getPassword(), 0, $_POST['nome'], $_POST['email'], $_POST['phone']);
    RepositoryUser::setMsgSuccess('Dados alterados com sucesso!');
    $repositoryUser = new repositoryUser();
    $repositoryUser->update($user);
    header("Location: /ecommerce/profile");
    exit;
});

$app->get("/profile/change-password", function() {
    RepositoryUser::checkLogin(true);
    $page = new Page();
    $page->setTpl("profile-change-password", [
        'changePassError' => RepositoryUser::getMsgError(),
        'changePassSuccess' => RepositoryUser::getMsgSuccess()
    ]);
});
$app->post("/profile/change-password", function() {
    RepositoryUser::checkLogin(true);
    if (!isset($_POST['current_pass']) || $_POST['current_pass'] === '') {
        RepositoryUser::setMsgError("Digite a senha atual.");
        header("Location: /ecommerce/profile/change-password");
        exit;
    }
    if (!isset($_POST['new_pass']) || $_POST['new_pass'] === '') {
        RepositoryUser::setMsgError("Digite a nova senha.");
        header("Location: /ecommerce/profile/change-password");
        exit;
    }
    if (!isset($_POST['new_pass_confirm']) || $_POST['new_pass_confirm'] === '') {
        RepositoryUser::setMsgError("Confirme a nova senha.");
        header("Location: /ecommerce/profile/change-password");
        exit;
    }
    if ($_POST['current_pass'] === $_POST['new_pass']) {
        RepositoryUser::setMsgError("A sua nova senha deve ser diferente da atual.");
        header("Location: /ecommerce/profile/change-password");
        exit;
    }
    $user = RepositoryUser::getFromSession();
    if (md5($_POST['current_pass']) !== $user->getPassword()) {
        RepositoryUser::setMsgError("A senha está inválida.");
        header("Location: /ecommerce/profile/change-password");
        exit;
    }
    $user->setPassword(md5($_POST['new_pass']));
    $repositoryUser = new RepositoryUser();
    $repositoryUser->update($user);
    RepositoryUser::setMsgSuccess("Senha alterada com sucesso.");
    header("Location: /ecommerce/profile/change-password");
    exit;
});

