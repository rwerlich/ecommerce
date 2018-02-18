<?php

use \Werlich\PageAdmin;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\Model\Repository\RepositoryOrder;
use \Werlich\Model\Repository\RepositoryCart;

$app->get('/admin/orders/:idorder/delete', function($idorder) {
    RepositoryUser::isAdmin();
    RepositoryOrder::remove($idorder);
    header("Location: /ecommerce/admin/orders");
    exit;
});

$app->get('/admin/orders/:idorder', function($idorder) {
    RepositoryUser::isAdmin();
    $order = RepositoryOrder::get($idorder);
    $repositoryCart = new RepositoryCart();
    $page = new PageAdmin();
    $page->setTpl("order", array(
        "order" => $order,
        'cart' => RepositoryCart::get($order['idcart']),
        'products' => $repositoryCart->getProducts($order['idcart'])
    ));
});


$app->get('/admin/orders', function() {
    RepositoryUser::isAdmin();
    $repositoryOrder = new RepositoryOrder();
    $page = new PageAdmin();
    $page->setTpl("orders", array(
        "orders" => $repositoryOrder->listAll()
    ));
});

