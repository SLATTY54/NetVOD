<?php

namespace netvod\actions;

use netvod\classes\User;
use netvod\database\ConnectionFactory;

class DisplayProfileAction extends Action
{

    public function execute(): string
    {
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
                    $user->__set('nom',$_POST['nom']);
                    $user->__set('prenom',$_POST['prenom']);
                    $user->__set('date_naissance',$_POST['dateN']);
                    $user->__set('biographie',$_POST['biographie']);

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
                                
                                <ul class="menu">                         
                                    <li class="profile">
                                        <a href="index.php?action=DisplayProfileAction"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="color: white"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/></svg></a>                   
                                    </li>
                                </ul>    
                            </nav>
                        </div> 
                   HEREDOC;

        if ($firstProfile){

            $html = <<<HEREDOC
                        <form method="post" action="?action=profile">
                            <div class="profile_container">
                               
                                <div class="profile">
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
                                                   <button type="submit" class="btnConnect" name="modifier">Modifier</button>
                                            </div>
                                        </form>
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