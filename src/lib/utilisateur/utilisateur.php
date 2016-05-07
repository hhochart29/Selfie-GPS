<?php

class Utilisateur {

    protected $email;
    protected $niveau;

    public function __construct($email, $niveau) {
        $this->email = $email;
        $this->niveau = $niveau;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getNiveau() {
        return $this->niveau;
    }

//	public function setEmail($email) {
//		$this->firstName = $nom;
//	}
//
//	public function setNiveau($niveau) {
//		$this->prenom = $prenom;
//	}


    public function __toString() {
        return 'Email : ' . $this->email . ', niveau : ' . $this->niveau;
    }

}

?>