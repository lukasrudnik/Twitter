<?php
session_start();

require_once 'initial.php';

/*
include_once __DIR__ . '/src/user.php';
// __DIR__ . dodaje z dodatkowymi odniesieniami elementu dodawanego
*/

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}

// Dodanie sesji użytkownika i połączenie z loadUserById w klasie User
$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);


// Sprawdzenie połączenia dodania tweeta metodą POST
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newTweetForm']) && 
    strlen(trim($_POST['newTweet'])) > 0){

    $tweet = new Tweet();
    $tweet->setText($_POST['newTweet']);
    $tweet->setUserId($userSession);
    $tweet->setCreationDate(date('Y-m-d H:i:s'));
    
    if($tweet->saveToDB($connect)){
        echo 'Tweet added from user: ' . $loggedUser->getUsername() . '<br>' . 'Tweet content: '
            . $_POST['newTweet'] . '<br>';
    }
    else{
        echo 'There was a problem adding the tweet!';
    }
}

// Sprawdzenie połączenia dodania komentarza metodą POST
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newCommentForm']) && 
    strlen(trim($_POST['newComment'])) > 0){
    
    $comment = new Comment();
    $comment->setText($_POST['newComment']);
    $comment->setIdUsera($userSession);
    $comment->setIdPostu($_POST['tweetId']); // id Tweeta pobrane z $tweet->getID() 
    $comment->setCreationDate(date('Y-m-d H:i:s'));
    
    if($comment->saveToDB($connect)){
        echo 'Comment added from user: ' . $loggedUser->getUsername() . '<br>' . 'Comment content: ' 
            . $_POST['newComment'] . '<br>';
    }
    else{
        echo 'There was a problem adding the comment!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Main Page</title>
    </head>
    <body>
        Welcome:
        <?php
            echo $loggedUser->getUsername() . "!"; 
        ?>
        <!-- powitanie zalogowanego użytkownika -->
        <ul>
            <li>
                <a href="user_page.php"> 
                <?php
                    echo $loggedUser->getUsername() 
                ?>
                page</a>    
                <!-- przekierowanie na stronę --> 
            </li>
            <li>
                <?php 
                    if(isset($_SESSION['userId'])){
                        echo "<a href='logout.php'>Logout</a>";
                    } 
                ?>
                <!-- Wylogowanie zalogowanego użytkownika -->
            </li>
        </ul>        
        <h3><br>Add new Tweet:</h3>
        <form action="#" method="POST">
            <input type="text" name="newTweet">
            <input type="hidden" name="newTweetForm" value="newTweetForm">
            <input role="button" class="btn btn-default" type="submit" value="Add new Tweet">
        </form>          
        <h3><br>Users lists:</h3>
        
        <?php
        
        // Dodawanie wszystich pozostałych użytkowników z bazy danych
        // Połączenie z loadAllUsers w klasie User
        $allUsers = User::loadAllUsers($connect);
        foreach($allUsers as $user){
            
            // Wyświetlenie wszystkich użytkowników w nie w tej sesji
            if($user->getId() != $userSession){
                echo $user->getUsername() . " ----> ";
                // możliwość wysłania do nich wiadomości GETem i przesłanie nim messageId
                echo '<a href="user_page.php?userId=' . $user->getId() . '">Send message</a><br>';       
            }
        }
        
        
        echo ('<h3><br>Tweets lists:</h3>');
          
        // Wyświetlanie Tweetów - połączenie z loadAllTweets w klasie Tweet
        $tweets = Tweet::loadAllTweets($connect);      
        foreach($tweets as $tweet){      
            // Pobieranie ID autora tweeta tweeta z klasy Tweet
            $authorTweetId = $tweet->getUserId();
            // Autor tweeta pobrany z klasy User z loadUserbyId oraz z getUserId z klasy Tweet
            // User id jest kluczem nadrzędnym id z klasy Tweet
            $authorTweet = User::loadUserbyId($connect, $authorTweetId);     
            echo $authorTweet->getUsername() . ' added tweet <br> at time: ' . ' ';
            echo $tweet->getCreationDate() . '<br>';
            echo 'Content: ' . $tweet->getText() . '<br>';
            

            //Wyświetlanie Komentarzy Połączenie z loadCommentsByTweetId w klasie Comment
            $comments = Comment::loadCommentByTweetId($connect, $tweet->getId());
            foreach($comments as $comment){
                // Pobieranie ID autora komentarza z klasy Comment
                $authorCommentId = $comment->getIdUsera();
                // Autor komentarza pobrany z klasy User z loadUserbyId oraz z getIdUsera z klasy Comment
                // User id jest kluczem nadrzędnym id z klasy Comment
                $authorComment = User::loadUserbyId($connect, $authorCommentId);
                echo '<br>' . $authorComment->getUsername() . ' added comment <br> at time: ' . ' ';
                echo $comment->getCreationDate() . '<br>';
                echo 'Content: ' . $comment->getText() . '<br>';
            }
            
            
            // Dodatanie komentarza do tweeta
            echo ('
            <form method="POST">
            <input type="hidden" name="newCommentForm" value="newCommentForm">
            <input type="text" name="newComment">
            <input type="hidden" name="tweetId" value="' . $tweet->getID() . '"> 
            <input role="button" type="submit" value="Add new Comment">
            </form>') . "<br><br>";
        }
        
        ?>
    </body>
</html>
