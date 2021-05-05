<?php

// //config access to database
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "JinJang";

//config access to database
$servername = "remotemysql.com";
$username = "tFjbmdtNxP";
$password = "b4XqxeoPVP";
$dbname = "tFjbmdtNxP";

//connect to database
$connect = new mysqli($servername, $username, $password, $dbname);

//start php session access
session_start();

//set timezone of website
date_default_timezone_set('Asia/Kuala_Lumpur');

//limit how many session records to show per page
$limit=10;

?>