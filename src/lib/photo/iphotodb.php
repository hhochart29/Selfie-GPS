<?php

require_once("lib/photo/photo.php");

interface IphotoDB {

    public function create(Photo $photo);

    public function create2tag($idphoto, $idtag);

    public function retrieve($idphoto);

    public function retrieveid($email);

    public function retrieveAll();

    public function retrievePhotobytag($idtag);

    public function retrieveCaractTag($idphoto);

    public function retrievenomphoto($idphoto);

    public function retrieveAllByEmail($email);

    public function deletePhoto($idphoto);

    public function deleteTag($idphoto);

    public function existsId($idphoto);
}

?>
