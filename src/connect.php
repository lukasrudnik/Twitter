<?php

// Połączenie do bazy danych mysql 
$servername = 'localhost';
$username = 'root';
$basename = "Twitter";
$password = '';  // połączenie bez hasła

//Tworzenie nowego połączenia:
$connect = new mysqli($servername, $username, $password, $basename);

//Sprawdzenie czy połączenie się udało:
if ($connect->connect_error){
    die("Połączenie nieudane!" . "<br>" . $connect->connect_error); 
}
echo("Połączenie udane." . "<br>");

//Tworzenie nowej bazy danych:
$sql = "CREATE DATABASE Twitter"; 
$result = $connect->query($sql);

// sprawdzenie polączenia:
if ($result != FALSE){
    echo ("Baza danych Twitter została stworzona poprawnie." . "<br>");
}
else{
    echo("Błąd podczas tworzenia bazy danych!" . "<br>" . $connect->error);
}

echo "<br>";

?>