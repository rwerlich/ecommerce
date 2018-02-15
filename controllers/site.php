<?php

use \Werlich\Page;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryProduct;
use \Werlich\Model\Repository\RepositoryCategory;
use \Werlich\Model\Repository\RepositoryCart;
use \Werlich\Model\Repository\RepositoryAddress;
use \Werlich\Model\Repository\RepositoryUser;

$app->get('/', function() {
    $repositoryProduct = new RepositoryProduct();
    $products = $repositoryProduct->listAll();

    $page = new Page();
    $page->setTpl("index", array(
        'products' => $products
    ));
});

$app->get('/categories/:idcategory', function($idcategory) {

    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

    $repositoryCategory = new RepositoryCategory();
    $pagination = $repositoryCategory->getProductsPage($page, 3, $idcategory);
    $pages = [];
    for ($i = 1; $i <= $pagination['pages']; $i++) {
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

$app->get('/products/:url', function($url) {

    $repositoryProduct = new RepositoryProduct();
    $product = $repositoryProduct->getFromUrl($url);

    $page = new Page();
    $page->setTpl("product-detail", array(
        'product' => $product,
        'categories' => $repositoryProduct->getCategories($product['idproduct'])
    ));
});

$app->get('/cart', function() {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();

    $page = new Page();
    $page->setTpl("cart", [
        'cart' => $cart,
        'products' => $repositoryCart->getProducts($cart['idcart']),
        'error' => RepositoryCart::getMsgError()
    ]);
});

$app->get('/cart/:idproduct/add', function(int $idproduct) {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $qtd = (isset($_GET['qtd'])) ? (int) $_GET['qtd'] : 1;
    for ($i = 0; $i < $qtd; $i++) {
        $repositoryCart->addProduct($idproduct, $cart['idcart']);
    }
    header("Location: /ecommerce/cart");
    exit;
});

$app->get('/cart/:idproduct/minus', function(int $idproduct) {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $repositoryCart->removeProduct($idproduct, $cart['idcart']);
    header("Location: /ecommerce/cart");
    exit;
});

$app->get('/cart/:idproduct/remove', function(int $idproduct) {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $repositoryCart->removeProduct($idproduct, $cart['idcart'], true);
    header("Location: /ecommerce/cart");
    exit;
});

$app->post('/cart/freight', function() {
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $repositoryCart->calcFrete($cart['idcart'], $_POST['zipcode']);
    header("Location: /ecommerce/cart");
    exit;
});

$app->get('/checkout', function() {
    RepositoryUser::checkLogin(true);
    $cart = RepositoryCart::getFromSession();
    $repositoryCart = new RepositoryCart();
    $page = new Page();
    $page->setTpl("checkout", [
        'cart' => $cart,
        'products' => $repositoryCart->getProducts($cart['idcart'])
    ]);
});



$app->post("/checkout", function() {
    RepositoryUser::checkLogin(true);
    $cart = RepositoryCart::getFromSession();
    if (!isset($_POST['zipcode']) || $_POST['zipcode'] === '') {
        Address::setMsgError("Informe o CEP.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['desaddress']) || $_POST['desaddress'] === '') {
        Address::setMsgError("Informe o endereço.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['desdistrict']) || $_POST['desdistrict'] === '') {
        Address::setMsgError("Informe o bairro.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['descity']) || $_POST['descity'] === '') {
        Address::setMsgError("Informe a cidade.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['desstate']) || $_POST['desstate'] === '') {
        Address::setMsgError("Informe o estado.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    if (!isset($_POST['descountry']) || $_POST['descountry'] === '') {
        Address::setMsgError("Informe o país.");
        header('Location: /ecommerce/checkout');
        exit;
    }
    $user = User::getFromSession();
    $address = new Address();
    $_POST['deszipcode'] = $_POST['zipcode'];
    $_POST['idperson'] = $user->getidperson();
    $address->setData($_POST);
    $address->save();
    $cart = Cart::getFromSession();
    $cart->getCalculateTotal();
    $order = new Order();
    $order->setData([
        'idcart' => $cart->getidcart(),
        'idaddress' => $address->getidaddress(),
        'iduser' => $user->getiduser(),
        'idstatus' => OrderStatus::EM_ABERTO,
        'vltotal' => $cart->getvltotal()
    ]);
    $order->save();
    switch ((int) $_POST['payment-method']) {
        case 1:
            header("Location: /order/" . $order->getidorder() . "/pagseguro");
            break;
        case 2:
            header("Location: /order/" . $order->getidorder() . "/paypal");
            break;
    }
    exit;
});

$app->get('/login', function() {
    $page = new Page();
    $page->setTpl("login", [
        'error' => RepositoryUser::getMsgError(),
        'errorRegister' => RepositoryUser::getMsgError(),
        'registerValues' => (isset($_SESSION['registerValues']) ? $_SESSION['registerValues'] : [
            'nome' => '', 'email' => '', 'phone' => ''
                ])
    ]);
});

$app->post('/login', function() {
    try {
        RepositoryUser::login($_POST["login"], $_POST["password"]);
    } catch (Exception $ex) {
        RepositoryUser::setMsgError($ex->getMessage());
    }
    header("Location: /ecommerce/checkout");
    exit;
});

$app->get('/logout', function() {
    RepositoryUser::logout();
    header("Location: /ecommerce/login");
    exit;
});

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
