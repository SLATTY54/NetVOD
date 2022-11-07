<?php

namespace netvod\actionFiles;

class defaultAction extends Action
{
    public function execute(): string
    {
        $html = <<<HEREDOC
                        <html lang="en">
                            <head><title>NetVod</title>
                                  <link href="./css/defaultActionStyle.css" rel="stylesheet">
                            </head>
                            <body>
                                <div class="txt" contenteditable="true">
                                    NetVod
                                </div>
                            </body>
                        </html>
        
HEREDOC;


        return $html;
          
    }
}
