<?php
class Shoes {
    private $conn;
    private $table_name = "shoes";
    
    public $id;
    public $merk;
    public $jenisSepatu;    
    public $imageId;
    public $email; 

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        return $stmt->get_result();
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . " (merk, jenis, image_id, email) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $this->merk, $this->jenisSepatu, $this->imageId, $this->email);
        return $stmt->execute();
    }

    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? AND email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $this->id, $this->email);
        return $stmt->execute();
    }

    function generateUniqueImageId() {
        return uniqid();
    }

    public function getUserByEmail($email){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

    function getByIdAndEmail($id, $email){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? AND email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $id, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        }
        return false;
    }
    
    function getImageBlob($imageId) {
        $pattern = "../images/" . $imageId . “.jpeg”;
        $imagePaths = glob($pattern);
        
        if (!empty($imagePaths)) {
            return file_get_contents($imagePaths[0]);
        }
        return false;
    }
}
?>