<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;
use PDO;

class DisplayPreferencesAction extends Action{

    public function execute(): string{
        if(!isset($_SESSION['user'])){
            $html="<h1>Veuillez vous connectez</h1>";
        }else{
            $user=unserialize($_SESSION['user']);
            $html=<<<end
                <div class="preferences">
                    <h1>Vos Préférences</h1>
                    <div class="list">
                        <ul>
            end;
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare("SELECT serie.titre as titre,serie.id as id FROM serie INNER JOIN preferences ON serie.id=preferences.id_serie WHERE preferences.id_user=?");
            $id=$user->id;
            $stmt->bindParam(1,$id);
            $stmt->execute();
            foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $row){
                $html.=<<<end
                    <li><a href="?action=serie&id_serie=$row->id">$row->titre</a></li>
                end;
            }
            $html.=<<<end
                        </ul
                    </div>
                </div>
            end;
        }
        return $html;
    }
}