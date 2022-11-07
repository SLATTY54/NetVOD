<?php

namespace netvod\actionFiles;

class defaultAction extends Action
{
    public function execute(): string
    {
        $html = <<<HEREDOC
                        <html lang="en">
                            <head><title>NetVod</title>
                                  <link href="../css/defaultActionStyle.scss" rel="stylesheet">
                            </head>
                            <body>
                                .txt contenteditable="true"
                                 | NetVod
                            </body>
                        </html>
        
HEREDOC;


        return $html;
          
    }
}
