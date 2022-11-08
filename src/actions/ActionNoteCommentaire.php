<?php

namespace netvod\actions;

class ActionNoteCommentaire extends Action {



        public function execute(): string {
            $html = <<<HEREDOC
                <html lang="en">
                    <head><title>NetVod</title>
                          <link href="./css/Notation-style.css" rel="stylesheet">
                          <meta name="viewport" content="width=device-with,initial-scale=1.0">
                    </head>
                    
            HEREDOC;

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $html .= $this->renderHtml(true);
            }else{
                if (isset($_POST['send'])){
                    $nbetoile = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                    $commentaire = filter_var($_POST['commentaire'], FILTER_SANITIZE_STRING);
                    $iduser = filter_var($_SESSION['user']->id, FILTER_SANITIZE_NUMBER_INT);
                    echo $iduser;
                    echo $nbetoile;
                    echo $commentaire;
                }
            }

            return $html;
        }

        public function renderHtml(bool $popup):string{

            $nbEtoiles =0;
            $html = <<<HEREDOC
                    <body>
                                <div class="container">
                                    <button type="submit" onclick="openPopup()">click me!</button>
                                    <div class="popup" id="popup">
                                        <form method="post" >
                                            <div class="popupContent">
                                                <h1>Noter le film</h1>
                                                <div class="rating">
                                                   <a id="5" title="Donner 5 étoiles">☆</a>
                                                   <a id="4" title="Donner 4 étoiles">☆</a>
                                                   <a id="3" title="Donner 3 étoiles">☆</a>
                                                   <a id="2" title="Donner 2 étoiles">☆</a>
                                                   <a id="1" title="Donner 1 étoile">☆</a>
                                                </div>
                                                
                                                    <div class="avis">
                                                        <textarea class="commentaire" name="commentaire" id="commentaire" cols="30" rows="10" placeholder="Commentaire"></textarea>
                                                    </div>
                                                

                                                    <button type="submit" name="send" class="btnE">Envoyer</button>
                                                
                                            </div>
                                        </form>
                                        <button type="button" class="btnC" onclick="closePopup()">Fermer</button>
                  
                                    </div>
                                </div>    
                                                                
                                <script>
                                        
                                
                                        let popup = document.getElementById("popup");
                                        function openPopup() {
                                            popup.classList.add("open-popup");
                                        }
                                        
                                        function closePopup() {
                                            popup.classList.remove("open-popup");
                                        }
                                </script>
                           
                       
                        </body>
                    </html>
                HEREDOC;





             return $html;

        }







}