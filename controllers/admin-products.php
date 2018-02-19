<?php

use \Werlich\Model\Entities\Product;
use \Werlich\Model\Repository\RepositoryProduct;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\PageAdmin;

$app->get('/admin/products', function() {
    RepositoryUser::isAdmin();
    $repositoryProduct = new RepositoryProduct();
    $search = (isset($_GET['search'])) ? $_GET['search'] : "";
    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
    if ($search != '') {
        $pagination = $repositoryProduct->getPageSearch($search, $page);
    } else {
        $pagination = $repositoryProduct->getPage($page);
    }
    $pages = [];
    for ($x = 0; $x < $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/ecommerce/admin/products?' . http_build_query([
                'page' => $x + 1,
                'search' => $search
            ]),
            'text' => $x + 1
        ]);
    }

    $page = new PageAdmin();
    $page->setTpl("products", array(
        "products" => $pagination['data'],
        "search" => $search,
        "pages" => $pages
    ));
});

$app->get('/admin/products/create', function() {
    RepositoryUser::isAdmin();
    $page = new PageAdmin();
    $page->setTpl("products-create");
});

$app->post('/admin/products/create', function() {
    RepositoryUser::isAdmin();
    $repositoryProduct = new RepositoryProduct();
    $img = $repositoryProduct->uploadImg($_FILES['imgproduct']);
    $product = new Product();
    $product->setAtributes('', $_POST['product'], $_POST['vlprice'], $_POST['vlwidth'], $_POST['vlheight'], $_POST['vllength'], $_POST['vlweight'], $_POST['url'], $img, '');

    $repositoryProduct->insert($product);
    header("Location: /ecommerce/admin/products");
    exit;
});

$app->get('/admin/products/:idproduct/delete', function($idproduct) {
    RepositoryUser::isAdmin();
    $repositoryProduct = new RepositoryProduct();
    $repositoryProduct->delete($idproduct);
    header("Location: /ecommerce/admin/products");
    exit;
});

$app->get('/admin/products/:idproduct', function($idproduct) {
    RepositoryUser::isAdmin();
    $repositoryProduct = new RepositoryProduct();
    $page = new PageAdmin();
    $page->setTpl("products-update", array(
        "product" => $repositoryProduct->find((int) $idproduct)
    ));
});

$app->post('/admin/products/:idproduct', function($idproduct) {
    RepositoryUser::isAdmin();
    $repositoryProduct = new RepositoryProduct();      
    if($_FILES['imgproduct']['name'] != '' && $_FILES['imgproduct']['size'] > 0){
        $img = $repositoryProduct->uploadImg($_FILES['imgproduct']);
    }else{
        $product = $repositoryProduct->find((int) $idproduct);
        $img = $product['imgproduct'];
    }        
    $product = new Product(); 
    $product->setAtributes($idproduct, $_POST['product'], $_POST['vlprice'], $_POST['vlwidth'], $_POST['vlheight'], $_POST['vllength'], $_POST['vlweight'], $_POST['url'], $img, '');
    $repositoryProduct->update($product);
    header("Location: /ecommerce/admin/products");
    exit;
});
