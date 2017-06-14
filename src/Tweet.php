<?php

class Tweet{
    private $id;
    private $userId;
    private text;
    private $creationDate;
    
    public function __construct(){
        $this->id = -1;
        $this->userId = '';
        $this->text = '';
        $this->creationDate = '';
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setUserId($userId){
        if($userId >= 0){
            $this->userId = $userId;
        }
    }
    
    public function getUserId(){
        return $this->userId;
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
        return $this->creationDate
    }
    
    
    
    
}

?>