<?php

       header('Access-Control-Allow-Origin: *');
       header('Content-Type: application/json');
       header('Access-Control-Allow-Methods: PUT');
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
        
        $category->id = $data->id;
        $category->category = $data->category;
        
       
        if (!$category->update()) { //again isValid possible here
               echo json_encode(array('message' => 'category_id Not Found'));
               exit();
  
        } 


        else if($category->update()){// just looking to see if the update came back true and returning the info
            echo json_encode(array('id'=>$category->id, 'category'=>$category->category));
        } else {
            echo json_encode(
                   array('message' => 'Category not Updated')
            );
        }


