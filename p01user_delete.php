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
$aflag = $values[2];
$sflag = $values[3];

// Display the tuple to be deleted
echo("
  <form method=\"post\" action=\"p01user_delete_action.php?sessionid=$sessionid\">
  User Name: <input type=\"text\" disabled value = \"$clientid\" name=\"clientid\"> <br /> 
  Password: <input type=\"text\" disabled value = \"$password\" name=\"password\">  <br />
  Admin Flag: <input type=\"text\" disabled value = \"$aflag\" name=\"aflag\">  <br />
  Student Flag: <input type=\"text\" disabled value = \"$sflag\" name=\"sflag\">  <br />
  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"p01manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

?>