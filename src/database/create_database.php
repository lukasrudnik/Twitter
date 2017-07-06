<?php

// Połączenie do bazy danych mysql 
$servername = 'localhost';
$username = 'root';
$basename = '';
$password = '';  // połączenie bez hasła

//Tworzenie nowego połączenia:
$connect = new mysqli($servername, $username, $basename, $password);

//Sprawdzenie czy połączenie się udało:
if ($connect->connect_error){
    die("Połączenie nieudane!" . "<br>" . $connect->connect_error); 
}
echo("Połączenie udane." . "<br>");

$sql = "CREATE DATABASE Twitter_DB"; 
$result = $connect->query($sql);

// sprawdzenie polączenia:
if ($result != FALSE){
    echo ("Baza danych Twitter_DB została stworzona poprawnie." . "<br>");
}
else{
    echo("Błąd podczas tworzenia bazy danych!" . "<br>" . $connect->error);
}

?>