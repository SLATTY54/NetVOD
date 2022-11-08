<?php

namespace netvod\actions;

use netvod\auth\Auth;

class SignUpAction extends Action
{

    public function execute(): string
    {
        $html = <<<HTML
        <html>
            <head>
            <title>NetVOD</title>
            <link href="./css/signup_style.css" rel="stylesheet">

            </head>
        HTML;

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $html .= <<<HEREDOC
            <body>
                <form method="post" action="?action=signup">
                    <label>Email : <input type="email" name="email" placeholder="<user@mail.bing>"></label>
                    <label>Mot de passe : <input type="password" name="password" placeholder="<password>"></label>
                    <label>Confirmer le mot de passe : <input type="password" name="password2" placeholder="<password>"></label>
                    <input type="submit" value="S'inscrire">
                </form>
            </body>
            </html>
            HEREDOC;
        }
        else
        {

            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && filter_var($_POST['password'], FILTER_SANITIZE_STRING) && filter_var($_POST['password2'], FILTER_SANITIZE_STRING)) {

                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                $password2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);

                if ($password != $password2) {
                    $html .= <<<HEREDOC
                    <p>Les mots de passe ne correspondent pas</p>
                    <a href="?action=signup">Retour</a>
                HEREDOC;
                } else if (!Auth::isRegistered($email)) {
                    Auth::register($email, $password);
                    $html .= <<<HEREDOC
                    <p>Vous êtes inscrit</p>
                    <a href="?action=login">Se connecter</a>
                HEREDOC;
                } else {
                    $html .= <<<HEREDOC
                    <p>Cet email est déjà utilisé</p>
                    <a href="?action=signup">Retour</a>
                HEREDOC;
                }
            }
        }

        return $html;
    }
}