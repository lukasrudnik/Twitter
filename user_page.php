<?php
session_start();

require_once 'initial.php';

if(!isset($_SESSION['userId'])){
    header('Location:login.php');
}

$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);


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


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['messageForm']) && 
    strlen(trim($_POST['message'])) > 0){
    
    $message = new Message();
    $message->setMessage($_POST['message']);
    $message->setIdSendera($userSession);
    $message->setIdRecivera($_POST['receiver']);
    $message->setCreationDate(date('Y-m-d H:i:s'));
   
    if($message->saveToDB($connect)){
        echo 'Wysłano wiadomość';
    } else {
        echo 'błąd wysyłania wiadomości';
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
        <ul>
            <li>
                Welcome: 
                <?php
                echo $loggedUser->getUsername() . "!";
                ?>
            </li>
            <li>
                <a href="index.php">Tweet page</a>
            </li>
             <li>
                <?php
                if(isset($_SESSION['userId'])){
                    echo "<a href='settings.php'>Settings page</a>";
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
        <?php
        
        // Wyświetlanie ilości tweetów użytkownika tej sesji i komentarzy do nich 
        if(isset($_SESSION['userId'])){
            
            $tweets = Tweet::loadTweetByUserId($connect, $userSession);
            
            if(count($tweets) > 0){       
                foreach($tweets as $tweet){
                    $user = User::loadUserById($connect, $userSession);
                    echo $user->getUsername();
                    echo $tweet->getCreationDate();
                    echo $tweet->getText();
                    
//                    $comments = Comment::loadCommentByTweetId($connect, $tweet->getId());
//                    $numberOfComments = count($comments);
                    
//                    if($numberOfComments > 0){
//                        echo 'Ilość komentarzy: '. $numberOfComments;
//                    }
                    
//                    foreach($comments as $comment){
//                        $commentAuthorId = $comment->getIdUsera();
//                        $commentAuthor = User::loadUserById($connect, $commentAuthorId);
//                        echo 'Autor komentarza: ' . $commentAuthor->getUsername();
//                        echo 'Utworzony: ' . $comment->getCreationDate();
//                        echo $comment->getText();
   
//                    echo '<form method="POST" >
//                    <input type="hidden" name="newCommentForm" value="newCommentForm">
//                    <input type="text" name="newComment">
//                    <input type="hidden" name="tweetId" value="' . $tweet->getId() . '"> <br> 
//                    <input role="button" class="btn btn-warning" type="submit" value="Dodaj komentarz">
//                    </form>
//                    </div>';
                }
            }
            else{
                echo 'nie masz jeszcze żadnych tweetów';
            }
        }

//       }
        ?>
            
         <?php
//                    if(isset($_SESSION['userId']) != $userSession) {
//                        echo 'Wyślij użytkownikowi wiadomość :
//                                    <form method="POST" >
//                                        <input type="hidden" name="messageForm" value="messageForm">
//                                        <input type="hidden" name="receiver" value="' . $_GET['userId'] . '">
//                                        <input type="text" name="message">
//                                        <input type="submit" value="Wyslij wiadomość">
//                                    </form >
//                                    ';
//                    } else {
//                        echo '<h3>Otrzymane Wiadomości:</h3>';
//                        $messages = Message::loadAllMessagesByIdRecivera($connect, $userSession);
//                        if(count($messages) > 0) {
//                            foreach ($messages as $message) {
//                                $messageAuthorId = $message->getSenderId();
//                                $messageAuthor = User::loadUserbyId($conn, $messageAuthorId);
//                                echo '<div class="messageAuthor">wiadomośc otrzymana od: ' . $messageAuthor->getName() .
//                                    '</div>';
//                                if ($message->getMessageRead() == 0) {
//                                    echo "Wiadomość nieprzeczytana";
//                                }
//                                echo '<div class="messageDate"> otrzymana dnia: ' . $message->getCreationDate() . '</div>';
//                                echo '<div class="messageText"> ' . substr($message->getMessage(), 0, 29) . '</div>';
//                                echo '<a href="message.php?messageId=' . $message->getId() . '">przeczytaj wiadomość</a>';
//                            };
//                        } else {
//                            echo "nie masz wiadomosci";
//                        }
//                        echo '<h3>Wysłane Wiadomości:</h3>';
//                        $messages = Message::loadAllMessagesByUserId($connect, $userSession);
//                        if(count($messages) > 0) {
//                            foreach ($messages as $message) {
//                                $messageReceiverId = $message->getReceiverId();
//                                $messageReceiver = User::loadUserbyId($conn, $messageReceiverId);
//                                echo '<div class="messageAuthor">wiadomośc wysłana do: ' . $messageReceiver->getName() .
//                                    '</div>';
//                                echo '<div class="messageDate"> wysłana dnia: ' . $message->getCreationDate() . '</div>';
//                                echo '<div class="messageText"> ' . $message->getMessage() . '</div><br>';
//                            };
//                        } else {
//                            echo "nie wyslales jeszcze zadnej wiadomosci";
//                        }
//                    }
//    
//        
//            if(isset($_SESSION['userId']) != $userSession) {
//            echo '<div class="col-md-offset-6 col-md-2">';
//        } else {
//            echo '<div class="col-md-2">';
//        }
        ?>

         
                    <h3>lista użytkowników:</h3>
                    <?php
                    $allUsers = User::loadAllUsers($connect);
                    foreach ($allUsers as $user) {
                        if ($user->getId() != $userSession) {
                            echo '<div class="showUser">' . $user->getUsername();
                            echo ' <a href="notice.php?userId=' . $user->getId() . '">Send message</a></div><br>';
                        }
                    }
                    ?>

    </body>
</html>
