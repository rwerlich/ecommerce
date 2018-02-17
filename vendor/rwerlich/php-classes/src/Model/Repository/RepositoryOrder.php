<?php

namespace Werlich\Model\Repository;

class RepositoryOrder {

    public static function initializeOrder($cart, $iduser, $total, $idaddress) {
        $query = "INSERT INTO tb_orders (idcart, iduser, vltotal, idaddress) "
                . "VALUES (:idcart, :iduser, :vltotal, :idaddress);";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':idcart', $cart);
        $stmt->bindValue(':iduser', $iduser);
        $stmt->bindValue(':vltotal', $total);
        $stmt->bindValue(':idaddress', $idaddress);
        $stmt->execute();
        return $bd->lastInsertId();
    }

    public static function get(int $idorder) {
        $query = "SELECT * 
                FROM tb_orders AS a
                INNER JOIN tb_ordersstatus AS b ON b.idstatus = a.idstatus
                INNER JOIN tb_carts AS c ON c.idcart = a.idcart
                INNER JOIN tb_users AS d ON d.iduser = a.iduser
                INNER JOIN tb_addresses AS e ON c.idcart = e.idcart
                WHERE a.idorder = :idorder;";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':idorder', $idorder);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}
