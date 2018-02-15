<?php

namespace Werlich\Model\Repository;

class RepositoryAddress {

    const SESSION_ERROR = "AddressError";

    public static function getCEP($nrcep) {
        $nrcep = str_replace("-", "", $nrcep);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/$nrcep/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $data;
    }

    public function loadFromCEP($nrcep) {
        $data = RepositoryAddress::getCEP($nrcep);
        if (isset($data['logradouro']) && $data['logradouro'] != '') {
            return $data;
        } else {
            return [];
        }
    }

    public function save() {
        $sql = new Sql();
        $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :desnumber, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
            ':idaddress' => $this->getidaddress(),
            ':idperson' => $this->getidperson(),
            ':desaddress' => utf8_decode($this->getdesaddress()),
            ':desnumber' => $this->getdesnumber(),
            ':descomplement' => utf8_decode($this->getdescomplement()),
            ':descity' => utf8_decode($this->getdescity()),
            ':desstate' => utf8_decode($this->getdesstate()),
            ':descountry' => utf8_decode($this->getdescountry()),
            ':deszipcode' => $this->getdeszipcode(),
            ':desdistrict' => $this->getdesdistrict()
        ]);
        if (count($results) > 0) {
            $this->setData($results[0]);
        }
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
