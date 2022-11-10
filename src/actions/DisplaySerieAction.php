<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\database\ConnectionFactory;
use PDO;

class DisplaySerieAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur n'a pas une session de connecté
        if(!Authentification::isAuthentified()){
            header('Location: ?action=login');
        }

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

        $query = $bd->prepare("SELECT AVG(note) as noteMoy FROM notation WHERE id_serie = ? GROUP BY id_serie");
        $query->bindParam(1, $id);
        $query->execute();
        $stmt = $query->fetch(PDO::FETCH_ASSOC);

        if(isset($stmt['noteMoy'])){
            $noteMoy = round($stmt['noteMoy'], 2);
        } else {
            $noteMoy = 0;
        }

        //recuperation de l image de la serie pour le css
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("SELECT img FROM serie where id = ? ");
        $query->bindParam(1, $id);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $img = "../resources/images/" . $row['img'];

        $html = <<<end
                <html lang="en">
                        <head>
                            <title>NetVod</title>
                            <link href="./css/serie_style.css" rel="stylesheet">
                        </head>
                         <div class="rtBeuteu">
                    <button>
                    <a href="index.php?action=catalogue">Retour</a>
                    </button>   
                    </div> 
                        <body>
                        
                <h1 style="padding-bottom: 6%">$data->titre</h1>               
                
                <div class="img-gradient"> 
                <img src=$img width="300" height="200" alt="image de couverture de la serie">
                </div>
                <div class="container">
               <div class="description">
               <h3>$data->descriptif</h3>
               <div class="zobard2000">
               
                <h4>paru en $data->annee</h4>
                <h4>ajouté le $data->date_ajout</h4>
                <h4>note moyenne $noteMoy/5</h4>
                <a href="?action=commentaire&serie=$id">voir les commentaires</a>
                </div>
                 
                </div>
                <h2>$nbEp Episode(s)</h2>
              
            end;

        $query = $bd->prepare("SELECT * FROM episode WHERE serie_id=?");
        $query->bindParam(1, $id);
        $query->execute();
        foreach ($query->fetchAll(PDO::FETCH_OBJ) as $row) {
            $html .= <<<end
                <div class="episodes">
                         
                        <br><a href="?action=episode&episode_id=$row->id"> 
                        <button>
                        <ul class="intButton">
                            <h2>$row->id</h2>  
                            <h3>$row->titre</h3>
                            <p>$row->duree sec</p>
                        </ul>
                        </button></a>
                        
                </div> 
                end;
        }
        $html .= <<<end
              
                </div>
                </body>
                </html>
            end;
        // ajout du bouton retour
        return $html;
    }
}