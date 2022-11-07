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

        if (!$res) throw new AuthException("Erreur d'authentification");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($psswrd, $user['passwd'])) throw new AuthException("Erreur d'authentification");

        $user = new User($user['id'],$email, $user['passwd']);
        $_SESSION['user'] = serialize($user);

    }

}