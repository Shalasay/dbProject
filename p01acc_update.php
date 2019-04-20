<?
include "p01utility_functions.php";
$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);	


//echo("$sessionid");

//change password
echo("<TITLE>Change Password </TITLE>");
echo("<h1>Change Password</h1>"); 	

$clientid = $_GET["clientid"];
$pass = $_GET["password"];
$aflag = $_GET["aflag"];
$sflag = $_GET["sflag"];

 echo("CLIENT ID: $clientid <br>");
// echo("PASSWORD: $pass <br>");
// echo("Admin Flag: $aflag<br>");
// echo("Student Flag: $sflag<br>");
// echo("SESSIONS ID: $sessionid <br>");
//"Account ID: <INPUT type=\"text\" name=\"clientid\" value=\"$clientid\"> <br>" .

echo("<FORM action=\"p01acc_update_action.php?sessionid=$sessionid&clientid=$clientid\" name=\"Change Password: \" method=\"POST\"> " .
	 "Account Password: <INPUT type=\"text\" name=\"pass\" value=\"$pass\"> <br>" .
	 "Admin Flag: <INPUT type=\"text\" name=\"aflag\" value=\"$aflag\"> <br>" .
	 "Student Flag: <INPUT type=\"text\" name=\"sflag\" value=\"$sflag\"> <br>" .
     "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Submit\"> " .
     "</FORM>"); 

//Main Page Button
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
						echo("<FORM action=\"p01stuadminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
					   "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
					   "</FORM>");
					}else{
						echo("<FORM action=\"p01adminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
					   "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
					   "</FORM>");
					}
				}else if($values[0] == 0){
					if($values[1] == 1){
						echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
					   "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
					   "</FORM>");
					}else{
						die ('An Error has occurred.  Click <A href="p01login.html">here</A> to go back to the login page.');
					}
				}
		  }
	}
?>