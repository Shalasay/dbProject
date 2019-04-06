<?
include "p01utility_functions.php";

$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


// connection OK - delete the session.
$sql = "delete from p01myclientsession where sessionid = '$sessionid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message($cursor);
  die("Session removal failed");
}

// jump to login page
header("Location:p01login.html");
?>