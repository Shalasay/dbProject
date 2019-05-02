<?
include "p01utility_functions.php";

$formtype = 'admin';
$sessionid = $_GET["sessionid"];
verify_session($sessionid, $formtype);

$connection = oci_connect ("gq055", "ujkorp", "gqiannew2:1521/pdborcl");
if ($connection == false){
	echo oci_error()."<BR>";
	exit;
}

$sql = 'begin create_new_id(:fname, :lname, :stage, :staddress, :sttype, :ststatus, :id); end;';

echo("<title>Add Success! </title>");

$cursor = oci_parse($connection, $sql);
if($cursor == false){
  echo oci_error($connection)."<br>";
  exit;
}

oci_bind_by_name($cursor, ':fname', $fname, 30);
oci_bind_by_name($cursor, ':lname', $lname, 30);
oci_bind_by_name($cursor, ':stage', $age, 3);
oci_bind_by_name($cursor, ':staddress', $staddress, 30);
oci_bind_by_name($cursor, ':sttype', $sttype, 30);
oci_bind_by_name($cursor, ':ststatus', $ststatus, 30);
oci_bind_by_name($cursor, ':id', $id, 30);

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$stage = $_POST["stage"];
$staddress = $_POST["staddress"];
$sttype = $_POST["sttype"];
$ststatus = $_POST["ststatus"];
$id;

oci_execute($cursor);
oci_free_statement($cursor);
 
Header("Location:admin_add_student.php?sessionid=$sessionid");
?>