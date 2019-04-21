<?
include "p01utility_functions.php";

$pagetype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


// Obtain the inputs from dept_add_action.php
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$clientid = $_POST["clientid"];
$stage = $_POST["stage"];
$staddress = $_POST["staddress"];
$sttype = $_POST["sttype"];
$ststatus = $_POST["ststatus"];
echo("<title>Adding Student</title>");

// display the insertion form.
echo("
  <form method=\"post\" action=\"admin_add_student_action.php?sessionid=$sessionid\">
  First Name (Required): <input type=\"text\" value = \"$fname\" size=\"50\" maxlength=\"32\" name=\"fname\">  <br />
  Last Name (Required): <input type=\"text\" value = \"$lname\" size=\"50\" maxlength=\"32\" name=\"lname\">  <br />
  ClientID: <input type=\"text\" value = \"$clientid\" size=\"50\" maxlength=\"8\" name=\"clientid\">  <br />
  Age: <input type=\"text\" value = \"$stage\" size=\"50\" maxlength=\"3\" name=\"stage\">  <br />
  Address: <input type=\"text\" value = \"$staddress\" size=\"50\" maxlength=\"50\" name=\"staddress\">  <br />
  Type(Undergraduate/Graduate): <input type=\"text\" value = \"$sttype\" size=\"50\" maxlength=\"32\" name=\"sttype\">  <br />
  Status(?): <input type=\"text\" value = \"$ststatus\" size=\"50\" maxlength=\"5\" name=\"ststatus\">  <br />


  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset\">
  </form>

  <form method=\"post\" action=\"p01adminwelcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");

?>