<?php

require_once 'initial.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){  
    if(isset($_POST['username']) && strlen(trim($_POST['username'])) >= 3 
       && isset($_POST['email']) && strlen(trim($_POST['email'])) >= 6 
       && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6
       
       && isset($_POST['repeadPassword'])
       && trim($_POST['repeadPassword']) === trim($_POST['password'])){
       //Powtórzenie hasła do rejestracji
         
        $user = new User();
        $user->SetUsername(trim($_POST['username']));
        $user->SetEmail(trim($_POST['email']));
        $user->setPasswordToHash(trim($_POST['password']));
                  
        if($user->saveToDB($connect)){
             echo "Udało sie zarejestrowac użytkownika";
        }
        else{
            echo "Niestety nie udało się zarejestrować użytkownika, podany e-mail już istnieje w bazie danch!";
        }
    }
    else{
        echo "Błędne dane w formularzu, sprawdź poprawność i spróbuj ponownie!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
       <meta charset="UTF-8">
       <title>Register Page</title>
    </head>
   <body>
        <form method ='POST'>
            <label>
                User name: 
                <br>
                <input type='text' name='username'>
            </label>
            <br>
            <label>
                E-mail:<br>
                <input type="text" name="email">
            </label>
            <br>
            <label>
                Password: <br>
                <input type="password" name='password'>
            </label>
            <br>
            <label>
                Repeat password: 
                <br>
                <input type='password' name="repeadPassword">          
            </label>
            <br>
            <input type="submit" value="Register new User!">
        </form>
        <a href="login.php">Click to move to Login Page</a>
    </body>
</html>
