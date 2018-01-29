<?php

namespace Werlich\Model\Repository;

use \Werlich\Model\Entities\Cart;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;

class RepositoryCart {
    
    private $bd;        

    public function __construct() {
        $this->bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
    }
    
    public static function getFromSession(){
        $cart = new Cart();
        $user = new User();
        
        if(isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0){
            $cart = $this->get((int)$_SESSION[Cart::SESSION]['idcart']);
        }else{
            $cart = RepositoryCart::getFromSessionId();
            if(!(int)$cart['idcart'] > 0){
                $sessionid = session_id();
                if(RepositoryUser::checkLogin()){
                    $user = RepositoryUser::getFromSession();
                }
                RepositoryCart::initializeCart($sessionid, $user->getIduser());
                RepositoryCart::setToSession($sessionid, $user->getIduser());
                
            }
        }
        return $cart;
    }
    
    public static function getFromSessionId() {        
        $query = "SELECT * FROM tb_carts WHERE sessionid = :sessionid";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':sessionid', session_id());
        $result = $stmt->execute();
        if($result > 0 ){
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }        
    }
    
    public static function initializeCart($session, $iduser) {
        $query = "INSERT INTO tb_carts (sessionid, iduser) "
                . "VALUES (:sessionid, :iduser)";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':sessionid', $session);
        $stmt->bindValue(':iduser', $iduser);
        $stmt->execute();
    }    
    
    public static function setToSession($session, $iduser) {
        $query = "SELECT * FROM tb_carts WHERE sessionid = :sessionid AND iduser = :iduser";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':sessionid', $session);
        $stmt->bindValue(':iduser', $iduser);
        $stmt->execute();
        $_SESSION[Cart::SESSION] = $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function get(int $idcart) {
        $query = "SELECT * FROM tb_carts WHERE idcart = :idcart";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcart', $idcart);
        $result = $stmt->execute();
        if($result > 0 ){
            return $stmt->fetchObject('\Werlich\Model\Entities\Cart');
        }
    }
    
    public function insert(Category $category) {
        $query = "INSERT INTO tb_carts (category) "
                . "VALUES (:category)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':category', $category->getCategory());
        $stmt->execute();
        $this->geraMenu();
    }    
    

    public function listAll() {
        $query = "SELECT * FROM tb_categories ORDER BY idcategory DESC";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }    

    public function delete(int $idcategory) {
        $query = "DELETE FROM tb_categories WHERE idcategory = :idcategory";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcategory', $idcategory);
        $stmt->execute();
        $this->geraMenu();
    }

    public function find(int $idcategory) {
        $query = "SELECT * FROM tb_categories WHERE idcategory = :idcategory";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcategory', $idcategory);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(Category $category) {
        $query = "UPDATE tb_categories SET
                    category = :category
                  WHERE idcategory = :idcategory";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':category', $category->getCategory());
        $stmt->bindValue(':idcategory', $category->getIdcategory());
        $stmt->execute();
        $this->geraMenu();
    }

    private function geraMenu() {
        $categories = $this->listAll();
        $html = [];
        foreach ($categories as $row) {
            array_push($html, "<li><a href='/ecommerce/categories/{$row['idcategory']}'>{$row['category']}</a></li>");
        }
        file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/ecommerce/views/categories-menu.html", implode('', $html));
    }

    public function getProducts(bool $related, int $idcategory) {
        if ($related === true) {
            $query = "SELECT * FROM tb_products WHERE idproduct IN(
    SELECT p.idproduct FROM tb_products as p INNER JOIN tb_productscategories as b ON p.idproduct = b.idproduct 
    WHERE b.idcategory = :idcategory) ";
        } else {
            $query = "SELECT * FROM tb_products WHERE idproduct NOT IN(
    SELECT p.idproduct FROM tb_products as p INNER JOIN tb_productscategories as b ON p.idproduct = b.idproduct 
    WHERE b.idcategory = :idcategory) ";
        }
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcategory', $idcategory);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function deleteProduct(int $idcategory, int $idproduct) {
        $query = "DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcategory', $idcategory);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->execute();
        $this->geraMenu();
    }
    
    public function insertProduct(int $idcategory, int $idproduct) {
        $query = "INSERT INTO tb_productscategories (idcategory, idproduct) "
                . "VALUES (:idcategory, :idproduct)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcategory', $idcategory);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->execute();
        $this->geraMenu();
    }
    
    public function getProductsPage(int $page, int $itens, $idcategory) {
        
        $start = ($page -1) * $itens;
        
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tb_products as p INNER JOIN tb_productscategories as b ON p.idproduct = b.idproduct "
                . "INNER JOIN tb_categories as c ON c.idcategory = b.idcategory WHERE c.idcategory = :idcategory LIMIT {$start}, {$itens}";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcategory', $idcategory);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $query = "SELECT FOUND_ROWS() as total";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        $total = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return [
            'data' => $results,
            'total' => $total[0]['total'],
            'pages' => ceil($total[0]['total'] / $itens)
        ];
    }
    
}
