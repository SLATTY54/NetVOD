<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\classes\User;
use netvod\database\ConnectionFactory;

class DisplayProfileAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur n'a pas une session de connecté
        if(!Authentification::isAuthentified()){
            header('Location: ?action=login');
        }

        $bd = ConnectionFactory::makeConnection();

        $user = unserialize($_SESSION['user']);


        if ($user->__get('nom') == null){
            $html = $this->renderHtml(true, false);
        }else{
            $html = $this->renderHtml(false,false,$user);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['modifier'])){
                $id = $user->__get('id');
                $stmt = $bd->prepare("UPDATE User SET nom = NULL, prenom = NULL, dateN = NULL, biographie = NULL WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $html = $this->renderHtml(true);
            }else if(!filter_var($_POST['nom'],FILTER_SANITIZE_STRING)||!filter_var($_POST['prenom'],FILTER_SANITIZE_STRING)||!filter_var($_POST['dateN'],FILTER_SANITIZE_STRING)||!filter_var($_POST['biographie'],FILTER_SANITIZE_STRING)) {
                    $html = $this->renderHtml(true, true);
                }else{
                    $user->__set('nom',filter_var($_POST['nom'],FILTER_SANITIZE_STRING));
                    $user->__set('prenom',filter_var($_POST['prenom'],FILTER_SANITIZE_STRING));
                    $user->__set('date_naissance',filter_var($_POST['dateN'],FILTER_SANITIZE_STRING));
                    $user->__set('biographie',filter_var($_POST['biographie'],FILTER_SANITIZE_STRING));

                    $_SESSION['user'] = serialize($user);
                    $id = $user->__get('id');
                    $nom = $user->__get('nom');
                    $prenom = $user->__get('prenom');
                    $date_naissance = $user->__get('date_naissance');
                    $bio = $user->__get('biographie');
                    $stmt = $bd->prepare("UPDATE User SET nom = :nom, prenom = :prenom, dateN = :date_naissance, biographie = :bio WHERE id = :id");
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':prenom', $prenom);
                    $stmt->bindParam(':date_naissance', $date_naissance);
                    $stmt->bindParam(':bio', $bio);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();

                    $html = $this->renderHtml(false,false,$user);
                }
        }

        return $html;
    }

    public function renderHtml(bool $firstProfile,bool $error = false,$user=null): string
    {
        $html = <<<HEREDOC
                         <html lang="fr">
                    <head><title>NetVOD</title>
                          <link href="./css/profile_style.css" rel="stylesheet">
                    </head>
                    <body>
                        <div class="header">
                            <nav>
                                
                                <img src="../resources/images/logo.png" alt="logo">
                                
                          
                            </nav>
                        </div> 
                   HEREDOC;

        if ($firstProfile){

            $html .= <<<HEREDOC
                        <form method="post" action="?action=profile">
                            <div class="profile_container">
                               
                                <div class="profile2">
                                    <h1 id="title">Mon Profil</h1>
                                   
                                    <div class="nom">
                                            <input type="text" name="nom" id="id_nom" placeholder="Nom" class="textfield">      
                                    </div>
                                    
                                    <div class="prenom">
                                            <input type="text" name="prenom" id="id_prenom" placeholder="Prénom" class="textfield">
                                    </div>
                                    
                                    <div class="dateN">
                                            <input type="date" name="dateN" id="id_dateN" placeholder="Date de naissance" class="textfield">
                                    </div>

                                    <div class="biographie">
                                            <input type="text" name="biographie" id="id_biographie" placeholder="biographie" class="textfield" maxlength="200">
                                    </div>
                       HEREDOC;

            // afficher le message indiquant une adresse mail ou mot de passe incorrect
            if ($error) {
                $html .= <<<HEREDOC
                                        <div class="errorMessage">
                                            <label>valeur invalide</label>
                                        </div>
                            HEREDOC;
            }

            $html .= <<<HEREDOC
                                        <div class="buttonControl">
                                            <button type="submit" class="btnConnect">Valider</button>
                                        </div>
                                        <a href="?action=catalogue" style="margin-top: 2%;margin-left: auto;margin-right: auto;margin-bottom: 2%"><button type="button" class="btnRetour" name="retour">Retour</button></a>
                                
                                    </div>
                                    </div>
                                
                                </form>
                                
                                <p class="footer">&#169; KINZELIN Rémy, SIGNORELLI LUCAS, HIRTZ Jules, PERROT Alexandre et ERPELDING Lucas</p>
                            </body>
                        </html>
                        HEREDOC;
        }else{
            $html.=<<<HEREDOC
                           <div class="profile_container"> 
                                <div class="profile">
                                    <h1 id="title">Mon Profil</h1>
                                    <div class="nom">
                                        <p>Nom : {$user->__get("nom")}</p>
                                        
                                    </div>
                                    <div class="prenom">
                                        <p>Prénom : {$user->__get('prenom')}</p>
                                        
                                    </div>
                                    <div class="dateN">
                                        <p>Date de naissance : {$user->__get('date_naissance')}</p>
                                        
                                    </div>
                                    <div class="biographie">
                                        <p>Biographie : {$user->__get('biographie')}</p>
                                    </div>
                                    
                                        <form action="" method="post">
                                            <div class="buttonControl">
                                                   <button type="submit" class="btnModif" name="modifier">Modifier</button>
                                            </div>
                                        </form>
                                        <a href="?action=catalogue" style="margin-top: 2%;margin-left: auto;margin-right: auto"><button type="submit" class="btnRetour" name="retour">Retour</button></a>
                                </div>
                            </div>
                        <p class="footer">&#169; KINZELIN Rémy, SIGNORELLI LUCAS, HIRTZ Jules, PERROT Alexandre et ERPELDING Lucas</p>
                        </body>
                       </html>
                    HEREDOC;


        }

        return $html;
    }


}