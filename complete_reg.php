<?php
echo "<strong>COMP344 Assignment 1, 2012 by Ali Alavi - #40876144</strong><br><br>";
//'smail' is a function for sending email using information gathered from a html form 

function smail(){
//assign form values to variables
$email = $_POST["email"];
$name = $_POST["fname"];
$subject = "Online bookstore registration";
$message = "Hello $name! Please fallow these steps to complete registration.";
$from = "admin@mqbookstore.com";
$headers = "From:" . $from;

//if statement to check mail function works, else returns error message.
if (mail($email,$subject,$message,$headers))
{
print("Thank you for registering for the bookstore, <i>$name</i>. Please check your email address(<i>$email</i>) for instruction to complete registration.");}
else {
	echo "Unable to send email.";
}
}
//execute function
smail();


?>

