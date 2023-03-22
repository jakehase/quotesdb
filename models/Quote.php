<?php
          class Quote{
                private $conn;
                private $table = 'quotes';

                public $quote;
                public $author;
                public $category;
                public $category_id;
                public $author_id;
                public $id;

                public function __construct($db){
                    $this->conn = $db;
                }
                
                public function getTable(){
                    return $this->table;
                  }
           
                  public function getConn(){
                    return $this->conn;
                  }
           

                public function read() {
                   $query = 'SELECT 
                             quotes.id,
                             quotes.quote,
                             authors.author,
                             categories.category
                    FROM ' . $this->table . '
                    INNER JOIN  authors
                    ON  quotes.author_id = authors.id
                    INNER JOIN categories
                    ON  quotes.category_id = categories.id
                    ORDER BY id';

                    $stmt = $this->conn->prepare($query);

                    $stmt->execute();
                    return $stmt;



                }

                public function read_single(){
                        if (isset($_GET['id'])){ //query for if looking for quote by ID
                            
                            $query = 'SELECT 
                                   quotes.id,
                                   quotes.quote,
                                   authors.author,
                                   categories.category
                            FROM  ' . $this->table . '
                            INNER JOIN  authors
                            ON quotes.author_id = authors.id
                            INNER JOIN categories
                            ON quotes.category_id = categories.id
                            WHERE quotes.id = :id
                            LIMIT 1 OFFSET 0';
                            
                            $stmt = $this->conn->prepare($query);
                            

                            $stmt->bindParam(':id',$this->id);
                            $stmt->execute();

                            $row=$stmt->fetch(PDO::FETCH_ASSOC);
                            

                            if(is_array($row)){
                                $this->quote = $row['quote'];
                                $this->author = $row['author'];
                                $this->category = $row['category'];
                            }
                        
                        
                        }

                    if (isset($_GET['author_id']) && isset($_GET['category_id'])){//looking for a sepcific quote by author and category id
                        $query = 'SELECT 
                               quotes.id,
                               quotes.quote,
                               authors.author,
                               categories.category
                        FROM   '. $this->table . '  
                        INNER JOIN authors
                        ON quotes.author_id = authors.id
                        INNER JOIN categories
                        ON quotes.category_id = categories.id
                        WHERE quotes.author_id = :author_id
                        AND quotes.category_id = :category_id
                        ORDER BY quotes.id';

                        $this->author_id = $_GET['author_id'];
                        $this->category_id = $_GET['category_id'];
                        $stmt = $this->conn->prepare($query);
                        $stmt->bindParam(':author_id', $this->author_id);
                        $stmt->bindParam(':category_id', $this->category_id);
                        $stmt->execute();
                        $quotes = [];

                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row);

                            $quotes[] = [
                                'id' => $id,
                                'quote' => $quote,
                                'author' => $author,
                                'category' => $category
                            ];
                        }
                         return $quotes; 
                    }  
                    

                    if (isset($_GET['author_id'])){ //looking for quote by author id
                        $query = 'SELECT
                               quotes.id,
                               quotes.quote,
                               authors.author,
                               categories.category
                        FROM ' . $this->table. '  
                        INNER JOIN authors 
                        ON quotes.author_id = authors.id
                        INNER JOIN categories
                        ON quotes.category_id = categories.id
                        WHERE quotes.author_id = :id
                        ORDER BY quotes.id';
                        
                        $stmt = $this->conn->prepare($query);
                        
                        
                        $stmt->bindParam(':id', $_GET['author_id']);
                        
                        $stmt->execute();

                        $quotes = [];
                       

                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                         extract($row);
                        
                         $quotes[] = [
                            'id' => $id,
                            'quote' => $quote,
                            'author' => $author,
                            'category' => $category

                         ];
                        }
                       return $quotes;
                       

                    }

                    if(isset($_GET['category_id'])){//looking for quote by category_id
                        $query = 'SELECT
                               quotes.id,
                               quotes.quote,
                               authors.author,
                               categories.category
                        FROM ' . $this->table . '
                        INNER JOIN authors
                        ON quotes.author_id = authors.id
                        INNER JOIN categories
                        ON quotes.category_id = categories.id
                        WHERE quotes.category_id = :id
                        ORDER BY quotes.id';  
                      
                       $stmt = $this->conn->prepare($query);
                       
                       $stmt->bindParam(':id', $_GET['category_id']);
                       $stmt->execute();

                       $quotes = [];

                       while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);

                        $quotes[] = [
                            'id' => $id,
                            'quote' => $quote,
                            'author' => $author,
                            'category' => $category
                        ];
                       }
                       return $quotes;
                    }

                   


                   }

                   public function create() { //create this quote in the table quotes, 
           
                      
                    $query = 'INSERT INTO ' .  $this->table . '(quote, author_id, category_id)
                    VALUES( :quote, :author_id, :category_id) RETURNING id, quote, author_id, category_id';    //return the query to echo to the user      
                    
                    $stmt = $this->conn->prepare($query);
                    $this->quote = htmlspecialchars(strip_tags($this->quote));
                    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
                    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
                    $stmt->bindParam(':quote', $this->quote);
                    $stmt->bindParam(':author_id',$this->author_id);
                    $stmt->bindParam(':category_id',$this->category_id);

                    if($stmt->execute()){
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $this->id = $row['id'];
                        $this->quote = $row['quote'];
                        $this->author_id = $row['author_id'];
                        $this->category_id = $row['category_id'];
                        return true;
                    }
                    printf("Error: %s.\n", $stmt->error);
                    return false;




                   }


                  public function update(){  //change the requested quote by id/author id and /category id
                    $query = 'UPDATE '. $this->table . '
                    SET quote = :quote,
                        author_id = :author_id,
                        category_id = :category_id
                    WHERE   
                         id = :id';

                   $stmt = $this->conn->prepare($query);
                   $this->quote = htmlspecialchars(strip_tags($this->quote));
			       
                   $this->author_id = htmlspecialchars(strip_tags($this->author_id));
			       $this->category_id = htmlspecialchars(strip_tags($this->category_id));
			       
                   $this->id = htmlspecialchars(strip_tags($this->id));
			       
                   $stmt->bindParam(':quote', $this->quote);
			       
                   $stmt->bindParam(':author_id', $this->author_id);
			       
                   $stmt->bindParam(':category_id', $this->category_id);
			       $stmt->bindParam(':id', $this->id);  
                   
                   if($stmt->execute() && $stmt->rowCount() > 0){ //the quote has to exist.
                    return true;
                   }else { return false;
                }
                   printf("Error: %s.\n", $stmt->error);
                   return false;
                  
                  } 
                  
                  public function delete(){ //remove the quote
                    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

                    $stmt = $this->conn->prepare($query);
                    $this->id = htmlspecialchars(strip_tags($this->id));

                    $stmt->bindParam(':id', $this->id);

                    if($stmt->execute()){ //return false if there is no quote with those specifications
                      if ($stmt->rowCount() > 0){
                        return true;
                        }
                    }else {
                        return false;
                    }
                   
                   return false;


                  }


                }
