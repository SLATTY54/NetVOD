<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\classes\Token;
use netvod\database\ConnectionFactory;

/**
 * Action permettant de demander le token de réinitialisation du mot de passe
 */
class ForgetPasswordAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur a déjà une session
        if(Authentification::isAuthentified()){
            header('Location: ?action=catalogue');
        }

        $html = <<<HTML
        <html>
            <head>
                <title>NetVOD</title>
                <link href="./css/formulaire_style.css" rel="stylesheet">
            </head>
        HTML;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= $this->renderHtml(false, false);
        } else {

            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

                if (Authentification::isRegistered($email)) {
                    // TODO extraire les 5 lignes suivantes dans une fonction
                    $db = ConnectionFactory::makeConnection();
                    $stmt = $db->prepare('SELECT id FROM User WHERE email = ?');
                    $stmt->execute([$email]);
                    $id_user = $stmt->fetch()['id'];
                    $stmt->closeCursor();

                    // genere le token et l'ajoute dans la base de données
                    $token = Token::generateToken();
                    Token::addTokenToUser($id_user, $token);
                    // le redirige vers le formulaire pour changer son mot de passe (simplification du processus)
                    header('Location: ?action=resetpassword&token=' . $token);

                } else {
                    $html .= $this->renderHtml(true, false);
                }
            }
        }

        return $html;
    }


    public function renderHtml(bool $errorEmail, bool $errorPwd): string
    {
        $html = <<<HEREDOC
                <body>
                <form method="post" action="?action=forgetPassword">
                <video autoplay muted loop id="trailer">
                    <source src="../resources/videos/netvod_trailer.mp4" type="video/mp4">
                </video>
                <img id="logo" src="../resources/images/logo.png" alt="logo">
                
                <div class="connexion">
                    <div class="register">
                        <h1 id="title">Mot de passe oublié</h1>
                    
                        <div class="emailControl">
                            <input type="email" name="email" id="id_email" placeholder="E-mail" class="textfield">
                        </div>

                HEREDOC;

        if ($errorEmail) {
            $html .= <<<HEREDOC
                <div class="error">
                    <p>L'adresse email n'est pas valide</p>
                </div>
                HEREDOC;
        }

        if ($errorPwd) {
            $html .= <<<HEREDOC
                        <div class="errorMessage">
                            <label>Les mots de passe ne correspondent pas</label>
                        </div>
                    HEREDOC;
        }

        $html .= <<<HEREDOC
                                    <div class="buttonControl">
                                      <button type="submit" class="btnForget">Changer le mot de passe</button>
                                    </div>
                                </div>
                            </div>
                            
                            </form>
                            <p class="footer">@copyright KINZELIN Rémy,SIGNORELLI LUCAS, HIRTZ Jules, PERROT Alexandre and ERPELDING Lucas</p>
                     HEREDOC;

        return $html;
    }
}