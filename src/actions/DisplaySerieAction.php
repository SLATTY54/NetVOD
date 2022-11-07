<?php

namespace netvod\actions;

use database\ConnectionFactory;
use PDO;

class DisplaySerieAction extends Action{

    public function execute(): string{
        $html="";
        if(isset($_GET['serie_id'])){
            $id=$_GET['serie_id'];
            $bd=ConnectionFactory::makeConnection();

            $serie=$bd->prepare("SELECT * FROM SERIE WHERE id=?");
            $serie->bindParam(1,$id);
            $serie->execute();
            $data=$serie->fetch(PDO::FETCH_OBJ);

            $query=$bd->prepare("SELECT COUNT(*) as 'nbEP' FROM EPISODE WHERE serie_id=?");
            $query->bindParam(1,$id);
            $query->execute();
            $stmt=$query->fetch(PDO::FETCH_OBJ);
            $nbEp=$stmt->nbEP;

            $html=<<<end
                <header>$data->titre</header>               
                <h2>$data->descriptif</h2>
                <h3>paru en $data->annee</h3>
                <h3>ajouté le $data->date_ajout</h3>
                <h4>$nbEp Episode(s)</h4>
            end;

            $query=$bd->prepare("SELECT * FROM EPISODE WHERE serie_id=?");
            $query->bindParam(1,$id);
            $query->execute();
            foreach($query->fetchAll(PDO::FETCH_OBJ) as $row){
                $html.=<<<end
                    <div class=$row->id>
                        <br><a href="?action=episode&episode_id=$row->id">$row->id : $row->titre ($row->duree min)</a>
                        //affichage de l'image
                    </div>
                end;
            }
        }
        return $html;
    }
}