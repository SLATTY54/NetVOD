<?php

namespace netvod\actions;

use netvod\classes\Authentification;
use netvod\database\ConnectionFactory;

class EnCoursAction
{
    public function execute(): void
    {

        // si l'utilisateur n'a pas une session de connecté
        if(!Authentification::isAuthentified()){
            header('Location: ?action=login');
        }

        $episode = $_GET['episode_id'];
        // assainissement de l id de l episode
        $episode = filter_var($episode, FILTER_SANITIZE_NUMBER_INT);
        $idSerie = $this->getSerieId($episode);
        $idUser = $this->getUserId();
        $this->addToList($idSerie, $idUser);
    }

    public function getSerieId($episode): int
    {
        $pdo = ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM episode WHERE id = :episode_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['episode_id' => $episode]);
        // veri si l episode existe
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            return $data['serie_id'];
        }
        // si l'utilisateur a touche a l url et rentre un id d episode qui n existe pas
        echo 'episode id inexistant';
        return -9999;
    }

    public function getUserId(): string {
        $user = unserialize($_SESSION['user']);
        return $user->id;
    }

    public function addToList($idSerie, $idUser): void {
        $pdo = ConnectionFactory::makeConnection();
        $stmt = $pdo->prepare("SELECT * FROM EnCours WHERE idSerie=? AND idUser=?");
        $stmt->bindParam(1,$idSerie);
        $stmt->bindParam(2,$idUser);
        $stmt->execute();
        if($stmt->rowCount()===0){
            $sql = "INSERT INTO EnCours (idUser,idSerie) VALUES (?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1,$idUser);
            $stmt->bindParam(2,$idSerie);
            $stmt->execute();
        }
    }
}