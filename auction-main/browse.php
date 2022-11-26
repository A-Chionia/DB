<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container-md">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="POST" action="search.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <input type="text" name ="search" class="form-control border-left-0" id="keyword" placeholder="Search for anything">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat"  class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat">
        <option value="empty">Choose...</option>
        <?php 
    

        #get categoryNames from the database
        $query = "SELECT DISTINCT categoryName FROM category";
        $result = $conn -> query($query);

        $array = array();
        foreach ($result as $category)
        {
          array_push($array, $category['categoryName']);
        }

        foreach ($array as $name)
        {
          echo '<option value="' . $name . '">' . $name . '</option>';
          
        }
        
        ?>



        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-0 h6" for="order_by" style="padding-right: 10px;">Sort by:</label>
        <select class="form-control" id="order_by" name="order">
          <option selected value="pricelow">Price (low to high)</option>
          <option value="pricehigh">Price (high to low)</option>
          <option value="date">Soonest expiry</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" name ="submit-search" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->





<?php
  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
  }
  else {
    $keyword = $_GET['keyword'];
  }

  if (!isset($_GET['cat'])) {
    // TODO: Define behavior if a category has not been specified.
  }
  else {
    $category = $_GET['cat'];
  }
  
  if (!isset($_GET['order_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
  }
  else {
    $ordering = $_GET['order_by'];
  }
  
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }

  /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
  
  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
  $num_results = 96; // TODO: Calculate me for real
  $results_per_page = 10;
  $max_page = ceil($num_results / $results_per_page);
?>

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<div class = "item-container">
  <div class = "row row-cols-1 row-cols-md-3 justify-content-center">
  <?php
    $sql = "SELECT * FROM auction";
    $result = mysqli_query($conn, $sql);
    $queryResult = mysqli_num_rows($result);

    if ($queryResult > 0) {
        while ($row = mysqli_fetch_assoc($result)) {  
          $item_id = $row["auctionID"];
          $title = $row["title"];
          $description = $row["description"];
          $bsql = "SELECT max(amount) FROM `bid` WHERE auctionID = '$item_id'";
          $bresult = mysqli_query($conn, $bsql);
          $brow = mysqli_fetch_array($bresult);  
          $price1 = $brow["max(amount)"];
          $price2 = $row["startingPrice"];
          $current_price = max($price1,$price2);
          $bids_query = "SELECT COUNT(bidID) FROM bid WHERE auctionID = '$item_id'"; 
          $bids_result = mysqli_query($conn, $bids_query);
          $bids_count = $bids_result->fetch_array()[0];
          $end_date = $row["endTime"];
          $oDate = new DateTime($end_date);
          $img = $row['image'];
          $seller_id = $row["sellerID"];
          $seller_query = "SELECT username FROM sellers WHERE sellerID IN(SELECT sellerID FROM auction WHERE sellerID='$seller_id')";  
          $seller_result = mysqli_query($conn, $seller_query);
          $srow = mysqli_fetch_array($seller_result);
          $seller_username = $srow["username"] ;


          print_listing_li($item_id, $title, $description, $current_price, $bids_count, $oDate, $img, $seller_username);
        }
       
    }

  ?>
  </div>
</div>


<!-- Pagination for results listings
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav> -->


<?php include_once("footer.php")?>