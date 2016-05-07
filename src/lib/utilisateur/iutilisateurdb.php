<?php

require_once("lib/utilisateur/utilisateur.php");

interface IUtilisateurDB {

    public function create(Utilisateur $utilisateur, $motdepasse);

    public function retrieve($email);

    public function isValid($email, $motdepasse);

    public function exists($email);

    public function delete($email);

    public function updateniveau($niveau, $email);
}

?>
