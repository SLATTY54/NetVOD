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
        // TODO: Implement execute() method.
        $episode = $_GET['episode_id'];
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
        $data = $stmt->fetch();
        return $data;
    }

    // TODO ajouter methode getDatabase
    public function render(array $data): string
    {
        return <<<END
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>{$data['titre']}</h1>
                    <p{$data['resume']}</p>
                    <p>durée : {$data['duree']}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <video controls>
                        <source src="../ressources/video/{$data['file']}" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
        END;
    }
}