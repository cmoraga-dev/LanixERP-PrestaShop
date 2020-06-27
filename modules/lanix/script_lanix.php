#!/usr/bin/php7.2
<?php

$servername = "localhost";
$username = "admin";
$password = "lanixerp";
$dbname = "prestashop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$date = new DateTime();
$result = $date->format('H:i:s');
$stringdate = strval ( $result);
$sql = "INSERT INTO ps_lanix (lastname, email)
VALUES ( '$stringdate','prueba@prueba.com')";

if ($conn->query($sql) === TRUE) echo "New record created successfully";
else echo "Error: " . $sql . "<br>" . $conn->error;

$conn->close();
