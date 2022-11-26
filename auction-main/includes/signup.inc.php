<?php require_once('functions.inc.php')?>
<?php require_once ('../database.php')?>


<?php

if(isset($_POST["submit"])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = $_POST['email'];
    $username = trim($_POST['username']); 
    $pwd = trim($_POST['pass']);
    $pwdrepeat = trim($_POST['passConfirm']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $postcode = trim($_POST['postcode']);
    $telephone = trim($_POST['telephone']);
    $account = trim($_POST['accountType']);


    if (emptyInputSignup($firstName,$lastName,$email,$username,$pwd,$pwdrepeat,$street,$city,$postcode,$telephone,$account) !== false){
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (invalidUid($username) !== false){
        header("location: ../signup.php?error=invalidUid");
        exit();
    }

    if (invalidEmail($email) !== false){
        header("location: ../signup.php?error=invalidemail");
        exit();
    }

    if (pwdMatch($pwd,$pwdrepeat) !== false){
        header("location: ../signup.php?error=passworddontmatch");
        exit();
    }

    if (uidExists($conn,$email,$username) !== false){
        header("location: ../signup.php?error=accounttaken");
        exit();
    }

    
    createUser($conn,$username,$email,$pwd,$firstName,$lastName,$telephone,$street,$city,$postcode,$account);
    
} 
else{
    header("location: ../signup.php");
    exit();

}
