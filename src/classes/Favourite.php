<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;
use PDO;

/**
 * Classe permettant d'interagir via la base de donnée concernant les favoris
 */
class Favourite
{

    /**
     * Méthode statique permettant d'ajouter une série favorite d'un utilisateur
     * @param int $id_user
     * @param int $id_serie
     * @return void
     */
    public static function addToFavourite(int $id_user, int $id_serie)
    {

        $db = ConnectionFactory::makeConnection();

        $ps = $db->prepare("INSERT INTO preferences(`id_user`, `id_serie`) VALUES (?, ?)");
        $ps->bindParam(1, $id_user);
        $ps->bindParam(2, $id_serie);
        $ps->execute();

        $db = null;

    }

    /**
     * Méthode statique permettant de retirer une série favorite d'un utilisateur
     * @param int $id_user
     * @param int $id_serie
     * @return void
     */
    public static function removeFromFavourite(int $id_user, int $id_serie)
    {

        $db = ConnectionFactory::makeConnection();

        $ps = $db->prepare("DELETE FROM preferences WHERE id_user = ? AND id_serie = ?");
        $ps->bindParam(1, $id_user);
        $ps->bindParam(2, $id_serie);
        $ps->execute();

        $db = null;

    }

    /**
     * Méthode statique permettant de vérifier si l'utilisateur à déjà cette série en favoris
     * @param int $id_user
     * @param int $id_serie
     * @return bool
     */
    public static function isAlreadyFavourite(int $id_user, int $id_serie): bool
    {

        $db = ConnectionFactory::makeConnection();
        $ps = $db->prepare("SELECT COUNT(*) as nombre FROM preferences WHERE id_user = ? AND id_serie = ?");
        $ps->bindParam(1, $id_user);
        $ps->bindParam(2, $id_serie);
        $ps->execute();

        $result = $ps->fetch(PDO::FETCH_ASSOC);
        $db = null;

        return $result['nombre'] != 0;

    }

}