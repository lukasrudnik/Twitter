<?php

$optionsHash = ['cost'=>11];

class User{
    
    private $id;
    private $username;
    private $email;
    private $hashedPassword;
    
    // Konstruktor bez żadnych atrybutów aby nastawiał wszystkie atrybuty na domyślne wartości
    public function __construct(){
        $this->id = -1; 
        // id ma -1 bo ten obiekt nie jest połączony z żadnym rzędem w bazie danych    
        $this->username = '';
        $this->email = '';
        $this->$hashedPassword = '';
    }
  
    
    // setery i getery 
    // id nie ma setera bo jest ustawione na AUTO_INCREMENT 
    
    public function getId(){
        return $this->id;
    }
    
    public function setUsername($username){
        if(is_string($username) && strlen(trim($username)) >= 3){ 
            //trim usuwa białe znaki
            $this->username = trim($username);
        }
        return $this;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function setEmail($email){
        if(is_string($email) && strlen(trim($email)) >= 5){
            $this->email = trim($email);
        }
        return $this->emial;
    }
    
    public function getEmail(){
        return $this->emial;
    }
    
    
    // Funkcja haszująca hasło + salt
    public function setHashedPassword($setNewScretPassword){ 
        if(is_string($setNewScretPassword) && strlen(trim($setNewScretPassword)) >= 5){
            $newHashedPassword = password_hash($setNewScretPassword, PASSWORD_BCRYPT, $optionsHash);
            $this->hashedPassword = $newHashedPassword;
        }
    }
    
    
    
    // Zapisywanie nowego obiektiu do bazy danych
    public function saveToDB(mysqli $connection){
        // Zapisujemy obiekt do bazy tylko jeżeli jego id jest równe -1
        if($this->id == -1){
                   
            $sql = "INSERT INTO Users (username, email, hashedPassword)
                    VALUES('{$this->username}', '{$this->email}', '{$this->hashedPassword}')";
            
            $result = $connection->query($sql);
            
            // Jeżeli udało się nam zapisać obiekt do bazy to przypisujemy mu klucz główny jako id
            if($result == true){ 
                $this->id = $connection->insert_id;
                return true;
            }
            else{
                return false;
            }
        }    
        // Jeżeli id NIE jest równe -1 to robimy jego aktualizację
        else{
            $sql = "UPDATE Users
                    SET name = '{$this->username}',
                        email = '{$this->email}',
                        hashedPassword = '{$this->hashedPassword}'
                    WHERE id = '{$this->id}'";
            
            if($connection->query($sql)){
                return true;
            }
            else{       
                return false;
            }
        }
    }
  
    
    // Wczytywanie obiektu z bazy danych po ID
    static public function loadUserById(mysqli $connection, $id) {
        // Funkcja jest statyczna – możemy jej używać na klasie a nie na obiekcie
        
        $sql = "SELECT * FROM Users WHERE id = $id" . $connection->mysqli_real_escape_string($id);
        // mysqli_real_escape_string usuwa znaki specjalne 
        
        $result = $connection->query($sql);
        if($result == true && $result->num_rows == 1){
            $row = $result->fetch_assoc();
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->email = $row['email'];
            $loadedUser->hashedPassword = $row['hashedPassword'];
            /*Tworzenie nowego obiektu użytkownika i nastawienie mu odpowiednich parametrów, 
            jesteśmy w środku klasy, więc mamy dostęp do własności prywatnych mimo działania w metodzie statycznej*/         
            return $loadedUser;     
        }
        else{
            return null;
        }    
    }
    
    
    // Wczytywanie WSZYSTKICH obiektów z bazy danych
    static public function loadAllUsers(mysqli $connection){
        
        $sql = "SELECT * FROM Users";
        
        $ret = [];
        // Tworzenie pustej tablicy którą potem wypełnimy obiektami wczytanymi z bazy danych
        
        $result = $connection->query($sql);    
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username']; 
                $loadedUser->email = $row['email'];
                $loadedUser->hashedPassword = $row['hashedPassword'];             
                /*Tworzymy nowy obiekt użytkownika i nastawiamy mu odpowiednie parametry. 
                Jako że jesteśmy w środku klasy mamy dostęp do własności prywatnych, 
                mimo działania w metodzie statycznej*/            
                $ret[] = $loadedUser;
                /*Po stworzeniu i wypełnieniu obiektu danymi wkładamy go do tablicy którą pod koniec zwracamy*/
            }
            return $ret;
        }
        else{
            return null;
        }     
    }
    
    
    // Wczytywanie obiektu oo jego EMAIL
    static public function loadUserByEmail(mysqli $connection, $email){
        
        $sql = "SELECT * FROM Users WHERE email = $email" . 
            $connection->mysqli_real_escape_string($email);
        
        $result = $connection -> query($sql);      
        if($result == true && $result->num_rows == 1){
            $row = $result->fetch_assoc();
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->email = $row['email'];
            $loadedUser->hashedPassword = $row['hashedPassword'];
            
            return $loadedUser;
        }
        else{
            return null;
        }
    }
    
    
    // Wczytywanie obiektu z bazy danych po LOGIN
    static public function login(mysqli $connection, $email, $password){
        
        // Łączenie się po poprawnosci emaila i hasła
        $loadedUser = self::loadUserByEmail($connection, $email);
        // jeżeli chce odwołać się do statycznego pola klasy to wtedy używamy self::pole.
        if($loadedUser && password_verify($password, $loadedUser->hashedPassword)){
            // password_verify - sprawdza, czy hasło pasuje do hash
            return $loadedUser;
        } 
        else {
            return false;
        }
    }
     
    
    // Usunięcie obiektu 
    public function delete(mysqli $connection){
        if($this->id != -1){
            $sql = "DELETE FROM Users WHERE id = '{$this->id}'";
            
            $result = $connection->query($sql);
            
            if($result == true){
                $this->id = -1; 
                // Jako, że usnęliśmy obiekt to zmieniamy jego id na -1
                return true;
            }
            else{
                return false;
            }
        }
        return true; 
        // Jeżeli obiektu nie było wcześniej w bazie danych to możemy od razu zwrócić true
    }
    
    
}

?>