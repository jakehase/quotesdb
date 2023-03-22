<?php
   
 
   
   include_once '../../models/Author.php';
   include_once '../../config/Database.php';
   include_once '../../functions/functions.php';
   

   $database = new Database();
   $db = $database->connect();

   $author = new Author($db);

   $author->id = $_GET['id'];

   //$author->read_single();

   if (!isValid($author->id, $author)) { //check for the id being in the database
      echo json_encode(array('message' => 'author_id Not Found'));
      exit();
  }
  
  $author->read_single();

   $author_arr = array(
    'id' => $author->id,
    'author' => $author->author
   );

   echo json_encode($author_arr);
   ?>
