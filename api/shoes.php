<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

include_once '../controllers/ShoesController.php';

$shoesController = new ShoesController();

$request_method = $_SERVER["REQUEST_METHOD"];
$headers = getallheaders();

if (!isset($headers['authorization'])) {
    echo json_encode(array("status" => "error", "message" => "Authorization header not provided."));
    exit();
}

$email = $headers['authorization'];

switch($request_method) {
    case 'GET':
        if(isset($_GET["imageId"])){
            $shoesUrl = $shoesController->getImage($_GET['imageId']);
            echo json_encode($shoesUrl);
            exit();
        }
        $shoes = $shoesController->read($email);
        echo json_encode($shoes);
        break;

    case 'POST':
        $data = $_POST;
        if($shoesController->create($data, $email)){
        echo json_encode(array("status" => "success", "message" => "Memory created successfully."));
        } else {
        echo json_encode(array("status" => "error", "message" => "Unable to create memory."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $id = $data['id'];
            if($shoesController->delete($id, $email)){
                echo json_encode(array("status" => "success", "message" => "Memory deleted successfully."));
            } else {
                echo json_encode(array("status" => "error", "message" => "Unable to delete memory."));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "ID not provided."));
        }
        break;

    default:
        echo json_encode(array("status" => "success", "message" => "Memory deleted successfully."));
        break;
}
?>
