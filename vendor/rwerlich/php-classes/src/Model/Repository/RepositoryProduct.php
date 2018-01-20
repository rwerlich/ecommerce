<?php

namespace Werlich\Model\Repository;

use \Werlich\Model\Entities\Product;

class RepositoryProduct {

    private $bd;

    public function __construct() {
        $dbuser = "root";
        $pass = "";
        $db = "db_ecommerce";
        $this->bd = new \PDO("mysql:host=localhost;dbname={$db}", $dbuser, $pass);
    }

    public function listAll() {
        $query = "SELECT * FROM tb_products ORDER BY idproduct DESC";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(Product $product) {
        $query = "INSERT INTO tb_products (product, vlprice, vlwidth, vlheight, vllength, vlweight, url, imgproduct) "
                . "VALUES (:product, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :url, :imgproduct)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':product', $product->getProduct());
        $stmt->bindValue(':vlprice', $product->getVlprice());
        $stmt->bindValue(':vlwidth', $product->getVlwidth());
        $stmt->bindValue(':vlheight', $product->getVlheight());
        $stmt->bindValue(':vllength', $product->getVllength());
        $stmt->bindValue(':vlweight', $product->getVlweight());
        $stmt->bindValue(':url', $product->getUrl());
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
                    imgproduct =:imgproduct
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

}
