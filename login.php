<?php

//session_start();
//
//include_once __DIR__ . '/src/database.php';
//include_once __DIR__ . '/src/User.php';
//
//
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    if (isset($_POST['email']) && strlen(trim($_POST['email'])) >= 6 && 
//        isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6){
//        
//        $email = trim($_POST['email']);
//        $password = trim($_POST['password']);
//        $user = User::login($connection, $email, $password);
//        if ($user) {
//            $_SESSION['userId'] = $user->getId();
//        //    header('Location: index.php'); 
//            echo "logowanie poprawne";
//        } else {
//            echo "niepoprawne dane logowania";
//        }
//    }
//}

?>

<!DOCTYPE html>
<!--
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css"> 
    <title>LoginPage</title>
</head>
<body>
    <nav>
    </nav>
    <main>
        <section class="jumbotron">
            <div class="container loginForm">
            <form method="POST">
                <label>
                    E-mail:<br>
                    <input type="text" name="email">
                </label>
                <br>
                <label>
                    Password:<br>
                    <input type="password" name="password">
                </label>
                <br>
                <input class="btn btn-default" type="submit" value="Login">
            </form>
                <a class="btn btn-primary" href="register.php" role="button">zarejestruj sie </a>
            </div>
        </section>
    </main>
    <footer>
    </footer>
  
</body>
</html>
-->

