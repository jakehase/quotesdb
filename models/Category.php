<?php

  class Category  {

       private $conn;
       private $table = 'categories';

       public $id;
       public $category;
       public $updatedCategory;
       public $updatedId;


       public function __construct($db){
        $this->conn = $db;
       }

       public function read() {

           $query = 'SELECT 
            id,
            category
           FROM
            ' . $this->table . '
           ORDER BY 
            id ASC'; 
           
           
           $stmt = $this->conn->prepare($query);

           $stmt->execute();

           return $stmt;
           



       }

       public function getTable(){
         return $this->table; 
       }

       public function getConn(){
         return $this->conn;
       }

       public function read_single(){
            
        $query = 'SELECT 
               id,
               category
           FROM
           ' . $this->table . '
        WHERE id = ?
        LIMIT 1 OFFSET 0';

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->id);
        
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row){
          $this->id = null;
          $this->category = null;
          return false;
        }

        $this->id = $row['id'];
        $this->category = $row['category'];

         return true;  







       }


       public function create() {
         $query = 'INSERT INTO ' .
               $this->table . '(category)
               VALUES(:category)
               RETURNING id';

         $stmt = $this->conn->prepare($query);
         $this->category = htmlspecialchars(strip_tags($this->category));
         $stmt->bindParam(':category', $this->category);
          
         if($stmt->execute()){
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          return $row['id'];
         }

         printf("Error: %s.\n", $stmt->error);
         
         return false;
       }

    
     public function update() {
       $query = 'UPDATE ' . 
              $this->table . '
        SET   category = :category
        WHERE  id = :id';

        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()){
          $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if(!$row){
            return false;
          }

          
          $this->updatedCategory = $this->category;
          $this->updatedId = $this->id;
          return json_encode(array("category" => $this->updatedCategory, "id" => $this->updatedId));

          
          return true;
 

        }
        printf("Error: %s.\n", $stmt->error);
        
        return false;
         
     }

     public function delete(){
       $query = 'DELETE FROM ' . 
              $this->table . 
       ' WHERE id = :id
         RETURNING id';

       $stmt = $this->conn->prepare($query);
       $this->id = htmlspecialchars(strip_tags($this->id));

       $stmt->bindParam(':id', $this->id);

       if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
          return $row['id'];
        } else {
          return false;
        }
        
       }
       
       printf("Error: %s.\n", $stmt->error);
       return false;

       

     }




   }
  
