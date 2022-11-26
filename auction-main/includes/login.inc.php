<?php require_once('functions.inc.php')?>
<?php require_once ('../database.php')?>


<?php 

if(isset($_POST["submit"])) {
    $email = $_POST['email'];
    $pwd = trim($_POST['pass']);



    if (emptyInputLogin($email,$pwd) !== false){
        header("location: ../login.php?error=emptyinput");
        exit();
    }
    

    loginUser($conn,$email,$pwd);
}
else{
    header("location: ../login.php");
    exit();

}
