<?
//ini_set( "display_errors", 0);  

include "p01utility_functions.php";

$formtype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

$clientid = $_POST["clientid"];
$password = $_POST["password"];
$clienttype = $_POST["clienttype"];

// the sql string
$sql = "insert into p01users values ('$clientid', '$password', '$clienttype')";
//echo($sql);
echo("<title>Add Success!</title>");
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"p01user_add.php?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$clientid\" name=\"clientid\">
  <input type=\"hidden\" value = \"$password\" name=\"password\">
  <input type=\"hidden\" value = \"$clienttype\" name=\"clienttype\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

Header("Location:p01manage.php?sessionid=$sessionid");
?>