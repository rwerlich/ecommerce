<?php

namespace Werlich\Model\Repository;
use \Werlich\Interfaces\SessionMsgs;

class RepositoryOrder implements SessionMsgs{

    private $bd;
    
    const SESSION_ERROR = 'orderError';
    const SESSION_SUCCESS = 'orderSuccess';

    public function __construct() {
        $this->bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
    }

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
                INNER JOIN tb_addresses AS e ON e.idaddress = a.idaddress
                WHERE a.idorder = :idorder;";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':idorder', $idorder);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public static function remove(int $idorder) {
        $query = "DELETE FROM tb_orders WHERE idorder = :idorder;";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':idorder', $idorder);
        $stmt->execute();
    }

    public static function updateStatus(int $idorder, int $status) {
        $query = "UPDATE tb_orders SET idstatus = :idstatus WHERE idorder = :idorder;";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':idorder', $idorder);
        $stmt->bindValue(':idstatus', $status);
        $stmt->execute();
    }
    
    public function listAll() {
        $query = "SELECT a.idorder, b.status, c.vltotal, c.vlfreight, d.nome 
                FROM tb_orders AS a
                INNER JOIN tb_ordersstatus AS b ON b.idstatus = a.idstatus
                INNER JOIN tb_carts AS c ON c.idcart = a.idcart
                INNER JOIN tb_users AS d ON d.iduser = a.iduser
                ORDER BY a.idorder DESC";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public static function listStatus() {
        $query = "SELECT idstatus, status FROM tb_ordersstatus ORDER BY status";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function setMsgError($msg) {
        $_SESSION[RepositoryOrder::SESSION_ERROR] = $msg;
    }

    public static function getMsgError() {
        $msg = (isset($_SESSION[RepositoryOrder::SESSION_ERROR])) ? $_SESSION[RepositoryOrder::SESSION_ERROR] : '';
        RepositoryOrder::clearMsgError();
        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[RepositoryOrder::SESSION_ERROR] = NULL;
    }

    public static function setMsgSuccess($msg) {
        $_SESSION[RepositoryOrder::SESSION_SUCCESS] = $msg;
    }

    public static function getMsgSuccess() {
        $msg = (isset($_SESSION[RepositoryOrder::SESSION_SUCCESS])) ? $_SESSION[RepositoryOrder::SESSION_SUCCESS] : '';
        RepositoryOrder::clearMsgSuccess();
        return $msg;
    }

    public static function clearMsgSuccess() {
        $_SESSION[RepositoryOrder::SESSION_SUCCESS] = NULL;
    }

}
