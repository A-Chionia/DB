<?php include_once("header.php")?>

<?php
/* (Uncomment this block to redirect people without selling privileges away from this page)
  // If user is not logged in or not a seller, they should not be able to
  // use this page.
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }
*/
?>

<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto"> 
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <form method="post" action="create_auction_result.php" enctype="multipart/form-data">
        <div class="form-group">
          <label for="auctionTitle" class="col-form-label text-right ">Title of auction</label>
            <input type="text" class="form-control" id="auctionTitle" name= "auctionTitle" placeholder="e.g. Beautiful Wooden Table" required>
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A descriptive title of your item, e.g. colour, condition, size etc.</small>
        </div>
        <div class="form-group">
          <label for="auctionDetails" class="col-form-label text-right">Details</label>
            <textarea class="form-control" id="auctionDetails" name="description" rows="4"></textarea>
            <small id="detailsHelp" class="form-text text-muted">Full details of the listing.</small>
        </div>

        <div class="form-group row align-items-center">
          <div class="form-group col-md-6">
            <label for="imageFile">Upload your image:</label>
            <input type="file" class="form-control-file" id="imageFile" name="imageFile" required>
          </div>
          <div class="form-group col-md-6">
              <select class="form-control" id="auctionCategory" name="category" required>
                <option selected>Choose a category...</option>

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
              <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
          </div>
        </div>

        <div class="form-group row">
          <div class="form-group col-md-6">
            <label for="auctionStartPrice" class="col-form-label">Starting price</label>
            <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
                </div>
                <input type="number" class="form-control" id="auctionStartPrice" name="startingPrice" required>
            </div>
              <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Initial bid amount.</small>
          </div>
          <div class="form-group col-md-6">
            <label for="auctionReservePrice" class="col-form-label">Reserve price</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
                </div>
                <input type="number" class="form-control" id="auctionReservePrice" name="reservePrice">
              </div>
              <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>

        <div class="form-group">
          <label for="auctionEndDate" class="col-form-label text-right">End date</label>
            <input type="datetime-local" class="form-control" id="auctionEndDate" name="endDate" required min="<?= date('Y-m-d H:i', strtotime('+1 hour')); ?>">
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Date and time for when the auction expires. This must be at least one hour after the time of creating the auction.</small>
        </div>
        <button type="submit" class="btn btn-primary form-control" name="submit">Create Auction</button>
      </form>
    </div>
  </div>
</div>


</div>


<?php include_once("footer.php")?>