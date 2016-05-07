<?php

require_once("lib/tag/itagDB.php");

class TagDB implements ItagDB {

    protected $pdo;
    protected $table;
    private $retrieveTagReq;
    private $retrieveAlltagreq;

    public function __construct(PDO $pdo, $table) {
        $this->pdo = $pdo;
        $this->table = $table;

        $query = "SELECT * FROM `" . $this->table . "` WHERE idtag=:idtag";
        $this->retrieveTagReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "`";
        $this->retrieveAllTagReq = $this->pdo->prepare($query);

        $query = "SELECT description FROM `" . $this->table . "` WHERE idtag=:idtag";
        $this->retrieveDescTagReq = $this->pdo->prepare($query);
    }

    // Create methods =======================================================================
    public function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->table . "` (";
        $query .= "`idtag` int(11) NOT NULL AUTO_INCREMENT, ";
        $query .= "`description` text NOT NULL, ";
        $query .= "PRIMARY KEY (`idtag`) ";
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->pdo->exec($query);
    }

    // Retrieve methods ====================================================================
    public function retrieve($idtag) {
        $this->retrieveTagReq->bindValue(":idtag", $idtag);
        $this->retrieveTagReq->execute();
        if ($row = $this->retrieveTagReq->fetch(PDO::FETCH_ASSOC))
        return new Tag($row["idtag"], $row["description"]);
        throw new Exception("Aucun tag avec cet ID : $idtag");
    }

    public function retrieveAll() {
        $this->retrieveAllTagReq->execute();
        $res = array();
        while ($row = $this->retrieveAllTagReq->fetch(PDO::FETCH_ASSOC))
        $res[] = new Tag($row["idtag"], $row["description"]);
        return $res;
    }

    public function retrieveDescTag($idtag){
        $this->retrieveDescTagReq->bindValue(":idtag", $idtag);
        $this->retrieveDescTagReq->execute();
        if ($row = $this->retrieveDescTagReq->fetch(PDO::FETCH_ASSOC))
        return $row["description"];
        throw new Exception("Aucune description n'existe pour ce tag : $idtag");
    }

    // Delete methods =======================================================================
    public function deleteTable() {
        $this->pdo->exec("DROP TABLE IF EXISTS `" . $this->table . "`");
    }

}

?>
