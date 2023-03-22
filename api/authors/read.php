<?php
   
 
   
 include_once '../../models/Author.php';
 include_once '../../config/Database.php';
   
 
 
   $database = new Database();
   $db = $database->connect();

   $author = new Author($db);

   $result = $author->read();
   $num = $result->rowCount();

   if($num > 0){
          $cat_arr = array();
          

          while($row = $result->fetch(PDO::FETCH_ASSOC)){
              extract($row);
              $cat_item = array(
               'id' => $id,
               'author' => $author
             );
   
             // Push to "data"
             array_push($cat_arr, $cat_item);
           }

           echo json_encode($cat_arr);

          }else {

            echo json_encode(  //if the author is empty completely
               array('message'=> 'author_id Not Found')
            );
          }


?>
