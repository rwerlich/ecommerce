<?php

//decode inserir
//encode visualizar

namespace Werlich\Model\Repository;

use \Werlich\Mailer;
use \Werlich\Model\Entities\User;
use \Werlich\Interfaces\SessionMsgs;

class RepositoryUser implements SessionMsgs {

    private $bd;

    const SESSION_ERROR = 'userError';
    const SESSION_ERROR_REGISTER = 'registerError';

    public function __construct() {
        $this->bd = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
    }

    public static function conecta() {
        return new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, PASS);
        ;
    }
    
    public static function validToken(String $token):int {
        $query = "SELECT * FROM tb_userspasswordsrecoveries WHERE token = :token AND dtrecovery IS NULL AND DATE_ADD(dtregister, INTERVAL 1 HOUR) >= NOW()";
        $bd = RepositoryUser::conecta();
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);    
        if($result['iduser'] > 0){
            return $result['iduser'];
        }else{
            return 0;
        }
    }
    
    public static function updatePass(String $pass, String $token, int $id) {
        $query = "UPDATE tb_users SET password = :password WHERE iduser = :iduser; "
                . "UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE token = :token AND iduser = :iduser";
        $bd = RepositoryUser::conecta();
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':password', md5($pass));
        $stmt->bindValue(':iduser', $id);
        $stmt->bindValue(':token', $token);
        $stmt->execute();    
        
    }
    
    public static function getForEmail(String $email) {
        $query = "SELECT * FROM tb_users WHERE email = :email";
        $bd = RepositoryUser::conecta();
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchObject('\Werlich\Model\Entities\User');        
    }

    public static function createForgot(String $email) {
        $user = RepositoryUser::getForEmail($email);
        $bd = RepositoryUser::conecta();
        if (is_object($user) && $user->getIduser() > 0) {
            $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $token = "";
            for ($count = 0; $count < 32; $count++) {
                $token .= $basic[rand(0, strlen($basic) - 1)];
            }
            $query = "INSERT INTO tb_userspasswordsrecoveries (iduser, desip, token) VALUES (:iduser, :desip, :token)";
            $stmt = $bd->prepare($query);
            $stmt->bindValue(':iduser', $user->getIduser());
            $stmt->bindValue(':desip', $_SERVER['REMOTE_ADDR']);
            $stmt->bindValue(':token', $token);
            $stmt->execute();
            $link = "http://localhost/ecommerce/admin/forgot/reset?code={$token}";
            $mailer = new Mailer($user->getEmail(), $user->getNome(), "Refeinir senha Ecommerce", "forgot", [
                'name' => $user->getNome(), 'link' => $link
            ]);
            $mailer->send();
        } else {
            throw new Exception("Não foi possível recuperar a senha.");
        }
    }

    public static function getFromSession() {
        $user = new User();
        if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION]->getIduser() > 0) {
            $user = $_SESSION[$user::SESSION];
        }
        return $user;
    }

    public static function checkLogin($needLogin = false) {
        if ($needLogin && (!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION]->getIduser() > 0)) {
            header("Location: /ecommerce/login");
            exit;
        }
        if (!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION]->getIduser() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function isAdmin() {
        if (!isset($_SESSION[User::SESSION]) || $_SESSION[User::SESSION] == NULL) {
            header("Location: /ecommerce/admin/login");
            exit;
        } else if ($_SESSION['user']->getIduser() > 0 && $_SESSION['user']->getAdmin() == 0) {
            header("Location: /ecommerce/admin/login");
            exit;
        }
    }

    public static function login(string $login, string $password) {
        $query = "SELECT * FROM tb_users WHERE login = :login AND password = :password";
        $bd = RepositoryUser::conecta();
        $stmt = $bd->prepare($query);
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

    public static function checkLoginExist(string $login) {
        $query = "SELECT * FROM tb_users WHERE login = :login ";
        $bd = RepositoryUser::conecta();
        $stmt = $bd->prepare($query);
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return (count($user) > 0);
    }

    public static function logout() {
        $_SESSION['user'] = NULL;
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

    public static function setMsgError($msg) {
        $_SESSION[RepositoryUser::SESSION_ERROR] = $msg;
    }

    public static function getMsgError() {
        $msg = (isset($_SESSION[RepositoryUser::SESSION_ERROR])) ? $_SESSION[RepositoryUser::SESSION_ERROR] : '';
        RepositoryUser::clearMsgError();
        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[RepositoryUser::SESSION_ERROR] = NULL;
    }

    public static function setMsgErrorRegister($msg) {
        $_SESSION[RepositoryUser::SESSION_ERROR_REGISTER] = $msg;
    }

    public static function getMsgErrorRegister() {
        $msg = (isset($_SESSION[RepositoryUser::SESSION_ERROR_REGISTER])) ? $_SESSION[RepositoryUser::SESSION_ERROR_REGISTER] : '';
        RepositoryUser::clearMsgErrorRegister();
        return $msg;
    }

    public static function clearMsgErrorRegister() {
        $_SESSION[RepositoryUser::SESSION_ERROR_REGISTER] = NULL;
    }

}
