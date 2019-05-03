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

$sql = 'begin create_new_id(:fname, :lname, :age, :street, :city, :state, :zipcode, :sttype, :status, :id); end;';

$cursor = oci_parse($connection, $sql);
if($cursor == false){
  echo oci_error($connection)."<br>";
  exit;
}

oci_bind_by_name($cursor, ':fname', $fname, 30);
oci_bind_by_name($cursor, ':lname', $lname, 30);
oci_bind_by_name($cursor, ':age', $age, 3);
oci_bind_by_name($cursor, ':street', $street, 30);
oci_bind_by_name($cursor, ':city', $city, 30);
oci_bind_by_name($cursor, ':state', $state, 30);
oci_bind_by_name($cursor, ':zipcode', $zipcode, 30);
oci_bind_by_name($cursor, ':sttype', $sttype, 30);
oci_bind_by_name($cursor, ':status', $status, 30);
oci_bind_by_name($cursor, ':id', $id, 30);

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$stage = $_POST["age"];
$street = $_POST["street"];
$city = $_POST["city"];
$state = $_POST["state"];
$zipcode = $_POST["zipcode"];
$sttype = $_POST["sttype"];
$status = $_POST["status"];
$id;

$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
if($result == false){
  display_oracle_error_message($cursor);
  echo oci_error($cursor)."<BR>";
  exit;
}

oci_free_statement($cursor);
 
Header("Location:admin_add_student.php?sessionid=$sessionid");
?>