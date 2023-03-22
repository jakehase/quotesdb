<?php
  
  function isValid($id, $model) { //checks the id for the specified model to see if it exists in database
    $query = 'SELECT id FROM ' . $model->getTable() . ' WHERE id = :id';
    
    $stmt = $model->getConn()->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if ($stmt->errorCode() !== '00000') { //added to check for errors when it wasn't working
      $errorInfo = $stmt->errorInfo();
      echo "Error: " . $errorInfo[2];
  }

    $numRows = $stmt->rowCount();  //what is the rowCount() of the id
    
    return $numRows > 0; //boolean greater than 0 = true, row exists, boolean < 0 false, id/row does not exist
    //return $stmt->rowCount() > 0;
}
