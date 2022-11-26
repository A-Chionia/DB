<?php include_once("header.php")?> 
<?php   #session_start();  
 
if (session_status() !== PHP_SESSION_ACTIVE)  
{ 
  session_start(); 
} 
 
?> 
 
<div class="container my-5"> 
 
<?php 
 
   
 
 
// This function takes the form data and adds the new auction to the database. 
 
/* TODO #1: Connect to MySQL database (perhaps by requiring a file that 
            already does this). */ 
 
    // $db = new mysqli("localhost", "root", "root", "auctionHouse");  
    // if ($db->errno)  
        // die("Error opening database: " . $db->error()); 
     
    
    $id = $_SESSION['sellerID']; 
     
 
 
/* TODO #2: Extract form data into variables. Because the form was a 'post' 
            form, its data can be accessed via $POST['auctionTitle'],  
            $POST['auctionDetails'], etc. Perform checking on the data to 
            make sure it can be inserted into the database. If there is an 
            issue, give some semi-helpful feedback to user. */ 
 
            if (isset($_POST["submit"])) 
                { 
                    #echo "Good"; 
                    $auctionTitle = trim($_POST['auctionTitle']); 
                    $description = trim($_POST['description']); 
                    $category = trim($_POST['category']); 
                    $startingPrice = trim($_POST['startingPrice']); 
                    $reservePrice = trim($_POST['reservePrice']); 
                    $endDate = trim($_POST['endDate']); 
 
                    $startDate = date("Y-m-d");
                    $imgContent = addslashes(file_get_contents($_FILES["imageFile"]["tmp_name"]));

                    # getting the id of the chosen category 
                    $cat_query = "SELECT categoryID FROM category WHERE categoryName = '$category'"; 
                    $cat_id = intval(mysqli_fetch_row(mysqli_query($conn, $cat_query))[0]);  
                    #echo $cat_id; #works 


                    if (!empty($auctionTitle) && !empty($category) && !empty($startingPrice) && !empty($endDate)) 
                        { 
                            #echo "All good so far"; 
                            #echo json_encode($_SESSION['logged_in']); true 
                            $insert_query = "INSERT INTO auction (sellerID, categoryID, title, `description`, startTime, endTime, reservedPrice, startingPrice, `image`) VALUES ('$id', '$cat_id', '$auctionTitle', '$description', '$startDate', '$endDate', '$reservePrice', '$startingPrice', '$imgContent')"; 
                            $run_query = mysqli_query($conn, $insert_query);
                        } 
                     
            }else{
                    echo "nope";
                }
 

 
 
/* TODO #3: If everything looks good, make the appropriate call to insert 
            data into the database. */ 
             
 
// If all is successful, let user know. 
echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your listings.</a></div>'); 
 
 
?> 
</div> 
 
 
<?php include_once("footer.php")?>