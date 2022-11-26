<?php require_once('includes/functions.inc.php')?>
<?php require_once ('database.php')?>
<?php
  session_start();
  
?>

 <?php

if(isset($_POST["submit"])) {
  $amount = trim($_POST['bidvalue']);
  $buyerID = $_SESSION['buyerID'];
  $auctionID = trim($_POST['pid']);

  if (bidamount($conn,$auctionID,$amount) !== false){
    header("location: item.php?item_id= . $auctionID . &errror=wrongbidamount");
    exit();
}
  
  createbid($conn,$auctionID,$buyerID,$amount);
} 
else{
  header("location: itemex.php");
  exit();

}
