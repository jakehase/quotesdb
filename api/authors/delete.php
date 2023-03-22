<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/Author.php';
include_once '../../config/Database.php';


$database = new Database();
$db = $database->connect();

$author = new Author($db);

$data = json_decode(file_get_contents("php://input"));

$author->id = $data->id;

if(!isset($data->id)){
    echo json_encode(
        array('message'=> 'Missing Required Parameters')
    );
    exit();
}

$author->id = $data->id;

if (!$author->delete()) { //this could be my isValid Function
    echo json_encode(array('message' => 'author_id Not Found'));
    exit();
 
}

echo json_encode(
    array('id'=>$author->id)
);
