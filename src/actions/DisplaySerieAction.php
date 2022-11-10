<?php

namespace netvod\actions;

use netvod\classes\Comment;
use netvod\classes\Serie;

class DisplaySerieAction extends Action
{

    public function execute(): string
    {

        $id = $_GET['serie_id'];

        $data = Serie::getSerieFromId($id);
        $nbEp = Serie::getNombreEpisodesFromSerieId($id);
        $noteMoy = Comment::getMoyenneGeneraleFromSerieId($id);

        $html = <<< HEREDOC
                <h1>$data->titre</h1>               
                <h3>$data->descriptif</h3>
                <h4>paru en $data->annee</h4>
                <h4>ajouté le $data->date_ajout</h4>
                <h4>note moyenne $noteMoy/5</h4>
                <a href="?action=commentaire&serie=$id">voir les commentaires</a>
                <h5>$nbEp Episode(s)</h5>
            HEREDOC;

        $html .= "<ul>";
        foreach (Serie::getEpisodesFromSerieId($id) as $episode) {
            $html .= <<<end
                    <li>
                        <img src="../resources/images/$data->img" width="200">
                        <a href="?action=episode&episode_id=$episode->id">$episode->numero : $episode->titre ($episode->duree min)</a>
                    </li>
                end;
        }
        $html .= "</ul>";

        // ajout du bouton retour

        return $html;
    }
}