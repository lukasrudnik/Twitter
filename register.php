<?php

include_once __DIR__ . '/src/database.php';
include_once __DIR__ . '/src/User.php';

/*
spl_autoload_register(function($class){
    require_once "src/{$class}.php";
});
*/

if($_SERVER['REQUEST_METHOD'] == 'POST'){  
    if(isset($_POST['username']) && strlen(trim($_POST['username'])) >= 3 
       && isset($_POST['email']) && strlen(trim($_POST['email'])) >= 6 
       && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6
       
       && isset($_POST['repeadPassword'])
       && trim($_POST['repeadPassword']) === trim($_POST['password'])){
       //Powtórzenie hasła do rejestracji
         
        $user = new User();
        $user->setUsername(trim($_POST['username']));
        $user->setEmail(trim($_POST['email']));
        $user->setPassword(trim($_POST['password']));
//        $userSaved = $user->saveToDB($conn);
                  
        if($user->saveToDB($connect)){
            echo "Użytkownik został zarejestrowany!";
//            header('Location: index.php');
        }
        else{
            echo "Niestety nie udało się zarejestrować użytkownika!";
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
                      <title>Strona rejestracji nowego użytkownika.</title>
                       <meta charset="UTF-8">
                        </head>
                         <body>
                          <form method ='POST'>
                           <label>
                            Imię: <br>
                            <input type='text' name='username'>
                        </label>
                        <br>
                        <label>
                            E-mail:<br>
                            <input type="text" name="email">
                        </label>
                        <br>
                        <label>
                            Hasło: <br>
                            <input type="password" name='password'>
                        </label>
                        <br>
                        <label>
                           Powtórz hasło: <br>
                            <input type='password' name="repeadPassword"><br> 
                            <input class="btn btn-default" type="submit" value="Zarejestruj!"> 
                         <!--    <a href="login.php">strona logowania</a> -->
                         </label>
                         </form>
                         </body>
                         </html>
