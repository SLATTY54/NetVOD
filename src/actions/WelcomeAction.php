<?php

namespace netvod\actions;

class WelcomeAction extends Action
{
    public function execute(): string
    {
        return <<<HEREDOC
                        <html lang="fr">
                            <head><title>NetVOD</title>
                                  <link href="./css/welcome-style.css" rel="stylesheet">
                            </head>
                            <body>
                                <div class="txt" contenteditable="false">
                                    NetVOD
                                </div>
                                <a href="?action=signup"><button class="btnI">Inscription</button></a>
                                <a href="?action=login"><button class="btnC">Connexion</button></a>
                            </body>
                        </html>
                    HEREDOC;
          
    }
}
