<?
include "p01utility_functions.php";

$formtype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

$q_clientid = $_GET["clientid"];

$sql = "select clientid, password, aflag, sflag from p01users where clientid = '$q_clientid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ // error unlikely
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
  // Record already deleted by a separate session.  Go back.
  Header("Location:p01manage.php?sessionid=$sessionid");
}
oci_free_statement($cursor);

$clientid = $values[0];
$password = $values[1];
$clienttype = $values[2];
$aflag = $values[2];
$sflag = $values[3];
// Display the tuple to be deleted
echo("
  <form method=\"post\" action=\"p01user_delete_action.php?sessionid=$sessionid\">
  Number (Read-only): <input type=\"text\" readonly value = \"$clientid\" name=\"clientid\"> <br /> 
  Name: <input type=\"text\" disabled value = \"$password\" name=\"password\">  <br />
 Admin Flag: <input type=\"text\" disabled value = \"$aflag\" name=\"aflag\">  <br />
  Student Flag: <input type=\"text\" disabled value = \"$sflag\" name=\"sflag\">  <br />  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"p01adminwelcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Main page\">
  </form>
  ");

  
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