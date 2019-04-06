<?
include "p01utility_functions.php";

$formtype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


//Obtain the inputs from dept_add_action.php
$clientid = $_POST["clientid"];
$password = $_POST["password"];
$clienttype = $_POST["clienttype"];
echo("<title>Adding Account</title>");

//display the insertion form.
echo("
  <form method=\"post\" action=\"p01user_add_action.php?sessionid=$sessionid\">
  Username (Up to 8 characters): <input type=\"text\" value = \"$clientid\" size=\"20\" maxlength=\"10\" name=\"clientid\"> <br /> 
  Password (Required): <input type=\"text\" value = \"$password\" size=\"50\" maxlength=\"50\" name=\"password\">  <br />
  Account Type (Required): <input type=\"text\" value = \"$clienttype\" size=\"50\" maxlength=\"50\" name=\"clienttype\">  <br />
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset\">
  </form>

  <form method=\"post\" action=\"p01adminwelcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");

?>