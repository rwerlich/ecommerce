<?php

namespace Werlich\Model\Entities;


class Product {
    
    private $idproduct;
    private $product;
    private $vlprice;
    private $vlwidth;
    private $vlheight;
    private $vllength;
    private $vlweight;
    private $url;
    private $dtregister;
    private $imgproduct;
    private $destaque;
    
    function getDestaque() {
        return $this->destaque;
    }

    function setDestaque($destaque) {
        $this->destaque = $destaque;
    }
        
    function setAtributes($idproduct, $product, $vlprice, $vlwidth, $vlheight, $vllength, $vlweight, $url, $imgproduct, $dtregister, $destaque) {
        $this->idproduct = $idproduct;
        $this->product = $product;
        $this->vlprice = $vlprice;
        $this->vlwidth = $vlwidth;
        $this->vlheight = $vlheight; 
        $this->vllength = $vllength;
        $this->vlweight = $vlweight;
        $this->url = $url;
        $this->imgproduct = $imgproduct;
        $this->dtregister = $dtregister;
        $this->destaque = $destaque;
    }
    
    function getIdproduct() {
        return $this->idproduct;
    }

    function getProduct() {
        return $this->product;
    }

    function getVlprice() {
        return $this->vlprice;
    }

    function getVlwidth() {
        return $this->vlwidth;
    }

    function getVlheight() {
        return $this->vlheight;
    }

    function getVllength() {
        return $this->vllength;
    }

    function getVlweight() {
        return $this->vlweight;
    }

    function getUrl() {
        return $this->url;
    }

    function getDtregister() {
        return $this->dtregister;
    }

    function setIdproduct($idproduct) {
        $this->idproduct = $idproduct;
    }

    function setProduct($product) {
        $this->product = $product;
    }

    function setVlprice($vlprice) {
        $this->vlprice = $vlprice;
    }

    function setVlwidth($vlwidth) {
        $this->vlwidth = $vlwidth;
    }

    function setVlheight($vlheight) {
        $this->vlheight = $vlheight;
    }

    function setVllength($vllength) {
        $this->vllength = $vllength;
    }

    function setVlweight($vlweight) {
        $this->vlweight = $vlweight;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setDtregister($dtregister) {
        $this->dtregister = $dtregister;
    }

    function getImgproduct() {
        return $this->imgproduct;
    }

    function setImgproduct($imgproduct) {
        $this->imgproduct = $imgproduct;
    }



    
}
