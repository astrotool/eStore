<?php
//for database connection
include 'dbOracle.php';

session_start();
if(isset($_SESSION['email']))
{
echo "hello user, ". $_SESSION['email']."";
} else {
	echo "not logged in.";
}
echo '<br><a href="logout.php">Logout</a>';

//create a new connection
$newconn = conndb();
//sql
$s = oci_parse($newconn, "select productname, productprice from product");
oci_execute($s);
//save result
$res = oci_fetch_all($s, $prod);

//dsplay products in table
 if ($s > 0) { 
 	   //define table properties
       print "<table border=1>"; 
       print "<tr>\n";
       //display colomn name in table header 
       while (list($key, $value) = each($prod)) { 
        print "<th>$key</th>\n"; 
       } 
       print "</tr>\n"; 
       print "</tr>\n"; 
       		//loop and display values in product table
  			for ($i = 0; $i < $res ; $i ++) {
				foreach($prod as $val){
					if($val[$i]==0){
					print '<td><a href="">'.$val[$i].'</a></td>\n';
					} else {
					print "<td>$val[$i]</td>\n";
					}
				}
		  //include another col for purchase button
          print "<td>Add</td>\n</tr>\n"; 
       } 
       print "</table>\n"; 
     } else { 
       echo "No products found<br />\n"; 
     }






