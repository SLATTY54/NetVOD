<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;

class RetirerFavorisAction extends Action{

    public function execute(): string{
        if(!isset($_SESSION['user'])){
            $html="<h1><a href='?action=login'>Veuillez vous connecter</a></h1>";
        }else{
            $pdo=ConnectionFactory::makeConnection();
            $user = unserialize($_SESSION['user']);
            $stmt = $pdo->prepare("DELETE FROM preferences WHERE id_user=? AND id_serie=?");
            $id=$user->id;
            $stmt->bindParam(1,$id);
            $stmt->bindParam(2,$_GET['serie_id']);
            $stmt->execute();
            $html="<h1>Serie retirée des favoris avec succès</h1>";
            $html.="<a href='?action=accueil'>Retour à l'accueil</a>";
        }
        return $html;
    }
}