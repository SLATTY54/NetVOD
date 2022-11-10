<?php

namespace netvod\dispatcher;

use netvod\actions\ActionNoteCommentaire;
use netvod\actions\DisplayProfileAction;
use netvod\actions\LoginAction;
use netvod\actions\DisplayCatalogueAction;
use netvod\actions\DisplayCommentaireAction;
use netvod\actions\DisplayEnCoursAction;
use netvod\actions\DisplayPreferencesAction;
use netvod\actions\DisplaySerieAction;
use netvod\actions\EnCoursAction;
use netvod\actions\FavouriteAction;
use netvod\actions\ForgetPasswordAction;
use netvod\actions\LectureEpisodeAction;
use netvod\actions\ResetPasswordAction;
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

        $retour = <<<end
            <div class="footer">
                <a href="?action=accueil">retour à l'accueil</a>
            </div>
        end;
        switch ($this->action) {
            case 'forgetPassword':
                $act = new ForgetPasswordAction();
                $html = $act->execute();
                break;

            case 'resetpassword':
                $act = new ResetPasswordAction();
                $html = $act->execute();
                break;

            case'catalogue':
                $act = new DisplayCatalogueAction();
                $html = $act->execute() . $retour;
                break;
            case'serie':
                $act = new DisplaySerieAction();
                $html = $act->execute() . $retour;
                break;
            // Ajouter ou retirer une série parmi ses favoris (POST only)
            case "favourite":
                $act = new FavouriteAction();
                $html = $act->execute();
                break;
            // Se connecter au site
            case 'login':
                $act = new LoginAction();
                $html = $act->execute();
                break;
            // S'enregister sur le site
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
            case"commentaire":
                $act = new DisplayCommentaireAction();
                $html = $act->execute();
                break;
            case"profile":
                $act = new DisplayProfileAction();
                $html = $act->execute();
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