<?php
echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br><br>";
session_start();
//for database connection
include 'dbOracle.php';

/* *** A5 - Cross-Site Request Forgery. To help avoid csrf, the session id is re-generated and saved as a token to be later used for logout along with a get request.
 * 
 */	
session_regenerate_id();

$new_sessionid = session_id();

$_SESSION['token'] = $new_sessionid;


/* *** A8 - Failure to Restrict URL Access. Check authentication before providing access to page.
 *
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
	
	
	/* *** A2 - Cross-Site Scripting. Using reg ex and sanitize functions to remove unwanted category value item requests. 'cat' value must be a digit with a length of either 1 or 2.
	* 
	*/
	if (isset($_GET["cat"])) {
		if(preg_match('/(^[0-9]{1,2}$)/', $_GET['cat'])){
			
		//assign category id number
		$val= filter_input(INPUT_GET, 'cat', FILTER_SANITIZE_SPECIAL_CHARS);
		$val= strip_tags($val);

		$catid= $val;
		} else {
		//Only allow 'cat' request made up of 1 or 2 digits 
		die('WARNING: Invalid category value entry.<br><a href="main.php">Keep shopping</a>'); 
		}
		
		/* *** A1 - Injection attacks, converted all SQL statments to include binding/placeholders to prevent injection attacks.
		*
		*/
		
		$newconn = conndb();
		//sql
		$s = oci_parse($newconn, "select categories.cat_name, categories.cat_id from categories inner join cat_rel on cat_rel.cat_child_id=categories.cat_id and cat_rel.cat_parent_id=:pid_prefix");
		oci_bind_by_name($s, ':pid_prefix', $catid);
		oci_execute($s);
		
		//display categories as html links
		while (($row = oci_fetch_array($s))){
			
			$catname=$row['CAT_NAME'];
			$row_catid=$row['CAT_ID'];
			echo 'Category: <a href="menucat.php?cat='.$row_catid.'"> '.$catname.'</a> <br>';
			} 
		if(!$row){
			//if no childs, free sql
			oci_free_statement($s);
			
			/* *** A1 - Injection attacks, converted all SQL statments to include binding/placeholders to prevent injection attacks.
			 *
			*/
			
			//new query
			$s = oci_parse($newconn, "select distinct product.productname, product.productid, product.productprice from product inner join cat_prod_rel on cat_prod_rel.rel_prod_id=product.productid and cat_prod_rel.rel_cat_id=:pid_prefix");
			oci_bind_by_name($s, ':pid_prefix', $catid);
			oci_execute($s);
			
			//echo "<br>Here are the avialable products.<br><br>";
			
			//display product
			while (($row = oci_fetch_array($s)))
			{
				echo '<form name="form1">';
			
			
				$pID=$row['PRODUCTID'];
				//
				echo "productID: " , $row['PRODUCTID'] , "<br>";
				echo "productname: " , $row['PRODUCTNAME'] , "<br>";
				echo "productprice: " , $row['PRODUCTPRICE'] , "<br>";
				echo '<a href="cart.php?add='.$pID.'">Add to cart</a>';
				echo "</form>";
				echo "<p><HR><p>";
			}
			
			//oci_free_statement($s);
			//oci_close($newconn);
		}
		
		
	} else {
	//if no get variable passed	
	echo "Here are the avialable Categories.<br><br>";
	//create a new connection
	$newconn = conndb();
	//sql
	$s = oci_parse($newconn, "select categories.cat_name, categories.cat_id from categories inner join cat_rel on cat_rel.cat_child_id=categories.cat_id and cat_rel.cat_parent_id=5");
	oci_execute($s);
	
	//display master level of category
	while (($row = oci_fetch_array($s))){
		$catname=$row['CAT_NAME'];
		$catid=$row['CAT_ID'];
		echo 'Category: <a href="menucat.php?cat='.$catid.'"> '.$catname.'</a> <br>';
	} 
	}
	

	}else { 
       //Dsiplay this if user is not logegd in
	echo 'Not logged in.<br><a href="login.html">Login to view page</a></br>'; 
     }
     
     oci_free_statement($s);
     oci_close($newconn);
     
