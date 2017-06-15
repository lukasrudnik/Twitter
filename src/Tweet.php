<?php

class Tweet{
    private $id;
    private $userId;
    private $text;
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
    
    
    
    public function saveToDB(mysqli $connection){      
        if($this->id == -1){
            
            $sql = "INSERT INTO Tweet (userId, text, creationDate)
            VALUES('{$this->userId}', '{$this->text}', '{$this->creationDate}')";
            
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
            $sql = "UPDATE Tweets
                    SET userId = '{$this->userId}',
                        text = '{$this->text}',
                        creationDate = '{$this->creationDate}'
                    WHERE id = '{$this->id}'";
            
            if($connection->query(sql)){
                return true;
            }
            else{       
                return false;
            }
        }
    } 
    
    
    static public function loadTweetById(mysqli $connection, $id){
        
        $sql = "SELECT * FROM Tweet WHERE id = $id" . $connection->mysqli_real_escape_string($id);
    
        $result = $connection->query($sql);
        if($result == true && $result->num_rows == 1){
            $row = $result->fetch_assoc();
            
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];

            return $loadedTweet;     
        }
        else{
            return null;
        } 
    }
    
    
    static public function loadTweetByUserId(mysqli $connection, $userId){
        $sql = "SELECT * FROM Tweet WHERE userId =$userId" .
            $connection->mysqli_real_escape_string($userId);
        
        $tweets = [];
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows > 0){
            foreach($result as $row){
                $row = $result->fetch_assoc();
                
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId']; 
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                
                $tweets[] = $loadedTweet;           
            }
            return $loadedTweet;
        }
        else{
            return null;
        } 
    }
    
    
    static public function loadAllTweets(mysqli $connection){
        
        $sql = "SELECT * FROM Tweet ORDER BY creationDate DESC";
        /* ORDER BY creationDate DESC -> łączenie wszystkich tweetów - sortowanie rosnąco poprzez utworzoną datę */
        
        $tweets = [];
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId']; 
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];             
                
                $tweets[] = $loadedTweet;           
            }
            return $tweets;
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