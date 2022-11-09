<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;
use PDO;

class DisplayCatalogueAction extends Action{

    public function execute(): string{
        $titles = array();

        $html= <<<END
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>NetVOD</title>
                    <link rel="stylesheet" href="css/catalogue-style.css">
                </head>
                <body>
                
                <div class="container">
                    <div class="header">
                        <div class="logo">
                            <img src="../resources/logo.png" alt="logo">
                        </div>
                        
                        <h1>Catalogue de Série</h1>
                        
                        <div class="search">
                            <input type="text" placeholder="Rechercher une série">
                            <button>Rechercher</button>
                        </div>    
                        
                            
                    </div>
                    <div class="wrapper">
                        <section id="section1">
                                
                                    
                        
                    
                    
                END;
        $bd = ConnectionFactory::makeConnection();
        //comptez le nombre de séries
        $stmt = $bd->prepare('SELECT max(id) as maxim FROM serie');
        $stmt->execute();
        $nbSeries = $stmt->fetch(PDO::FETCH_ASSOC);
        $nbSeries = $nbSeries['maxim'];

        $c = 1;
        while(count($titles)+1 != $nbSeries){
            $cApres = $c+1;
            $query=$bd->prepare("SELECT id,titre,img FROM serie");
            $query->execute();
            $compteur = 1;
            foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
                if ($compteur>4){
                    break;
                }
                $titre=$row['titre'];

                if (in_array($titre,$titles)){
                    break;
                }

                $id=$row['id'];
                $img=$row['img'];
                $html.=<<<end
                        <div class="item">
                            <br><a href='?action=serie&serie_id=$id'>
                                    <img src=../ressources/images/$img href='?action=serie&serie_id=$id' style="width:341px;height:192px ">
                                    <h1 class="heading">$titre</h1>
                                </a>
                            <button type="submit">ajouter au favoris</button>
                        </div>
                end;
                $titles[]=$titre;
                $compteur++;
            }
            if (count($titles)+1 != $nbSeries){
                $html.=<<<HEREDOC
                            <a href="#section$cApres" class="arrow__btn right-arrow">›</a>
                            </section>
                            <section id="section$cApres">
                                <a href="#section$c" class="arrow__btn left-arrow">‹</a>
                        HEREDOC;
            }else{
                $html.=<<<HEREDOC
                            </section>
                        HEREDOC;
            }
            $c++;
        }

        $html.=<<<HEREDOC
                       
                    </div>
                    </div>
                    </body>
                    </html>
                HEREDOC;

        return $html;
    }

}