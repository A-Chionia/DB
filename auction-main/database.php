<?php
$server = "localhost";
$username = "root";
$password = "root";
$dbname = "auctionHouse";

$conn = new mysqli($server,$username,$password,$dbname); 

if (!$conn) {
    die("connection failed: ". mysqli_connect_error());

}