<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container-md">

<h2 class="my-3">Search</h2>
<div class = "item-container">
<?php 
echo ('<a href="browse.php" >Back to browse page</a>');
?>
<br />
<br />

    <div class = "row row-cols-1 row-cols-md-3 justify-content-center">

<?php
    if (isset($_POST['submit-search']))
{
        $search = mysqli_real_escape_string($conn,$_POST['search']);
        $category = mysqli_real_escape_string($conn,$_POST['cat']); 
        $sort = mysqli_real_escape_string($conn, $_POST['order']);
} #should this parenthesis encompass everyhitng below?

    #no criteria was given
    if (empty($search) && $category == "empty")
    {
        echo "No results found.";
    }

    else 
    {
        #only category was given
        if (empty($search))
        {
            $cat_id_query = "SELECT categoryID FROM category WHERE categoryName = '$category'";
            $cat_id = intval(mysqli_fetch_row(mysqli_query($conn, $cat_id_query))[0]); 
            $sql = "SELECT * FROM auction WHERE categoryID = '$cat_id'";
            
        }

        #only keyword was given
        else if ($category == "empty")
        {
            $sql = "SELECT * FROM auction WHERE title LIKE '%$search%' 
                    OR `description` LIKE '%$search%'"; 
        }
        
        #both keyword and category were given
        else
        {
            $cat_id_query = "SELECT categoryID FROM category WHERE categoryName = '$category'";
            $cat_id = intval(mysqli_fetch_row(mysqli_query($conn, $cat_id_query))[0]); 
            
            $sql = "SELECT * FROM auction 
                    WHERE title LIKE '%$search%' AND categoryID = '$cat_id'
                    OR `description` LIKE '%$search%' AND categoryID = '$cat_id'";
                
        }
        
        
        ## Pulling results using the alternate queries
        $result = mysqli_query($conn, $sql);

        $res_array = array();
        # attempting to add max_price component to the result/array
        foreach ($result as $row)
        {
            $auc_id = $row['auctionID'];
            $max_query = "SELECT MAX(amount) FROM bid WHERE auctionID = '$auc_id'";
            $max = intval(mysqli_fetch_row(mysqli_query($conn, $max_query))[0]); 
            if ($max > $row['startingPrice'])
            {
                $row['max_price'] = $max;
            }
            else
            {
                $row['max_price'] = $row['startingPrice'];
            }
            
            #only adding the auctions that are not yet finished

            #$now = new DateTime();
            #if ($row['endTime'] > $now)
            #{
                array_push($res_array, $row);
            #}
            
        }

        
        #here comes the sorting part (hopefully)

        $price = array_column($res_array, 'max_price'); 
        $date = array_column($res_array, 'endTime'); 

        #sorting by endDate
        if ($sort == "date")
        {
            array_multisort($date, SORT_ASC, $res_array);
        }

        #sorting by price asc
        else if ($sort == "pricelow")
        {
            array_multisort($price, SORT_ASC, $res_array);
        }

        #sorting by price desc
        else
        {   
            array_multisort($price, SORT_DESC, $res_array);
        }

        
        #Printing the results
        if (empty($res_array))
        {
            echo "No results found.";
        }
        else
        {
            foreach ($res_array as $list)
            {
                $auc_id = $list['auctionID'];
                $count_query = "SELECT COUNT(bidID) FROM bid WHERE auctionID = '$auc_id'"; 
                $count_result = mysqli_query($conn, $count_query);
                $count = $count_result->fetch_array()[0];

                $seller_id = $row["sellerID"];
                $seller_query = "SELECT username FROM sellers WHERE sellerID IN(SELECT sellerID FROM auction WHERE sellerID='$seller_id')";  
                $seller_result = mysqli_query($conn, $seller_query);
                $srow = mysqli_fetch_array($seller_result);
                $seller_username = $srow["username"];

                
                print_listing_li($auc_id, $list['title'], $list['description'], 
                $list['max_price'], $count, $list['endTime'], $list['image'], $seller_username);
                
                
            }
        }



        #print_r($res_array);



    }

    


  
?>
    </div>
</div>
