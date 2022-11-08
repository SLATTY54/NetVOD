<?php

namespace netvod\actions;

// TODO : ajouter une option pour sauvegarder l avancement d un episode
// TODO un attribut dans la liste d episode pour savoir si l episode a ete vu ou non
// TODO skip episode
// TODO pause / play
// TODO ajouter un bouton pour revenir a la liste des episodes
// TODO avancer / reculer de 10 secondes

use netvod\database\ConnectionFactory;

class lectureEpisodeAction extends Action
{

    public function execute(): string
    {
        $episode = $_GET['episode_id'];
        // assainissement de l id de l episode
        $episode = filter_var($episode, FILTER_SANITIZE_NUMBER_INT);
        $existe = false;

        $attributs = $this->getEpisode($episode);

        $html = $this->render($attributs);
        return $html;
    }

    public function getEpisode($episode): array
    {
        $pdo = ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM episode WHERE id = :episode_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['episode_id' => $episode]);
        // veri si l episode existe
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            return $data;
        }
        // si l'utilisateur a touche a l url et rentre un id d episode qui n existe pas
        echo 'episode id inexistant';
        return [];
    }

    public function render(array $data): string
    {
        return <<<END
        <html>
            <head>
            <title>NetVOD</title>
            <link href="./css/epRendererStyle.css" rel="stylesheet">

            </head>
        <div class="container">
            <div class="row">
                <div class="col-12">
               
                    <h1>{$data['titre']}</h1>
                    <div class="duree">{$data['duree']}</div>
                    <p>{$data['resume']}</p>
                </div>
            </div>
                    <video controls>
                        <source src="../ressources/video/{$data['file']}" type="video/mp4">
                    </video>
        </div>
        </html> 
        END;
    }
}