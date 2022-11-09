<?php

namespace netvod\actions;

use netvod\classes\Favourite;

/**
 * Action permettant d'ajouter ou de retirer sa série préférée et ne gère que la méthode POST
 */
class FavouriteAction extends Action
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

            // si la série est déjà dans les favoris de l'utilisateur, retirer la série aux favoris sinon l'ajouter
            if (Favourite::isAlreadyFavourite($id_user, $id_serie)) {
                Favourite::removeFromFavourite($id_user, $id_serie);
            } else {
                Favourite::addToFavourite($id_user, $id_serie);
            }

        }

        // Peu importe le résultat, rafraîchir la page et on ne renvoit pas de réponse HTML
        // Si un callback est passé en paramètre, renvoyer vers ce lien sinon on renvoie au catalogue
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
            header("Location: ?$callback");
        } else {
            header('Location: ?action=catalogue');
        }

        return "";

    }

}