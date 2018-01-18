<?php

namespace Werlich\Model\Entities;


class Category {
    
    private $idcategory;
    private $category;
    private $dtregister;    
    
    function getIdcategory() {
        return $this->idcategory;
    }

    function getCategory() {
        return $this->category;
    }

    function getDtregister() {
        return $this->dtregister;
    }

    function setIdcategory($idcategory) {
        $this->idcategory = $idcategory;
    }

    function setCategory($category) {
        $this->category = $category;
    }

    function setDtregister($dtregister) {
        $this->dtregister = $dtregister;
    }


}
