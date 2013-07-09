<?php
echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
session_start();
//Functions included, database connection, encryption, user registration 
include 'dbOracle.php';

/* *** A5 - Cross-Site Request Forgery. To help avoid csrf, the session id is re-generated and saved as a token to be later used for 
 *	logout along with a get request.
*/
session_regenerate_id();

$new_sessionid = session_id();

$_SESSION['token'] = $new_sessionid;
//add to cart function
function addToCart(){
	if(isset($_GET['add'])){
		$_SESSION['cart'] = array();
	}
	/* *** A10 -  Unvalidated Redirects and Forwards. Only relative url is given and the full/absolute is avoided. 
	 * 
	 */
	header("location: cart.php");
	//print "test";
}

/* *** A8 - Failure to Restrict URL Access. Check authentication before providing access to page.
 * Diplay main page if user is logged in.
*/
if(isset($_SESSION['email']))
{
//decode encrypted data	
$usr_ = decEnc($_SESSION['email']);
//welcome message and logout link
echo "<h2>The MQ Bookstore!</h2><br>";
echo "hello user, ".$usr_.", ";
/* *** A5 - Cross-Site Request Forgery. To help avoid csrf, the session id is saved as a token which must be included in the logout request.
 * 
 */	
echo '<a href="logout.php?csrf='. $_SESSION["token"] .' ">Logout</a>, <a href="cart.php">View your cart</a></br>';
echo '<h4>*NEW*<a href="menucat.php">Category view of menu items</a></h4></br>';
echo "Here are the entire items avilable for purchase.<br><br>";

	
//create a new connection
$newconn = conndb();
//sql
$s = oci_parse($newconn, "select * from PRODUCT order by PRODUCTID asc");
oci_execute($s);
//save result
//$res = oci_fetch_array($prod);

//dsplay products in table
 if ($s) { 

    while (($row = oci_fetch_array($s)))
	{
	echo '<form name="form1">';
	
		
		$pID=$row['PRODUCTID'];
    // Use the uppercase column names for the associative array indices
    echo "productID:" , $row['PRODUCTID'] , "<br>";
	echo "productname:" , $row['PRODUCTNAME'] , "<br>";
	echo "productprice:" , $row['PRODUCTPRICE'] , "<br>";
	echo '<a href="cart.php?add='.$pID.'">Add to cart</a>';
	echo "</form>";
	echo "<p><HR><p>";
	}
 	
 	
       
       
       
     } else { 
       echo "No products found<br />\n"; 
     }
	 //get call to add item to cart	
     if(isset($_GET['add']))
     {
     	addToCart();
     }
     
     //not used, for testing only
     if (isset($_GET['action'])){
     	
     }
     
     //output
}   else {
	//Dsiplay this if user is not logegd in
	echo 'Not logged in.<br><a href="login.html">Login to view page</a></br>';
	
}  



	 
	 
	 
	 





