<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/Category.php';
include_once '../../config/Database.php';


$database = new Database();
$db = $database->connect();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

$category->id = $data->id;

if(!isset($data->id)){
    echo json_encode(
        array('message'=> 'Missing Required Parameters')
    );
    exit();
}

$category->id = $data->id;

if (!$category->delete()) { //again could use isValid function here
    echo json_encode(array('message' => 'category_id Not Found'));
    exit();
 
}

echo json_encode(
    array('id'=>$category->id)
);
