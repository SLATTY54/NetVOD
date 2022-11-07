<?php

namespace netvod\actions;

use database\ConnectionFactory;
use PDO;

class DisplayCatalogueAction extends Action{

    public function execute(): string{
        $html="<h1>Catalogue de Série</h1>";
        $bd = ConnectionFactory::makeConnection();
        $query=$bd->prepare("SELECT id,titre,img FROM SERIE");
        $query->execute();
        foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
            $id=$row['id'];
            $titre=$row['titre'];
            $img=$row['img'];
            $html.="<br><a href='?action=DisplaySerieAction&serie_id=$id'>$titre</a>";
            $html.="<img src=$img>";
        }
        return $html;
    }

}