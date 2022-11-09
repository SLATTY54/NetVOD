<?php

namespace netvod\actions;

use netvod\classes\Authentification;

class ForgetPasswordAction extends Action
{

    private $token;


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
                    // TODO : export this to a token generation static function (in Authentification class ? or in a new class ?)
                    $this->token = openssl_random_pseudo_bytes(8);
                    $this->token = bin2hex($this->token);

                    // TODO : redirect to a page to change password
                    $html .= $this->renderChangingPassword($this->token);

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
                <form method="post" action="?action=forget_password&token={$this->token}"> 
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


    public function renderChangingPassword(string $token): string
    {
        // TODO : use this <a href="?action=forget_password&token={$token}"></a> to put the token in the url or line 58
        $hmtl = "<p>$token</p>";

        return $hmtl;
    }
}