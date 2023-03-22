<?php
   
 
   
   include_once '../../models/Category.php';
   include_once '../../config/Database.php';
   include_once '../../functions/functions.php';
   
   

   $database = new Database();
   $db = $database->connect();

   $category = new Category($db);

   $category->id = $_GET['id'];

   //$category->read_single();

   if (!isValid($category->id, $category)) { //check to see if category id is in database
      echo json_encode(array('message' => 'category_id Not Found'));
      exit();
  }

  $category->read_single();

   $category_arr = array(
    'id' => $category->id,
    'category' => $category->category
   );

   echo json_encode($category_arr);
   ?>
