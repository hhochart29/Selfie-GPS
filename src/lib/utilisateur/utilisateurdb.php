<?php

require_once("lib/utilisateur/iutilisateurdb.php");

class UtilisateurDB implements IUtilisateurDB {

    protected $pdo;
    protected $table;
    private $createUtilisateurReq;
    private $retrieveUtilisateurReq;
    private $updateNiveauReq;
    private $checkMotdepasseReq;
    private $deleteUtilisateurReq;

    public function __construct(PDO $pdo, $table) {
        $this->pdo = $pdo;
        $this->table = $table;

        $values = ":email,:niveau,SHA1(:motdepasse)";
        $query = "INSERT INTO `" . $this->table . "` VALUES(" . $values . ")";
        $this->createUtilisateurReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "` WHERE email=:email";
        $this->retrieveUtilisateurReq = $this->pdo->prepare($query);

        $clause = "email=:email AND password=SHA1(:motdepasse)";
        $query = "SELECT * FROM `" . $this->table . "` WHERE " . $clause;
        $this->checkMotdepasseReq = $this->pdo->prepare($query);

        $query = "UPDATE ".$this->table." SET niveau=:niveau WHERE email=:email";
        $this->updateNiveauReq = $this->pdo->prepare($query);

        $query = "DELETE FROM `" . $this->table . "` WHERE email=:email";
        $this->deleteUtilisateurReq = $this->pdo->prepare($query);
    }

    // Create methods =======================================================================
    public function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->table . "` (";
        $query .= "`email` varchar(255) NOT NULL, ";
        $query .= "`niveau` text NOT NULL, ";
        $query .= "`password` text NOT NULL, ";
        $query .= "PRIMARY KEY (`email`) ";
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->pdo->exec($query);
    }

    public function create(Utilisateur $utilisateur, $motdepasse) {
        $this->createUtilisateurReq->bindValue(":email", $utilisateur->getEmail());
        $this->createUtilisateurReq->bindValue(":niveau", $utilisateur->getNiveau());
        $this->createUtilisateurReq->bindValue(":motdepasse", $motdepasse);
        $this->createUtilisateurReq->execute();
    }

    // Retrieve methods ====================================================================
    public function retrieve($email) {
        $this->retrieveUtilisateurReq->bindValue(":email", $email);
        $this->retrieveUtilisateurReq->execute();
        if ($row = $this->retrieveUtilisateurReq->fetch(PDO::FETCH_ASSOC))
            return new Utilisateur($row["email"], $row["niveau"]);
        throw new Exception("Aucun utilisateur avec cet email : $email");
    }

    public function isValid($email, $motdepasse) {
        $this->checkMotdepasseReq->bindValue(":email", $email);
        $this->checkMotdepasseReq->bindValue(":motdepasse", $motdepasse);
        $this->checkMotdepasseReq->execute();
        return $this->checkMotdepasseReq->fetch(PDO::FETCH_ASSOC);
    }

    public function exists($email) {
        $this->retrieveUtilisateurReq->bindValue(":email", $email);
        $this->retrieveUtilisateurReq->execute();
        return $this->retrieveUtilisateurReq->fetch() !== false;
    }

    public function updateniveau($niveau, $email) {
        $this->updateNiveauReq->bindValue(":niveau", $niveau);
        $this->updateNiveauReq->bindValue(":email", $email);
        $this->updateNiveauReq->execute();
    }

    // Delete methods =======================================================================
    public function deleteTable() {
        $this->pdo->exec("DROP TABLE IF EXISTS `" . $this->table . "`");
    }

    public function delete($email) {
        $this->deleteUtilisateurReq->bindValue(":email", $email);
        $this->deleteUtilisateurReq->execute();
    }

}

?>
