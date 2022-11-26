<?php require("utilities.php")?>
<?php include("header.php")?>

<?php #session_start();

if (session_status() !== PHP_SESSION_ACTIVE) 
{
  session_start();
}

?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

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


$auctions_bidded = "SELECT DISTINCT auction.auctionID FROM auction JOIN bid ON auction.auctionID = bid.auctionID
    WHERE bid.buyerID = '$id'";

$auctions_watched = "SELECT DISTINCT auction.auctionID FROM auction JOIN watching ON auction.auctionID = watching.auctionID
    WHERE watching.buyerID = '$id'";

$bidded_result = $conn -> query($auctions_bidded);
$watched_result = $conn -> query($auctions_watched);

#echo mysqli_num_rows($watched_result);

#$bidded_array = $bidded_result -> fetch_array();
#$watched_array = $watched_result -> fetch_array();
#echo json_encode($bidded_array);

if (mysqli_num_rows($bidded_result) == 0 && mysqli_num_rows($watched_result) == 0)
{
echo "Your recommendations will unveil themselves once you have done some more shopping/following";
}
else
{
$auctions_array = array();

foreach ($bidded_result as $row)
{
array_push($auctions_array, $row['auctionID']);
}

foreach ($watched_result as $row)
{
array_push($auctions_array, $row['auctionID']);
}
#}
#echo json_encode($auctions_array);







$cat_query = "SELECT DISTINCT category.categoryID FROM category JOIN auction ON category.categoryID = auction.categoryID 
  WHERE auction.auctionID IN (" . implode(',', $auctions_array) . ")"; 
$cat_result = $conn -> query($cat_query); #I think this should by necessity be more than 0 by now

$cat_array = array();
foreach ($cat_result as $cat)
{
array_push($cat_array, $cat['categoryID']);
}

#echo json_encode($cat_array); 
// TODO: Perform a query to pull up auctions they might be interested in.
$recommendations = array();
$rec_query = "SELECT * FROM auction WHERE categoryID IN (" . implode(',', $cat_array) . ")";
$rec_result = $conn -> query($rec_query);

if(mysqli_num_rows($rec_result) == 0)
{
echo "We are still working on those recommendations. Please check in again once oyu've shopped some more";
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

$date=date_create($value['endTime']);

print_listing_li($value['auctionID'], $value['title'], $value['description'], $value['startingPrice'], $bids_count, $date, $img);

}

}


}






#In this version of the code, I am not recommending the buyer the auctions they are already following

?>