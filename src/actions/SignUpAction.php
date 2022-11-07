<?php

namespace netvod\actions;

use netvod\auth\Auth;

class SignUpAction extends Action
{

     private string $html = "";

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->html = <<<HEREDOC
                <form method="post" action="?action=signup">
                    <label>Email : <input type="email" name="email" placeholder="<user@mail.bing>"></label>
                    <label>Mot de passe : <input type="password" name="password" placeholder="<password>"></label>
                    <label>Confirmer le mot de passe : <input type="password" name="password2" placeholder="<password>"></label>
                    <input type="submit" value="S'inscrire">
                </form>
            HEREDOC;
        }
        else
        {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if ($password != $password2)
            {
                $this->html = <<<HEREDOC
                    <p>Les mots de passe ne correspondent pas</p>
                    <a href="?action=signup">Retour</a>
                HEREDOC;
            }
            else if (!Auth::isRegistered($email))
            {
                Auth::register($email, $password);
                $this->html = <<<HEREDOC
                    <p>Vous êtes inscrit</p>
                    <a href="?action=login">Se connecter</a>
                HEREDOC;
            }
            else
            {
                $this->html = <<<HEREDOC
                    <p>Cet email est déjà utilisé</p>
                    <a href="?action=signup">Retour</a>
                HEREDOC;
            }
        }

        return $this->html;
    }
}