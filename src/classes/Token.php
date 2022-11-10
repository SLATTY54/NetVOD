<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;

class Token
{

    /**
     * Génération du token
     * @return string valeur du token
     */
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        return $token;
    }


    /**
     * Ajout un token et ainsi qu'un temps d'expiration dans la base de données.
     * @param int $id_user id de l'utilisateur
     * @param string $token token généré généré par la fonction generateToken()
     * @return void
     */
    public static function addTokenToUser(int $id_user, string $token): void
    {
        $db = ConnectionFactory::makeConnection();

        $timestamp = date('Y-m-d H:i:s', time() + 30);

        $stmt = $db->prepare('UPDATE User SET token = ?, lifetime = ? WHERE id = ?');
        $stmt->execute([$token, $timestamp, $id_user]);
        $stmt->closeCursor();

    }


    /**
     * Vérifie si le token est valide
     * @param string $token token à vérifier
     * @return bool vrai si le token est valide, faux sinon
     */
    public static function isValidToken(string $token): bool
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT id FROM User WHERE token = ? AND lifetime > ?');
        $stmt->execute([$token, date('Y-m-d H:i:s', time())]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if(!$result){
            echo 'Token invalide';
            return false;
        }
        return true;
    }
}