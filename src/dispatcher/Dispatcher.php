<?php

namespace netvod\dispatcher;

use netvod\actions\ActionNoteCommentaire;
use netvod\actions\ActionLogin;
use netvod\actions\AddSerieFavourite;
use netvod\actions\DisplayCatalogueAction;
use netvod\actions\DisplayCommentaireAction;
use netvod\actions\DisplayEnCoursAction;
use netvod\actions\DisplayPreferencesAction;
use netvod\actions\DisplaySerieAction;
use netvod\actions\EnCoursAction;
use netvod\actions\LectureEpisodeAction;
use netvod\actions\SignUpAction;
use netvod\actions\WelcomeAction;

class Dispatcher
{
    private ?string $action;

    // constructeur qui stocke la valeur du paramètre action du query-string dans un attribut $action
    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    public function run(): void
    {

        $retour=<<<end
            <div class="footer">
                <a href="?action=pref">retour à l'accueil</a>
            </div>
        end;
        switch ($this->action) {
            case 'notation':
                $action = new ActionNoteCommentaire();
                $html = $action->execute();
                break;
            case'catalogue':
                $act = new DisplayCatalogueAction();
                $html = $act->execute().$retour;
                break;
            case'serie':
                $act = new DisplaySerieAction();
                $html = $act->execute().$retour;
                break;
            case "favourite":
                $act = new AddSerieFavourite();
                $html = $act->execute();
                break;
            case 'login':
                $act = new ActionLogin();
                $html = $act->execute();
                break;

            case 'signup':
                $act = new SignUpAction();
                $html = $act->execute();
                break;

        // si l'utilisateur clique sur un episode d'une serie
            case 'episode':
                $act = new LectureEpisodeAction();
                $html = $act->execute();
                $act = new EnCoursAction();
                $act->execute();
                break;

            case"accueil":
                $act = new DisplayPreferencesAction();
                $html= $act->execute();
                $act = new DisplayEnCoursAction();
                $html.= $act->execute();
                break;
            case"commentaire":
                $act = new DisplayCommentaireAction();
                $html= $act->execute();
                break;

            default:
                $act = new WelcomeAction();
                $html = $act->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        echo $html;
    }
}