<?php

class Controller_connected extends Controller {

    public function __construct(View $view, Router $router) {
        Controller::__construct($view, $router);
    }

    public function mettreenligne() {
        if (isset($_POST['submit'])) {
            if ((isset($_POST['MAX_FILE_SIZE']) && !empty($_POST['MAX_FILE_SIZE']))
            && (isset($_FILES['fichier'])  && !empty($_FILES['fichier']['name']))
            && (isset($_POST['titre'])  && !empty($_POST['titre']))
            && (isset($_POST['description'])  && !empty($_POST['description']))
            && (isset($_POST['lattitude'])  && !empty($_POST['lattitude']))
            && (isset($_POST['longitude'])  && !empty($_POST['longitude']))
            && (isset($_POST['tag'])  && !empty($_POST['tag']))){
                // Tableaux de donnees
                $tabExt = array('jpg', 'gif', 'png', 'jpeg'); // Extensions autorisees
                $infosImg = array();
                // Variables
                $extension = '';
                $retour = '';
                $nomImage = '';
                $fichier = $_FILES['fichier'];
                // Recuperation de l'extension du fichier
                $extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
                // On verifie l'extension du fichier
                if (in_array(strtolower($extension), $tabExt)) {
                    // On recupere les dimensions du fichier
                    $infosImg = getimagesize($fichier['tmp_name']);
                    // On verifie le type de l'image
                    if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {
                        // On verifie les dimensions et taille de l'image
                        if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($fichier['tmp_name']) <= MAX_SIZE)) {
                            // Parcours du tableau d'erreurs
                            if (isset($fichier['error']) && UPLOAD_ERR_OK === $fichier['error']) {
                                // On renomme le fichier
                                $nomImage = md5(uniqid()) . '.' . $extension;
                                // Si c'est OK, on teste l'upload
                                if (move_uploaded_file($fichier['tmp_name'], TARGET . $nomImage)) {
                                    $photo = new Photo("", $nomImage, addslashes($_POST['lattitude']), addslashes($_POST['longitude']), addslashes($_POST['titre']), addslashes($_POST['description']), Date("Y-m-d H:i:s"), ($_SESSION['session_email']), 0);
                                    $this->bdd_photo->create($photo);
                                    $idphoto = $this->bdd_photo->retrieveid($_SESSION['session_email']);
                                    foreach ($_POST['tag'] as $tag_post){
                                        $this->bdd_photo->create2tag($idphoto, $tag_post);
                                    }
                                    $retour = 'Upload réussi !';
                                } else {
                                    $retour = 'Problème lors de l\'upload !';
                                } // Sinon on affiche une erreur systeme
                            } else {
                                $retour = 'Une erreur interne a empêché l\'uplaod de l\'image';
                            }
                        } else {
                            // Sinon erreur sur les dimensions et taille de l'image
                            $retour = 'Erreur dans les dimensions de l\'image !';
                        }
                    } else {
                        // Sinon erreur sur le type de l'image
                        $retour = 'L\'image est trop lourde';
                    }
                } else {
                    // Sinon on affiche une erreur pour l'extension
                    $retour = 'L\'extension du fichier est incorrecte !';
                }
            } else {
                $retour = "il manque des champs";
            }
        } else {
            $retour = false;
        }

        $message = 'Mettre en ligne une photo

        <form enctype="multipart/form-data" action="' . $this->router->getMettreEnLigneURL() . '" method="post" class ="formulaire">

        <fieldset>

        <table>
            <tr>
                <td>Titre de la photo :</td>
                <td><input name="titre" type="text" placeholder="titre"/><br></td>
            </tr>
            <tr>
                <td>Description : </td>
                <td><input name="description" type="text" placeholder="description" /><br></td>
            </tr>
            <tr>
                <td>Lattitude : </td>
                <td><input name="lattitude" type="number" step="any" placeholder="latitude"/><br><t/d>
            </tr>
            <tr>
                <td>Longitude : </td>
                <td><input name="longitude" type="number" step="any" placeholder="longitude"/><br></td>
            </tr>
            <tr>
                <td id="tabletag">Tags : </td>
            <td>

        <link rel="stylesheet" href="' . $this->router->getURL("multiple-select.css"). '" />

        <div class="form-group">
        <select class="select" multiple="multiple" name="tag[]">';
        $tags = $this->bdd_tag->retrieveAll();
        foreach ($tags as $tag) {
            $message.= '<option value="' . $tag->getIdtag() . '">' . $tag->getDescription() . '</option>';
        }
        $message.= '</select>
        </div>

        <script src="' . $this->router->getURL("js/jquery.min.js"). '"></script>
        <script src="' . $this->router->getURL("js/multiple-select.js"). '"></script>
        <script src="' . $this->router->getURL("js/form.js"). '"></script>


        <br></td></tr>
        <tr>
            <td><label for="fichier_a_uploader" title="Recherchez le fichier à uploader !">Envoyer le fichier :</label></td>

            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />

            <td><input name="fichier" type="file" id="fichier_a_uploader" /><br></td>
        </tr></td>
        </table>
        <input type="submit" name="submit" value="Uploader" class="submit"/>


        </fieldset>
        </form>';
        if ($retour != false) {
            $message .= '<p class="retour">' . $retour . '</p>';
        }
        $this->view->makePage($message);
    }

    public function votepositif($idphoto) {
        $idphoto = addslashes($idphoto);
        if (!$this->bdd_vote->exists($_SESSION['session_email'], $idphoto)){
            $vote = new Vote("1", $_SESSION['session_email'], $idphoto, 1);
            $this->bdd_vote->create($vote);
            $this->view->makePage($message);
        } else {
            $this->bdd_vote->update($_SESSION['session_email'], $idphoto, 1);
        }
    }

    public function votenegatif($idphoto) {
        $idphoto = addslashes($idphoto);
        if (!$this->bdd_vote->exists($_SESSION['session_email'], $idphoto)){
            $vote = new Vote("1", $_SESSION['session_email'], $idphoto, 0);
            $this->bdd_vote->create($vote);
            $this->view->makePage($message);
        } else {
            $this->bdd_vote->update($_SESSION['session_email'], $idphoto, 0);
        }
    }

}

?>
