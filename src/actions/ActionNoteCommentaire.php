<?php

namespace netvod\actions;


use netvod\classes\Authentification;
use netvod\classes\Comment;
use netvod\Exceptions\CommentException;

class ActionNoteCommentaire extends Action {

        private int $id_serie;

        public function __construct(int $id_serie)
        {
            $this->id_serie = $id_serie;
        }

    public function execute(): string {

        // si l'utilisateur n'a pas une session de connecté
        if(!Authentification::isAuthentified()){
            header('Location: ?action=login');
        }

            $html = "";

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $html .= $this->renderHtml(true,false);
            }else{
                if (isset($_POST['send'])){
                    $nbetoile = filter_var($_POST['rate'], FILTER_SANITIZE_NUMBER_INT);
                    $commentaire = filter_var($_POST['commentaire'], FILTER_SANITIZE_STRING);
                    $user = unserialize($_SESSION['user']);
                    $id = $user->__get('id');

                    try {
                        Comment::addComment($id, $this->id_serie, $commentaire, $nbetoile);
                    }catch (CommentException $e){
                        $html .= $this->renderHtml(true,true);
                    }
                }
            }

            return $html;
        }

        public function renderHtml(bool $popup,bool $exc):string
        {
            $html = <<<END
                        <button type='submit' onclick='openPopup()'>Donner votre avis</button>
                        END;

            if ($exc){
                $html .= <<<HEREDOC
                    <div class="erreur">
                        <p>Vous avez déjà commenté cette série</p>
                    </div>
                HEREDOC;
            }

            $html .= <<<HEREDOC
                                    <div class="popup" id="popup">
                                        <form method="post" >
                                            <div class="popupContent">
                                                <h1>Noter le film</h1>
                                                    <div class="rating">
                                                        <div class="note">
                                                                <input type="radio" id="star5" name="rate" value="5" />
                                                                <label for="star5" title="text">5 stars</label>
                                                                <input type="radio" id="star4" name="rate" value="4" />
                                                                <label for="star4" title="text">4 stars</label>
                                                                <input type="radio" id="star3" name="rate" value="3" />
                                                                <label for="star3" title="text">3 stars</label>
                                                                <input type="radio" id="star2" name="rate" value="2" />
                                                                <label for="star2" title="text">2 stars</label>
                                                                <input type="radio" id="star1" name="rate" value="1" />
                                                                <label for="star1" title="text">1 star</label>
                                                        </div>
                                                    
                                                        <div class="avis">
                                                            <textarea class="commentaire" name="commentaire" id="commentaire" cols="30" rows="10" placeholder="Commentaire"></textarea>
                                                        </div>
                                                    </div>

                                                    <button type="submit" name="send" class="btnE">Envoyer</button>
                                                    <br>
                                                    <button type="reset" class="btnC" onclick="closePopup()">Fermer</button>
                                                
                                            </div>
                                        </form>
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
                           
                HEREDOC;
             return $html;

        }
}