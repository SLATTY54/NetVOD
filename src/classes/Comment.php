<?php

namespace netvod\classes;


use netvod\database\ConnectionFactory;
use netvod\Exceptions\CommentException;
use PDO;

class Comment
{

    public static function addComment(int $id_user, int $id_serie, string $commentaire, int $note): void
    {
        if (self::alreadyComment($id_user, $id_serie)) {
            throw new CommentException('Vous avez déjà commenté cette série');
        } else {
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare('INSERT INTO notation VALUES (?, ?, ?, ?)');
            $stmt->execute([$id_user, $id_serie, $commentaire, $note]);
        }
    }


    public static function alreadyComment(int $id_user, int $id_serie): bool
    {
        $db = ConnectionFactory::makeConnection();

        $stmt = $db->prepare('SELECT * FROM notation WHERE id_user = ? and id_serie = ?');
        $stmt->execute([$id_user, $id_serie]);
        $comment = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($comment) {
            return true;
        }
        return false;

    }

    public static function getCommentaires(int $serie_id): array
    {
        $db = ConnectionFactory::makeConnection();

        $ps = $db->prepare("SELECT email, note, commentaire FROM notation INNER JOIN User ON notation.id_user = User.id WHERE id_serie = ?");
        $ps->bindParam(1, $serie_id);
        $ps->execute();
        $commentaires = $ps->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        return $commentaires;

    }


    public static function getMoyenneGeneraleFromSerieId(int $id_serie): float
    {
        $db = ConnectionFactory::makeConnection();

        $query = $db->prepare("SELECT AVG(note) as noteMoy FROM notation WHERE id_serie = ? GROUP BY id_serie");
        $query->bindParam(1, $id_serie);
        $query->execute();
        $stmt = $query->fetch(PDO::FETCH_ASSOC);

        $db = null;

        return round($stmt['noteMoy'], 2);

    }

}
