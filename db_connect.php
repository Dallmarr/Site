<?php
try {
    $host = "localhost";
    $dbname = "f0459275_galary";
    $user = "f0459275_galary";
    $pass = "f0459275_galary_pass";

    $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

} catch (PDOException $e) {
    echo $e->getMessage();
}