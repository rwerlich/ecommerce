<?php

namespace Werlich\Model\Repository;

use \Werlich\Model\Entities\Category;

class RepositoryCategory {
    
    private $bd;

    public function __construct() {
        $dbuser = "root";
        $pass = "";
        $db = "db_ecommerce";
        $this->bd = new \PDO("mysql:host=localhost;dbname={$db}", $dbuser, $pass);
    }    
    
    public function listAll() {
        $query = "SELECT * FROM tb_categories ORDER BY idcategory DESC";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function insert(Category $category) {
        $query = "INSERT INTO tb_categories (category) "
                . "VALUES (:category)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':category', $category->getCategory());
        $stmt->execute();
        $this->geraMenu();
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
        foreach ($categories as $row){
            array_push($html, "<li><a href='/ecommerce/categories/{$row['idcategory']}'>{$row['category']}</a></li>");
        }
        file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/ecommerce/views/categories-menu.html", implode('', $html));
    }

}
