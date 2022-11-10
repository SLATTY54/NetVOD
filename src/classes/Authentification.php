<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;
use netvod\exceptions\ActivateException;
use netvod\exceptions\AuthException;
use PDO;

/**
 * Classe permettant de gérer l'authentification
 */
class Authentification
{

    public static function authenticate($email, $psswrd): void
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
        $stmt->bindParam(1, $email);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!isset($user['email'])) throw new AuthException("ttt");

        if (!password_verify($psswrd, $user['passwd'])) {
            throw new AuthException("Erreur d'authentification");
        }

        if (!$user['account_enabled']) {
            throw new ActivateException("Le compte n'est pas activé !");
        }

        $user = new User($user['id'], $email, $user['passwd']);
        self::defineUserData($user);
        $_SESSION['user'] = serialize($user);
    }

    public static function register(string $email, string $password): ?string
    {
        if (strlen($password) >= 5 && !self::isRegistered($email)) {

            $token = Token::generateToken();

            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare('INSERT INTO User (email, passwd, token_activate) VALUES (?, ?, ?)');
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $token]);

            return $token;
        }
        return null;
    }

    public static function enableAccount(string $token)
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('UPDATE User SET account_enabled = 1 WHERE token_activate = ?');
        $stmt->execute([$token]);
    }

    public static function defineUserData(User $user)
    {

        $email = $user->__get('email');

        $db = ConnectionFactory::makeConnection();
        $ps = $db->prepare('SELECT nom, prenom, dateN, biographie FROM User WHERE email = ?');
        $ps->bindParam(1, $email);
        $ps->execute();
        $result = $ps->fetch(PDO::FETCH_ASSOC);

        $user->__set('nom', $result['nom']);
        $user->__set('prenom', $result['prenom']);
        $user->__set('date_naissance', $result['dateN']);
        $user->__set('biographie', $result['biographie']);
    }

    public static function isRegistered(string $email): bool
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return true;
        }
        return false;
    }

    public static function isAuthentified(): bool
    {
        return isset($_SESSION['user']);
    }

}