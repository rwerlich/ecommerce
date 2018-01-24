<?php

namespace Werlich\Model\Repository;

use \Werlich\Model\Entities\User;

class RepositoryUser {

    private $bd;

    public function __construct() {
        $this->bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
    }

    public function getFromSession() {
        $user = new User();
        if ($_SESSION['user']->getIduser() > 0 && $_SESSION['user']->getAdmin() == 0) {
            $user = $_SESSION['user'];
        }
        return $user;
    }

    public function checkLogin() {
        if (!isset($_SESSION['user']) || $_SESSION['user'] == NULL || !$_SESSION['user']->getIduser() > 0) {
            header("Location: /ecommerce/admin/login");            
            exit;
        } 
    }

    public function login(string $login, string $password) {
        $query = "SELECT * FROM tb_users WHERE login = :login AND password = :password";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':password', md5($password));
        $stmt->execute();
        $user = $stmt->fetchObject('\Werlich\Model\Entities\User');
        if (!is_object($user)) {
            throw new \Exception("Usuário ou senha inválidos!");
        } else {
            $_SESSION['user'] = $user;
        }
    }

    public function verifyLogin() {        
        if (!isset($_SESSION['user']) || $_SESSION['user'] == NULL) {
            header("Location: /ecommerce/admin/login");            
            exit;
        } else if ($_SESSION['user']->getIduser() > 0 && $_SESSION['user']->getAdmin() == 0) {
            header("Location: /ecommerce/admin/login");
            exit;
        }
    }

    public function listAll() {
        $query = "SELECT * FROM tb_users ORDER BY iduser DESC";
        $stmt = $this->bd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $iduser) {
        $query = "SELECT * FROM tb_users WHERE iduser = :iduser";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':iduser', $iduser);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(User $user) {
        $query = "UPDATE tb_users SET
                    login = :login,
                    password = :password,
                    admin = :admin,
                    nome = :nome,
                    email = :email,
                    phone =:phone
                  WHERE iduser = :iduser";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':login', $user->getLogin());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':admin', $user->getAdmin());
        $stmt->bindValue(':nome', $user->getNome());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':phone', $user->getPhone());
        $stmt->bindValue(':iduser', $user->getIduser());
        $stmt->execute();
    }

    public function insert(User $user) {
        $query = "INSERT INTO tb_users (login, password, admin, nome, email, phone) "
                . "VALUES (:login, :password, :admin, :nome, :email, :phone)";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':login', $user->getLogin());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':admin', $user->getAdmin());
        $stmt->bindValue(':nome', $user->getNome());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':phone', $user->getPhone());
        $stmt->execute();
    }

    public function delete(int $iduser) {
        $query = "DELETE FROM tb_users WHERE iduser = :iduser";
        $stmt = $this->bd->prepare($query);
        $stmt->bindValue(':iduser', $iduser);
        $stmt->execute();
    }

}
