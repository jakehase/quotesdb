<?php
   
 
   
 include_once '../../models/Quote.php';
 include_once '../../config/Database.php';
   
 
 
   $database = new Database();
   $db = $database->connect();

   $quote = new Quote($db);

   $result = $quote->read();

   $num = $result->rowCount();

   if($num > 0){
      $quote_arr = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){ // puts the quotes into the array to return
        extract($row);
        
        $quote_item = array(
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
            'category' => $category

        );

        array_push($quote_arr, $quote_item);

      }

      echo json_encode($quote_arr);
   } else {  //no quotes in the table
      echo json_encode(
        array('message' => 'No Quotes Found')

      );
   }
