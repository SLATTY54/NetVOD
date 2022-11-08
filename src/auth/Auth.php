<?php

namespace netvod\auth;

use netvod\database\ConnectionFactory;

class Auth
{
    private string $identifiant;
    private string $password;



    public static function register(string $email, string $password)
    {
        if (strlen($password) >= 10 && !self::isRegistered($email)) {
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