<?php

namespace netvod\actions;

use netvod\classes\Favourite;
use netvod\database\ConnectionFactory;
use PDO;

class DisplayCatalogueAction extends Action
{

    public function execute(): string
    {
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
                            <img src="../resources/images/logo.png" alt="logo">
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

        $stmt = $bd->prepare('SELECT max(id) as maxim FROM serie');
        $stmt->execute();
        $nbSeries = $stmt->fetch(PDO::FETCH_ASSOC);
        $nbSeries = $nbSeries['maxim'];

        $c = 1;
        while(count($titles) != $nbSeries){
            $cApres = $c+1;
            $query=$bd->prepare("SELECT id,titre,img FROM serie");
            $query->execute();
            $compteur = 1;
            foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
                if ($compteur>4){
                    break;
                }
                $titre=$row['titre'];

                if (!in_array($titre,$titles)){
                    $id=$row['id'];
                    $img=$row['img'];

                    $user = unserialize($_SESSION['user']);
                    $id_user = $user->__get("id");

                    $alreadyFav = Favourite::isAlreadyFavourite($id_user, $id);
                    $star = $alreadyFav ? "★" : "⭐";

                    $html.=<<<end
                        <div class="item">
                            <br><a href='?action=serie&serie_id=$id'>
                                    <img src=../resources/images/$img href='?action=serie&serie_id=$id' style="width:440px;height:210px ">
                                    <h1 class="heading">$titre</h1>
                                </a>
                            <form method="post" action="?action=favourite&callback={$_SERVER['QUERY_STRING']}">
                                <div class="fav">
                                    <input type="checkbox" id="star" name="serie_id" value="$id"/>
                                    <label for="star" title="text">Like</label>
                                </div>
                            </form>
                            
                            
                        </div>
                    end;
                    $titles[]=$titre;
                    $compteur++;
                }


            }
            if (count($titles) != $nbSeries){
                $html.=<<<HEREDOC
                            <a href="#section$cApres" class="arrow__btn right-arrow">›</a>
                            </section>
                            <section id="section$cApres">
                                <a href="#section$c" class="arrow__btn left-arrow">‹</a>
                        HEREDOC;
            }else{
                if (count($titles) === $nbSeries){
                    $html.=<<<HEREDOC
                                </section>
                            HEREDOC;
                }
            }
            $c++;
        }

        $html.=<<<HEREDOC
                       
                    </div>
                    </div>
                    <script>
                            $('.click').click(function() {
                                if ($('span').hasClass("fa-star")) {
                                        $('.click').removeClass('active')
                                    setTimeout(function() {
                                        $('.click').removeClass('active-2')
                                    }, 30)
                                        $('.click').removeClass('active-3')
                                    setTimeout(function() {
                                        $('span').removeClass('fa-star')
                                        $('span').addClass('fa-star-o')
                                    }, 15)
                                } else {
                                    $('.click').addClass('active')
                                    $('.click').addClass('active-2')
                                    setTimeout(function() {
                                        $('span').addClass('fa-star')
                                        $('span').removeClass('fa-star-o')
                                    }, 150)
                                    setTimeout(function() {
                                        $('.click').addClass('active-3')
                                    }, 150)
                                    $('.info').addClass('info-tog')
                                    setTimeout(function(){
                                        $('.info').removeClass('info-tog')
                                    },1000)
                                }
                            })
                    </script>
                    </body>
                    </html>
                HEREDOC;

        return $html;

    }



}