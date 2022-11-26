<?php include("database.php")?>
<?php
  session_start();
  
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/style.css">

  <title>[My Auction Site] <!--CHANGEME!--></title>
</head>


<!-- Navbars -->
<?php
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
  if (isset($_SESSION['buyerID'])) {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="browse.php">Auction House <!--CHANGEME!--></a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      
  
      </li>
    </ul>
  </nav>';
  }
  elseif (isset($_SESSION['sellerID'])) {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="mylistings.php">Auction House <!--CHANGEME!--></a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      
  
      </li>
    </ul>
  </nav>';
  }
}
else {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="banner.php">Auction House <!--CHANGEME!--></a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      
  
      </li>
    </ul>
  </nav>';
  }
?>




<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #A6926D;">
  <ul class="navbar-nav align-middle">
  <?php
    if (isset($_SESSION['buyerID'])) {      
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="mybids.php">My bids</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="recommendations.php">Recommended</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="includes/logout.inc.php">Log out</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link active"</a></li>'),"Welcome " .$_SESSION["firstName"]. "!";

    }
    elseif (isset($_SESSION['sellerID'])) {
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="mylistings.php">My Listings</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="create_auction.php">+ Create auction</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="includes/logout.inc.php">Log out</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link active"</a></li>'),"Welcome " .$_SESSION["firstName"]. "!";
    }
    else {
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="signup.php">Register</a></li>');
      echo ('<li class="nav-item mx-1 mb-0 h5 fs-2"><a class="nav-link" href="login.php">Login</a></li>');

    }
  ?>

  </ul>
</nav>