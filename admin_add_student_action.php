<?
include "p01utility_functions.php";

$formtype = 'admin';
$sessionid = $_GET["sessionid"];
verify_session($sessionid, $formtype);

$stid = md5(uniqid(rand()));
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$clientid = $_POST["clientid"];
$stage = $_POST["stage"];
$staddress = $_POST["staddress"];
$sttype = $_POST["sttype"];
$ststatus = $_POST["ststatus"];

$sql = "insert into p01student values " .
		"('$stid', '$fname', '$lname', '$stage', '$staddress', '$sttype', '$ststatus', '$clientid')";
		
echo("<title>Add Success!</title>");
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
	echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"admin_add_student_action.php?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$stid\" name=\"stid\">
  <input type=\"hidden\" value = \"$fname\" name=\"fname\">
  <input type=\"hidden\" value = \"$lname\" name=\"lname\">
  <input type=\"hidden\" value = \"$clientid\" name=\"clientid\">
  <input type=\"hidden\" value = \"$stage\" name=\"stage\">
  <input type=\"hidden\" value = \"$staddress\" name=\"staddress\">
  <input type=\"hidden\" value = \"$sttype\" name=\"sttype\">
  <input type=\"hidden\" value = \"$ststatus\" name=\"ststatus\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

Header("Location:admin_add_student.php?sessionid=$sessionid");
?>