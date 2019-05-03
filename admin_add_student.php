<?
include "p01utility_functions.php";

$pagetype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


// Obtain the inputs from dept_add_action.php
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$age = $_POST["age"];
$street = $_POST["street"];
$city = $_POST["city"];
$state = $_POST["state"];
$zipcode = $_POST["zipcode"];
$sttype = $_POST["sttype"];
$status = $_POST["status"];
echo("<title>Adding Student</title>");

// display the insertion form.
echo("
  <form method=\"post\" action=\"admin_add_student_action.php?sessionid=$sessionid\">
  First Name (Required): <input type=\"text\" value = \"$fname\" size=\"50\" maxlength=\"32\" name=\"fname\">  <br />
  Last Name (Required): <input type=\"text\" value = \"$lname\" size=\"50\" maxlength=\"32\" name=\"lname\">  <br />
  Age: <input type=\"text\" value = \"$age\" size=\"50\" maxlength=\"3\" name=\"age\">  <br />
  Address: <input type=\"text\" value = \"$street\" size=\"50\" maxlength=\"50\" name=\"street\">  <br />
  City: <input type=\"text\" value = \"$city\" size=\"50\" maxlength=\"50\" name=\"city\">  <br />
  State: <input type=\"text\" value = \"$state\" size=\"50\" maxlength=\"50\" name=\"state\">  <br />
  Zipcode: <input type=\"text\" value = \"$zipcode\" size=\"50\" maxlength=\"50\" name=\"zipcode\">  <br />
  Student Type(U/G): <input type=\"text\" value = \"$sttype\" size=\"50\" maxlength=\"32\" name=\"sttype\">  <br />
  Probation Status(Y/N): <input type=\"text\" value = \"$status\" size=\"50\" maxlength=\"5\" name=\"status\">  <br />


  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset\">
  </form>

  <form method=\"post\" action=\"p01adminwelcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");

?>