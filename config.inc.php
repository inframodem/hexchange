<?php
$servername = "localhost";
$sqlusername = "webuser";
$sqlpassword = "AlphaTest123";
$database = "hungerexchange";
$port = "3306";
$conn = new mysqli($servername, $sqlusername, $sqlpassword, $database, $port);
  if($conn ->connect_error){
  die("Connection failed: " . $conn->connect_error);
  }
 ?>
