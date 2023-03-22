<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/Category.php';
include_once '../../config/Database.php';


$database = new Database();
$db = $database->connect();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

//Test for parameters
if(!isset($data->category)){
    echo json_encode(
        array('message'=> 'Missing Required Parameters')
    );
    exit();
}

$category->category = $data->category;

$categoryId = $category->create();
if($categoryId){
    echo json_encode(
        array('id'=>$categoryId, 'category'=>$category->category)
    );
} else {
    echo json_encode(
           array('message' => 'Category not Created')
    );
}



