<?php
    //Start session
    session_start();
    //Include database connection details
    require_once('config.php');

    //Connect to mysql server
    $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if(!$link) {
        die('Failed to connect to server: ' . mysql_error());
    }

    //Select database
    $db = mysql_select_db(DB_DATABASE);
    if(!$db) {
        die("Unable to select database");
    }

    //Function to sanitize values received from the form. Prevents SQL injection
    function clean($str) {
        $str = @trim($str);
        if(get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return mysql_real_escape_string($str);
    }

    if( isset($_POST['user_id']) &&  isset($_POST['food_id']) ) {

        $member_id = $_POST['user_id'];
        $food_id = $_POST['food_id'];

        //retrive the first quantity from the quantities table
        $quantities=mysql_query("SELECT * FROM quantities")
        or die("Something is wrong ... \n" . mysql_error());
        $row=mysql_fetch_assoc($quantities);
        $quantity_value = $row['quantity_value'];

        //retrive food_price from food_details based on $food_id
        $result=mysql_query("SELECT * FROM food_details WHERE food_id='$food_id'") or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours.");
        $food_row=mysql_fetch_assoc($result);
        $food_price=$food_row['food_price'];

        //define default values for quantity(got from $row), total($food_price*$quantity_value), and flag_0
        $quantity_id = 34;  // for 1 item
        $total = $food_price*1;
        $flag_0 = 0;

        //Create INSERT query
        $qry = "INSERT INTO cart_details(member_id, food_id, quantity_id, total, flag) VALUES('$member_id','$food_id','$quantity_id','$total','$flag_0')";
        $result = @mysql_query($qry);
        if($result) {
            echo "Added to Cart";
        }else {
            echo "Operation failed";
        }
    }
 ?>
