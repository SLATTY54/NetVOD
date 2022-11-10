<?php

namespace netvod\actions;

// TODO : ajouter une option pour sauvegarder l avancement d un episode
// TODO un attribut dans la liste d episode pour savoir si l episode a ete vu ou non
// TODO skip episode
// TODO pause / play
// TODO ajouter un bouton pour revenir a la liste des episodes
// TODO avancer / reculer de 10 secondes

use netvod\classes\Authentification;
use netvod\database\ConnectionFactory;

class LectureEpisodeAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur n'a pas une session de connecté
        if(!Authentification::isAuthentified()){
            header('Location: ?action=login');
        }

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
        $html = <<< END
        <html>
            <head>
            <title>NetVOD</title>
            <link href="./css/EpRendererStyle.css" rel="stylesheet">
                                      <link href="./css/Notation-style.css" rel="stylesheet">
                          <meta name="viewport" content="width=device-with,initial-scale=1.0">

            </head>
            <body xmlns="http://www.w3.org/1999/html">
        <div class="container">
            <div class="top">
                    <h1>{$data['titre']}</h1>
                    <div class="duree">
                    <script>
                    var dureeRaw = {$data['duree']};
                    if (dureeRaw < 60) {
                        document.write(dureeRaw + "sec");
                    } else {
                        var dureeM = Math.floor(dureeRaw / 60);
                        var dureeS = dureeRaw % 60;
                        document.write(dureeM + "min" + dureeS + "sec");
                    }
                    </script>
                    </div>
                     <div class ="button">
                        <a href="index.php?action=serie&serie_id={$data['serie_id']}"><button>Retour</button></a>
        END;

        $act = new ActionNoteCommentaire($data['serie_id']);
        $html .= $act->execute();

        $html .= <<< END
                    </div>   

            </div>  
                    <p>{$data['resume']}</p>
                    
        </div>
                    <video controls>
                        <source src="../resources/videos/{$data['file']}" type="video/mp4">
                    </video>
                   </body> 
        
        </html> 
        END;

        return $html;
    }
}