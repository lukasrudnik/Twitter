<?php
session_start();

require_once 'initial.php';

if(!isset($_SESSION['userId'])){
    header('Location:login.php');
}

$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);
    
//Message::updateMessageRead($connect, $_GET['messageId']);
//$message = Message::loadAllMessageByMesssageId($connect, $_GET['messageId']);
//

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
                <a href="index.php">Main page</a>
            </li>
            <li>
                <a href="user_page.php"> 
                <?php
                    echo $loggedUser->getUsername() 
                ?>
                page</a>    
            </li>
            <li>
                <?php
                    if(isset($_SESSION['userId'])){
                         echo "<a href='logout.php'>Logout</a>";
                    }
                ?> 
            </li>
        </ul>
        Message:
        <?php
//            $messageSender = User::loadUserById($connect, $message->getIdSendera());  
//            echo "otrzymana od: " . $messageSender->getUsername() . '<br>';
//            echo "otrzymana dnia: " . $message->getCreationDate() . '<br>';
//            echo $message->getMessage() . '<br>';
//            
//            echo '<h3>Users lists:</h3>';
//        
//            $allUsers = User::loadAllUsers($connect);
//            
//            foreach ($allUsers as $user){
//                if ($user->getId() != $loggedUserId){
//                    echo $user->getUsername();
//                    echo '<a href="user_page.php?userId=' . $user->getId() . '">Send message</a<br>';
//                }
//            }
        ?>
    </body>
</html>
