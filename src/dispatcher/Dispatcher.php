<?php

namespace netvod\dispatcher;

use netvod\actions\DisplayCatalogueAction;
use netvod\actions\DisplayPreferencesAction;
use netvod\actions\DisplaySerieAction;
use netvod\actions\AddSerieFavourite;
use netvod\actions\WelcomeAction;
use netvod\actions\ActionLogin;
use netvod\actions\SignUpAction;

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
            case"pref":
                $act = new DisplayPreferencesAction();
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