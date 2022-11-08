<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;
use PDO;

class Favourite
{

    public static function addToFavourite(int $id_user, int $id_serie)
    {

        $db = ConnectionFactory::makeConnection();

        $ps = $db->prepare("INSERT INTO preferences(`id_user`, `id_serie`) VALUES (?, ?)");
        $ps->bindParam(1, $id_user);
        $ps->bindParam(2, $id_serie);
        $ps->execute();

    }

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