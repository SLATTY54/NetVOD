<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;
use PDO;

class DisplaySerieAction extends Action
{

    public function execute(): string{
        $id = $_GET['serie_id'];
        $bd = ConnectionFactory::makeConnection();

        $serie = $bd->prepare("SELECT * FROM serie WHERE id=?");
        $serie->bindParam(1, $id);
        $serie->execute();
        $data = $serie->fetch(PDO::FETCH_OBJ);

        $query = $bd->prepare("SELECT COUNT(*) as 'nbEP' FROM episode WHERE serie_id=?");
        $query->bindParam(1, $id);
        $query->execute();
        $stmt = $query->fetch(PDO::FETCH_OBJ);
        $nbEp = $stmt->nbEP;

        $html = <<<end
                <h1>$data->titre</h1>               
                <h3>$data->descriptif</h3>
                <h4>paru en $data->annee</h4>
                <h4>ajouté le $data->date_ajout</h4>
                <h5>$nbEp Episode(s)</h5>
            end;

        $query = $bd->prepare("SELECT * FROM episode WHERE serie_id=?");
        $query->bindParam(1, $id);
        $query->execute();
        foreach ($query->fetchAll(PDO::FETCH_OBJ) as $row) {
            $html .= <<<end
                    <div class=$row->id>
                        <br><a href="?action=episode&episode_id=$row->id">$row->id : $row->titre ($row->duree min)</a>
                    </div>
                end;
        }
        return $html;
    }
}