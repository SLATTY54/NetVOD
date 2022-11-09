<?php

namespace netvod\actions;

use netvod\classes\Authentification;

class ForgetPasswordAction extends Action
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
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                if (Authentification::isRegistered($email))
                {
                    $token = openssl_random_pseudo_bytes(8);
                    $token = bin2hex($token);

                    $html .= <<<HEREDOC
                    <a href="?action=forget_password&token={$token}">réinitialisé mot de passe</a>
                    HEREDOC;

                }
                else
                {
                    $html .= $this->renderHtml(true);
                }
            }
        }

        return $html;
    }

    public function renderHtml(bool $errorEmail): string
    {
        $html = <<<HEREDOC
                <body>
                <form method="post" action="?action=forget_password">
                <video autoplay muted loop id="trailer">
                    <source src="../resources/netvod_trailer.mp4" type="video/mp4"></video>
                <img id="logo" src="../resources/logo.png" alt="logo">
                
                <div class="connexion">
                    <div class="register">
                        <h1 id="title">Mot de passe oublié</h1>
                    
                        <div class="emailControl">
                            <input type="email" name="email" id="id_email" placeholder="E-mail" class="textfield">
                        </div>
                HEREDOC;

        if ($errorEmail) {
            $html .= <<<HEREDOC
                        <div class="errorMessage">
                            <label>Cet email n'existe pas</label>
                        </div>
                    HEREDOC;
        }
        $html .= <<<HEREDOC
                        
                        <div class="buttonControl">
                            <input type="submit" class="btnRegister" value="Mot de passe oublié">
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