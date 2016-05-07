<?php

class View_admin extends View {

    public function __construct(Router $router, $feedback) {
        View::__construct($router, $feedback);
    }

    protected function getMenu() {
        return array(
            $this->router->getAccueilURL() => "Accueil",
            $this->router->getMettreEnLigneURL() => "Mettre en ligne",
            $this->router->getGallerieURL() => "Gallerie",
            $this->router->getMapURL() => "Map",
            $this->router->getAdministrationURL() => "Administration",
            $this->router->getDeconnexionURL() => "Deconnexion"
        );
    }

}

?>
