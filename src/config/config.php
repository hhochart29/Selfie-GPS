<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "./src");
define("HOST", "127.0.0.1");
define("PORT", "");
define("USER", "root");
define("PWD", "");
define("DB", "selfie_gps_bdd");
define("bdd_photo", "photo");
define("bdd_utilisateur", "user");
define("bdd_caracterisation", "caracterisation");
define("bdd_tag", "tag");
define("bdd_vote", "vote");
define('TARGET', realpath('upload') . '\\'); // Repertoire cible
define('MAX_SIZE', 2097152); // Taille max en octets du fichier
define('WIDTH_MAX', 1920); // Largeur max de l'image en pixels
define('HEIGHT_MAX', 1080); // Hauteur max de l'image en pixels
?>
