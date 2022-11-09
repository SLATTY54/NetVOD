<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;
use netvod\Exceptions\AuthException;
use PDO;

class Authentification
{
    public static function authenticate($email, $psswrd): void
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
        $stmt->bindParam(1, $email);
        $res = $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!isset($user['email'])) throw new AuthException("ttt");
        
        if (!password_verify($psswrd, $user['passwd'])) throw new AuthException("Erreur d'authentification");

        $user = new User($user['id'], $email, $user['passwd']);
        $_SESSION['user'] = serialize($user);
    }

    public static function register(string $email, string $password)
    {
        if (strlen($password) >= 5 && !self::isRegistered($email)) {
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare('INSERT INTO User (email, passwd) VALUES (?, ?)');
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            return true;
        }
    }

    public static function isRegistered(string $email): bool
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($user) {
            return true;
        }
        return false;
    }

}