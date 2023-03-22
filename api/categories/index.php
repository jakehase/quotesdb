<?php
     
     header('Access-Control-Allow-Origin: *');
     header('Content-Type: application/json');
     $method = $_SERVER['REQUEST_METHOD'];
     
     if ($method === 'OPTIONS') {
         header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
         header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
         exit();
     }
     
     if ($method === 'GET') {
        if (isset($_GET['id']) || isset($_GET['category_id']) || isset($_GET['author_id']) || (isset($_GET['category_id']) && isset($_GET['author_id']))) {
             include_once "../../api/categories/read_single.php";
         } else {
             include_once "../../api/categories/read.php";
         }
     }
     
    else if ($method === 'POST') {
    
        include_once "../../api/categories/create.php";
        
    }
    else if ($method === 'PUT') {
        require('../../api/categories/update.php');
    }

    else if ($method === 'DELETE'){
      require('../../api/categories/delete.php');
      
    }



?>



