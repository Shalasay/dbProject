<?
include "p01utility_functions.php";
ini_set( "display_errors", 0);  
$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);	

$clientid = $_GET["clientid"];
$pass = trim($_POST["pass"]);
$aflag = $_POST["aflag"];
$sflag = $_POST["sflag"];

	echo("<title>Change Success!</title>");

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
	  if($values = oci_fetch_array($cursor)){
		  $sql = "update p01users set password ='$pass', aflag = '$aflag', sflag = '$sflag' where clientid = '$clientid'";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"acc_update.php?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$clientid\" name=\"clientid\">
  <input type=\"hidden\" value = \"$aflag\" name=\"admin flag\">
  <input type=\"hidden\" value = \"$sflag\" name=\"student flag\">
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}
else{
	echo("<h1>Update Successful </h1><br>");
	echo("New CLIENT ID: $clientid <br>");
	echo("New PASSWORD: $pass <br>");
	echo("New Admin Flag: $aflag <br>");
	echo("New Student Flag: $sflag <br>");
// echo("SESSIONS ID: $sessionid <br>");	
}
	  }
  }



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