<?
include "p01utility_functions.php";

$pagetype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


// Obtain the inputs from dept_add_action.php
$fname = $_POST["fname"];
$lname = $_POST["lname"];
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
  Age: <input type=\"text\" value = \"$stage\" size=\"50\" maxlength=\"3\" name=\"stage\">  <br />
  Address Number: <input type=\"text\" value = \"$staddress\" size=\"50\" maxlength=\"50\" name=\"staddress\">  <br />
  Student Type(U/G): <input type=\"text\" value = \"$sttype\" size=\"50\" maxlength=\"32\" name=\"sttype\">  <br />
  Probation Status(Y/N): <input type=\"text\" value = \"$ststatus\" size=\"50\" maxlength=\"5\" name=\"ststatus\">  <br />


  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset\">
  </form>

  <form method=\"post\" action=\"p01adminwelcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");

?>