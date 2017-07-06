<?php
session_start();

require_once '../src/initial.php';

if(!isset($_SESSION['userId'])){
    header('Location: ../public/login.php');
}

// Pobranie ID zalogowanego użytkownika z klasy User poprzez User::loadUserById (funkcja statyczna)
// Ustawienie sesji tego użytkownika
$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(isset($_POST['username']) && strlen(trim($_POST['username'])) >= 3
       && isset($_POST['email'] ) && strlen(trim($_POST['email'])) >= 6
       && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6
      
       && isset($_POST['repeadPassword'])
       && trim($_POST['repeadPassword']) === trim($_POST['password'])){
         
        // zabezpieczeine przed mysql injection
        if(!empty($_POST['email']) . $connect->real_escape_string($_POST['email']) && 
           !empty($_POST['password']) . $connect->real_escape_string($_POST['password']) &&
           !empty($_POST['username']) . $connect->real_escape_string($_POST['username'])){
      
            // Zmiana dnych użytkownka
            $loggedUser->setUsername(trim($_POST['username']));
            $loggedUser->setEmail(trim($_POST['email']));
            $loggedUser->setHashedPassword(trim($_POST['password']));
            $loggedUser->saveToDB($connect);
            
            echo 'Data corrected correctly! Your new username is: ' . $_POST['username'] . '<br>';
        }
        else{
            echo 'The given passwords are not identical, data is were not corrected! <br>';
        }
    }
    else{
        echo 'Invalid data provided! <br>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Settings Page</title>
    </head>
    <body> 
        Welcome:
        <?php 
            echo $loggedUser->getUsername() . "!"; 
        ?>
        <ul>
            <li>
                <a href="../index.php">Main page</a>
            </li>
            <li>
                <a href="user_page.php"> 
                <?php
                    echo $loggedUser->getUsername() 
                ?>
                page </a>    
            </li>
            <li>
                <?php
                    if(isset($_SESSION['userId'])){
                        echo "<a href='logout.php'>Logout</a>";
                    }
                ?>
            </li>
        </ul>
        <hr>
        <form method="POST">
            <label>
                Your Name:
                <?php
                    echo $loggedUser->getUsername();
                ?>
                <br>
                <input type="text" name="username" placeholder="change name here">
            </label>
            <br>
            <label>
                Your E-mail:
                <?php
                    echo $loggedUser->getEmail();
                ?>
                <br>
                <input type="email" name="email" placeholder="change e-mail here">
            </label>
            <br>
            <label>
                Give a new password: 
                <br>
                <input type="password" name='password' placeholder="change password here">
            </label>
            <br>
            <label>
                Repeat password:
                <br>
                <input type='password' name="repeadPassword" placeholder="repeat password here">
                <br>
                <input role="button" type="submit" value="Change the values">
            </label>
        </form> 
        <br>
        <form action="delete.php" method="post">
            <button type="submit" value="deleteUser">Delete User</button>
        </form>    
    </body>
</html>
