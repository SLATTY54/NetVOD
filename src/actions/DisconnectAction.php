<?php

namespace netvod\actions;

/**
 * Action permettant de se déconnecter du site
 */
class DisconnectAction extends Action
{

    public function execute(): string
    {

        // on supprime la session et on le redirige vers la page d'accueil
        unset($_SESSION['user']);
        header('Location: index.php');
        return "";

    }

}