<?php
     
     header('Access-Control-Allow-Origin: *');
     header('Content-Type: application/json');
     $method = $_SERVER['REQUEST_METHOD'];
     
     $uri = $_SERVER['REQUEST_URI'];
     if ($method === 'OPTIONS') {
         header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
         header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
         exit();
     }
     
     if ($method === 'GET') { //check the method for an id
         if (parse_url($uri, PHP_URL_QUERY)) {
             include_once "../../api/quotes/read_single.php";
             
         } else {
             include_once "../../api/quotes/read.php";
            
         }
     }
     
    else if ($method === 'POST') {
    
        include_once "../../api/quotes/create.php";
        
    }
    else if ($method === 'PUT') {
        require('../../api/quotes/update.php');
    }

    else if ($method === 'DELETE'){
      require('../../api/quotes/delete.php');
      
    }



?>
     
