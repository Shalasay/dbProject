<?
include "p01utility_functions.php";

$formtype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


//Obtain the inputs from dept_add_action.php
$clientid = $_POST["clientid"];
$password = $_POST["password"];
echo("<title>Adding Administrator Account</title>");

//display the insertion form.
echo("
  <form method=\"post\" action=\"p01user_add_action.php?sessionid=$sessionid\">
  Username (Up to 8 characters): <input type=\"text\" value = \"$clientid\" size=\"20\" maxlength=\"10\" name=\"clientid\"> <br /> 
  Password (Required): <input type=\"text\" value = \"$password\" size=\"50\" maxlength=\"50\" name=\"password\">  <br />
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset\">
  </form>

  <form method=\"post\" action=\"p01adminwelcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Main Page\">
  </form>");

  
  //Back button to manage
echo("<br>");
$sql = "select clientid " .
"from p01users natural join p01myclientsession " .
"where sessionid = '$sessionid'";

$result_array = execute_sql_in_oracle($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$result = oci_execute($cursor);
if($result == false){
	display_oracle_error_message($cursor);
	die("SQL Execution problem.");
}	
else{
	$sql = "select aflag, sflag " .
	"from p01users natural join p01myclientsession " .
	"where sessionid = '$sessionid'";
	
	$result_array = execute_sql_in_oracle($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	$result = oci_execute($cursor);
	if($result == false){
		display_oracle_error_message($cursor);
		die("SQL Execution problem.");
	}	
	if($values = oci_fetch_array($cursor)){
		if($values[0] == 1){
			if($values[1] == 1){
				echo("<FORM action=\"p01manage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
				"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Back\"> " .
				"</FORM>");
			}else{
				echo("<FORM action=\"p01manage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
				"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Back\"> " .
				"</FORM>");
			}
		}else if($values[0] == 0){
			if($values[1] == 1){
				echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
				"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Back\"> " .
				"</FORM>");
			}else{
				die ('An Error has occurred.  Click <A href="p01login.html">here</A> to go back to the login page.');
			}
		}
	}
}
?>