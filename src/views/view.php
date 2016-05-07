<?php

class View {

    protected $router;
    protected $feedback;
    protected $styleSheetURL;
    protected $faviconURL;
    protected $content;
    protected $header;
    protected $footer;

    public function __construct(Router $router, $feedback) {
        $this->router = $router;
        $this->feedback = $feedback;
        $this->styleSheetURL = $router->getURL("minimalsite.css");
        $this->styleSheetURL2 = $router->getURL("multiple-select.css");
        $this->faviconURL = $router->getFaviconURL("logo.png");
        $this->content = null;
        $this->header = null;
        $this->footer = null;
    }

    public function render() {
        $this->makeHeaderFooter();
        if ($this->content === null || $this->header === null || $this->footer === null) {
            $this->unexpectedErrorPage(new Exception("Tried to render a view with a null content"));
        }
        $styleSheetURL = $this->styleSheetURL;
        $faviconURL = $this->faviconURL;
        $content = $this->content;
        $header = $this->header;
        $footer = $this->getMenu();
        $feedback = $this->feedback;
        $menu = $this->getMenu();
        include("template.php");
    }

    public function unknownPage() {
        $this->content = file_get_contents("fragments/unknownURL.html", true);
    }

    public function unexpectedErrorPage($e) {
        $this->content = $e;
        $this->content .= file_get_contents("fragments/unexpectedError.html", true);
    }

    public function noPermission() {
        $this->content = file_get_contents("fragments/nopermission.html", true);
    }

    public function notConnected() {
        $this->content = file_get_contents("fragments/notconnected.html", true);
    }

    protected function getMenu() {
        return array(
            $this->router->getAccueilURL() => "Accueil",
            $this->router->getMettreEnLigneURL() => "Mettre en ligne",
            $this->router->getGallerieURL() => "Gallerie",
            $this->router->getMapURL() => "Map",
            $this->router->getConnexionURL() => "Connexion"
        );
    }

    public function makePage($message) {
        $this->content = $message;
    }

    public function makeHeaderFooter() {
        $this->header = file_get_contents("fragments/header.frag.html", true);
        $this->footer = file_get_contents("fragments/footer.frag.html", true);
    }

}

?>
