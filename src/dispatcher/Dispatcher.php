<?php

namespace netvod\dispatcher;

use netvod\actions\AddSerieFavourite;
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
        switch ($this->action) {
            case "add-serie-favourite":
                $act = new AddSerieFavourite();
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