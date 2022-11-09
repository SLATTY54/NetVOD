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
                <html>
                    <head>
                        <title>Catalogue</title>
                        <link href="./css/accueil_style.css" rel="stylesheet">
                    </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>Votre Accueil</h1>
                        </div>
                        <div class="fonction">
                            <a href="?action=catalogue">Catalogue</a>
                        </div>
                            <div class="listPref">
                                <h2>Vos Preferences</h2>
                        
            end;
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare("SELECT serie.titre as titre,serie.id as id FROM serie INNER JOIN preferences ON serie.id=preferences.id_serie WHERE preferences.id_user=?");
            $id=$user->id;
            $stmt->bindParam(1,$id);
            $stmt->execute();
            if($stmt->rowCount()===0){
                $html.="<h2>Vous n'avez aucune série dans vos préférences</h2>";
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
                    </body>
                </html>
            end;
        }
        return $html;
    }
}