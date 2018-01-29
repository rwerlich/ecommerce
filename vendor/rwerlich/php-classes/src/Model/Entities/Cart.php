<?php

namespace Werlich\Model\Entities;

class Cart{
    
    private $idcart;
    private $sessionid;
    private $iduser;
    private $zipcode;
    private $nrdays;
    private $vlfreight;
    private $dtregister;
    
    function getIdcart() {
        return $this->idcart;
    }

    function getSessionid() {
        return $this->sessionid;
    }

    function getIduser() {
        return $this->iduser;
    }

    function getZipcode() {
        return $this->zipcode;
    }

    function getNrdays() {
        return $this->nrdays;
    }

    function getVlfreight() {
        return $this->vlfreight;
    }

    function getDtregister() {
        return $this->dtregister;
    }

    function setIdcart($idcart) {
        $this->idcart = $idcart;
    }

    function setSessionid($sessionid) {
        $this->sessionid = $sessionid;
    }

    function setIduser($iduser) {
        $this->iduser = $iduser;
    }

    function setZipcode($zipcode) {
        $this->zipcode = $zipcode;
    }

    function setNrdays($nrdays) {
        $this->nrdays = $nrdays;
    }

    function setVlfreight($vlfreight) {
        $this->vlfreight = $vlfreight;
    }

    function setDtregister($dtregister) {
        $this->dtregister = $dtregister;
    }




    
}