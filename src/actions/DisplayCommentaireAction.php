<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\database\ConnectionFactory;
use PDO;

class DisplayCommentaireAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur n'a pas une session de connecté
        if (!Authentification::isAuthentified()) {
            header('Location: ?action=login');
        }

        $html = <<<end
            <html>
            <head>
            <title>NetVOD</title>
            <link href="./css/coms-style.css" rel="stylesheet">
                                      <link href="./css/Notation-style.css" rel="stylesheet">
                          <meta name="viewport" content="width=device-with,initial-scale=1.0">

            </head> 
            <body>
                <div class="titre">
                    <h1>AVIS</h1>
                </div>  
                    <div class="coms">
            end;
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare("SELECT note, commentaire, nom, prenom FROM notation INNER JOIN User on notation.id_user = User.id WHERE id_serie = ?");
        $serie = $_GET["serie"];
        $stmt->bindParam(1, $serie);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $html .= <<<end
                
                    <p>{$row['note']} sur 5 - {$row['prenom']} {$row['nom']}</p> 
                   <div class="commentaire"> 
                    <p>
                    "{$row['commentaire']}" 
                    </p>
                    </div>
                    
                    <br>
                end;
        }
        // ajout d un bouton pour retourner a la page de la serie
        $html .= <<<end
                    </div>
                    <div class="intButton">
                    <button>
                    <a href="index.php?action=serie&serie_id={$serie}">Retour</a>
                    </button>   
                    </div>
            </body>
            </html>
            end;
        return $html;
    }
}