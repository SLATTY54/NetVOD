<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;
use PDO;

class DisplayCommentaireAction extends Action
{

    public function execute(): string
    {
        $html = <<<end
                <div class="commentaire">
                    <h1>Commentaires</h1>
                    <div class="commentaires">
            end;
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare("SELECT note, commentaire FROM notation WHERE id_serie = ?");
        $serie = $_GET["serie"];
        $stmt->bindParam(1, $serie);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $html .= <<<end
                    <p>commentaire : {$row['commentaire']} <br>note : {$row['note']} <br><br></p>
                end;
        }
        $html .= <<<end
                    </div>
                </div>
            end;
        return $html;
    }
}