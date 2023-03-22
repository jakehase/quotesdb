<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/Author.php';
include_once '../../config/Database.php';


$database = new Database();
$db = $database->connect();

$author = new Author($db);

$data = json_decode(file_get_contents("php://input"));

//Test for parameters
if(!isset($data->author)){
    echo json_encode(
        array('message'=> 'Missing Required Parameters')
    );
    exit();
}

$author->author = $data->author;

$authorId = $author->create(); // bool
if($authorId){ //is it true? return the created author id and author
    echo json_encode(
        array('id'=>$authorId, 'author'=>$author->author)
    );
} else {
    echo json_encode(
           array('message' => 'author not Created')
    );
}
