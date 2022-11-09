<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;
use PDO;

class DisplayCatalogueAction extends Action
{

    public function execute(): string
    {
        $html =<<<END
                    <html lang="en">
                        <head>
                            <title>NetVod</title>
                            <link href="./css/catalogue-style.css" rel="stylesheet">
                        </head>
                        <body>
                            <div class="container">
                                <div class="header">
                                    <h1>Catalogue de Série</h1>
                                </div>
                                <div class="catalogue">
                END;
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("SELECT id,titre,img FROM serie");
        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $titre = $row['titre'];
            $img = "../ressources/images/".$row['img'];
            $html .= <<<end
                <div class=$titre>
                    <img src=$img href='?action=serie&serie_id=$id' width="300" height="200">
                    <br><a href='?action=serie&serie_id=$id'>$titre</a>
                    <a href="?action=favourite&id=$id">⭐</a>
                </div>
            end;
        }
        $html .=<<<END
                                </div>
                            </div>
                        </body>
                    </html>
                END;

        return $html;
    }

    public function rendererHtml(){

    }

}