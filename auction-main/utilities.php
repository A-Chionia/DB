<style>
<?php include("css/custom.css") ?>
</style>

<?php

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $img, $seller_username)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <div class="card">
      <div class="image">
        <img class="card-img-top" src="data:image/jpeg;base64,'.base64_encode( $img ).'">
      </div>
      <div class="p-1 mr-5 text-left"><h4><a href="item.php?item_id=' . $item_id . '">' . $title . '</a></h5><b>Listed by:</b> ' . $seller_username . '</div>
      <div class="text-left text-nowrap p-1"><span style="font-size: 1.1em"><b>Price:</b> £' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . ' · ' . $time_remaining . '</div>
    </div>
    <br />
  ');
}

function print_listing_seller($item_id, $title, $price, $num_bids, $end_time, $img, $res_price, $buyer_username)
{
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <div class="card">
      <div class="image">
        <img class="card-img-top" src="data:image/jpeg;base64,'.base64_encode( $img ).'">
      </div>
      <div class="p-1 mr-5 text-left"><h4>' . $title . '</h4></div>
      <div class="text-left text-nowrap p-1"><span style="font-size: 1.1em"><b>Price:</b> £' . number_format($price, 2) . '</span>
      <br /><span style="font-size: 1.1em"><b>Reserve Price:</b> £' . number_format($res_price, 2) . '</span>
      <br /><span style="font-size: 1.1em">Highest Bidder: ' .$buyer_username .'
      <br />' . $num_bids . $bid . ' · ' . $time_remaining . '</div>
    </div>
    <br />
  ');
}

?>