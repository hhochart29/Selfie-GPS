<?php

require_once("lib/vote/vote.php");

interface IVoteDB {

    public function create(Vote $idvote);

    public function retrieve($idphoto);

    public function retrievesingle($idphoto, $email);

    public function exists($email, $vote);

    public function update($email, $idphoto, $vote);

    public function deleteVote($idphoto);
}

?>
