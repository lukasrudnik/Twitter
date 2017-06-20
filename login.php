<?php
session_start();

require_once 'initial.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(isset($_POST['email']) 
        && ($_POST['email']) ? $connect -> real_escape_string(trim($_POST['email'])) : null 
        && strlen(trim($_POST['email'])) >= 6 
        
        && isset($_POST['password']) 
        && ($_POST['password']) ? trim($_POST['password']) : null 
        && strlen(trim($_POST['password'])) >= 6 ){
          
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $user = new User();
        $user = User::loadUserByEmail($connect, $email);
        
        if($user != null){
//            $user = $this->passwordToHash;
//            $user = $this->username;
//            $user = $this->getmail;
            //$user->verifieHashedPassword($password, $getUserPass);

            $_SESSION['userId'] = $user->getId();
            header('Location: index.php');
        } 
        else{
            //header('Location: register.php');
            echo "niepoprawne dane logowania";
       }       
    }
}

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $email = isset($_POST['email']) ? $connect -> real_escape_string(trim($_POST['email'])) : null;
//
//    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
//    
//    if (strlen($email) >= 6 && strlen($password) >= 6) {
//        $LoggedUser = new User();
//        $LoggedUser -> loadUserByEmail($connect, $email);
//        
//        if ($LoggedUser instanceof User) {
//            $_SESSION['userId'] = $LoggedUser -> getId();
//            header('location: index.php');
//        } else {
//            echo "nie dziala" . "<br>";
//        }
//    }
//    $connect -> close();
//    $connect = null;
//}


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
            <input type="text" name="email">
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