<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\Exceptions\AuthException;

class ActionLogin extends Action
{
    public function execute(): string
    {
        $html = <<<HEREDOC
                    <html lang="en">
                    <head><title>NetVod</title>
                          <link href="./css/login-style.css" rel="stylesheet">
                    </head> 
                    HEREDOC;

        if ($_SERVER['REQUEST_METHOD']=='GET'){

            $html .= <<<HEREDOC
            
                <body>
                <form method="post" action="?action=login">
                    <div class="login">
                        <h1 id="title">Se connecter</h1>
                       
                        <div class="emailControl">
                                <input type="email" name="email" id="id_email" placeholder="E-mail" class="textfield">      
                        </div>
                        
                        <div class="passwordControl">
                                <input type="password" name="password" id="id_password" placeholder="Mot de passe" class="textfield">
                        </div>
                        <div class="buttonControl">
                          <button type="submit" class="btnConnect">Connection</button>
                        </div>
                    </div>
                </form>
                    
                    
                
            HEREDOC;
        }else{
            if (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) && filter_var($_POST['password'],FILTER_SANITIZE_STRING)){
                $email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
                $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
                try {
                    Authentification::authenticate($email,$password);
                    echo "Vous êtes connecté";
                }catch (AuthException $e){
                    $html.= <<<HEREDOC
                        <label style="color: white">Erreur de connexion l'adresse email ou le mot de passe est incorrect </label>
                    HEREDOC;

                }

            }else{
                $html.= <<<HEREDOC
                        <label style="color: white">Erreur de connexion l'adresse email ou le mot de passe est incorrect </label>
                    HEREDOC;
            }

        }
        $html .= <<<HEREDOC
                </body>
                </html>
            HEREDOC;
        return $html;
    }
}