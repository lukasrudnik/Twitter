<?php

 class Comment{
     private $id;
     private $idUsera;
     private $idPostu;
     private $text;
     private $creationDate;
     
     
     public function __construct(){
         $this->id = -1;
         $this->idUsera = '';
         $this->idPostu = '';
         $this->text = '';
         $this->creationDate = '';
     }
     
     public function getId(){
         return $this->id;
     }
     
     public function setIdUsera($idUsera){
         $this->idUsera = $idUsera;
     }
     
     public function getIdUsera(){
         return $this->idUsera;
     }
     
     public function setIdPostu($idPostu){
         $this->idPostu = $idPostu;
     }
     
     public function getIdPostu(){
         return $this->idPostu;
     }
     
     public function setText($text){
        if(is_string($text) && strlen(trim($text)) > 0){
            $this->text = $text;
        }
     }
     
     public function getText(){
         return $this->text;
     }
     
     public function setCreationDate($creationDate){
         $this->creationDate = $creationDate;
     }
     
     public function getCreationDate(){
         return $this->creationDate;
     }
     
     
     
     public function saveToDB(mysqli $connection){      
        if($this->id == -1){
            
            $sql = "INSERT INTO Comment (idUsera, idPostu, text, creationDate)
            VALUES('{$this->idUsera}', '{$this->idPostu}', '{$this->text}', '{$this->creationDate}')";
            
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
            $sql = "UPDATE Comment
                    SET idUsera = '{$this->idUsera}',
                        idPostu = '{$this->idPostu}',
                        text = '{$this->text}',
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
     
     
     // ... Funkcję loadCommentById
     static public function loadCommentByUserId(mysqli $connection, $idUsera){
        $sql = "SELECT * FROM Comment WHERE idUsera = " .
                $connection->real_escape_string($idUsera) . "ORDER BY creationDate DESC";
        
        $comments = [];
        
        $result = $connection->query($sql);    
        if($result == true){
            foreach($result as $row){
                
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->idUsera = $row['idUsera'];
                $loadedComment->idPostu = $row['idPostu']; 
                $loadedComment->text = $row['text'];
                $loadedComment->creationDate = $row['creationDate'];
                
                $comments[] = $loadedComment;           
            }
            return $loadedComment;
        }
        else{
            return null;
        } 
    }
     
     
     // ...  Funkcję loadAllCommentsByPostId
     static public function loadCommentByTweetId(mysqli $connection, $idPostu){
        $sql = "SELECT * FROM Comment WHERE idPostu = " . 
                $connection->real_escape_string($idPostu);
        
        $comments = [];
        
        $result = $connection->query($sql);    
        if($result == true){
            foreach($result as $row){
                
                $comment = new Comment();
                $comment->id = $row['id'];
                $comment->idUsera = $row['idUsera'];
                $comment->idPostu = $row['idPostu'];
                $comment->text = $row['text'];
                $comment->creationDate = $row['creationDate'];
                
                $comments[] = $comment;           
            }
            return $comments;
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
     
     
}

?>