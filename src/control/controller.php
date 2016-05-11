<?php

require_once('lib/photo/photodb.php');
require_once('lib/utilisateur/utilisateurdb.php');
require_once('lib/tag/tagdb.php');
require_once('lib/vote/votedb.php');

class Controller {

    protected $view;
    protected $router;
    protected $bdd;
    protected $bdd_photo;
    protected $bdd_utilisateur;
    protected $bdd_tag;
    protected $bdd_vote;

    public function __construct(View $view, Router $router) {
        $this->view = $view;
        $this->router = $router;
        $this->bdd = new PDO("mysql:host=" . HOST . ";port=" . PORT . ";dbname=" . DB, USER, PWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
        $this->bdd_photo = new PhotoDB($this->bdd, bdd_photo, bdd_caracterisation);
        $this->bdd_utilisateur = new UtilisateurDB($this->bdd, bdd_utilisateur);
        $this->bdd_tag = new TagDB($this->bdd, bdd_tag);
        $this->bdd_vote = new VoteDB($this->bdd, bdd_vote);
    }

    public function accueil() {
        $message = "<p>Bienvenue sur le site 'Selfie-GPS'</p><br>Consultez les photos, ou connectez-vous pour en mettre en ligne.";
        $this->view->makePage($message);
    }

    public function gallerie() {
        if (isset($_POST['submit'])) {
            if (isset($_POST['tag']) && !empty($_POST['tag'])) {
                $photos = $this->bdd_photo->retrievePhotobytag($_POST['tag']);
                $default = false;
            } else {
                $default = true;
            }
        } else {
            $default = true;
        }
        if ($default) {
            $photos = $this->bdd_photo->retrieveAll();
        }
        $message ='Gallerie de photos';
        $message.='
        <script src="' . $this->router->getURL("js/vote.js"). '"></script>
        <form method="POST">
        <fieldset>
        <table id="tablerechercher">
        <tr>
        <td>Recherche par tag : </td>
        <td><div class="form-group"><select class="select" name="tag">';
        $tags = $this->bdd_tag->retrieveAll();
        $message.= '<option></option>';
        foreach ($tags as $tag) {
            $message.= '<option value="'.$tag->getIdtag().'" '.((isset($_POST["tag"]))?(($tag->getIdtag() == $_POST["tag"])?'selected' :null):null).'>'. $tag->getDescription() . '</option>';
        }
        $message.='</select></td>
        </tr>
        </table>
        <input type="submit" name="submit" value="Rechercher" class="submit"/>
        </fieldset>
        </form>
        <a href="'. $this->router->GetGallerieURL() .'" class="submit">Réinitialiser</a>
        ';
        if (sizeof($photos)!= 0){
            if (isset($_POST['tag']) && !empty($_POST['tag'])) {
                $idtagpost = $_POST['tag'];
                $desctag = $this->bdd_tag->retrieveDescTag($idtagpost);
                $message.='<p class="retour">Vous avez recherchés les photos avec le tag : '. $desctag . ' </p>';
            }
            foreach ($photos as $photo) {
                $votes = $this->bdd_vote->retrieve($photo->getIdphoto());
                if (isset($_SESSION['session_email'])){
                    $votesingle = $this->bdd_vote->retrievesingle($photo->getIdphoto(),$_SESSION['session_email']);
                    $existant = $this->bdd_vote->exists($_SESSION['session_email'], $photo->getIdphoto());
                } else {
                    $votesingle = 0;
                    $existant = false;
                }
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
                <div id="gallerie">
                <div class="galleriephoto">
                <div class="galtitre">' . $photo->getTitre() . '</div>
                <div class="galdesc">' . $photo->getDescription() . '</div>
                <div class="galphoto">
                    <a href="#'.$photo->getIdphoto().'" class="photosmall"><img src="' . $this->router->getPhotosURL() . $photo->getFichier() . '"/></a>
                    <a href="#_" class="lightbox" id="'.$photo->getIdphoto().'"><img src="' . $this->router->getPhotosURL() . $photo->getFichier() . '"/></a></div>
                <div class="vote">
                <div class="votebar">
                <div class="progress">
                <div class="progresslike" id="b_'.$photo->getIdphoto().'" style="width:' . $pourcentagevote . '%"></div>
                </div>
                </div>
                <div class="thumb">
                <div class="thumbsup"';
                if (isset($_SESSION['session_email'])) {
                    $message .= 'onclick="fonctionvote('.$photo->getIdphoto().', 1)"';
                }
                $message.='"><img src="' . $this->router->getImageURL("thumb-up.png") . '"><span id="p_'.$photo->getIdphoto().'" '.(($votesingle==1)?'style="color:green;"':null).'>' .  $votepositif .'</span></div>
                <div class="thumbsdown"';
                if (isset($_SESSION['session_email'])) {
                    $message .= 'onclick="fonctionvote('.$photo->getIdphoto().', 0)"';
                }
                $message.='"><img src="' . $this->router->getImageURL("thumb-down.png") . '"><span id="n_'.$photo->getIdphoto().'" '.(($votesingle==0 && $existant)?'style="color:red;"':null).'>' .  $votenegatif .'</span></div>
                </div>
                </div>
                <div class="galtagcontainer">';

                foreach ($photo->getTag() as $tag){
                    $tags = $this->bdd_tag->retrieve($tag);
                    $message.='<div class="galtag">'.$tags->getDescription().'</div>';
                }
                $message.= '</div><div id="galinfos">Prise le : <b>' . $photo->getDate() . '</b> par : <b>' . $photo->getEmail() . '</b></div></div></div></div>';
            }
        } else {
            $message.= '<p class="retour">Aucune image n\'appartient à ce tag.</p>';
        }
        $this->view->makePage($message);
    }

    public function map() {
        $photos = $this->bdd_photo->retrieveAll();
        $nbphotos = sizeof($photos);
        $virgule = 1;
        $message = '
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
        var nombrephotos = '.$nbphotos.';
        var photos = new Array(';
        foreach($photos as $photo) {
            $message .= '["'.$photo->getFichier().'", "'.$photo->getTitre().'", "'.$photo->getDescription().'", "'.$photo->getGeo_lat().'" ,"'.$photo->getGeo_long().'", "'.$photo->getEmail().'"]';
            if($virgule != $nbphotos) {
                $message .= ',';
            }
            $virgule++;
        }
        $message .= ');
        </script>
        <script src="'.$this->router->getURL("js/map.js").'"></script>
        <script type="text/javascript">google.maps.event.addDomListener(window, \'load\', initialisation);</script>
        <div id="carte"></div>';
        $this->view->makePage($message);

    }
}

?>
