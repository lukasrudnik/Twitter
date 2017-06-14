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
     
  
    
     
     
     
 }

?>