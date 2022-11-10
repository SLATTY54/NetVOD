<?php

namespace netvod\actions;

use netvod\classes\Authentification;

/**
 * Action permettant de se connecter au site Internet et plus précisement de se connecter à sa session
 */
class SignUpAction extends Action
{

    public function execute(): string
    {
        // méthode GET, retourne la réponse HTML de base
        $html = $this->renderHtml();

        // méthode POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && filter_var($_POST['password'], FILTER_SANITIZE_STRING) && filter_var($_POST['password2'], FILTER_SANITIZE_STRING)) {

                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                $password2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);

                // si les mot de passe ne correspondent pas, alors on retourne une erreur de mot de passe
                if ($password != $password2) {
                    $html = $this->renderHtml(false, true);
                // si les mots de passe correspondent et que l'email n'est pas déja utilisé, alors l'inscription se fait
                } else if (!Authentification::isRegistered($email)) {
                    Authentification::register($email, $password);
                    header('Location: ?action=login');
                // si l'email est déja utilisé, alors on retourne une erreur
                } else {
                    $html = $this->renderHtml(true, false);
                }
            }
        }

        return $html;
    }


    public function renderHtml(bool $errorEmail = false, bool $errorPwd = false): string
    {
        $html = <<<HEREDOC
                    <html>
                        <head>
                            <title>NetVOD</title>
                            <link href="./css/formulaire_style.css" rel="stylesheet">
                        </head>
                        <body>
                            <form method="post" action="?action=signup">
                                <video autoplay muted loop id="trailer">
                                    <source src="../resources/videos/netvod_trailer.mp4" type="video/mp4">
                                </video>
                                <img id="logo" src="../resources/images/logo.png" alt="logo">
                    
                                <div class="connexion">
                                    <div class="register">
                                        <h1 id="title">S'inscrire</h1>
                                    
                                        <div class="emailControl">
                                            <input type="email" name="email" id="id_email" placeholder="E-mail" class="textfield">
                                        </div>
                                
                                        <div class="passwordControl">
                                            <input type="password" name="password" id="id_password" placeholder="Mot de passe" class="textfield" minlength="5">
                                        </div>
                                    
                                        <div class="passwordControl">
                                            <input type="password" name="password2" id="id_password" placeholder="Confirmer mot de passe" class="textfield" minlength="5">
                                        </div>
                HEREDOC;

        // afficher le message indiquant que les mots de passe ne correspondent pas
        if ($errorPwd) {
            $html .= <<<HEREDOC
                                        <div class="errorMessage">
                                            <label>Les mots de passe ne correspondent pas</label>
                                        </div>
                        HEREDOC;
        }

        // afficher le message indiquant que les mots de passe ne correspondent pas
        if ($errorEmail) {
            $html .= <<<HEREDOC
                                        <div class="errorMessage">
                                            <label>Cet email est déjà utilisé</label>
                                        </div>
                        HEREDOC;
        }

        $html .= <<<HEREDOC

                                        <div class="buttonControl">
                                            <input type="submit" class="btnRegister" value="S'inscrire">
                                        </div>
                                        <div class="Signup">
                                            <p>Vous êtes déjà inscrit ? <a href="?action=login" style="color: red" >Se connecter</a></p>
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