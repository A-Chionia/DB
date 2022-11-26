<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php #session_start(); 

if (session_status() !== PHP_SESSION_ACTIVE) 
{
  session_start();
}
?>

<div class="container-md">

<h2 class="my-3">My bids</h2>
<div class="item-container">
<div class = "row row-cols-1 row-cols-md-3 g-4 justify-content-center">

<?php



  // This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

   
  // TODO: Check user's credentials (cookie/session).
  $user_id = $_SESSION["buyerID"];

  
  // TODO: Perform a query to pull up the auctions they've bidded on.


  $bids_query = "SELECT DISTINCT auction.auctionID, title, `description`, startTime, endTime, startingPrice, `image`, sellerID
                FROM auction JOIN bid ON auction.auctionID = bid.auctionID 
                WHERE buyerID = '$user_id'";

 
  $bids_result = $conn -> query($bids_query);
  #$rows = mysqli_num_rows($bids_result);
  #echo $rows;
  
  if (mysqli_num_rows($bids_result) == 0)
  {
    echo "Once you've done some bidding, you can see it here";
  }
  else
  {
    // TODO: Loop through results and print them out as list items.
    foreach ($bids_result as $row)
    {
      $auc_id = $row['auctionID'];
      $count_query = "SELECT COUNT(bidID) FROM bid WHERE auctionID = '$auc_id'"; 
      $count_result = mysqli_query($conn, $count_query);
      $count = $count_result->fetch_array()[0];

      $img = $row['image'];

      $date=date_create($row['endTime']);
      $bsql = "SELECT max(amount) FROM `bid` WHERE auctionID = '$auc_id'";
      $bresult = mysqli_query($conn, $bsql);
      $brow = mysqli_fetch_array($bresult);  
      $price1 = $brow["max(amount)"];
      $price2 = $row["startingPrice"];
      $current_price = max($price1,$price2);

      $seller_id = $row["sellerID"];
      $seller_query = "SELECT username FROM sellers WHERE sellerID IN(SELECT sellerID FROM auction WHERE sellerID='$seller_id')";  
      $seller_result = mysqli_query($conn, $seller_query);
      $srow = mysqli_fetch_array($seller_result);
      $seller_username = $srow["username"] ;

      print_listing_li($auc_id, $row['title'], $row['description'], $current_price, $count, $date, $img, $seller_username);

    }

  }


  
  
?>
</div>
</div>

<?php include_once("footer.php")?>