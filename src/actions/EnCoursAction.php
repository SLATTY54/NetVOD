<?php

namespace netvod\actions;

use netvod\database\ConnectionFactory;

class EnCoursAction
{
    public function execute(): void
    {
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

    public function getUserId(): int {
        $id = $_SESSION['user'];
        return $id;
    }

    public function addToList($idSerie, $idUser): void {
        $pdo = ConnectionFactory::makeConnection();
        $sql = "INSERT INTO EnCours (serie_id, user_id) VALUES (:serie_id, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['serie_id' => $idSerie, 'user_id' => $idUser]);
    }
}