<?php

class Message{
  
    private $id;
    private $idSendera;
    private $idRecivera;
    private $text;
    private $messageRead;
    private $creationDate;  
    
    public function __construct(){
        $this->id = -1;
        $this->idSendera = '';
        $this->idRecivera = '';
        $this->text = '';
        $this->messageRead = 0; // domyślnie 0 bo wiadomość nie jest przeczytana 
        $this->creationDate = '';
    }
        
    public function getId(){
        return $this->id;
    }
        
    public function setIdSendera($idSendera){
        return $this->idSendera = $idSendera;
    }
    
    public function getIdSendera(){
        return $this->idSendera;
    }
    
    public function setIdRecivera($idRecivera){
        return $this->idRecivera = $idRecivera;
    }
    
    public function getIdRecivera(){
        return $this->idRecivera;
    }
    
    public function setText($text){
        if(is_string($text) && strlen(trim($text)) > 0){
            $this->text = $text;
        }
     }
     
    public function getText(){
         return $this->text;
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
            
            $sql = "INSERT INTO Message (idSendera, idRecivera, text, messageRead, creationDate)
                    VALUES('{$this->idSendera}', '{$this->idRecivera}', '{$this->text}',
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
//        else{
//            $sql = "UPDATE Message
//                    SET idSendera = '{$this->idSendera}',
//                        idRecivera = '{$this->idRecivera}',
//                        text = '{$this->text}',
//                        messageRead = '{$this->messageRead}',
//                        creationDate = '{$this->creationDate}'
//                    WHERE id = '{$this->id}'";
//            
//            if($connection->query($sql)){
//                return true;
//            }
//            else{       
//                return false;
//            }
//        }
    }
    
    
    static public function loadSentAllMessagesByUserId(mysqli $connection, $idSendera){
        $sql = "SELECT * FROM Message WHERE idSendera = $idSendera" . "ORDER BY creationDate DESC";
             
        $messages = [];
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows > 0){
            foreach($result as $row){
//                $row = $result->fetch_assoc();
                
                $message = new Message();
                $message->id = $row['id'];
                $message->idSendera = $row['idSendera'];
                $message->idRecivera = $row['idRecivera']; 
                $message->text = $row['text'];
                $message->$messageRead = $row['messageRead'];
                $message->$creationDate = $row['creationDate'];
                
                $messages[] = $message;           
            }
            return $messages;
        }
        else{
            return null;
        } 
    }
    
    /* Aktualizacja nieprzeczytanych wiadomośći: W tabeli stwórz pole trzymające informację, 
    czy wiadomość została przeczytana np.: 1–wiadomośćprzeczytana, lub, 0–wiadomośćnieprzeczytana) */
    static public function updateMessageRead(mysqli $connection, $messageRead){
        $sql = "SELECT * FROM Message WHERE messageRead = $messageRead";
        
        $result = $connection->query($sql);
        if($result){
            $updateQuery = "UPDATE Message SET messageRead = '1' WHERE id = '$messageRead'";
            // '1' - bo wiadomosc jest przeczytana po aktualizacji
       
            if($result = $connection->query($updateQuery)){             
                return true;
            } 
            else{
                return null;
            }
        }
        return false;
    }
    
    
    static public function loadAllMessagesByIdRecivera(mysqli $connection, $idRecivera){
        $sql = "SELECT * FROM Message WHERE idRecivera = $idRecivera" . "ORDER BY creationDate DESC";
        
        $messages = [];
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows > 0){
            foreach($result as $row){
                $row = $result->fetch_assoc();
                           
                $message = new Message();
                $message->id = $row['id'];
                $message->idSendera = $row['idSendera'];
                $message->idRecivera = $row['idRecivera']; 
                $message->text = $row['text'];
                $message->$messageRead = $row['messageRead'];
                $message->$creationDate = $row['creationDate'];
                
                $messages[] = $message;
            }
            return $messages;
        }
        else{
            return null;
        }
    }
    
    
    static public function loadAllMessageByMesssageId(mysqli $connection, $id){
        $sql = "SELECT * FROM Message WHERE id = $id";
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows == 1){
            foreach($result as $row){
                $row = $result->fetch_assoc();
                
                $message = new Message();
                $message->id = $row['id'];
                $message->idSendera = $row['idSendera'];
                $message->idRecivera = $row['idRecivera']; 
                $message->text = $row['text'];
                $message->$messageRead = $row['messageRead'];
                $message->$creationDate = $row['creationDate'];
                
                return $message;                     
            }     
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
            return false;
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