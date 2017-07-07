<?php
session_start();

require_once '../src/initial.php';

if(!isset($_SESSION['userId'])){
    header('Location:login.php');
}

$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);
    
// przesłane formularzem $_GET messageId
Message::updateMessageRead($connect, $_GET['messageId']);
$message = Message::loadAllMessageByMesssageId($connect, $_GET['messageId']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message Page</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>  
<body>
    <div class="container">
    <div class="jumbotron"> 
        <?php
//            echo $loggedUser->getUsername() . "!"; 
        ?>
<!--
        <ul>
            <li>
                <a href="index.php">Main page</a>
            </li>
            <li>
                <a href="user_page.php"> 
-->
                <?php
//                    echo $loggedUser->getUsername() 
                ?>
<!--
                page</a>    
            </li>
            <li>
-->
                <?php
//                    if(isset($_SESSION['userId'])){
//                         echo "<a href='logout.php'>Logout</a>";
//                    }
                ?> 
<!--
            </li>
        </ul>
        <hr>
-->
        <?php    
        $messageId = '';
        
        if($_GET["messageId"]){  
            $messageId = ($_GET['messageId']);
            echo '<span class="glyphicon glyphicon-ok"></span> <br><br> Messages status changed !<br>';
        }       
        /*
        // Odbieranie i wyświetlanie wiadomości 
        $senderMessage = User::loadUserById($connect, $message->getIdSendera());  
        echo "otrzymana od: " . $senderMessage->getUsername() . '<br>';
        echo "otrzymana dnia: " . $message->getCreationDate() . '<br>';
        echo $message->getText() . '<br>'
        */
        ?>
        <br> 
        <form action="user_page.php" method="post">
        <button type="submit" class="btn btn-info" value="backSubmit">back</button>
        </form>
    </div>
    </div> 
</body>
</html>