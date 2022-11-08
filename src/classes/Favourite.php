<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;

class Favourite
{

    public static function addToFavourite(int $id_user, int $id_serie){

        $db = ConnectionFactory::makeConnection();

        $ps = $db->prepare("INSERT INTO preferences(`id_user`, `id_serie`) VALUES (?, ?)");
        $ps->bindParam(1, $id_user);
        $ps->bindParam(2, $id_serie);
        $ps->execute();

    }

}