<?php
echo "COMP344 Assignment 1, 2012 by Ali Alavi - #40876144<br>";
session_start();
//Functions included, database connection, encryption, user registration
include 'dbOracle.php';

/* *** A5 - Cross-Site Request Forgery. To help avoid csrf, the session id is saved as a token which must be included in the logout request.
 *
*/

if(isset($_GET["csrf"]) && $_GET["csrf"] == $_SESSION["token"]) {

	session_unset(isset($_SESSION['email']));
	session_destroy();
echo '<br>You have logged out.<br><a href="login.html">Return to login page</a>';} else {
	
	die('WARNING: Invalid logout attempt.<br><a href="main.php">Keep shopping</a>');
}