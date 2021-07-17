<?php
$database_server     = 'localhost';
$database_name        = 'cottagerentals';
$database_user   = 'root';
$database_passwd  = '';

$conn = new PDO("mysql:host=$database_server;dbname=$database_name", $database_user, $database_passwd);
