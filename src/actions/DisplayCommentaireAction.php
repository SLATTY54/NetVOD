<?php

namespace netvod\actions;

use netvod\classes\Comment;

class DisplayCommentaireAction extends Action
{

    public function execute(): string
    {
        $html = <<<HEREDOC
                    <html lang="fr">
                        <head><title>NetVOD</title>
                            <link href="./css/formulaire_style.css" rel="stylesheet">
                        </head> 
                        <body>
                HEREDOC;

        $serie_id = $_GET["serie"];
        $commentaires = Comment::getCommentaires($serie_id);
        foreach ($commentaires as $com) {
            $html .= <<<end
                    <p>$com->email : $com->commentaire <br>note : $com->note <br><br></p>
                end;
        }
        $html .= <<<end
                    </div>
                </div>
            end;
        return $html;
    }
}