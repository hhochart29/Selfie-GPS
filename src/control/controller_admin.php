<?php

require_once("control/controller_connected.php");

class Controller_admin extends Controller_connected {

    public function __construct(View $view, Router $router) {
        Controller::__construct($view, $router);
    }

    public function administration(){
        $photos = $this->bdd_photo->retrieveAll();
        $message = 'Administration des photos
        <table class="tableadmin">
        <tr>
        <th>Titre</th>
        <th>Photo</th>
        <th>Ratio de vote</th>
        <th>Auteur</th>
        <th>Date</th>
        <th>Supprimer</th>
        <th>Bannir l\'auteur</th>
        </tr>';
        foreach ($photos as $photo) {
            $votes = $this->bdd_vote->retrieve($photo->getIdphoto());
            $votepositif = 0;
            $votenegatif = 0;
            foreach ($votes as $vote) {
                if ($vote == true){
                    $votepositif++;
                } else {
                    $votenegatif++;
                }
            }
            if (sizeof ($votes) != 0) {
                $pourcentagevote = $votepositif * 100 / sizeof ($votes);
            } else {
                $pourcentagevote = 100;
            }

            $message .= '
            <tr>
            <td class="tdadmintitre">' . $photo->getTitre() . '</td>
            <td class="tdadminphoto"><img src="' . $this->router->getPhotosURL() . $photo->getFichier() . '"/></td>
            <td class="tdadminvote">
            <div class="adminvote">
            <div class="adminprogress">
            <div class="adminprogresslike" style="width:' . $pourcentagevote . '%"></div></div>
            <div class="adminthumb">
            <div class="adminthumbsup"';
            $message.='"><img src="' . $this->router->getImageURL("thumb-up.png") . '">' .  $votepositif .'</div>
            <div class="adminthumbsdown"';
            $message.='"><img src="' . $this->router->getImageURL("thumb-down.png") . '">' .  $votenegatif .'</div>
            </div>
            </td>
            <td class="tdadminemail">' . $photo->getEmail() . '</td>
            <td class="tdadmindate">' . $photo->getDate() . '</td>
            <td class="tdadminsuppr"><a href="'.$this->router->getAdministrationSupprURL().'/'.$photo->getIdphoto().'" />Supprimer</a></td>';
            $message.= '<td class="tdadminban"><a href="'.$this->router->getAdministrationBannURL().'/'.$photo->getEmail().'" />Bannir</a></td>
            </tr>';
        }
        $message.= '</table>';
        $this->view->makePage($message);
    }

    public function supprimerPhoto($idphoto){
        $idphoto = addslashes($idphoto);
        if ($this->bdd_photo->existsId($idphoto)) {
            $nomfichier = $this->bdd_photo->retrievenomphoto($idphoto);
            if (file_exists ('./upload/'.$nomfichier)) {
                unlink('./upload/'.$nomfichier);
            }
            $this->bdd_vote->deleteVote($idphoto);
            $this->bdd_photo->deleteTag($idphoto);
            $this->bdd_photo->deletePhoto($idphoto);
        }
        $this->router->redirect($this->router->getAdministrationURL());
    }

    public function bannirUtilisateur($email){
        $email = addslashes($email);
        if ($this->bdd_utilisateur->exists($email)) {
            $utilisateur = $this->bdd_utilisateur->retrieve($email);
            if ($utilisateur->getNiveau() != 'admin') {
                $this->bdd_utilisateur->updateniveau('banni',$email);
                $photoemail = $this->bdd_photo->retrieveAllByEmail($email);
                foreach ($photoemail as $photo) {
                    if (file_exists ('./upload/'.$photo->getFichier())) {
                        unlink('./upload/'.$photo->getFichier());
                    }
                    $this->bdd_vote->deleteVote($photo->getIdphoto());
                    $this->bdd_photo->deleteTag($photo->getIdphoto());
                    $this->bdd_photo->deletePhoto($photo->getIdphoto());
                }
            }
        }
        $this->router->redirect($this->router->getAdministrationURL());
    }

}

?>
