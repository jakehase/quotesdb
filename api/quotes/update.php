<?php
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: PUT');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
	
	include_once '../../models/Quote.php';
    include_once '../../config/Database.php';
    include_once '../../functions/functions.php';

	$database = new Database();
	$db = $database->connect();
	
	$quotes = new Quote($db);
		
	$data = json_decode(file_get_contents("php://input"));

    if(!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
		echo json_encode(
			array('message' => 'Missing Required Parameters')
		);
		exit();
	}

    $quotes->id = $data->id;
    $quotes->quote = $data->quote;
    $quotes->author_id = $data->author_id;
    $quotes->category_id = $data->category_id;
     
    if(!isValid($quotes->author_id, $quotes)){
    
        echo json_encode(array('message'=> 'author_id Not Found')); //added
        exit();
    }
    
    if(!isValid($quotes->category_id, $quotes)){
        echo json_encode(array('message'=> 'category_id Not Found'));//added
        exit();
    }
    

    
    if($quotes->update()){ //true bool? return the query
        echo json_encode(array('id'=>$quotes->id, 'quote'=>$quotes->quote, 'author_id'=>$quotes->author_id, 'category_id'=>$quotes->category_id));
    }else if (empty($quotes->quote)){
        echo json_encode(array('message'=>'No Quotes Found'));
    } else {
        echo json_encode(array('message'=>'No Quotes Found'));
    } 
