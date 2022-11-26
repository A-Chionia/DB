<?php  

function emptyInputSignup($firstName,$lastName,$email,$username,$pwd,$pwdrepeat,$street,$city,$postcode,$telephone){
    $result;
    if (empty($firstName) || empty($lastName) || empty($email)|| empty($username) || empty($pwd) || empty($pwdrepeat) || empty($street) || empty($city) || empty($postcode) || empty($telephone)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}


function invalidUid($username) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function invalidEmail($email) {
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd,$pwdrepeat) {
    $result;
    if ($pwd !== $pwdrepeat) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function uidExists($conn,$email,$username) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $bquery = "SELECT * FROM buyers WHERE email = ? OR username = ?";
    $bsql = $conn -> prepare($bquery);
    $bsql -> bind_param("ss", $email,$username);
    $bsql -> execute();
    $bresult = $bsql -> get_result();

    $squery = "SELECT * FROM sellers WHERE email = ? OR username = ?";
    $ssql = $conn -> prepare($squery);
    $ssql -> bind_param("ss", $email,$username);
    $ssql -> execute();
    $sresult = $ssql -> get_result();

    $resultData = NULL;
    if (mysqli_num_rows($bresult) > 0){
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $bquery)) {
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }
        $resultData = $bresult;
    }
    else if(mysqli_num_rows($sresult) > 0){
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $squery)) {
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }
        $resultData = $sresult;

    }


    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}


function createUser($conn,$username,$email,$pwd,$firstName,$lastName,$telephone,$street,$city,$postcode,$account){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $sql = "INSERT INTO {$account} (username, email, pwd, firstName, lastName, telephone, street, city, postcode) VALUES (?,?,?,?,?,?,?,?,?)"; 
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssisss", $username, $email, $hashedPwd, $firstName, $lastName, $telephone, $street, $city, $postcode);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
    exit(); 
}

function emptyInputLogin($email,$pwd){
    $result;
    if (empty($email) || empty($pwd)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function loginUser($conn,$email,$pwd){
    $uidExists = uidExists($conn,$email,$email);

    if ($uidExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit(); 
    }

    $pwdHashed = $uidExists["pwd"];
    $checkPwd = password_verify($pwd,$pwdHashed);
    
    if ($checkPwd === false) {
        header("location: ../login.php?error=wronglogin");
        exit(); 
    }
    else if ($checkPwd === true) {
        session_start();
        $_SESSION["email"] = $uidExists["email"];
        $_SESSION["buyerID"] = $uidExists["buyerID"];
        $_SESSION["sellerID"] = $uidExists["sellerID"];
        $_SESSION["firstName"] = $uidExists["firstName"];
        $_SESSION["username"] = $uidExists["username"];
        $_SESSION["logged_in"] = true;
        if (isset($_SESSION['buyerID'])) {
            header("location: ../browse.php");
        }
        elseif (isset($_SESSION['sellerID'])) {
            header("location: ../mylistings.php");
        }
        exit(); 
    }
}


function createwatcher($conn,$auctionID,$buyerID){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $sql = "INSERT INTO watching (auctionID,buyerID) VALUES (?,?)"; 
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: item.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $auctionID, $buyerID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: item.php?item_id= . $auctionID . &errror=none");
    exit(); 
}

function alreadyWatching($conn,$auctionID) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $query = "SELECT * FROM watching WHERE auctionID = ?";
    $sql = $conn -> prepare($query);
    $sql -> bind_param("s", $auctionID);
    $sql -> execute();
    $result = $sql -> get_result();
    $resultData = NULL;

    if (mysqli_num_rows($result) > 0){
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            header("location: itemex.php?error=stmtfailed");
            exit();
        }
        $resultData = $result;
    }


    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createbid($conn,$auctionID,$buyerID,$amount){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $sql = "INSERT INTO bid (auctionID,buyerID,amount) VALUES (?,?,?)"; 
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: mybidex.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $auctionID, $buyerID,$amount);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: item.php?item_id= . $auctionID . &errror=none2");
    exit(); 
}

function bidamount($conn,$auctionID,$amount) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $query = "SELECT * FROM bid WHERE auctionID = ?  AND amount >= ?";
    $sql = $conn -> prepare($query);
    $sql -> bind_param("ss", $auctionID,$amount);
    $sql -> execute();
    $result = $sql -> get_result();
    $resultData = NULL;

    if (mysqli_num_rows($result) > 0){
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            header("location: itemex.php?error=stmtfailed");
            exit();
        }
        $resultData = $result;
    }


    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}


?>

