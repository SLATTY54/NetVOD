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
                    $nbetoile = filter_var($_POST['note'], FILTER_SANITIZE_NUMBER_INT);
                    $commentaire = filter_var($_POST['commentaire'], FILTER_SANITIZE_STRING);
                    $user = unserialize($_SESSION['user']);
                    $id = $user->__get('id');
                    echo $id;
                    echo $nbetoile;
                    echo $commentaire;
                }
            }

            return $html;
        }

        public function renderHtml(bool $popup):string{

            $nbEtoiles =0;
            $html = <<<HEREDOC
                    <body xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
                                <div class="container">
                                    <button type="submit" onclick="openPopup()">click me!</button>
                                    <div class="popup" id="popup">
                                        <form method="post" >
                                            <div class="popupContent">
                                                <h1>Noter le film</h1>
                                                <div class="rating">
                                                   <input type="radio" value="5" name="note" id="1" title="Donner 5 étoiles">
                                                        <span class="etoile">★</span>
                                                   </input>
                                                   
                                                   <input type="radio" value="4" name="note" id="2" title="Donner 4 étoiles">
                                                        <span class="etoile">★</span>
                                                   </input>
                                                   <input type="radio" value="3" name="note" id="3" title="Donner 3 étoiles">
                                                        <span class="etoile">★</span>
                                                   </input>
                                                   <input type="radio" value="2" name="note" id="4" title="Donner 2 étoiles">
                                                        <span class="etoile">★</span>
                                                   </input>

                                                   <input type="radio" value="1" name="note" id="5" title="Donner 1 étoile">
                                                        <span class="etoile">★</span>
                                                    </input>
                                                </div>
                                                
                                                    <div class="avis">
                                                        <textarea class="commentaire" name="commentaire" id="commentaire" cols="30" rows="10" placeholder="Commentaire"></textarea>
                                                    </div>
                                                

                                                    <button type="submit" name="send" class="btnE">Envoyer</button>
                                                
                                            </div>
                                        </form>
                                        <button type="reset" class="btnC" onclick="closePopup()">Fermer</button>
                  
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