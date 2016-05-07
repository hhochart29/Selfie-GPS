<?php

class View_connected extends View {

    public function __construct(Router $router, $feedback) {
        View::__construct($router, $feedback);
    }

    protected function getMenu() {
        return array(
            $this->router->getAccueilURL() => "Accueil",
            $this->router->getMettreEnLigneURL() => "Mettre en ligne",
            $this->router->getGallerieURL() => "Gallerie",
            $this->router->getMapURL() => "Map",
            $this->router->getDeconnexionURL() => "Deconnexion",
        );
    }

}

?>
