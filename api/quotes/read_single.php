<?php
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	
	include_once '../../config/Database.php';
	include_once '../../models/Quote.php';
    include_once '../../functions/functions.php';
	
	$database = new Database();
	$db = $database->connect();
	
	$quotes = new Quote($db);
    
	
	if (isset($_GET['id'])) { //just the id is set?
		
        $quotes->id = $_GET['id'];
		$quotes->read_single();
        
		if($quotes->quote !== null) {
			$quotes_arr = array(
				'id' => $quotes->id,
				'quote' => $quotes->quote,
				'author' => $quotes->author,
				'category' => $quotes->category
			);
			echo json_encode($quotes_arr);
            
		} else {
			echo json_encode(array('message' => 'No Quotes Found'));
		}
	}

    elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) { //both author and category id is set, retrieve the quote with these parameters
        $quotes->category_id = $_GET['category_id'];
        $quotes->author_id = $_GET['author_id'];
       
       if(!isValid($quotes->category_id, $quotes)){   //is the id in the database?
            echo json_encode(array('message'=> 'category_id Not Found'));
            exit();
            
        }elseif(!isValid($quotes->author_id, $quotes)) {
            echo json_encode(array('message'=> 'author_id Not Found'));
            exit();
            
        } 
        $quotes_arr = $quotes->read_single();
        echo json_encode($quotes_arr);
       
    }

	elseif (isset($_GET['author_id'])) {
		$quotes->author_id = $_GET['author_id'];
        if(!isValid($quotes->author_id, $quotes)){
            echo json_encode(array('message'=> 'author_id Not Found'));
            exit();
            
        } 
        
        
        $quotes->author_id = $_GET['author_id'];
		$quotes_arr = $quotes->read_single();
        echo json_encode($quotes_arr);
       
	}
	elseif (isset($_GET['category_id'])) {
		$quotes->category_id = $_GET['category_id'];
      
        if(!isValid($quotes->category_id, $quotes)){
            echo json_encode(array('message'=> 'category_id Not Found'));
            exit();
        } 
        
		$quotes_arr = $quotes->read_single();
		echo json_encode($quotes_arr);
        
	}
	
?>
