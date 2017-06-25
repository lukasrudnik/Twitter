<?php
session_start();

require_once 'initial.php';

//include_once __DIR__ . '/src/user.php';
//include_once __DIR__ . '/src/Tweet.php';

if (!isset($_SESSION['userId'])){
    header('Location: login.php');
}

$userSession = $_SESSION['userId'];
$loggedUser = User::loadUserById($connect, $userSession);
// dodanie sesji użytkownika i połączenie z loadUserById w klasie User

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addTweetForm']) && strlen(trim($_POST['addTweet'])) > 0) {
    $tweet = new Tweet();
    $tweet->setText($_POST['addTweet']);
    $tweet->setUserId($loggedUserId);
    $tweet->setCreationDate(date('Y-m-d-h:i:s'));
    if ($tweet->saveToDB($conn)) {
        echo 'Dodano Tweeta ' . $_POST['addTweet'] . "<br>";
    } else {
        echo 'wystapil problem z dodawaniem tweeta';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCommentForm']) && strlen(trim($_POST['addComment'])) > 0) {
    $comment = new Comment ();
    $comment->setText($_POST['addComment']);
    $comment->setId_usera($loggedUserId);
    $comment->setId_postu($_POST['tweetId']);
    $comment->setCreationDate(date('Y-m-d-h:i:s'));
    if ($comment->saveToDB($conn)) {
        echo 'Dodano komentarz ' . $_POST['addComment'] . '<br>';
    } else {
        echo 'wystapil blad z dodawaniem komentarza';
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
        <nav class="navbar navbar-fixed-top">
            <ul>
                <li>
                    <a href="index.php">strona z Tweetami</a>
                </li>
                <li>
                    <?php // echo '<a href="userPage.php?userId=' . $loggedUser->getId() . '">Twoja Tablica</a>' ?>
                </li>
                <li>
                    <?php
                    if (isset($_SESSION['userId'])) {
                        echo "<a href='logout.php'>logout</a>";
                    }
                    ?>
                </li>
                <li style="float: right;">
                    Witaj <?php echo $loggedUser->getUsername(); ?>
                </li>
                <div style="clear: both;"></div>
            </ul>
        </nav>
        <main>
            <div class="row">
                <div class="col-md-offset-2 col-md-8 bg-success columnSection">
                    <section class="addTweetForm text-center">
                        <h3>Dodaj Tweeta:</h3>
                        <form action="#" method="POST">
                            <input type="text" name="addTweet">
                            <input type="hidden" name="addTweetForm" value="addTweetForm">
                            <input role="button" class="btn btn-default" type="submit" value="Dodaj Tweeta">
                        </form>
                    </section>
                </div>
                <div class="col-md-2">
                    <section class="allUsers class-fixed">
                        <h3>lista użytkowników:</h3>
                        <?php
                        $allUsers = User::loadAllUsers($conn);
                        foreach ($allUsers as $user) {
                            if ($user->getId() != $loggedUserId) {
                                echo '<div class="showUser" >' . $user->getName();
                                echo ' <a href="userPage.php?userId=' . $user->getId() . '">Wyślij wiadomość</a></div><br>';
                            }
                        }
                        ?>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-2 col-md-8 col-md-offset-2 bg-success columnSection">
                    <section class="tweetTable">
<!--
                        <?php
                        $tweets = Tweet::loadAllTweets($conn);
                        foreach ($tweets as $tweet) {
                            $authorId = $tweet->getUserId();
                            $author = User::loadUserbyId($conn, $authorId);
                            echo '<div class="tweetAuthor">Autor: ' . $author->getName() . '</div>';
                            echo '<div class="tweetDate"> Czas dodania: ' . $tweet->getCreationDate() . '</div>';
                            echo '<div class="tweetText">' . $tweet->getText() . '</div>';
                            echo '<div class="tweetComment">';
                            $comments = Comment::loadCommentsByTweetID($conn, $tweet->getId());
                            foreach ($comments as $comment) {
                                $commentAuthorId = $comment->getId_usera();
                                $commentAuthor = User::loadUserbyId($conn, $commentAuthorId);
                                echo '<div class="commentAuthor">Autor komentarza: ' . $commentAuthor->getName() . '</div>';
                                echo '<div class="commentDate"> Utworzony: ' . $comment->getCreationDate() . '</div>';
                                echo '<div class="commentText">' . $comment->getText() . '</div>';
                            };
                            echo '</div>';
                            echo '<form method="POST" >
                                    <input type="hidden" name="addCommentForm" value="addCommentForm">
                                    <input type="text" name="addComment">
                                    <input type="hidden" name="tweetId" value="' . $tweet->getID() . '"> 
                                    <input role="button" class="btn btn-warning" type="submit" value="Dodaj komentarz">
                                </form>';
                        };
                        ?>
-->
                    </section>
                </div>
            </div>
        </main>
    </body>
</html>