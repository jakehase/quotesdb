<?php

  class Author {

    private $conn;
    private $table = 'authors';

    public $id;
    public $author;
    public $updatedId;
    public $updatedAuthor;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getTable(){ //this is needed in all models for isValid to work
        return $this->table;
      }

      public function getConn(){ //this is needed in all models for isValid to work
        return $this->conn;
      } 

    public function read() { //query returns id and author, same with category read function
        $query = 'SELECT 
            id,
            author
            FROM
            ' . $this->table . '
            ORDER BY 
            id ASC'; 
           
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_single() { //here we limit 1 for the specific ID
        $query = 'SELECT 
            id,
            author
            FROM
            ' . $this->table . '
            WHERE id = ?
            LIMIT 1 OFFSET 0';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {  //lets us return a null if the row doesnt exist
            $this->id = null;
            $this->author = null;
            return false;
        }

        $this->id = $row['id'];
        $this->author = $row['author'];

        return true;
    }

    public function create() {  //insert query, same on category, returns id to let us echo in create.php
        $query = 'INSERT INTO ' .
               $this->table . '(author)
               VALUES(:author)
               RETURNING id';

        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);
          
        if ($stmt->execute()) {  //returns the array
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    public function update() { // updates the database with the new info that is in put request, specifying author and the id to be updated
        $query = 'UPDATE ' . 
               $this->table . '
               SET   author = :author
               WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$row) {  //if there is nothing to update
                return false;
            }

            $this->updatedAuthor = $this->author;
            $this->updatedId = $this->id;
            return json_encode(array("author" => $this->updatedAuthor, "id" => $this->updatedId));
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    public function delete() {  //removes from the table the specified row with id.
        $query = 'DELETE FROM ' . 
               $this->table . 
               ' WHERE id = :id
               RETURNING id';

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['id'];
            } else {
                return false;  //if the row wasnt there to delete
            }
        }
       
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

  }




   
