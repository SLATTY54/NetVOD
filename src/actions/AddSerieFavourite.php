<?php

namespace netvod\actions;

class AddSerieFavourite extends Action
{

    public function execute(): string
    {
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