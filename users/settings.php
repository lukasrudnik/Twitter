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
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body> 
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header navbar-left">
                <a class="navbar-brand">Hello
                <?php
                    echo $loggedUser->getUsername() . " !"; 
                ?> <!-- powitanie zalogowanego użytkownika -->
                </a> 
                <ul>
                    <a href="../index.php">
                    <?php
                        echo "Main page" 
                    ?> <!-- przekierowanie na stronę główną --> 
                    </a> 
                    <br>
                    <a href="user_page.php">
                    <?php
                        echo $loggedUser->getUsername() 
                    ?> <!-- przekierowanie na stronę --> 
                        page
                    </a>                    
                    <br>
                    <?php 
                        if(isset($_SESSION['userId'])){
                            echo "<a href='logout.php'>Logout</a>";
                        } 
                    ?> <!-- Wylogowanie zalogowanego użytkownika -->   
                </ul> 
            </div>
        </div>
    </nav>
    <div class="container">
    <div class="jumbotron"> 
        <form method="POST">
            <label>
                <h4>Your Name:</h4>
                <?php
                    echo '<b>' . $loggedUser->getUsername() . '</b>';
                ?>
                <br>
                <input type="text" class="form-control" name="username" placeholder="change name here">
            </label>
            <br>
            <label>
                <br><h4>Your E-mail:</h4>
                <?php
                    echo $loggedUser->getEmail();
                ?>
                <br>
                <input type="email" class="form-control" name="email" placeholder="change e-mail here">
            </label>
            <br>
            <label>
                <br><h4>Give a new password:</h4> 
                <input type="password" class="form-control" name='password' 
                       placeholder="change password here">
            </label>
            <br>
            <label>
                <br><h4>Repeat password:</h4>
                <input type='password' class="form-control" name="repeadPassword" 
                       placeholder="repeat password here">
                <br>
                <br>
                <input role="button" class="btn btn-warning" type="submit" value="Change the values">
            </label>
        </form> 
        <br>
        <br>
        <form action="delete.php" method="post">
            <button type="submit" class="btn btn-danger" value="deleteUser">Delete User</button>
        </form> 
        </div>
     </div>   
    </body>
    <footer class="footer">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header navbar-left">
                    <a class="navbar-brand">...</a>
                </div>
            </div>
        </nav>  
    </footer>
</html>