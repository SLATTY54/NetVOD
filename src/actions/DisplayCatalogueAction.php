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
            $html.=<<<end
                <div class=$titre>
                    <br><a href='?action=serie&serie_id=$id'>$titre</a>
                    <img src=$img>
                    <button type="submit">ajouter au favoris</button>
                </div>
            end;
        }
        return $html;
    }

}