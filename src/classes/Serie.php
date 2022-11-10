<?php

namespace netvod\classes;

use netvod\database\ConnectionFactory;
use PDO;

class Serie
{

    public static function getSerieFromId(int $serie_id): mixed
    {
        $db = ConnectionFactory::makeConnection();

        $serie = $db->prepare("SELECT * FROM serie WHERE id = ?");
        $serie->bindParam(1, $serie_id);
        $serie->execute();
        $serie_object = $serie->fetch(PDO::FETCH_OBJ);

        $db = null;

        return $serie_object;
    }

    public static function getNombreEpisodesFromSerieId(int $serie_id): int
    {
        $db = ConnectionFactory::makeConnection();

        $serie = $db->prepare("SELECT COUNT(*) as nombre FROM episode WHERE serie_id = ?");
        $serie->bindParam(1, $serie_id);
        $serie->execute();
        $result = $serie->fetch(PDO::FETCH_ASSOC);

        $db = null;

        return $result['nombre'];
    }

    public static function getEpisodesFromSerieId(int $serie_id): array
    {
        $db = ConnectionFactory::makeConnection();
        $serie = $db->prepare("SELECT * FROM episode WHERE serie_id = ?");
        $serie->bindParam(1, $serie_id);
        $serie->execute();
        $serie_object = $serie->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        return $serie_object;
    }


}