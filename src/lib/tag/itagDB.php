<?php

require_once("lib/tag/tag.php");

interface ITagDB {

    public function retrieve($idtag);

    public function retrieveAll();

    public function retrieveDescTag($idtag);
}

?>
