<?php

namespace netvod\actions;

class welcomeAction extends Action
{
    public function execute(): string
    {
        $html = <<<HEREDOC
                        <html lang="en">
                            <head><title>NetVod</title>
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


        return $html;
          
    }
}
