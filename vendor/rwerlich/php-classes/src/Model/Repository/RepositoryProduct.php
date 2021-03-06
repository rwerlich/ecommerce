<?php

namespace Werlich\Model\Repository;

use \Werlich\Model\Entities\Product;

class RepositoryProduct {

    private $bd;

    public function __construct() {
        $this->bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
    }

    public function listAll() {
        $query = "SELECT * FROM tb_products ORDER BY idproduct DESC";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function listDestaques() {
        $query = "SELECT * FROM tb_products WHERE destaque = 1 ORDER BY idproduct DESC LIMIT 5";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(Product $product) {
        $query = "INSERT INTO tb_products (product, vlprice, vlwidth, vlheight, vllength, vlweight, url, imgproduct, destaque) "
                . "VALUES (:product, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :url, :imgproduct, :destaque)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':product', $product->getProduct());
        $stmt->bindValue(':vlprice', $product->getVlprice());
        $stmt->bindValue(':vlwidth', $product->getVlwidth());
        $stmt->bindValue(':vlheight', $product->getVlheight());
        $stmt->bindValue(':vllength', $product->getVllength());
        $stmt->bindValue(':vlweight', $product->getVlweight());
        $stmt->bindValue(':url', $product->getUrl());
        $stmt->bindValue(':destaque', $product->getDestaque());
        $stmt->bindValue(':imgproduct', $product->getImgproduct());
        $stmt->execute();
    }

    public function delete(int $idproduct) {
        $query = "DELETE FROM tb_products WHERE idproduct = :idproduct";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->execute();
    }

    public function find(int $idproduct) {
        $query = "SELECT * FROM tb_products WHERE idproduct = :idproduct";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(Product $product) {
        $query = "UPDATE tb_products SET
                    product = :product,
                    vlprice = :vlprice,
                    vlwidth = :vlwidth,
                    vlheight = :vlheight,
                    vllength = :vllength,
                    vlweight =:vlweight,
                    url =:url,
                    imgproduct =:imgproduct,
                    destaque = :destaque
                  WHERE idproduct = :idproduct";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':product', $product->getProduct());
        $stmt->bindValue(':vlprice', $product->getVlprice());
        $stmt->bindValue(':vlwidth', $product->getVlwidth());
        $stmt->bindValue(':vlheight', $product->getVlheight());
        $stmt->bindValue(':vllength', $product->getVllength());
        $stmt->bindValue(':vlweight', $product->getVlweight());
        $stmt->bindValue(':url', $product->getUrl());
        $stmt->bindValue(':imgproduct', $product->getImgproduct());
        $stmt->bindValue(':idproduct', $product->getIdproduct());
        $stmt->bindValue(':destaque', $product->getDestaque());
        $stmt->execute();
    }

    public function uploadImg($file) {
        $extension = explode('.', $file['name']);
        $extension = end($extension);
        switch ($extension) {
            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($file["tmp_name"]);
                break;
            case "gif":
                $image = imagecreatefromgif($file["tmp_name"]);
                break;
            case "png":
                $image = imagecreatefrompng($file["tmp_name"]);
                break;
        }
        $hora = time();
        $dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
                "ecommerce" . DIRECTORY_SEPARATOR .
                "assets" . DIRECTORY_SEPARATOR .
                "site" . DIRECTORY_SEPARATOR .
                "img" . DIRECTORY_SEPARATOR .
                "products" . DIRECTORY_SEPARATOR .
                $hora . ".jpg";
        imagejpeg($image, $dist);
        imagedestroy($image);
        return "{$hora}.jpg";
    }
    
    public function getFromUrl(String $url) {
        $query = "SELECT * FROM tb_products WHERE url = :url";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':url', $url);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function getCategories(int $idproduct) {
        $query = "SELECT * FROM tb_categories as c INNER JOIN tb_productscategories as b ON c.idcategory = b.idcategory "
                . "WHERE b.idproduct = :idproduct";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getPage(int $page = 1, int $itens = 10) {        
        $start = ($page -1) * $itens;
        
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tb_products ORDER BY product ASC LIMIT {$start}, {$itens}";
        $stmt = $this->bd->prepare($query);
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
    
    public function getPageSearch(string $search, int $page = 1, int $itens = 10) {        
        $start = ($page -1) * $itens;
        
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tb_products "
                . "WHERE product LIKE :search "
                . "ORDER BY product ASC LIMIT {$start}, {$itens}";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':search', "%{$search}%");
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
