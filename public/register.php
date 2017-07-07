<?php
require_once '../src/initial.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){  
    if(isset($_POST['username']) && strlen(trim($_POST['username'])) >= 3 
       && isset($_POST['email']) && strlen(trim($_POST['email'])) >= 6 
       && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6
       
       && isset($_POST['repeadPassword'])
       && trim($_POST['repeadPassword']) === trim($_POST['password'])){
       //Powtórzenie hasła do rejestracji
        
        if(!empty($_POST['email']) . $connect->real_escape_string($_POST['email']) && 
           !empty($_POST['password']) . $connect->real_escape_string($_POST['password'])){        
            // usuwanie znaków specjalnych jeśli hasło i email nie są puste
            
            $user = new User();
            $user->setUsername(trim($_POST['username']));
            $user->setEmail(trim($_POST['email']));
            $user->setHashedPassword(trim($_POST['password']));
                  
            if($user->saveToDB($connect)){
                echo ("Successfully registered new user!");
            }
            else{
                echo ("Unfortunately, we failed to register the new user, 
            the given email already exists in the database, please change your e-mail!");
            }
        }
        else{
            echo ("Incorrect data in form, validate and try again!");
        }   
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
       <meta charset="UTF-8">
       <title>Register Page</title>
           <link rel="stylesheet" type="text/css" href="css/style.css">
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
           integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body> 
        <style> 
        </style>
        <form method ='POST'>
           <div class="container">
            <div class="jumbotron">
                <h2>Please register first ;)</h2>
            </div>
               <div class="form-group">
                    <label for="Username">Username:</label>
                    <input type="text" class="form-control" name="username">
                    <br>
                    <label for="E-mail">E-mail:</label>
                    <input type="email" class="form-control" name="email">
                    <br>
                    <label for="Password">Password:</label>
                    <input type="password" class="form-control" name="password">
                    <br>
                    <label for="Repeat_password">Repeat password:</label>
                    <input type="password" class="form-control" name="repeadPassword">
                    <br>
                </div>
                <div class="form-group">
                    <br>
                    <input class="btn btn-success" type="submit" value="Register new User!">
                    <a class="btn btn-info" href="login.php">
                        Click to move to Login Page</a>
                </div>
            </div>
        </form>    
    </body>
</html>