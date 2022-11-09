<?php

namespace netvod\actions;

use netvod\classes\Favourite;

class AddSerieFavourite extends Action
{

    public function execute(): string
    {


        $user = unserialize($_SESSION['user']);
        $id_user = $user->__get("id");
        $id_serie = $_GET['id'];


        $html = <<< HEREDOC
                        <html lang="fr">
                            <head><title>NetVod</title>
                                  <link href="./css/welcome-style.css" rel="stylesheet">
                            </head>
                            <body>
                                <div>
                HEREDOC;

        if (Favourite::isAlreadyFavourite($id_user, $id_serie)) {
            $html .= "<p>Ta serie est déja dans tes favoris ! :(</p>";
        } else {
            Favourite::addToFavourite($id_user, $id_serie);
            $html .= "<p>Ta serie a bien été ajoutée à tes favoris !</p>";
        }


        $html .= <<< HEREDOC

                                </div>
                            </body>
                        </html>
                HEREDOC;

        return $html;
    }
}