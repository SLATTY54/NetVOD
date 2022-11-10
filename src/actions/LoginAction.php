<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\Exceptions\AuthException;

/**
 * Action permettant de se connecter au site Internet et plus précisement de se connecter à sa session
 */
class LoginAction extends Action
{

    public function execute(): string
    {

        // méthode GET, retourne la réponse HTML de base
        $html = $this->renderHtml();

        // méthode POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && filter_var($_POST['password'], FILTER_SANITIZE_STRING)) {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

                try {
                    Authentification::authenticate($email, $password);
                    header('Location: ?action=catalogue');
                    // si l'email ou mot de passe est incorrect, alors on retourne une erreur
                } catch (AuthException) {
                    $html = $this->renderHtml(true);
                }

                // si les données ne passent pas les filter, alors on retourne une erreur
            } else {
                $html = $this->renderHtml(true);
            }

        }

        return $html;
    }


    public function renderHtml(bool $error = false): string
    {

        $html = <<<HEREDOC
                   <html lang="fr">
                    <head><title>NetVOD</title>
                          <link href="./css/formulaire_style.css" rel="stylesheet">
                    </head> 
                    <body>
                    <form method="post" action="?action=login">
                        <video autoplay muted loop id="trailer">
                            <source src="../resources/videos/netvod_trailer.mp4" type="video/mp4"></video>
                        <img id="logo" src="../resources/images/logo.png" alt="logo">
                        
                        <div class="connexion">
                           
                            <div class="login">
                                <h1 id="title">Se connecter</h1>
                               
                                <div class="emailControl">
                                        <input type="email" name="email" id="id_email" placeholder="E-mail" class="textfield">      
                                </div>
                                
                                <div class="passwordControl">
                                        <input type="password" name="password" id="id_password" placeholder="Mot de passe" class="textfield">
                                </div>
                                <div class="Signup">
                                        <p><a href="?action=forgetPassword" style="color: red" >Mot de passe oublié ?</a></p>
                                </div>
                   HEREDOC;

        // afficher le message indiquant une adresse mail ou mot de passe incorrect
        if ($error) {
            $html .= <<<HEREDOC
                                    <div class="errorMessage">
                                        <label>L'adresse e-mail ou le mot de passe est incorrect </label>
                                    </div>
                        HEREDOC;
        }

        $html .= <<<HEREDOC
                                    <div class="buttonControl">
                                        <button type="submit" class="btnConnect">Connexion</button>
                                    </div>
                                    <div class="Signup">
                                        <p>Vous n'avez pas de compte ? <a href="?action=signup" style="color: red" >S'inscrire</a></p>
                                    </div>
                                </div>
                            </div>
                            
                            </form>
                            <p class="footer">&#169; KINZELIN Rémy, SIGNORELLI LUCAS, HIRTZ Jules, PERROT Alexandre et ERPELDING Lucas</p>
                        </body>
                    </html>
                    HEREDOC;

        return $html;
    }

}