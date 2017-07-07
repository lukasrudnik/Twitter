<?php
session_start();

require_once '../src/initial.php';

if(!isset($_SESSION['userId'])){
    header('Location: ../public/login.php');
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
    strlen(trim($_POST['newMessage'])) > 0){
       
    $message = new Message();
    $message->setText($_POST['newMessage']);
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
                    <?php
                        // przekierowanie na stronę zmiany danych użytkwnika
                         if(isset($_SESSION['userId'])){              
                             echo "<a href='settings.php'>Settings page</a> <br>";
                         }
                        
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
    <h3>Your tweets:</h3>
    <br>
<?php
// Wyświetlanie tweetów użytkownika tej sesji
$tweets = Tweet::loadTweetByUserId($connect, $userSession);
            
if(count($tweets) > 0){       
    foreach($tweets as $tweet){          
        $user = User::loadUserById($connect, $userSession);
        
        echo '<b>' . $tweet->getCreationDate() . ' ';
        echo $user->getUsername() . '</b> added tweet. <br><b> Content: </b>';
        echo $tweet->getText() . '<br><br>';
                    
        // Wyświetlanie ilośći komentarzy do danego tweeta
        $comments = Comment::loadCommentByTweetId($connect, $tweet->getId());
        $quantityComents = count($comments);
        
        if($quantityComents > 0){
            echo ' <b> Number of comments: </b>'. $quantityComents . '<br><br><br><h4> Comments: </h4><br>';
        }
        
        // Wyświetlanie treśći komentarzy do danego tweeta
        foreach ($comments as $comment){
            $authorCommentId = $comment->getIdUsera();
            $authorComment = User::loadUserbyId($connect, $authorCommentId);
            
            echo '<b> Author of the comment: </b>' . $authorComment->getUsername() . '<br>';
            echo '<b> Created: </b>' . $comment->getCreationDate() . "<br>";
            echo '<b> Content: </b>' . $comment->getText() . "<br><br>";
        }
                    
        // formularz do wysyłania komentarza
        echo ('<form method="POST">
              <input type="hidden" name="newCommentForm" value="newCommentForm">
              <input type="text" class="form-control" name="newComment" placeholder="only 60 characters">
              <input type="hidden" name="tweetId" value="' . $tweet->getId() . '">
              <input type="submit" class="btn btn-success" value="Add new Comment">
              </form>' . "<br><hr>");
    }
}
else{
    echo "You don't have any tweets yet <br>";
}
?>
    </div>
    </div>
    <div class="container">
    <div class="jumbotron">
<?php
// Wyświetlanie pozostałych użytkowników
echo '<h3>Users lists:</h3>' . '<br>';
  
// Wyświetlenie wszystkich użytkowników w nie w tej sesji
$allUsers = User::loadAllUsers($connect);    
foreach($allUsers as $user){
    
    // != oznacza że są widoczni użytkownicy nie tej sesji
    if($user->getId() != $userSession){
        
        // formularz do wysyłania wiadomości
        echo '<b>' . $user->getUsername() . '</b><br>'; 
        echo ('Send message to this user <br><br>
              <form method="POST">
              <input type="hidden" name="messageForm" value="messageForm">
              <input type="text" class="form-control" name="newMessage" 
              placeholder="only 255 characters"> <input type="hidden" name="receiver" value="' 
              . $user->getId() . '"> <input type="submit" class="btn btn-success" value="Send message">
              </form >' . "<br>");
    }
}

// Otrzymane wiadomośći
echo '<br> <h3>Received messages:</h3> <br>';
$messages = Message::loadAllMessagesByIdRecivera($connect, $userSession); // wyświetlanie po ID odbiorcy
            
if(count($messages) > 0){
    
    foreach($messages as $message){  
        // Ustawienie ID nadawcy wiadomości
        $authorMessageId = $message->getIdSendera();
        // Przypisanie ID nadawcy wiadomości do ID Username
        $authorMessage = User::loadUserbyId($connect, $authorMessageId);        
        
        // Wyświetla komuniakt jeśli jest nieprzeczytana wiadomość 
        if($message->getMessageRead() == 0){
            echo '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>' . 
                 '<u><h4><font color= "red">Unread message!</font></h4></u>';
        }
        
        // wyświetlanie Username nadawcy wiadomości po tym ID przypisanym wyżej            
        echo '<b> Message received from: </b>' . $authorMessage->getUsername() . '<br>';        
        echo '<b>Received on: </b>' . $message->getCreationDate() . '<br>';
        // wyświetla do 10 znaków wiadomości w przypadku - substr($message->getText(), 0, 10)
        echo ('<b> Content: </b>' . substr($message->getText(), 0, 255) . '<br><br>' .
              '<a href="notice.php?messageId=' . $message->getId() .
              '"><button type="submit" class="btn btn-info" value="changeStatusMessage">
              Change message to status readed</button></a>' . '<br><br><br>');    
        
        /*
        // formularz do zmiany statusu wiadomości metodą POST
        echo ('<form method="POST">
        <input type="hidden" name="messageId" value="messageId">
        <input type="submit" value="Change message to status readed">
        </form > ') . "<br>";
        */  
        
    }
}
else{
    echo "You don't have any messages yet";
}
        
/*
// Zmiana statusu wiadomości na przeczytany
$messageId = '';
     
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['messageId'])){
    
    Message::updateMessageRead($connect, $_POST['messageId']);
    $message = Message::loadAllMessageByMesssageId($connect, $_POST['messageId']);
      
    echo "Messages status changed";
}
*/
        
// Wysłane wiadomośći 
echo '<h3>Sended messages:</h3><br>';    
$messages = Message::loadAllMassagesBySenderId($connect, $userSession); // wyświetlanie po ID nadawcy

if(count($messages) > 0){
    
    foreach($messages as $message){ 
        // Ustawienie ID osoby odbierającej wiadomość
        $reciverMessageId = $message->getIdRecivera();
        // Przypisanie ID odbiorcy wiadomości do ID Username
        $reciverMessage = User::loadUserById($connect, $reciverMessageId); 
        // Wyświetlanie Username odbiorcy po tym ID przypisanym wyżej            
        echo '<b> Message sent to: </b>' . $reciverMessage->getUsername() . "<br>";  
        echo '<b> Sended on: </b>' . $message->getCreationDate() . '<br>';
        echo '<b> Content: </b>' . $message->getText() . '<br><br>';
    }
}
else{
    echo 'You have not send any messages yet';
}
?>
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