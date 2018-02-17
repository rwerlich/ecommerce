<?php

namespace Werlich\Model\Repository;

use \Werlich\Model\Entities\Cart;
use \Werlich\Model\Entities\User;
use \Werlich\Model\Repository\RepositoryUser;
use \Werlich\Interfaces\SessionMsgs;

class RepositoryCart implements SessionMsgs {

    private $bd;

    const SESSION_ERROR = 'cartError';

    public function __construct() {
        $this->bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
    }

    public static function getFromSession() {
        $cart = new Cart();
        $user = new User();

        if (isset($_SESSION[Cart::SESSION]) && (int) $_SESSION[Cart::SESSION]['idcart'] > 0) {
            $cart = RepositoryCart::get((int) $_SESSION[Cart::SESSION]['idcart']);
        } else {
            $cart = RepositoryCart::getFromSessionId();
            if (!(int) $cart['idcart'] > 0) {
                $sessionid = session_id();
                if (RepositoryUser::checkLogin()) {
                    $user = RepositoryUser::getFromSession();
                }
                RepositoryCart::initializeCart($sessionid, $user->getIduser());
                RepositoryCart::setToSession($sessionid, $user->getIduser());
                $cart = RepositoryCart::getFromSessionId();
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
        if ($result > 0) {
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

    public static function get(int $idcart) {
        $query = "SELECT * FROM tb_carts WHERE idcart = :idcart";
        $bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':idcart', $idcart);
        $result = $stmt->execute();
        if ($result > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }

    public function addProduct(int $idproduct, int $cart) {
        $query = "INSERT INTO tb_cartsproducts (idproduct, idcart) "
                . "VALUES (:idproduct, :idcart)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
        $this->updateFrete($cart);
    }

    public function removeProduct(int $idproduct, int $cart, $all = false) {
        if ($all) {
            $query = "UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idproduct = :idproduct AND idcart = :idcart";
        } else {
            $query = "UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idproduct = :idproduct AND idcart = :idcart AND dtremoved IS NULL LIMIT 1";
        }
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idproduct', $idproduct);
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
        $this->updateFrete($cart);
    }

    public function getProducts(int $cart) {
        $query = "SELECT b.idproduct, b.product, b.vlprice, b.vlwidth, b.vlheight, b.vllength, b.vlweight, b.url, b.imgproduct, COUNT(*) as nrqtd, SUM(b.vlprice) as vltotal "
                . "FROM tb_cartsproducts as a "
                . "INNER JOIN tb_products as b ON a.idproduct = b.idproduct "
                . "WHERE a.idcart = :idcart AND a.dtremoved IS NULL "
                . "GROUP BY b.idproduct, b.product, b.vlprice, b.vlwidth, b.vlheight, b.vllength, b.vlweight, b.url, b.imgproduct "
                . "ORDER BY b.product";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductsTotal(int $cart) {
        $query = "SELECT SUM(vlprice) AS vlprice, SUM(vlwidth) AS vlwidth, SUM(vlheight) AS vlheight, SUM(vllength) AS vllength, SUM(vlweight) AS vlweight, COUNT(*) AS nrqtd 
                    FROM tb_products AS a 
                    INNER JOIN tb_cartsproducts AS b ON a.idproduct = b.idproduct 
                    WHERE b.idcart = :idcart AND b.dtremoved IS NULL
                    ";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateZipcode(String $cep, int $cart) {
        $vlfrete = $this->find($cart);
        $totals = $this->getProductsTotal($cart);
        $query = "UPDATE tb_carts SET zipcode = :zipcode WHERE idcart = :idcart ";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':zipcode', preg_replace("/[^0-9]/", "", $cep));
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
        $this->calcFrete($cart, $cep);
    }

    public function calcFrete(int $cart, String $zipcode) {
        $cep = preg_replace("/[^0-9]/", "", $zipcode);
        $totals = $this->getProductsTotal($cart);
        if ($totals['nrqtd'] > 0) {
            if ($totals['vlheight'] < 2)
                $totals['vlheight'] = 2;
            if ($totals['vllength'] < 16)
                $totals['vllength'] = 16;
            $qs = http_build_query([
                'nCdEmpresa' => '',
                'sDsSenha' => '',
                'nCdServico' => '40010',
                'sCepOrigem' => '88103050',
                'sCepDestino' => $cep,
                'nVlPeso' => $totals['vlweight'],
                'nCdFormato' => '1',
                'nVlComprimento' => $totals['vllength'],
                'nVlAltura' => $totals['vlheight'],
                'nVlLargura' => $totals['vlwidth'],
                'nVlDiametro' => '1',
                'sCdMaoPropria' => 'N',
                'nVlValorDeclarado' => $totals['vlprice'],
                'sCdAvisoRecebimento' => 'S'
            ]);
            $xml = simplexml_load_file('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?' . $qs);
            $result = $xml->Servicos->cServico;
            if ($result->MsgErro != '') {
                RepositoryCart::setMsgError($result->MsgErro);
            } else {
                RepositoryCart::clearMsgError();
            }
            $nrdays = $result->PrazoEntrega;
            $vlfrete = RepositoryCart::formatValueToDecimal($result->Valor);

            $this->saveFrete($cart, $vlfrete, $nrdays, $cep);
        } else {
            $this->saveFrete($cart, 0, 0,$cep);
        }
    }

    public function saveFrete(int $cart, $vlfrete, $nrdays, $cep) {
        $query = "UPDATE tb_carts SET zipcode = :zipcode, vlfreight = :vlfreight, nrdays = :nrdays "
                . "WHERE idcart = :idcart ";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':zipcode', $cep);
        $stmt->bindValue(':vlfreight', $vlfrete);
        $stmt->bindValue(':nrdays', $nrdays);
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
        $this->updateTotal($cart);
    }

    public function updateTotal(int $cart) {
        $vlfrete = $this->find($cart);
        $totals = $this->getProductsTotal($cart);
        $query = "UPDATE tb_carts SET vltotal = :vltotal, vlsubtotal = :vlsubtotal "
                . "WHERE idcart = :idcart ";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':vlsubtotal', $totals['vlprice']);
        $stmt->bindValue(':vltotal', $totals['vlprice'] + $vlfrete['vlfreight']);
        $stmt->bindValue(':idcart', $cart);
        $stmt->execute();
    }

    public function updateFrete(int $cart) {
        $c = $this->find($cart);
        if ($c['zipcode'] != '') {
            $this->calcFrete($cart, $c['zipcode']);
        }
        $this->updateTotal($cart);
    }

    public function find(int $idcart) {
        $query = "SELECT * FROM tb_carts WHERE idcart = :idcart";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':idcart', $idcart);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function formatValueToDecimal($value): float {
        $value = str_replace('.', '', $value);
        return str_replace(',', '.', $value);
    }

    public static function setMsgError($msg) {
        $_SESSION[RepositoryCart::SESSION_ERROR] = $msg;
    }

    public static function getMsgError() {
        $msg = (isset($_SESSION[RepositoryCart::SESSION_ERROR])) ? $_SESSION[RepositoryCart::SESSION_ERROR] : '';
        RepositoryCart::clearMsgError();
        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[RepositoryCart::SESSION_ERROR] = NULL;
    }

}
