<?php

       header('Access-Control-Allow-Origin: *');
       header('Content-Type: application/json');
       header('Access-Control-Allow-Methods: PUT');
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
        
        $author->id = $data->id;
        $author->author = $data->author;
        
       
        if (!$author->update()) {
               echo json_encode(array('message' => 'author_id Not Found'));
               exit();
  
        } 


        else if($author->update()){  //if the update function returns true
            echo json_encode(array('id'=>$author->id, 'author'=>$author->author)); //moved them from multiple lines to see if issue is resolved.
        } else {
            echo json_encode(
                   array('message' => 'author not Updated')
            );
        }
