<?php
session_start();

require_once 'initial.php';

if(!isset($_SESSION['userId'])){
    header('Location:login.php');
}

// Aktywna sesja użytkownika
$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);


// Sprawdzenie formularza komentrzarza
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newCommentForm']) && 
    strlen(trim($_POST['newComment'])) > 0){
    
    $comment = new Comment();
    $comment->setText($_POST['newComment']);
    $comment->setIdUsera($userSession);
    $comment->setIdPostu($_POST['tweetId']);
    $comment->setCreationDate(date('Y-m-d H:i:s'));
    
    if($comment->saveToDB($connect)){
        echo 'Comment added from user: ' . $loggedUser->getUsername() . '<br>' . 'Comment content: '
            . $_POST['newComment'] . '<br>';
    }
    else{
        echo 'There was a problem adding the comment!';
    }
}


// Sprawdzenie formularza wiadomiośći
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['messageForm']) && 
    strlen(trim($_POST['message'])) > 0){
    
    $message = new Message();
    $message->setMessage($_POST['message']);
    $message->setIdSendera($userSession);
    $message->setIdRecivera($_POST['receiver']);
    $message->setCreationDate(date('Y-m-d H:i:s'));
   
    if($message->saveToDB($connect)){
        echo 'Message sent';
    } else {
        echo 'Error sending message!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <title>User page</title>
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
                <?php
                if(isset($_SESSION['userId'])){
                    echo "<a href='settings.php'>Settings page</a>";
                    // przekierowanie na stronę zmiany danych użytkwnika
                };
                ?>
            </li>
            <li>
                <?php
                if(isset($_SESSION['userId'])){
                    echo "<a href='logout.php'>Logout</a>";
                }
                ?>
            </li>
        </ul>
        <h3><br>Your tweets:</h3>
       <?php
           
        
        // Wyświetlanie ilości tweetów użytkownika tej sesji
        if(isset($_SESSION['userId']) == $loggedUser){      
            $tweets = Tweet::loadTweetByUserId($connect, $userSession);
            
            if(count($tweets) > 0){       
                foreach($tweets as $tweet){          
                    $user = User::loadUserById($connect, $userSession);
                    
                    echo $tweet->getCreationDate() . ' ';
                    echo $user->getUsername() . ' added tweet <br> about content: ';
                    echo $tweet->getText() . '<br>';
                    
                    // Wyświetlanie ilośći komentarzy do danego tweeta
                    $comments = Comment::loadCommentByTweetId($connect, $tweet->getId());
                    $quantityComents = count($comments);
                    
                    if($quantityComents > 0){
                        echo ' Number of comments: '. $quantityComents . '<br>';
                    }
                    
                    // Wyświetlanie treśći komentarzy do danego tweeta
                    foreach ($comments as $comment){
                        $authorCommentId = $comment->getIdUsera();
                        $authorComment = User::loadUserbyId($connect, $authorCommentId);
                        echo '<br> The author of the comment: ' . $authorComment->getUsername() . '<br>';
                        echo 'Created: ' . $comment->getCreationDate() . "<br>";
                        echo 'Content: ' . $comment->getText() . "<br>";
                    }
                    
                    // formularz do wysyłania komentarza
                    echo ('<form method="POST">
                    <input type="hidden" name="newCommentForm" value="addCommentForm">
                    <input type="text" name="newComment">
                    <input type="hidden" name="tweetId" value="' . $tweet->getId() . '">
                    <input role="button" type="submit" value="Add new Comment">
                    </form>') . "<br>";
                    echo '<hr>';
                }
            }
            else{
                echo "You don't have any tweets yet <br>";
            }
        }
        
        
        // Wysyłane i otrzymane wiadomośći
        if(isset($_SESSION['userId'])){
            echo (' Send user a message :
            <form method="POST" >
            <input type="hidden" name="messageForm" value="messageForm">
            <input type="hidden" name="receiver" value="' . ($_SESSION['userId']) . '">
            <input type="text" name="message">
            <input type="submit" value="Send message">
            </form > ');
        }
        else{
            echo '<h3>Received messages:</h3>';
            $messages = Message::loadAllMessagesByIdRecivera($connect, $userSession);
            
            if(count($messages) > 0){
                foreach ($messages as $message){
                    $messageAuthorId = $message->getIdSendera();
                    $messageAuthor = User::loadUserbyId($connect, $messageAuthorId);  
                    
                    echo 'Message received from: ' . $messageAuthor->getUsername();
                    
                    if($message->getMessageRead() == 0){
                        echo 'Unread message';
                    }
                    echo 'Received on: ' . $message->getCreationDate();
                    echo substr($message->getMessage(), 0, 29);
                    echo '<a href="message.php?messageId=' . $message->getId() .
                        '">Read message now</a>';
                }
            }
            else{
                echo "You don't have any messages yet";
            }
            echo '<h3>Sended messages:</h3>';
            
            $messages = Message::loadAllMessagesByUserId($connect, $userSession);
            
            if(count($messages) > 0){
                foreach ($messages as $message){
                    
                    $messageReceiverId = $message->getIdRecivera();
                    $messageReceiver = User::loadUserbyId($connect, $messageReceiverId);
                    echo 'Message sent to: ' . $messageReceiver->getUserame();
                    echo 'Received on: ' . $message->getCreationDate();
                    echo $message->getMessage() . '<br>';
                }
            }
            else{
                echo 'You have not received any messages yet';
            }
        }
        
        
        // Wyświetlanie pozostałych użytkowników
        if(isset($_SESSION['userId']) == $loggedUser){ 
            // != oznacza że są widoczni tylko zalogowani użytkownicy
            echo '<h3>Users lists:</h3>';
            
            $allUsers = User::loadAllUsers($connect);
           
            // Wyświetlenie wszystkich użytkowników w nie w tej sesji
            foreach($allUsers as $user){  
                if($user->getId() != $userSession){
                    echo $user->getUsername() . " ----> ";
                    echo '<a href="user_page.php?userId=' . $user->getId() . '">Send message</a><br>';       
                }
            }
        }
        
        ?>
    </body>
</html>