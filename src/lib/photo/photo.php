<?php

class Photo {

    protected $idphoto;
    protected $fichier;
    protected $geo_lat;
    protected $geo_long;
    protected $titre;
    protected $description;
    protected $date;
    protected $email;
    protected $tag;

    public function __construct($idphoto, $fichier, $geo_lat, $geo_long, $titre, $description, $date, $email, $tag) {
        $this->idphoto = $idphoto;
        $this->fichier = $fichier;
        $this->geo_lat = $geo_lat;
        $this->geo_long = $geo_long;
        $this->titre = $titre;
        $this->description = $description;
        $this->date = $date;
        $this->email = $email;
        $this->tag = $tag;
    }

    public function getIdphoto() {
        return $this->idphoto;
    }

    public function getFichier() {
        return $this->fichier;
    }

    public function getGeo_lat() {
        return $this->geo_lat;
    }

    public function getGeo_long() {
        return $this->geo_long;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDate() {
        return $this->date;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTag() {
        return $this->tag;
    }

    public function __toString() {
        return 'idphoto : ' . $this->idphoto . ', fichier : ' . $this->fichier . ', geo lat : ' . $this->geo_lat . 'get long : ' . $this->geo_long . ', titre : ' . $this->titre . ', description : ' . $this->description . 'date : ' . $this->date . ', email : ' . $this->email;
    }

}

?>
