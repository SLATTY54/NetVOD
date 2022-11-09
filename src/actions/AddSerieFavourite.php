<?php

namespace netvod\actions;

use netvod\classes\Favourite;

/**
 * Action permettant d'ajouter sa série préférée et ne gère que la méthode POST
 */
class AddSerieFavourite extends Action
{

    public function execute(): string
    {

        // méthode POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // on récupère l'user via la session
            $user = unserialize($_SESSION['user']);
            $id_user = $user->__get("id");

            // on récupère l'ID de la série via le POST
            $id_serie = $_POST['serie_id'];

            // si la série n'est pas déjà dans les favoris de l'utilisateur, ajouter la série aux favoris
            if (!Favourite::isAlreadyFavourite($id_user, $id_serie)) {
                Favourite::addToFavourite($id_user, $id_serie);
            }

        }

        // Peu importe le résultat, rafraîchir la page et on ne renvoit pas de réponse HTML
        header('Location: ?action=catalogue');
        return "";

    }

}