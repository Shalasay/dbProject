<?
include "p01utility_functions.php";

$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

$clientid = $_GET["clientid"];
$password = $_GET["password"];
$aflag = $_GET["aflag"];
$sflag = $_GET["sflag"];

echo("Username: $clientid <br>");
echo("Password: $password <br>");
echo("Account Type: $aflag <br>");
echo("Account Type: $sflag <br>");



$sql = "delete from p01users where clientid='$clientid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
    if ($result)
    {
        echo "Deleted Successfully";
        echo "<br>";
        echo("Click <A HREF = \"p01manage.php?sessionid=$sessionid\">Here</A> to go back to the Manage Accounts page.");
		Header("Location:p01manage.php?sessionid=$sessionid");

    }
    else
    {
       echo "ERROR!";
        // close connection 
       mysql_close();
	   echo("Cannot delete ");
		
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