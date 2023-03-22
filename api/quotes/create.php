<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
   
   include_once '../../models/Quote.php';
   include_once '../../config/Database.php';
   include_once '../../functions/functions.php';
   
   
   $database = new Database();
   $db = $database->connect();
   

   $quotes = new Quote($db);

   $data = json_decode(file_get_contents("php://input"));

   if(!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)){ //check for the required parameters of quote/author id and category id
       echo json_encode(array('message'=> 'Missing Required Parameters'));
       exit();
   }

   $quotes->quote = $data->quote;
   $quotes->author_id = $data->author_id;
   $quotes->category_id = $data->category_id;
   
  
  if(!isValid($quotes->author_id, $quotes)){ //is the id in the database?
    
    echo json_encode(array('message'=> 'author_id Not Found'));
    exit();
}

if(!isValid($quotes->category_id, $quotes)){
    echo json_encode(array('message'=> 'category_id Not Found'));
    exit();
}


   if($quotes->create()){ //if the bool is true
    echo json_encode(array('id' =>$quotes->id, 'quote'=>$quotes->quote, 'author_id'=>$quotes->author_id, 'category_id'=>$quotes->category_id ));
   }
