<?php
   
 
   
 include_once '../../models/Category.php';
 include_once '../../config/Database.php';
   
 
 
   $database = new Database();
   $db = $database->connect();

   $category = new Category($db);

   $result = $category->read();
   $num = $result->rowCount();

   if($num > 0){ //if the category table has some stuff in it
          $cat_arr = array();
          

          while($row = $result->fetch(PDO::FETCH_ASSOC)){
              extract($row);
              $cat_item = array(
               'id' => $id,
               'category' => $category
             );
   
             // Push to "data"
             array_push($cat_arr, $cat_item);
           }

           echo json_encode($cat_arr);

          }else { //there isnt anything there.

            echo json_encode( 
               array('message'=> 'category_id Not Found')
            );
          }


?>





   

   
