<?
include "p01utility_functions.php";

$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

$clientid = $_GET["clientid"];
$password = $_GET["password"];
$clienttype = $_GET["type"];

echo("Username: $clientid <br>");
echo("Password: $password <br>");
echo("Account Type: $clienttype <br>");


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
	
	
?>