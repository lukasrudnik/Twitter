<?php

include_once "connect.php";

/*
AUTO_INCREMENT - klucz główny
UNIQUE - kolumna jest uniklana (brak dwóch takich samych kolumn)
PRIMARY KEY - identyfikuje każdy rekord w tabeli - klucz główny
FOREIGN KEY - klucz obcy słurzący do łączenia dwóch tabel - odnosi się do innej tabeli
PREFERENCES - klucz obcy zawiera odwołanie do innej tabeli przez umieszczenie w deklarowanej kolumnie wartości z klucza głównego do tamtej tabeli
*/

// Tworzenie nowej tabeli Users:
$sql = "CREATE TABLE Users ( 
        id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        username VARCHAR(255) NOT NULL,
        hashedPassword VARCHAR(255) NOT NULL
        )";

//Sprawdzenie czy tabela stworzyla sie poprawnie:
$result = $connect->query($sql);
if($result === TRUE){
    echo ("Tabela Users została stworzona poprawine.");
}
else{
    ("Błąd podczas tworzenia tabeli Users!" . "<br>" . $connect->error);
}

echo "<br>";

// Tworzenie tabeli Tweet:
$sql = "CREATE TABLE Tweet (
        id INT NOT NULL AUTO_INCREMENT,
        userId INT NOT NULL,
        text VARCHAR(140) NOT NULL, 
        creationDate DATE NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(userId) REFERENCES Users(id)
        )";

//Sprawdzenie czy tabela stworzyla sie poprawnie:
$result = $connect->query($sql);
if($result === TRUE){
    echo ("Tabela Tweet zosała stworzona poprawine.");
}
else{
    ("Błąd podczas tworzenia babeli Tweet!" . "<br>" . $connect->error);
}

echo "<br>";

// Tworzenie tabeli Comment:
$sql = "CREATE TABLE Comment (
        id INT NOT NULL AUTO_INCREMENT,
        idUsera INT NOT NULL,
        idPostu INT NOT NULL,
        text VARCHAR(60) NOT NULL,
        creationDate DATE NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(idUsera) REFERENCES Users(id),
        FOREIGN KEY(IdPostu) REFERENCES Tweet(id)
        )";

//Sprawdzenie czy tabela stworzyla sie poprawnie:
$result = $connect->query($sql);
if($result === TRUE){
    echo ("Tabela Comment zosała stworzona poprawine.");
}
else{
    ("Błąd podczas tworzenia babeli Comment!" . "<br>" . $connect->error);
}

echo "<br>";

// Tworzenie tabeli Message
$sql = "CREATE TABLE Message (
        id INT NOT NULL AUTO_INCREMENT,
        idSendera INT NOT NULL,
        idRecivera INT NOT NULL,
        message VARCHAR(255) NOT NULL,
        messageRead INT NOT NULL,
        creationDate DATE NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(idSendera) REFERENCES Users(id),
        FOREIGN KEY(idRecivera) REFERENCES Users(id)
        )";

//Sprawdzenie czy tabela stworzyla sie poprawnie:
$result = $connect->query($sql);
if($result === TRUE){
    echo ("Tabela Message zosała stworzona poprawine.");
}
else{
    ("Błąd podczas tworzenia babeli Message!" . "<br>" . $connect->error);
}

echo "<br>";

?>