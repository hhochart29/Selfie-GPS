<?php

require_once("config/config.php");
require_once('control/controller.php');
require_once("control/controller_admin.php");
require_once("control/controller_connexion.php");
require_once("control/controller_connected.php");
require_once("views/view.php");
require_once("views/view_admin.php");
require_once("views/view_connected.php");

class Router {

    protected $baseURL;
    protected $webBaseURL;
    private $view;

    public function __construct($baseURL, $webBaseURL) {
        $this->baseURL = $baseURL;
        $this->webBaseURL = $webBaseURL;
    }

    public function main() {
        session_start();
        $feedback = isset($_SESSION["feedback"]) ? $_SESSION["feedback"] : null;
        $_SESSION["feedback"] = null;
        $this->view = new View($this, $feedback);
        $controller_connexion = new Controller_connexion($this->view, $this);
        $controller = new Controller($this->view, $this);

        if($controller_connexion->isConnected()){
            if($controller_connexion->isAdmin()) {
                $this->view = new View_admin($this, $feedback);
                $controller = new Controller_admin($this->view, $this);
            } else {
                $this->view = new View_connected($this, $feedback);
                $controller = new Controller_connected($this->view, $this);
            }
        }


        try {
            $url = getenv('PATH_INFO');
            $urlParts = explode('/', $url);
            array_shift($urlParts);
            $page = array_shift($urlParts);
            switch ($page) {
                case 'gallerie':
                $controller->gallerie();
                break;
                case 'mettreenligne':
                if ($controller_connexion->isConnected()) {
                    $controller->mettreenligne();
                } else {
                    $this->view->notConnected();
                }
                break;
                case '':
                $controller->accueil();
                break;
                case 'connexion':
                if ($controller_connexion->isConnected()) {
                    $this->redirect($this->getAccueilURL());
                } else {
                    $controller_connexion->connexion();
                }
                break;
                case 'inscription':
                if ($controller_connexion->isConnected()) {
                    $this->redirect($this->getAccueilURL());
                } else {
                    $controller_connexion->inscription();
                }
                break;
                case 'deconnexion':
                $controller_connexion->deconnexion();
                break;
                case 'administration':
                if ($controller_connexion->isAdmin()) {
                    if(isset($urlParts[0])) {
                        if($urlParts[0] == "supprimer") {
                            $controller->supprimerPhoto($urlParts[1]);
                        } elseif($urlParts[0] == "bannir") {
                            $controller->bannirUtilisateur($urlParts[1]);
                        } else {
                            $this->view->unknownPage();
                        }
                    } else {
                        $controller->administration();
                    }
                } else {
                    $this->redirect($this->getAccueilURL());
                }
                break;
                case 'map':
                $controller->map();
                break;
                case 'votepositif':
                    if ($controller_connexion->isConnected()) {
                        if(isset($urlParts[0])) {
                            $controller->votepositif($urlParts[0]);
                        } else {
                            $this->redirect($this->getAccueilURL());
                        }
                    } else {
                        $this->redirect($this->getAccueilURL());
                    }
                    break;
                    case 'votenegatif':
                        if ($controller_connexion->isConnected()) {
                            if(isset($urlParts[0])) {
                                $controller->votenegatif($urlParts[0]);
                            } else {
                                $this->redirect($this->getAccueilURL());
                            }
                        } else {
                            $this->redirect($this->getAccueilURL());
                        }
                        break;
                        default:
                        $this->view->unknownPage();
                        break;
                    }
                } catch (Exception $e) {
                    $this->view->unexpectedErrorPage($e);
                }
                $this->view->render();
            }

            public function getAccueilURL() {
                return $this->baseURL;
            }

            public function getMettreEnLigneURL() {
                return $this->baseURL . "/mettreenligne";
            }

            public function getGallerieURL() {
                return $this->baseURL . "/gallerie";
            }

            public function getConnexionURL() {
                return $this->baseURL . "/connexion";
            }

            public function getInscriptionURL() {
                return $this->baseURL . "/inscription";
            }

            public function getDeconnexionURL() {
                return $this->baseURL . "/deconnexion";
            }

            public function getMapURL() {
                return $this->baseURL . "/map";
            }

            public function getAdministrationURL() {
                return $this->baseURL . "/administration";
            }

            public function getAdministrationSupprURL() {
                    return $this->getAdministrationURL()."/supprimer";
            }

            public function getAdministrationBannURL() {
                    return $this->getAdministrationURL()."/bannir";
            }

            public function getPhotosURL() {
                return $this->webBaseURL . "/upload/";
            }

            public function getURL($path) {
                return $this->webBaseURL . "/web/" . $path;
            }

            public function getFaviconURL($path) {
                return $this->webBaseURL . "/web/images/favicon/" . $path;
            }

            public function getImageURL($path) {
                return $this->webBaseURL . "/web/images/" . $path;
            }
            public function redirect($url, $feedback = null) {
                if ($feedback != null) {
                    $_SESSION["feedback"] = $feedback;
                }
                header("HTTP/1.1 303 See Other");
                header("Location: " . $url);
                exit();
            }

        }

        ?>
