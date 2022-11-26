
<?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>

<?php
if (isset($_GET["error"])) {
  if ($_GET["error"] == "emptyinput"){
    echo "<p>Fill in all fields!</p>";
  }
  else if ($_GET["error"]== "invalidUid"){
    echo "<p>choose a proper username</p>";
  }
  else if ($_GET["error"]== "invalidemail"){
    echo "<p>choose a proper email</p>";
  }
  else if ($_GET["error"]== "passworddontmatch"){
    echo "<p>password doesnt match </p>";
  }
  else if ($_GET["error"]== "stmtfailed"){
    echo "<p>Something went wrong, try again</p>";
  }
  else if ($_GET["error"]== "accounttaken"){
    echo "<p>Account already taken!</p>";
  } 
  else if ($_GET["error"]== "none"){
    echo "<p>You have signed up!</p>";
  }
}

?>

<!-- Create auction form -->
<div class="card">
  <div class="card-body">
    <form method="POST" action="includes/signup.inc.php">
      <div class="form-row align-items-center">
        <label for="accountType" class=" text-nowrap col-sm-2 col-form-label text-left">Registering as a:</label>
      <div class="col-md-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyers" required>
            <label class="form-check-label" for="accountBuyer">Buyer</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="sellers" required>
            <label class="form-check-label" for="accountSeller">Seller</label>
          </div>
          <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
          
</div>
</div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <input type="firstName" class="form-control" id="firstName" placeholder="First Name" name="firstName" required >
          <small id="firstNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
        <div class="form-group col-md-6">
          <input type="lastName" class="form-control" id="lastName" placeholder="Last Name" name="lastName" required>
          <small id="lastNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-12">
          <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
          <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">@</div>
          </div>
          <input type="username" class="form-control" id="username" placeholder="Username" name="username" required>
        </div>
          <small id="usernameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <input type="password" class="form-control" id="password" placeholder="Password" name="pass" minlength="8" required>
          <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
        <div class="form-group row">
        <div class="col-sm-12">
          <input type="password" class="form-control" id="passwordConfirmation" placeholder="Enter password again" name="passConfirm" minlength="8" required>
          <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <input type="street" class="form-control" id="street" placeholder="Street Address" name="street" required>
          <small id="addressHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
            <input type="city" class="form-control" id="city" placeholder="City" name="city" required>
            <small id="cityHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
        <div class="form-group col-md-3">
            <input type="postcode" class="form-control" id="postcode" placeholder="Postcode" name="postcode" required>
            <small id="postcodeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
        <div class="form-group col-md-3">
            <input type="telephone" class="form-control" id="telephone" placeholder="Telephone" name="telephone" maxlength="11" required>
            <small id="telephoneHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <button type="submit" name = "submit" class="btn btn-primary form-control">Register</button>
      </div>
    </form>
  </div>
</div>

<div class="text-center">Already have an account? <a href="login.php" >Login</a>

</div>
</div>

<?php include_once("footer.php")?>
