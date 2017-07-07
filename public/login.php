<?php
session_start();

require_once '../src/initial.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && 
            isset($_POST['email']) && (strlen(trim($_POST['email'])) >= 6) && 
            isset($_POST['password']) && (strlen(trim($_POST['email'])) >= 6)){
       
    if(strlen(trim($_POST['email'])) && strlen(trim($_POST['password'])) < 6){
        echo ("e-mail and password must be have minimum 6 characters! <br>");
    }
   
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);    
    
    if(!empty($_POST['email']) . $connect->real_escape_string($email) && 
       !empty($_POST['password']) . $connect->real_escape_string($password)){        
        // usuwanie znaków specjalnych jeśli hasło i email nie są puste
        
        $sql = "SELECT * FROM Users WHERE email = '$email'"; 
        $query = $connect->query($sql);
        // połączenie do email w tabeli Users
    
        if(!`mysqli_num_rows` > 0){
            echo "Please check the correctness of contents! <br>";
        }
        // sprawdzenie czy podany email lub hasło są w bazie danych 
        // używam tego do spawdzenia czy email jest w bazie danych
        
        if($query->num_rows > 0){
            $row = $query->fetch_assoc();  
            // pobieranie rzędu w tablicy assocjacyjnej
                                    
            $userPassword = $row['hashed_password'];
            $checkPassword = password_verify($password, $userPassword);
            // sprawdzenie podanego w formularzu hasła z hasłem zapisanym w bazie danych
                      
            if($checkPassword){
                $_SESSION['userId'] = $row['id'];
                header('Location: ../index.php'); 
                // ustawienie sesji i przekierowanie na stronę główną
            }
            else{
                echo ("Wrong e-mail or password, please check password and try again! <br>");
            }   
        }
    }
}

/*
if ($LoggedUser instanceof User){
    $_SESSION['userId'] = $LoggedUser -> getId();
...
(instanceof == ::) odwołanie się do klasy statycznej
}
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
       <link rel="stylesheet" href="css/style.css">
       <link rel="stylesheet"
       href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
       integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
       crossorigin="anonymous">
</head>
<body>
    <style> 
    </style>
    <form method="POST">
        <div class="container">
            <div class="jumbotron">
                <h2>Login to continue...</h2>
            </div>
            <div class="form-group">
               <label for="E-mail">E-mail:</label>
               <input type="email" class="form-control" name="email">
               <br>
               <label for="Password">Password:</label>
               <br>
               <input type="password" class="form-control" name="password">    
               <br>
               <br>
               <input class="btn btn-primary" type="submit" value="Login In">
               <a class="btn btn-info" href="register.php" role="button">
                    Click to move to Register Page</a>
            </div>
       </div>
    </form>
</body>
</html>