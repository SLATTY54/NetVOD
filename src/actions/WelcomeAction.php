<?php

namespace netvod\actions;

class WelcomeAction extends Action
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
                                <footer><a href="?action=login"><button class="btn"> Entrez sur le site</button></a></footer>
                            </body>
                        </html>
        
HEREDOC;


        return $html;
          
    }
}
