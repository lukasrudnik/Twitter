<?php
session_start();

require_once 'initial.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && 
            isset($_POST['email']) && (strlen(trim($_POST['email'])) >= 6) && 
            isset($_POST['password']) && (strlen(trim($_POST['email'])) >= 6)){
       
    if (strlen(trim($_POST['email'])) && strlen(trim($_POST['password'])) < 6){
        echo ("e-mail and password must be have minimum 6 characters! <br>");
    }
   
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);    
    
    if(!empty($_POST['email']) . $connect->real_escape_string($email) && 
       !empty($_POST['password']) . $connect->real_escape_string($email)){
        // usuwanie znaków specjalnych jeśli hasło i email nie są puste
        
        $sql = "SELECT * FROM Users WHERE email = '$email'"; 
        $query = $connect->query($sql);
        // połączenie do email w tabeli Users
        
        if($query->num_rows > 0){
            $row = $query->fetch_assoc();  
            // pobieranie rzędu w tablicy assocjacyjnej
            
            $userPassword = $row['hashed_password'];
            $checkPassword = password_verify($password, $userPassword);
            // sprawdzenie podanego w formularzu hasła z hasłem zapisanym w bazie danych
                      
            if($checkPassword){
                $_SESSION['userId'] = $row['id'];
                header('Location: index.php'); 
                // ustawienie sesji i przekierowanie na stronę główną
            }
            else{
                echo ("Wrong e-mail or password, please check password and try again!");
            }
        }
        
//        $sql = "SELECT * FROM Users WHERE username = '$username'"; 
//        $query = $connect->query($sql);
//        
//        if($query->num_rows > 0){
//            $row = $query->fetch_assoc();
//            
//            $username = $row['username'];
//            
//            if(!$userName){
//                echo (";(");
//            }       
//        }
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
</head>
<body>
    <form method="POST">
        <label>
            E-mail:
            <br>
            <input type="email" name="email">
        </label>
        <br>
        <label>
            Password:
            <br>
            <input type="password" name="password">
        </label>
        <br>
        <input class="btn btn-default" type="submit" value="Login In">
    </form>
    <a class="btn btn-primary" href="register.php" role="button">Click to move to Register Page</a>
</body>
</html>