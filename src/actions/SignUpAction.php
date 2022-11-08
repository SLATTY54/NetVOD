<?php

namespace netvod\actions;

use netvod\auth\Auth;
use netvod\classes\Authentification;

class SignUpAction extends Action
{

    public function execute(): string
    {
        $html = <<<HTML
        <html>
            <head>
            <title>NetVOD</title>
            <link href="./css/formulaire_style.css" rel="stylesheet">

            </head>
        HTML;

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $html .= $this->renderHtml(false);
        }
        else
        {

            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && filter_var($_POST['password'], FILTER_SANITIZE_STRING) && filter_var($_POST['password2'], FILTER_SANITIZE_STRING)) {

                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                $password2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);

                if ($password != $password2) {
                    $html .= $this->renderHtml(false, true);

                } else if (!Authentification::isRegistered($email)) {
                    Authentification::register($email, $password);
                    $html .= <<<HEREDOC
                    <p>Vous êtes inscrit</p>
                    <a href="?action=login">Se connecter</a>
                HEREDOC;
                } else {
                    $html .= $this->renderHtml(true);
                }
            }
        }

        return $html;
    }


    public function renderHtml(bool $errorEmail = false, bool $errorPwd = false): string
    {
        $html = <<<HEREDOC
                <body>
                <form method="post" action="?action=signup">
                <video autoplay muted loop id="trailer">
                    <source src="../resources/netvod_trailer.mov" type="video/mp4"></video>
                <img id="logo" src="../resources/logo.png" alt="logo">
                
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

        if ($errorPwd) {
            $html .= <<<HEREDOC
                        <div class="errorMessage">
                            <label>Les mots de passe ne correspondent pas</label>
                        </div>
                    HEREDOC;
        }

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
                    </div>
                </div>
                </form>
            </body>
            </html>
            HEREDOC;

        return $html;
    }
}