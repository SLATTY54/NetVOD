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
        $user = unserialize($_SESSION['user']);
        $id_user = $user->__get("id");

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
                        <nav>
                            
                            <img src="../resources/images/logo.png" alt="logo">
                            
                            <ul class="menu">                         
                                <li class="profile">
                                    <a href="index.php?action=DisplayProfileAction"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="color: white"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/></svg></a>                   
                                </li>
                            </ul>    
                        </nav>
                        
                            
                    </div>
                    <h1 style="color: white;margin-bottom: 0;margin-top: 2%;font-family: Netflix Sans,Helvetica Neue,Segoe UI,Roboto,Ubuntu,sans-serif;text-align: left">CATALOGUE</h1>
                    <div class="wrapper">
                        <section id="section1">
                                
                                    
                        
                    
                    
                END;

        $bd = ConnectionFactory::makeConnection();

        $stmt = $bd->prepare('SELECT count(*) as nbSerie FROM serie');
        $stmt->execute();
        $nbSeries = $stmt->fetch(PDO::FETCH_ASSOC);
        $nbSeries = $nbSeries['nbSerie'];
        $stmt->closeCursor();

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


                    $alreadyFav = Favourite::isAlreadyFavourite($id_user, $id);
                    $star = $alreadyFav ? "🌟" : "⭐";

                    $html.=<<<end
                        <div class="item">
                            <br><a href='?action=serie&serie_id=$id'>
                                    <img src=../resources/images/$img href='?action=serie&serie_id=$id' style="width:440px;height:210px ">
                                    <h1 class="heading">$titre</h1>
                                </a>
                                <form method="post" action="?action=favourite&callback={$_SERVER['QUERY_STRING']}">
                                    <div class="like">
                                        <input type="hidden" name="serie_id" value="$id">
                                        <button type="submit">$star</button>
                                    </div>
                                </form>
                        </div>
                        
                    end;
                    $titles[]=$titre;
                    $compteur++;
                }


            }
            $query->closeCursor();
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









        $query=$bd->prepare("SELECT id_serie FROM preferences where id_user = $id_user");
        $query->execute();
        $nbligne=$query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        $html.=<<<END
                </div>
                <h1 style="color: white;margin-bottom: 0;margin-top: 2%;font-family: Netflix Sans,Helvetica Neue,Segoe UI,Roboto,Ubuntu,sans-serif;text-align: left">FAVORIE</h1>
                END;
        if (!$nbligne){
            $html.=<<<HEREDOC
                <div class="footer">
                    <h1 style="color: white;margin-bottom: 0;margin-top: 2%;font-family: Netflix Sans,Helvetica Neue,Segoe UI,Roboto,Ubuntu,sans-serif;text-align: center">You don't have any favourite serie yet</h1>
                </div>
            HEREDOC;
        }else{

            $html.=<<<END
                           <div class="fav">
                                <section id="sectionFav1">             
                        END;

            $c = 1;
            $titles = array();
            $stmt = $bd->prepare('SELECT count(*) as nbSerieFav FROM preferences WHERE id_user = :id_user');
            $stmt->execute(['id_user' => $id_user]);@
            $nbSeriesFav = $stmt->fetch(PDO::FETCH_ASSOC);
            $nbSeriesFav = $nbSeriesFav['nbSerieFav'];

            $stmt->closeCursor();
            while (count($titles) != $nbSeriesFav){
                $cApres = $c+1;
                $query=$bd->prepare("SELECT id,titre,img FROM serie inner join preferences on serie.id = preferences.id_serie where preferences.id_user = $id_user");
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

                        $alreadyFav = Favourite::isAlreadyFavourite($id_user, $id);
                        $star = $alreadyFav ? "🌟" : "⭐";

                        $html.=<<<end
                        <div class="item">
                            <br><a href='?action=serie&serie_id=$id'>
                                    <img src=../resources/images/$img href='?action=serie&serie_id=$id' style="width:440px;height:210px ">
                                    <h1 class="heading">$titre</h1>
                                </a>
                                <form method="post" action="?action=favourite&callback={$_SERVER['QUERY_STRING']}">
                                    <div class="like">
                                        <input type="hidden" name="serie_id" value="$id">
                                        <button type="submit">$star</button>
                                    </div>
                                </form>
                        </div>
                        
                    end;
                        $titles[]=$titre;
                        $compteur++;
                    }


                }
                $query->closeCursor();
                if (count($titles) != $nbSeriesFav){
                    $html.=<<<HEREDOC
                            <a href="#sectionFav$cApres" class="arrow__btn right-arrow">›</a>
                            </section>
                            <section id="sectionFav$cApres">
                                <a href="#sectionFav$c" class="arrow__btn left-arrow">‹</a>
                        HEREDOC;
                }else{
                    if (count($titles) === $nbSeriesFav){
                        $html.=<<<HEREDOC
                                </section>
                            HEREDOC;
                    }
                }
                $c++;
            }
        }






        $query=$bd->prepare("SELECT idSerie FROM EnCours where idUser = $id_user");
        $query->execute();
        $nbligne=$query->fetch(PDO::FETCH_ASSOC);

        $query->closeCursor();

        $html.=<<<END
                </div>
                <h1 style="color: white;margin-bottom: 0;margin-top: 2%;font-family: Netflix Sans,Helvetica Neue,Segoe UI,Roboto,Ubuntu,sans-serif;text-align: left">REPRENDRE</h1>
                END;

        if (!$nbligne){
            $html.=<<<HEREDOC
                <div class="footer">
                    <h1 style="color: white;margin-bottom: 0;margin-top: 2%;font-family: Netflix Sans,Helvetica Neue,Segoe UI,Roboto,Ubuntu,sans-serif;text-align: center">Vous n'avez pas encore débuté de serie</h1>
                </div>
            HEREDOC;
        }else{

            $html.=<<<HEREDOC
                        <div class="enCours">
                            <section id="sectionEnCours1">
                    HEREDOC;

            $c = 1;
            $titles = array();
            $stmt = $bd->prepare('SELECT count(*) as nbSeriesEnCours FROM EnCours WHERE idUser = :id_user');
            $stmt->execute(['id_user' => $id_user]);@
            $nbSeriesEnCours = $stmt->fetch(PDO::FETCH_ASSOC);
            $nbSeriesEnCours = $nbSeriesEnCours['nbSeriesEnCours'];

            $stmt->closeCursor();
            while (count($titles) != $nbSeriesEnCours){
                $cApres = $c+1;
                $query=$bd->prepare("SELECT id,titre,img FROM serie inner join EnCours on serie.id = EnCours.idSerie where EnCours.idUser = $id_user");
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

                        $alreadyFav = Favourite::isAlreadyFavourite($id_user, $id);
                        $star = $alreadyFav ? "🌟" : "⭐";

                        $html.=<<<end
                        <div class="item">
                            <br><a href='?action=serie&serie_id=$id'>
                                    <img src=../resources/images/$img href='?action=serie&serie_id=$id' style="width:440px;height:210px ">
                                    <h1 class="heading">$titre</h1>
                                </a>
                                <form method="post" action="?action=favourite&callback={$_SERVER['QUERY_STRING']}">
                                    <div class="like">
                                        <input type="hidden" name="serie_id" value="$id">
                                        <button type="submit">$star</button>
                                    </div>
                                </form>
                        </div>
                        
                    end;
                        $titles[]=$titre;
                        $compteur++;
                    }


                }
                $query->closeCursor();
                if (count($titles) != $nbSeriesEnCours){
                    $html.=<<<HEREDOC
                            <a href="#sectionEnCours$cApres" class="arrow__btn right-arrow">›</a>
                            </section>
                            <section id="sectionEnCours$cApres">
                                <a href="#sectionEnCours$c" class="arrow__btn left-arrow">‹</a>
                        HEREDOC;
                }else{
                    if (count($titles) === $nbSeriesEnCours){
                        $html.=<<<HEREDOC
                                </section>
                            HEREDOC;
                    }
                }
                $c++;
            }
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