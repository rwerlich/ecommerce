<?php

namespace Werlich\Model\Entities;


class User {
    
    const SESSION = 'user';

    private $iduser;
    private $login;
    private $password;
    private $admin;
    private $nome;
    private $email;
    private $phone;
    private $dtregister;

    function setAtributes($iduser, $login, $password, $admin, $nome, $email, $phone) {
        $this->iduser = $iduser;
        $this->login = $login;
        $this->password = $password;
        $this->admin = $admin;
        $this->nome = $nome; 
        $this->email = $email;
        $this->phone = $phone;
    }
    
    public static function userToArray($user) {
        $array = [];
        $array['iduser'] = $user->getIduser();
        $array['login'] = $user->getLogin();
        $array['password'] = $user->getPassword();
        $array['admin'] = $user->getAdmin();
        $array['nome'] = $user->getNome();
        $array['email'] = $user->getEmail();
        $array['phone'] = $user->getPhone();
        return $array;
    }


    function getIduser() {
        return $this->iduser;
    }

    function getLogin() {
        return $this->login;
    }

    function getPassword() {
        return $this->password;
    }

    function getAdmin() {
        return $this->admin;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getPhone() {
        return $this->phone;
    }

    function getDtregister() {
        return $this->dtregister;
    }

    function setIduser($iduser) {
        $this->iduser = $iduser;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setAdmin($admin) {
        $this->admin = $admin;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setDtregister($dtregister) {
        $this->dtregister = $dtregister;
    }

}
