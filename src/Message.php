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
    
  
    
}

?>