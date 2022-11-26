<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php # session_start();

if (session_status() !== PHP_SESSION_ACTIVE) 
{
  session_start();
}

?>

<div class="container-md">

<h2 class="my-3">Recommendations for you</h2>

<div class="item-container">
<div class = "row row-cols-1 row-cols-md-3 justify-content-center">
<?php
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  


  // TODO: Check user's credentials (cookie/session).
  $id = $_SESSION["buyerID"];

  
    #getting the IDs of auctions watched and bidded on by the user 

    #$auctions_query = "SELECT DISTINCT auction.auctionID FROM auction 
    #JOIN watching ON auction.auctionID = watching.auctionID 
    #JOIN bid ON watching.auctionID = bid.auctionID
    #WHERE bid.buyerID = '$id' OR watching.buyerID = '$id'";


$auctions_bidded = "SELECT COUNT(bidID) FROM bid WHERE buyerID = '$id'";

$bidded_result = $conn -> query($auctions_bidded);
$bidded_result = $bidded_result->fetch_array();
$quantity = intval($bidded_result[0]);

#echo mysqli_num_rows($watched_result);

#$bidded_array = $bidded_result -> fetch_array();
#$watched_array = $watched_result -> fetch_array();
#echo json_encode($bidded_array);

$auctions_array = array();

if ($quantity < 3)
{
echo "No bids"; ?>
<div class="text-center">Bid on more auctions to see recommendations based on other users' history! <a href="collaborative_filtering.php" >Start browsing now.</a>
<?php
}
else
{



#echo json_encode($cat_array); 
// TODO: Perform a query to pull up auctions they might be interested in.
$recommendations = array();
$rec_query = "SELECT * FROM auction WHERE auctionID IN (
    SELECT DISTINCT auctionID
    FROM bid
    WHERE auctionID NOT IN (
        SELECT DISTINCT auctionID
        FROM bid
        WHERE buyerID = (
            SELECT buyerID FROM buyers
            WHERE buyerID = '$id'
            )
        )
    AND buyerID IN (
        SELECT DISTINCT buyerID
        FROM bid
        GROUP BY buyerID
        HAVING COUNT(buyerID) >= 3
        )
    )";

$rec_result = $conn -> query($rec_query);

if(mysqli_num_rows($rec_result) == 0)
{
echo "We are still working on those collaborative filtering recommendations. Please check in again once you've shopped some more";
}
else 
{
foreach ($rec_result as $row)
{
if (!in_array($row['auctionID'], $auctions_array))
{
array_push($recommendations, $row);
}
}
// TODO: Loop through results and print them out as list items.
foreach ($recommendations as $value)
{
$auc_id = $value['auctionID'];
$bids_query = "SELECT COUNT(bidID) FROM bid WHERE auctionID = '$auc_id'"; 
$bids_result = mysqli_query($conn, $bids_query);
$bids_count = $bids_result->fetch_array()[0];

$img = $value['image'];
$seller_id = $value["sellerID"];
$seller_query = "SELECT username FROM sellers WHERE sellerID IN(SELECT sellerID FROM auction WHERE sellerID='$seller_id')";  
$seller_result = mysqli_query($conn, $seller_query);
$srow = mysqli_fetch_array($seller_result);
$seller_username = $srow["username"] ;

$date=date_create($value['endTime']);

print_listing_li($value['auctionID'], $value['title'], $value['description'], $value['startingPrice'], $bids_count, $date, $img,$seller_username);


}

}


}






#In this version of the code, I am not recommending the buyer the auctions they are already following

?>