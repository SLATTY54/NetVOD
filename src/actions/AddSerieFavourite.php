<?php

namespace netvod\actions;

use netvod\classes\Favourite;

class AddSerieFavourite extends Action
{

    public function execute(): string
    {


        $user = unserialize($_SESSION['user']);
        Favourite::addToFavourite($user->__get("id"), $_GET['id']);

        $html = <<< HEREDOC
                        <html lang="fr">
                            <head><title>NetVod</title>
                                  <link href="./css/welcome-style.css" rel="stylesheet">
                            </head>
                            <body>
                                <div>
                                    <p>Ajout de ta série préférée</p>
                                </div>
                            </body>
                        </html>
                HEREDOC;

        return $html;
    }
}