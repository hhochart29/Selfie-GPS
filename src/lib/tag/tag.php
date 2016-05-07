<?php

class Tag {

    protected $idtag;
    protected $description;

    public function __construct($idtag, $description) {
        $this->idtag = $idtag;
        $this->description = $description;
    }

    public function getIdtag() {
        return $this->idtag;
    }

    public function getDescription() {
        return $this->description;
    }

//	public function setIdtag($idtag) {
//		$this->idtag = $idtag;
//	}
//
//	public function setDescription($description) {
//		$this->description = $description;
//	}


    public function __toString() {
        return 'IDtag : ' . $this->idtag . ', Description : ' . $this->description;
    }

}

?>