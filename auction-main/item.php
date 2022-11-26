<?php include_once("header.php")?>
<?php require("utilities.php")?>


<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #A6926D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:disabled {
  width: 100%;
  background-color: #B8B8B8;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: auto;
}

input[type=submit]:hover:enabled {
  background-color: #735229;
}

div {
  border-radius: 5px;
  background-color: #FFFFFF;
  padding: 20px;
  border-style: solid;
  border-width: thin;
  border-color: #FFFFFF;
  color: #261201;
}
</style>

<?php
if (isset($_GET['item_id'])){
    $item_id = preg_replace('#[^0-9]#i', '', $_GET['item_id']); 
    $sql = "SELECT * FROM auction WHERE auctionID = '$item_id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $queryResult = mysqli_num_rows($result);
    $bsql = "SELECT max(amount) FROM `bid` WHERE auctionID = '$item_id'";
    $bresult = mysqli_query($conn, $bsql);
    $brow = mysqli_fetch_array($bresult);  
    if ($queryResult > 0){
        while ($row = mysqli_fetch_array($result)) {
          $item_id = $row["auctionID"];
          $title = $row["title"];
          $description = $row["description"];
          $price1 = $brow["max(amount)"];
          $price2 = $row["startingPrice"];
          $seller_id = $row["sellerID"];
          $current_price = max($price1,$price2);
          $bids_query = "SELECT COUNT(bidID) FROM bid WHERE auctionID = '$item_id'"; 
          $bids_result = mysqli_query($conn, $bids_query);
          $bids_count = $bids_result->fetch_array()[0];
          $end_date = $row["endTime"];
          $oDate = new DateTime($end_date);
          $img = $row['image'];
          $seller_query = "SELECT username FROM sellers WHERE sellerID IN(SELECT sellerID FROM auction WHERE sellerID='$seller_id')";  
          $seller_result = mysqli_query($conn, $seller_query);
          $srow = mysqli_fetch_array($seller_result);
          $seller_username = $srow["username"];


        }
      
    } else{
        echo "No Product in the system with that ID";
        exit();
    }


}else{

    echo "No Product in the system with that ID";
    exit();
}

  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $oDate) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $oDate);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }

$date = strtotime($end_date)

?>




<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Bootstrap and FontAwesome CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Custom CSS file -->
<link rel="stylesheet" href="css/style.css">
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
</head>
<body>
<div align="center" id="mainWrapper">
  <div id="pageContent">
  <table width="75%" border="0" cellspacing="0" cellpadding="15">
  <tr>
  <?php
   echo('
   <td width="30%" valign="top"><img src="data:image/jpeg;base64,'.base64_encode( $img ).'"width=100%" height="100%">
   ')
  ?>
      <td width="75%" valign="top"><h2><?php echo $title; ?></h2>
      <?php echo "<b> Listed by: </b>".$seller_username; ?>
<br />
        <?php echo "<b> Description: </b>".$description; ?>
<br />
<br />
        <h4><?php echo "Current Price: £".$current_price; ?></h4>

        <?php echo "<b> Number of bids: </b>".$bids_count; ?>

<br />
<br />
        <?php 
        if ($now > $oDate) {
        echo $time_remaining;
        }
        else {
        echo $time_remaining.' - Auction ends on: ' .date('d/m/y',$date). ' at ' .date('h:ia',$date); 
        }
        
        ?>

<br />
        </p>
      <form id="form1" name="form1" method="post" action="mybidex.php">
      <input type="hidden" name="pid" id="pid" value="<?php echo $item_id; ?>" />
        <input type="text" name="bidvalue" id="bidv" value="<?php echo $current_price + 1; ?>" />
        <input type="submit" name="submit" id="button" value="Place Bid" <?php if($now > $oDate){ ?> disabled <?php } ?>/>
        <?php
          if (isset($_GET["errror"])) {
            if ($_GET["errror"] == "wrongbidamount"){
              echo "<p>Please choose a larger bid amount!!</p>";
            }
            else if ($_GET["errror"]== "none2"){
              echo "<p>Bid Sucessful!</p>";
            }
          }

        ?>
      </form>

      </p>
      <form id="form2" name="form2" method="post" action="watchlist_funcs.php">
        <input type="hidden" name="pid" id="pid" value="<?php echo $item_id; ?>" />
        <input type="submit" name="submit" id="button" value="Watch" <?php if($now > $oDate){ ?> disabled <?php } ?> />
        <?php
          if (isset($_GET["errror"])) {
            if ($_GET["errror"] == "alreadyWatching"){
              echo "<p>You are already Watching!!</p>";
            }
            else if ($_GET["errror"]== "none"){
              echo "<p>You are now watching this item!</p>";
            }
          }

        ?>
      </form>
      </td>
    </tr>
</table> 
</body>
</html>


