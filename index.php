<?php
session_start();

require_once 'src/initial.php';

/*
include_once __DIR__ . '/src/user.php';
// __DIR__ . dodaje z dodatkowymi odniesieniami elementu dodawanego
*/

if(!isset($_SESSION['userId'])){
    header('Location: public/login.php');
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
                    <a href="users/user_page.php">
                    <?php
                        echo $loggedUser->getUsername() 
                    ?> <!-- przekierowanie na stronę --> 
                        page
                    </a>                    
                    <br>
                    <?php 
                        if(isset($_SESSION['userId'])){
                            echo "<a href='users/logout.php'>Logout</a>";
                        } 
                    ?> <!-- Wylogowanie zalogowanego użytkownika -->   
                </ul> 
            </div>
        </div>
    </nav>   
    <div class="container">
    <div class="jumbotron">     
        <h3>Add new Tweet:</h3>
        <br>
        <form action="#" method="POST">
            <input type="text" name="newTweet" class="form-control" placeholder="only 140 characters">
            <input type="hidden" name="newTweetForm" value="newTweetForm">
            <input role="button" class="btn btn-success" type="submit" value="Add new Tweet">
        </form>   
    </div>
    </div>
    <div class="container">
    <div class="jumbotron">      
        <h3>Users lists:</h3>  
        <br>    
        <?php     
        /* Dodawanie wszystich pozostałych użytkowników z bazy danych.
        Połączenie z loadAllUsers w klasie User*/
        $allUsers = User::loadAllUsers($connect);
        foreach($allUsers as $user){
            
            // Wyświetlenie wszystkich użytkowników w nie w tej sesji
            if($user->getId() != $userSession){
                echo '<b>' . $user->getUsername() . '</b>';
        ?>
        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
        <?php
        // możliwość wysłania do nich wiadomości GETem i przesłanie nim messageId
                echo ('<a href="users/user_page.php?userId=' . $user->getId() .
                      '"><button class="btn btn-info type="submit" value="changeStatusMessage">
                      Send message</button></a>'); 
            }
        }
        ?>
    </div>
    </div>
    <div class="container">
    <div class="jumbotron">			
        <?php          
        // Wyświetlanie Tweetów - połączenie z loadAllTweets w klasie Tweet
        echo ('<h3>Tweets lists:</h3><br>');

        $tweets = Tweet::loadAllTweets($connect);      
        foreach($tweets as $tweet){            
            // Pobieranie ID autora tweeta tweeta z klasy Tweet
            $authorTweetId = $tweet->getUserId();  
            // Autor tweeta pobrany z klasy User z loadUserbyId oraz z getUserId z klasy Tweet
            // User id jest kluczem nadrzędnym id z klasy Tweet
            $authorTweet = User::loadUserbyId($connect, $authorTweetId);    
            
            echo '<b>' . $authorTweet->getUsername() . '</b> added tweet at time: <b>' . 
                 $tweet->getCreationDate() . '</b><br><br><b> Content: </b>' . 
                 $tweet->getText() . ' <br><br><br><h4> Comments: </h4>';
            

            //Wyświetlanie Komentarzy Połączenie z loadCommentsByTweetId w klasie Comment
            $comments = Comment::loadCommentByTweetId($connect, $tweet->getId());
            foreach($comments as $comment){
                // Pobieranie ID autora komentarza z klasy Comment
                $authorCommentId = $comment->getIdUsera();
                // Autor komentarza pobrany z klasy User z loadUserbyId oraz z getIdUsera z klasy Comment
                // User id jest kluczem nadrzędnym id z klasy Comment
                $authorComment = User::loadUserbyId($connect, $authorCommentId);
                
                echo '<br><b>' . $authorComment->getUsername() . '</b> added comment at time: <b>' . $comment->getCreationDate() . '</b><br>';
                echo '<b>Content: </b>' . $comment->getText() . '<br>';
            }
            
            // Dodatanie komentarza do tweeta
            echo (' <br> <form method="POST">
            <input type="hidden" name="newCommentForm" value="newCommentForm">
            <input type="text" class="form-control" name="newComment" placeholder="only 60 characters">
            <input type="hidden" name="tweetId" value="' . $tweet->getId() . '"> 
            <input role="button" class="btn btn-success" type="submit" value="Add new Comment">
            </form>' . '<br><hr>');
        }   
        ?>
    </div>
    </div>             
</body>
<footer class="footer">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header navbar-left">
                <a class="navbar-brand"></a>
            </div>
        </div>
    </nav>  
</footer>
</html>