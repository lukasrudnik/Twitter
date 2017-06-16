<?php

class Message{
  
    private $id;
    private $idSendera;
    private $idRecivera;
    private $message;
    private $messageRead;
    private $creationDate;  
    
    public function __construct(){
        $this->id = -1;
        $this->idSendera = '';
        $this->idRecivera = '';
        $this->message = '';
        $this->messageRead = 0; // domyślnie 0 bo wiadomość nie jest przeczytana 
        $this->creationDate = '';
    }
        
    public function getId(){
        return $this->id;
    }
        
    public function setIdSendera($idSendera){
        return $this->idSendera = $idSendera;
    }
    
    function getIdSendera(){
        return $this->idSendera;
    }
    
    function setIdRecivera($idRecivera){
        return $this->idRecivera = $idRecivera;
    }
    
    function getIdRecivera(){
        return $this->idRecivera;
    }
    
    function setMessage($message){
        return $this->message = $message;
    }
    
    function getMessage(){
        return $this->message;
    }
    
    public function setMessageRead($messageRead){
        return $this->messageRead = $messageRead;
    }
    
    public function getMessageRead(){
        return $this->messageRead;
    }
    
    public function setCreationDate($creationDate){
        return $this->creationDate = $creationDate;
    }
    
    public function getCreationDate(){
        return $this->creationDate;
    }
    
    
    
    public function saveToDB(mysqli $connection){      
        if($this->id == -1){
            
            $sql = "INSERT INTO Message (idSendera, idRecivera, message, messageRead, creationDate)
                    VALUES('{$this->idSendera}', '{$this->idRecivera}', '{$this->message}',
                    '{$this->messageRead}', '{$this->creationDate}')";
            
            $result = $connection->query($sql);
            
            if($result == true){ 
                $this->id = $connection->insert_id;
                return true;
            }
            else{
                return false;
            }
        }    
        else{
            $sql = "UPDATE Message
                    SET idSendera = '{$this->idSendera}',
                        idRecivera = '{$this->idRecivera}',
                        message = '{$this->message}',
                        messageRead = '{$this->messageRead}',
                        creationDate = '{$this->creationDate}'
                    WHERE id = '{$this->id}'";
            
            if($connection->query($sql)){
                return true;
            }
            else{       
                return false;
            }
        }
    }
    
    
    static public function loadAllMessagesByUserId(mysqli $connection, $idSendera){
        $sql = "SELECT * FROM Message WHERE idSendera =" .
            $connection->mysqli_real_escape_string($idSendera) . "ORDER BY creationDate DESC";
        
        $messages = [];
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows > 0){
            foreach($result as $row){
                $row = $result->fetch_assoc();
                
                $loadedMessage = new Messages();
                $loadedMessage->id = $row['id'];
                $loadedMessage->idSendera = $row['idSendera'];
                $loadedMessage->idRecivera = $row['idRecivera']; 
                $loadedMessage->message = $row['message'];
                $loadedMessage->$messageRead = $row['messageRead'];
                $loadedMessage->$creationDate = $row['creationDate'];
                
                $messages[] = $loadedMessage;           
            }
            return $loadedMessage;
        }
        else{
            return null;
        } 
    }
    
    /* Aktualizacja nieprzeczytanych wiadomośći: W tabeli stwórz pole trzymające informację, 
    czy wiadomość została przeczytana np.: 1–wiadomośćprzeczytana, lub, 0–wiadomośćnieprzeczytana) */
    static public function updateMessageRead(mysqli $connection, $messageId){
        $sql = "SELECT * FROM Message WHERE id ='" . 
                $connection->mysqli_real_escape_string($messageId) . "'";
        
        $result = $connection->query($sql);
        if($result){
            $updateQuery = "UPDATE Message SET MessageRead = '1' WHERE id = '$messageId'";
            // '1' - bo wiadomosc nie jest przeczytana
       
            if($result = $connection->query($updateQuery)) {             
                return true;
            } 
            else{
                return null;
            }
        }
        return false;
    }
    
    
    static public function loadAllMessagesByIdRecivera(mysqli $connection, $userId){
        $sql = "SELECT * FROM Message WHERE idRecivera ='" . 
                $connection->mysqli_real_escape_string($userId) . "'ORDER BY creationDate DESC";
        
        $messages = [];
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows > 0){
            foreach($result as $row){
                $row = $result->fetch_assoc();
                           
                $loadedMessage = new Messages();
                $loadedMessage->id = $row['id'];
                $loadedMessage->idSendera = $row['idSendera'];
                $loadedMessage->idRecivera = $row['idRecivera']; 
                $loadedMessage->message = $row['message'];
                $loadedMessage->$messageRead = $row['messageRead'];
                $loadedMessage->$creationDate = $row['creationDate'];
                
                $messages[] = $message;
            }
            return $messages;
        }
        else{
            return null;
        }
    }
    
    
    static public function loadAllMessageByMesssageId(mysqli $connection, $messageId){
        $sql = "SELECT * FROM Message WHERE id = '$massageId'";
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows > 0){
            foreach($result as $row){
                $row = $result->fetch_assoc();
                
                $loadedMessage = new Messages();
                $loadedMessage->id = $row['id'];
                $loadedMessage->idSendera = $row['idSendera'];
                $loadedMessage->idRecivera = $row['idRecivera']; 
                $loadedMessage->message = $row['message'];
                $loadedMessage->$messageRead = $row['messageRead'];
                $loadedMessage->$creationDate = $row['creationDate'];
                
                return $loadedMessage;                     
            }     
        }
         else{
             return null;
         }
    }
    
    
    public function delete(mysqli $connection){
        if($this->id != -1){
            $sql = "DELETE FROM Tweet WHERE id = '{$this->id}'";
            
            $result = $connection->query($sql);
            
            if($result == true){
                $this->id = -1; 
     
                return true;
            }
            else{
                return false;
            }
        }
        return true; 
       
    }
    
    /*
    // FUNKCJA DODAJĄCA WIADOMOŚĆ 
    public function createMessage(mysqli $connection){
        if($this->id == -1){
            
            $sql = "INSERT INTO Message(idSendera, idRecivera, message, messageRead, creationDate)
                    VALUES ('{$this->idSendera}', '{$this->idRecivera}', '{$this->message}',
                    '{$this->messageRead}', '{$this->creationDate}')";
            
            $result = $connnection->query($sql);
            if ($result == true){
                $this->id = $connection->idSendera;
                return true;
            }
        }
        else{          
            $sql = "UPDATE Message SET idSendera='{$this->idSendera}',
                                        idRecivera='{$this->idRecivera}',
                                        message='{$this->message}',
                                        idRecivera='{$this->idRecivera}',
                                        creationDate='{$this->creationDate}'
                    WHERE id='{$this->id}'";
            
            $result = $connection->query($sql);
            if($result == true){
                return true;
            }
        }
        return false;
    }   
    */
    
    
}

?>