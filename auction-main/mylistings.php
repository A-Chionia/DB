<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container-md">

<h2 class="my-3">My listings</h2>
<div class="item-container">
<div class = "row row-cols-3 row-cols-md-3 g-4 justify-content-center">

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

  #session_start();
  if (session_status() !== PHP_SESSION_ACTIVE) 
{
  session_start();
}
  $_SESSION['logged_in'] = true;
  #print_r($_SESSION);
  

  $user_id = $_SESSION['sellerID'];


  $listings_query = "SELECT * FROM auction WHERE sellerID = '$user_id'"; 

  $listings = array();

  $listings_result = $conn -> query($listings_query);

  if (mysqli_num_rows($listings_result) == 0)
  {
    echo "Your listing will be displayed here";
  }
  else
  {
    foreach($listings_result as $row)
  {
  array_push($listings, $row);
  }
  
  foreach($listings as $value)
  {
    $auc_id = $value['auctionID'];
    $bids_query = "SELECT COUNT(bidID),COUNT(buyerID) FROM bid WHERE auctionID = '$auc_id'"; 
    $bids_result = mysqli_query($conn, $bids_query);
    $bids_count_row = mysqli_fetch_array($bids_result);
    $bids_count = $bids_count_row['COUNT(bidID)'];
    $buyer_count = $bids_count_row['COUNT(buyerID)'];
    
    $img = $value['image'];
    $res_price = $value['reservedPrice'];
    if ($buyer_count > 0){
      $new_query = "SELECT buyerID from bid where auctionID='$auc_id'";
      $new_result = mysqli_query($conn, $new_query);
      $new_result_row = mysqli_fetch_array($new_result);
      $buyer_id=$new_result_row['buyerID'];
      $buyer_query = "SELECT username FROM buyers WHERE buyerID IN(SELECT buyerID FROM bid WHERE buyerID='$buyer_id')";  
      $buyer_result = mysqli_query($conn, $buyer_query);
      $brow = mysqli_fetch_array($buyer_result);
      $buyer_username = $brow["username"];
    } else {
      $buyer_username = '';
    }
    
    
    $date=date_create($value['endTime']);

    $img = $value['image'];

    print_listing_seller($value['auctionID'], $value['title'], $value['startingPrice'], $bids_count, $date, $img, $res_price, $buyer_username);
  }

  }

  
  
  // TODO: Perform a query to pull up their auctions.
  
  // TODO: Loop through results and print them out as list items.
  
?>
</div>
</div>

<?php include_once("footer.php")?>