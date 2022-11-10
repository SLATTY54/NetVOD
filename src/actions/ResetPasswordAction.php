<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\classes\Token;
use netvod\database\ConnectionFactory;

/**
 * Action permettant de changer le mot de passe à partir d'un token
 */
class ResetPasswordAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur a déjà une session
        if(Authentification::isAuthentified()){
            header('Location: ?action=catalogue');
        }

        $html = <<<HERE
        <html>
            <head>
            <title>NetVOD</title>
            <link href="./css/formulaire_style.css" rel="stylesheet">

            </head>
        HERE;
        $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= $this->renderHtml(false);

        } else {
            if (filter_var($_POST['password'], FILTER_SANITIZE_STRING) && filter_var($_POST['password2'], FILTER_SANITIZE_STRING)) {
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                $password2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);

                if ($password != $password2) {
                    $html .= $this->renderHtml(false);
                } else if (Token::isValidToken($token)) {

                    $db = ConnectionFactory::makeConnection();

                    $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);

                    $query = $db->prepare("UPDATE User SET passwd = ? WHERE token = ?");
                    $query->execute([$password, $token]);
                    $query->closeCursor();
                    header('Location: ?action=login');


                }
            }

        }

        return $html;
    }


    public function renderHtml(bool $errorPwd): string
    {
        $html = <<<HEREDOC
                <body>
                <video autoplay muted loop id="trailer">
                    <source src="../resources/videos/netvod_trailer.mp4" type="video/mp4"></video>
                    <img id="logo" src="../resources/images/logo.png" alt="logo">
                    <form method="post">

                    <div class="connexion">
                        <div class="register">
                            <h1 id="title">Réinitialiser mot de passe</h1>
                                 
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

        $html .= <<<HEREDOC

                            <div class="buttonControl">
                                <input type="submit" class="btnRegister" value="Réinitialiser">
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