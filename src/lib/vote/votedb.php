<?php

require_once("lib/vote/ivoteDB.php");

class VoteDB implements IVoteDB {

    protected $pdo;
    protected $table;
    private $createVoteReq;
    private $retrieveVoteReq;
    private $existVoteReq;
    private $updateVoteReq;
    private $retrieveSingleVoteReq;
    private $deleteVoteReq;

    public function __construct(PDO $pdo, $table) {
        $this->pdo = $pdo;
        $this->table = $table;

        $values = ":email,:idphoto,:vote";
        $query = "INSERT INTO `" . $this->table . "`(email, idphoto, vote) VALUES(" . $values . ")";
        $this->createVoteReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "` WHERE idphoto=:idphoto";
        $this->retrieveVoteReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "` WHERE idphoto=:idphoto AND email=:email";
        $this->retrieveSingleVoteReq = $this->pdo->prepare($query);

        $clause = "email=:email AND idphoto=:idphoto";
        $query = "SELECT * FROM `" . $this->table . "` WHERE " . $clause;
        $this->existVoteReq = $this->pdo->prepare($query);

        $query = "UPDATE " . $this->table . " SET vote=:vote WHERE idphoto=:idphoto AND email=:email";
        $this->updateVoteReq = $this->pdo->prepare($query);

        $query = "DELETE FROM `" . $this->table . "` WHERE idphoto=:idphoto";
        $this->deleteVoteReq = $this->pdo->prepare($query);
    }

    // Create methods =======================================================================
    public function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->table . "` (";
        $query .= "`idvote` int(11) NOT NULL AUTO_INCREMENT, ";
        $query .= "`email` varchar(255) NOT NULL, ";
        $query .= "`idphoto` int(11) NOT NULL, ";
        $query .= "`vote` tinyint(1) NOT NULL, ";
        $query .= "PRIMARY KEY (`idvote`) ";
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->pdo->exec($query);
    }

    public function create(Vote $vote) {
        $this->createVoteReq->bindValue(":email", $vote->getEmail());
        $this->createVoteReq->bindValue(":idphoto", $vote->getIdPhoto());
        $this->createVoteReq->bindValue(":vote", $vote->getVote());
        $this->createVoteReq->execute();
    }

    public function exists($email, $idphoto) {
        $this->existVoteReq->bindValue(":email", $email);
        $this->existVoteReq->bindValue(":idphoto", $idphoto);
        $this->existVoteReq->execute();
        return $this->existVoteReq->fetch() !== false;
    }

    // Retrieve methods ====================================================================
    public function retrieve($idphoto) {
        $this->retrieveVoteReq->bindValue(":idphoto", $idphoto);
        $this->retrieveVoteReq->execute();
        $res = array();
        while ($row = $this->retrieveVoteReq->fetch(PDO::FETCH_ASSOC))
            $res[] = $row["vote"];
        return $res;
    }

    public function retrievesingle($idphoto, $email) {
        $this->retrieveSingleVoteReq->bindValue(":idphoto", $idphoto);
        $this->retrieveSingleVoteReq->bindValue(":email", $email);
        $this->retrieveSingleVoteReq->execute();
        $row = $this->retrieveSingleVoteReq->fetch(PDO::FETCH_ASSOC);
        return $row["vote"];
    }

    public function update($email, $idphoto, $vote) {
        $this->updateVoteReq->bindValue(":idphoto", $idphoto);
        $this->updateVoteReq->bindValue(":email", $email);
        $this->updateVoteReq->bindValue(":vote", $vote);
        $this->updateVoteReq->execute();
    }

    // Delete methods =======================================================================
    public function deleteTable() {
        $this->pdo->exec("DROP TABLE IF EXISTS `" . $this->table . "`");
    }

    public function deleteVote($idphoto) {
        $this->deleteVoteReq->bindValue(":idphoto", $idphoto);
        $this->deleteVoteReq->execute();
    }

}

?>
