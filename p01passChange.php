<?
include "p01utility_functions.php";
$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);	


//echo("$sessionid");

//change password
echo("<TITLE>Change Password </TITLE>");
echo("<h1>Change Password</h1>"); 	

//get values to be changed from p01Pass_Change_Action
//$password = $_GET("password");

//echo("$getid");

//display insertion form to change pass

echo("<FORM action=\"p01Pass_Change_Action.php?sessionid=$sessionid\" name=\"Change Password: \" method=\"POST\"> " .
"New password: <INPUT type=\"text\" name=\"password\"> " .
"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Submit\"> " .
"</FORM>"); 


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
		
		if($values[0] != 1){
			//echo $values[0] . " != " . $formtype . "<br>\n";
			echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
			"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
			"</FORM>"); 
		}
		
		else if($values[1] != 1){
			//echo $values[1] . " != " . $formtype . "<br>\n";
			echo("<FORM action=\"p01adminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
			"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Go Back\"> " .
			"</FORM>"); 
		}	
		
	}
	
}					

?>