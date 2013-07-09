//---------------------form_validate.js by Ali Alavi 40876144------------------------
/*This file validates the fallowing inputed values;
 * Email: must contain '@' and no other illegal characters(ie. : / '). The address must end with 'mq.edu.au'.
 *Postcode: Must be 4 numbers long and made up of only numbers.
 *Credit card: If visa selected then number must be 10 digits long. For Mastarcard 14 digits long.
 */
//----------------------------------------------------------------------------
function checkForm(formObj)
{

//assign email input from form to variable 'email'
var email = formObj.email.value;

//Check the email field if form is valid
	if (email.length == 0) {
window.alert("You must provide an e-mail address.");
formObj.email.focus();
return false;
}
//Check if email field contains illegal characters
if (email.indexOf("/")> -1||email.indexOf(":") > -1||email.indexOf(",") > -1||email.indexOf(";") > -1) {
window.alert("E-mail address has invalid character.");
formObj.email.focus();
return false;
}
//Check email value has '@' and '.'
if (email.indexOf("@") < 0||email.indexOf("\.") < 0) {
window.alert("E-mail address is invalid.");
formObj.email.focus();
return false;
}
//check email is mq.edu.au using reg exp, if the email doesn not end with 'mq.edu.au' an alert will be raised
if (String(email).search(/mq.edu.au$/) < 0){
window.alert("Requires an mq.ed.au email address");
formObj.email.focus();
return false;
}
	
//Check password is entered
if (formObj.pass1.value == "") {
window.alert("You must enter a password in the field.");
formObj.pass1.focus();
return false;
}
//Check password confirmation is entered
if (formObj.pass2.value == "") {
window.alert("You must re-enter your password in the field.");
formObj.pass2.focus();
return false;
}
//Check password is entered
if (formObj.pass1.value != formObj.pass2.value) {
window.alert("Passwords do not match, please re-enter.");
formObj.pass1.focus();
return false;
}
//Check first name field
if (formObj.fname.value == "") {
window.alert("You must enter a value in the field.");
formObj.fname.focus();
return false;
}
//Check last name field
if (formObj.lname.value == "") {
window.alert("You must enter a value in the field.");
formObj.lname.focus();
return false;
}

//assign postcode to variable 'postc'
var postc = formObj.postcode.value;

//check postcode length
if (postc.length != 4) {
window.alert("Invalid post code please check number.");
formObj.postcode.focus();
return false;
}

//check postcode is made up of numbers only
if (String(postc).search(/^\s*\d+\s*$/) < 0){
window.alert("postcode must be all numbers");
formObj.postcode.focus();
return false;
}

//assign credit card number from form to variable 'cardnum'
var cardnum = formObj.cardnum.value;

//Check the type of credit card chosen
if (formObj.ccselect.value == 0) {
window.alert("You must select a credit card type from the list.");
formObj.ccselect.focus();
return false;
}
//Check if credit card number field is empty
	if (cardnum.length == 0) {
window.alert("You must provide a credit card number.");
formObj.cardnum.focus();
return false;
}
//Check the length depending on credit card list choice, Visa, 16 digits
if (formObj.ccselect.value == 1) {
	if (cardnum.length != 16) {
	window.alert("Invalid credit card number please check number. Must be 16 digits long.");
	formObj.cardnum.focus();
	return false;
	}
}
//Check the length depending on credit card list choice, Mastercard, 14 digits
if (formObj.ccselect.value == 2) {
	if (cardnum.length != 14) {
	window.alert("Invalid credit card number please check number. Must be 14 digits long.");
	formObj.cardnum.focus();
	return false;
	}
}

for (i=0; i<cardnum.length; i++) {
if (cardnum.charAt(i) < "0" || cardnum.charAt(i) > "9") {
window.alert("Credit Card must only contain numbers.");
return false;
}
}

return formValid;
}
