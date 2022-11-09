<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;
use PDO;

class DisplayEnCoursAction extends Action{

    public function execute(): string{
        if(!isset($_SESSION['user'])){
            $html="<a href='?action=login'>Veuillez vous connecter</a>";
        }else{
            $user=unserialize($_SESSION['user']);
            $html=<<<end
                <div class="current">
                    <h1>Vos Préférences</h1>
                    <div class="listCurrent">
                        
            end;
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare("SELECT serie.titre as titre,serie.id as id FROM serie INNER JOIN EnCours ON serie.id=EnCours.idSerie WHERE EnCours.idUser=?");
            $id=$user->id;
            $stmt->bindParam(1,$id);
            $stmt->execute();
            if($stmt->rowCount()===0){
                $html.="<h2>Vous n'avez aucune série dans vos cours</h2>";
            }else{
                $html.="<ul>";
                foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $row){
                    $html.=<<<end
                    <li><a href="?action=serie&serie_id=$row->id">$row->titre</a></li>
                end;
                }
                $html.="</ul>";
            }
            $html.=<<<end
                    </div>
                </div>
            end;
        }
        return $html;
    }
}