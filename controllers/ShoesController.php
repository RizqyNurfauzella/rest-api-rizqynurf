<?php
include_once '../models/Shoes.php';

class ShoesController {
    private $db;
    public $shoes;

    public function __construct(){
        $this->db = include('../config/Database.php');
        $this->shoes = new Shoes($this->db);
    }

    public function read($email){
        $this->shoes->email = $email;
        $result = $this->shoes->read();
        $shoes = array();
        while ($row = $result->fetch_assoc()){
            $shoes[] = $row;
        }
        return $shoes;
    }

    public function create($data, $email){
        $this->shoes->email = $email;
        $this->shoes->merk = $data['merk'];
        $this->shoes->jenisSepatu = $data['jenisSepatu'];

        // Handle image upload
        if (isset($_FILES['image'])) {
            $imageId = $this->shoes->generateUniqueImageId();
            $fileName = $imageId . ".jpeg";
            $directory = "../images/";
                        
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $targetFilePath = $directory . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $this->shoes->imageId = $imageId;
                if($this->shoes->create()){
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public function delete($id, $email){
        $this->shoes->id = $id;
        $this->shoes->email = $email; 

        // Delete the memory record
        if($this->shoes->delete()){
            return true;
        }
        return false;
    }     
    
        public function getImage($imageId){
        $imageBlob = $this->shoes->getImageBlob($imageId);
        if ($imageBlob !== false) {
            header("Content-Type: image/jpeg"); // You can modify the content type as needed
            echo $imageBlob;
            exit;
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Image not found."));
            exit;
        }
    }
}
?>

