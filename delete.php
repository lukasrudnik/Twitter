<?php
session_start();

require_once 'initial.php';

if(!isset($_SESSION['userId'])){
    header('Location:login.php');
}

$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);

// sprawdzenie przesłania formularza postem 
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteUser'])){     
    
    $deleteUser = trim($_POST['deleteUser']);
     
        // pole wyboru usunięcia użytkownika 
        switch ($deleteUser){
            case 'no':
                header("Location: user_page.php");
                break;
            case 'yes':
                echo 'Bye ' . $loggedUser->getUsername() . '!'; 
                // Usunięcie użytkownika z bazy danych
                if ($loggedUser->delete($connect)){
                    header("Location: register.php");
                }
                else{
                    echo 'Something went wrong, please try again! <br>';
                }
                break;
        }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Delete Page</title>
    </head>
    <body> 
        <form action="" method="post" role="form">
               <legend>
                   Are you sure to delete your account 
                   <?php 
                    echo $loggedUser->getUsername() . '?'; 
                   ?>
               </legend>
               <button type="submit" value="yes" name="deleteUser">Yes</button>
               <button type="submit" value="no" name="deleteUser">No</button>  
        </form>       
    </body>
</html>