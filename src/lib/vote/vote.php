<?php

class Vote {

    protected $idvote;
    protected $email;
    protected $idphoto;
    protected $vote;

    public function __construct($idvote, $email, $idphoto, $vote) {
        $this->idvote = $idvote;
        $this->email = $email;
        $this->idphoto = $idphoto;
        $this->vote = $vote;
    }

    public function getIdVote() {
        return $this->idvote;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getIdPhoto() {
        return $this->idphoto;
    }

    public function getVote() {
        return $this->vote;
    }

    public function __toString() {
        return 'Idvote : ' . $this->idvote . ', Email : ' . $this->email . ', Idphoto : ' . $this->idphoto . ', Vote : ' . $this->vote;
    }

}

?>
