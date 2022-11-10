<?php

namespace netvod\actions;

use netvod\classes\Authentification;

/**
 * Action permettant de changer le mot de passe à partir d'un token
 */
class ActivateAction extends Action
{

    public function execute(): string
    {

        // si l'utilisateur a déjà une session
        if (Authentification::isAuthentified()) {
            header('Location: ?action=catalogue');
        }

        $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);

        Authentification::enableAccount($token);
        header('Location: ?action=login');

        return "";
    }

}