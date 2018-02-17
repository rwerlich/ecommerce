<?php

namespace Werlich\Model\Repository;
use \Werlich\Interfaces\SessionMsgs;

class RepositoryAddress implements SessionMsgs{

    const SESSION_ERROR = "AddressError";

    public static function getEnderecoAPI($nrcep) {
        $nrcep = str_replace("-", "", $nrcep);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/$nrcep/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $data;
    }
    

    public static function save($endereco, $cart, $user) {
        $query = "INSERT INTO tb_addresses (iduser, idcart, address, complement, number, city, state, country, zipcode, district) "
                . "VALUES (:iduser, :idcart, :address, :complement, :number, :city, :state, :country, :zipcode, :district)";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':iduser', $user);
        $stmt->bindValue(':idcart', $cart);
        $stmt->bindValue(':address', utf8_decode($endereco['logradouro']));
        $stmt->bindValue(':complement', utf8_decode($endereco['complemento']));
        $stmt->bindValue(':number', utf8_decode($endereco['numero']));
        $stmt->bindValue(':district', utf8_decode($endereco['bairro']));
        $stmt->bindValue(':city', utf8_decode($endereco['cidade']));
        $stmt->bindValue(':state', utf8_decode($endereco['estado']));
        $stmt->bindValue(':country', utf8_decode($endereco['pais']));
        $stmt->bindValue(':zipcode', preg_replace("/[^0-9]/", "", $endereco['zipcode']));
        $stmt->execute();    
        return $bd->lastInsertId();
    }

    public static function setMsgError($msg) {
        $_SESSION[RepositoryAddress::SESSION_ERROR] = $msg;
    }

    public static function getMsgError() {
        $msg = (isset($_SESSION[RepositoryAddress::SESSION_ERROR])) ? $_SESSION[RepositoryAddress::SESSION_ERROR] : "";
        RepositoryAddress::clearMsgError();
        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[RepositoryAddress::SESSION_ERROR] = NULL;
    }

}
