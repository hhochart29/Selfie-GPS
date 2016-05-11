<?php

class Controller_connexion extends Controller {

    public function __construct(View $view, Router $router) {
        Controller::__construct($view, $router);
    }

    public function connexion() {
        if (isset($_POST['connexion'])) {
            if ((isset($_POST['email']) && !empty($_POST['email'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
                $email = addslashes($_POST['email']);
                $password = addslashes($_POST['password']);

                if ($this->bdd_utilisateur->isValid($email, $password)) {
                    $utilisateur = $this->bdd_utilisateur->retrieve($email);
                    if ($utilisateur->getNiveau($email) != 'banni') {
                        $_SESSION['session_email'] = $email;
                        $this->router->redirect($this->router->getAccueilURL());
                    } else {
                        $retour = "Vous êtes banni";
                    }
                } else {
                    $retour = "La combinaison que vous avez entré n'est pas valide";
                }
            } else {
                $retour = "Vous n'avez pas saisi tous les champs";
            }
        } else {
            $retour = false;
        }
        $message = '<form method="POST" action="#" class="formulaire">
        <div class="champ"><input type="text" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="mot de passe" /></div>
        <input type="submit" name="connexion" Value="Connexion" class="submit"/>';
        $message.='<a class="submit" href=' . $this->router->getInscriptionURL() . '>Inscription</a></form>';
        $this->view->makePage($message);
        if ($retour != false) {
            $message.= '<p class="retour">' . $retour . '<p>';
        }
        $this->view->makePage($message);
    }

    public function Inscription() {

        if (isset($_POST['inscription'])) {
            if ((isset($_POST['email']) && !empty($_POST['email'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
                $email = addslashes($_POST['email']);
                $rang = 'authentifie';
                $password = addslashes($_POST['password']);
                $password2 = addslashes($_POST['password2']);
                if (!$this->bdd_utilisateur->exists($email)) {
                    if ($password != $password2) {
                        $retour = "Les mots de passes ne correspondent pas";
                    } else {
                        $personn = new Utilisateur($email, $rang);
                        $this->bdd_utilisateur->create($personn, $password);
                        header('Location: ' . $this->router->getAccueilURL() . '');
                    }
                } else {
                    $retour = "Cet email est déjà utilisé. Choississez-en un autre";
                }
            } else {
                $retour = "Vous n'avez pas saisi tous les champs.";
            }
        } else {
            $retour = false;
        }

        if (isset($_POST['email'])) {
            $email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
        } else {
            $email = "";
        }
        $message = '<form method="POST" action="#" class="formulaire">
        <div class="champ"><input type="text" name="email" placeholder="Email" value="' . $email . '" />
        <input type="password" name="password" placeholder="mot de passe" />
        <input type="password" name="password2" placeholder="confirmer mot de passe" /></div>
        <input type="submit" name="inscription" Value="S\'inscrire" class="submit" />
        </form>';
        $message.= '<p class="retour">' . $retour . '<p>';
        $this->view->makePage($message);
    }

    public function isConnected() {
        if (isset($_SESSION['session_email'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin() {
        if (isset($_SESSION['session_email'])) {
            $admin = $this->bdd_utilisateur->retrieve($_SESSION['session_email']);
            $niveau = $admin->getNiveau();
            if ($niveau === 'admin') {
                return true;
            } else {
                return false;
            }
        }
    }

    public function deconnexion() {
        if (isset($_SESSION['session_email'])) {
            unset($_SESSION['session_email']);
        }
        $this->router->redirect($this->router->getAccueilURL());
    }

}

?>
