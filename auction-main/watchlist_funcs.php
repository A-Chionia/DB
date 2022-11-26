<?php require_once('includes/functions.inc.php')?>
<?php require_once ('database.php')?>
<?php
  session_start();
  
?>

 <?php

if(isset($_POST["submit"])) {
  $auctionID = trim($_POST['pid']);
  $buyerID = $_SESSION['buyerID'];

  if (alreadyWatching($conn,$auctionID) !== false){
    header("location: item.php?item_id= . $auctionID . &errror=alreadyWatching");
    exit();
}

  createwatcher($conn,$auctionID,$buyerID);
} 
else{
  header("location: itemex.php");
  exit();

}

