<?php include_once("header.php")?>
<!-- <style>
body {
  background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
    url("../images/auction.webp");
  background-size: cover;
  background-position: center;
  height: 100%;
  padding: 150px 0;
  text-align: center;
}
</style> -->

<div class="container">

<section class="login-form">
  <form method="POST" action="includes/login.inc.php">
    <h2 class="my-3">Login</h2>
    <div class="form-group">
      <label for="email">Email/Username</label>
      <input type="text" class="form-control" name="email" id="email" placeholder="Email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="pass" id="password" placeholder="Password">
    </div>
    <button type="submit" name = "submit" class="btn btn-primary form-control">Sign in</button>
  </form>
  <div class="text-center">or <a href="signup.php">create an account</a></div>

  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput"){
      echo "<p>Fill in all fields!</p>";
    }
    else if ($_GET["error"]== "wronglogin"){
      echo "<p>Incorrect Login information!</p>";
    }
  }

  ?>

</section>

<?php include_once("footer.php")?>

