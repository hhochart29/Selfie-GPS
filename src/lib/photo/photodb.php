<?php

require_once("lib/photo/iphotodb.php");

class PhotoDB implements IphotoDB {

    protected $pdo;
    protected $table;
    protected $tablecaract;
    private $createPhotoReq;
    private $retrievePhotoReq;
    private $retrieveIdPhotoReq;
    private $retrieveAllPhotoReq;
    private $retrieveAllPhotobyEmailReq;
    private $deletePhotoReq;
    private $deleteTagReq;
    private $retrievePhotoByTagReq;
    private $createCaractReq;
    private $retrieveCaractTagReq;
    private $retrieveNomPhotoReq;

    public function __construct(PDO $pdo, $table, $tablecaract) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->tablecaract = $tablecaract;
        //PHOTO
        $values = ":fichier,:geo_lat,:geo_long,:titre,:description,:date,:email";
        $query = "INSERT INTO `" . $this->table . "`(fichier, geo_lat, geo_long, titre, description, date, email) VALUES(" . $values . ")";
        $this->createPhotoReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "` WHERE idphoto=:idphoto";
        $this->retrievePhotoReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "` ORDER BY date DESC";
        $this->retrieveAllPhotoReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->table . "` WHERE email=:email";
        $this->retrieveAllPhotoByEmailReq = $this->pdo->prepare($query);

        $query = "DELETE FROM `" . $this->table . "` WHERE idphoto=:idphoto";
        $this->deletePhotoReq = $this->pdo->prepare($query);

        $query = "DELETE FROM `" . $this->tablecaract . "` WHERE idphoto=:idphoto";
        $this->deleteTagReq = $this->pdo->prepare($query);

        $query = "SELECT idphoto FROM `" . $this->table . "` WHERE email=:email ORDER BY idphoto DESC LIMIT 1";
        $this->retrieveIdPhotoReq = $this->pdo->prepare($query);

        $query = "SELECT fichier FROM `" . $this->table . "` WHERE idphoto=:idphoto";
        $this->retrieveNomPhotoReq = $this->pdo->prepare($query);

        $query = "SELECT `" . $this->table . "`.* FROM `" . $this->table . "` , `" . $this->tablecaract . "` WHERE `" . $this->tablecaract . "`.idphoto=`" . $this->table . "`.idphoto AND idtag=:idtag ORDER BY date DESC";
        $this->retrievePhotoByTagReq = $this->pdo->prepare($query);

        //TAG
        $values = ":idtag,:idphoto";
        $query = "INSERT INTO `" . $this->tablecaract . "`(idtag, idphoto) VALUES(" . $values . ")";
        $this->createCaractReq = $this->pdo->prepare($query);

        $query = "SELECT * FROM `" . $this->tablecaract . "` WHERE idphoto=:idphoto";
        $this->retrieveCaractTagReq = $this->pdo->prepare($query);
    }

    // Create methods =======================================================================
    public function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->table . "` (";
        $query .= "`idphoto` int(11) NOT NULL AUTO_INCREMENT, ";
        $query .= "`fichier` text NOT NULL, ";
        $query .= "`geo_lat` float NOT NULL, ";
        $query .= "`geo_long` float NOT NULL, ";
        $query .= "`titre` text NOT NULL, ";
        $query .= "`description` text NOT NULL, ";
        $query .= "`date` date NOT NULL, ";
        $query .= "`email` int(11) NOT NULL, ";
        $query .= "PRIMARY KEY (`idphoto`) ";
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->pdo->exec($query);
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->tablecaract . "` (";
        $query .= "`id_caracterisation` int(11) NOT NULL AUTO_INCREMENT, ";
        $query .= "`idphoto` int(11) NOT NULL, ";
        $query .= "`idtag` int(11) NOT NULL, ";
        $query .= "PRIMARY KEY (`id_caracterisation`) ";
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->pdo->exec($query);
    }

    public function create(Photo $photo) {
        $this->createPhotoReq->bindValue(":fichier", $photo->getFichier());
        $this->createPhotoReq->bindValue(":geo_lat", $photo->getGeo_lat());
        $this->createPhotoReq->bindValue(":geo_long", $photo->getGeo_long());
        $this->createPhotoReq->bindValue(":titre", $photo->getTitre());
        $this->createPhotoReq->bindValue(":description", $photo->getDescription());
        $this->createPhotoReq->bindValue(":date", $photo->getDate());
        $this->createPhotoReq->bindValue(":email", $photo->getEmail());
        $this->createPhotoReq->execute();
    }

    public function create2tag($idphoto, $idtag) {
        $this->createCaractReq->bindValue(":idphoto", $idphoto);
        $this->createCaractReq->bindValue(":idtag", $idtag);
        $this->createCaractReq->execute();
    }

    // Retrieve methods ====================================================================
    //retrieve photo
    public function retrieve($idphoto) {
        $this->retrievePhotoReq->bindValue(":idphoto", $idphoto);
        $this->retrievePhotoReq->execute();
        $tag = array();
        if ($row = $this->retrievePhotoReq->fetch(PDO::FETCH_ASSOC))
            $tag[] = $this->retrieveCaractTagReq($row["idphoto"]);
        return new Photo($row["idphoto"], $row["fichier"], $row["geo_lat"], $row["geo_long"], $row["titre"], $row["description"], $row["date"], $row["email"], $tag);
        throw new Exception("Aucune photo n'existe avec cet ID : $idphoto");
    }

    public function retrieveid($email) {
        $this->retrieveIdPhotoReq->bindValue(":email", $email);
        $this->retrieveIdPhotoReq->execute();
        if ($row = $this->retrieveIdPhotoReq->fetch(PDO::FETCH_ASSOC))
            return $row["idphoto"];
        throw new Exception("Aucune photo n'existe avec cet email : $email");
    }

    public function retrievenomphoto($idphoto) {
        $this->retrieveNomPhotoReq->bindValue(":idphoto", $idphoto);
        $this->retrieveNomPhotoReq->execute();
        $row = $this->retrieveNomPhotoReq->fetch(PDO::FETCH_ASSOC);
        return $row["fichier"];
    }

    public function retrieveAll() {
        $this->retrieveAllPhotoReq->execute();
        $res = array();
        while ($row = $this->retrieveAllPhotoReq->fetch(PDO::FETCH_ASSOC)) {
            $tag = $this->retrieveCaractTag($row["idphoto"]);
            $res[] = new Photo($row["idphoto"], $row["fichier"], $row["geo_lat"], $row["geo_long"], $row["titre"], $row["description"], $row["date"], $row["email"], $tag);
        }
        return $res;
    }

    public function retrieveAllByEmail($email) {
        $this->retrieveAllPhotoByEmailReq->bindValue(":email", $email);
        $this->retrieveAllPhotoByEmailReq->execute();
        $res = array();
        while ($row = $this->retrieveAllPhotoByEmailReq->fetch(PDO::FETCH_ASSOC)) {
            $tag = $this->retrieveCaractTag($row["idphoto"]);
            $res[] = new Photo($row["idphoto"], $row["fichier"], $row["geo_lat"], $row["geo_long"], $row["titre"], $row["description"], $row["date"], $row["email"], $tag);
        }
        return $res;
    }

    public function retrievePhotobytag($idtag) {
        $this->retrievePhotoByTagReq->bindValue(":idtag", $idtag);
        $this->retrievePhotoByTagReq->execute();
        $res = array();
        while ($row = $this->retrievePhotoByTagReq->fetch(PDO::FETCH_ASSOC)) {
            $tag = $this->retrieveCaractTag($row["idphoto"]);
            $res[] = new Photo($row["idphoto"], $row["fichier"], $row["geo_lat"], $row["geo_long"], $row["titre"], $row["description"], $row["date"], $row["email"], $tag);
        }
        return $res;
    }

    public function existsId($idphoto) {
        $this->retrievePhotoReq->bindValue(":idphoto", $idphoto);
        $this->retrievePhotoReq->execute();
        return $this->retrievePhotoReq->fetch() !== false;
    }

    //retrieve tag
    public function retrieveCaractTag($idphoto) {
        $this->retrieveCaractTagReq->bindValue(":idphoto", $idphoto);
        $this->retrieveCaractTagReq->execute();
        $res = array();
        while ($row = $this->retrieveCaractTagReq->fetch(PDO::FETCH_ASSOC))
            $res[] = $row["idtag"];
        return $res;
    }

    // Delete methods =======================================================================
    public function deleteTable() {
        $this->pdo->exec("DROP TABLE IF EXISTS `" . $this->table . "`");
    }

    public function deletePhoto($idphoto) {
        $this->deletePhotoReq->bindValue(":idphoto", $idphoto);
        $this->deletePhotoReq->execute();
    }

    public function deleteTag($idphoto) {
        $this->deleteTagReq->bindValue(":idphoto", $idphoto);
        $this->deleteTagReq->execute();
    }

}

?>
