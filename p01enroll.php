<html>
<head><title>Student Page</title></head>
<body>
<?
include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];
$clientid = $_GET['clientid'];
verify_session($sessionid, $formtype);
// fetch queryCno argument if any
$queryCno = $_POST["queryCno"];

// $queryCno is the variable holds the query condition
if(!isset($queryCno) or ($queryCno==""))
$whereClause = " 1=1 ";
else{
	$queryCno = strtoupper($queryCno);
	$whereClause = " cno like '%$queryCno%' ";
}

// set up environment
//putenv("ORACLE_HOME=/home/oracle/OraHome1");
//putenv("ORACLE_SID=orcl");

$connection = oci_connect ("gq055", "ujkorp", "gqiannew2:1521/pdborcl");
if($connection == false){
	$e = oci_error(); 
	die($e['message']);
}    

// processing queries based on course number
// note that the variable queryCno is passed to the file
// multi.php itself and processed at the beginning of the file
echo("<FORM name=\"queryCourse\" method=\"POST\" action=\"multi.php\"> " .
"Course No: <INPUT type=\"text\" name=\"queryCno\"> " .
"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Search\"> " .
"</FORM>"); 

// the query string
$query = "select sectid , crn, ctitle, description, credit , max_size, cur_size from p01gensection where " . $whereClause;

$cursor = oci_parse ($connection, $query);
if ($cursor == false){
	$e = oci_error($connection);  
	die($e['message']);
}

$result = oci_execute ($cursor);
if ($result == false){
	$e = oci_error($cursor);  
	die($e['message']);
}

// the form to process the selected courses
// the selected course numbers are in array cnoList[]
// and passed to proc.php
echo "<form action=\"proc.php?sessionid=$sessionid&clientid=$clientid\" method=\"post\">";
echo "<table border=1>";
echo "<tr>
	<td> </td>".
"<td><b>Section ID</b></td>" .
"<td><b>Course No</b></td>" .
"<td><b>Course title</b></td>" .
"<td><b>Description</b></td>" .
"<td><b>Credits</b></td>" .
"<td><b>Max Seats</b></td>" .
"<td><b>Open Seats</b></td> 

	</tr>";  

// fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
	$sectid = $values[0];
	$cno = $values[1];
	$ctitle = $values[2];
	$description = $values[3];
	$credits = $values[4];
	$max = $values[5];
	$cur = $values[6];
	echo "<tr>
<td><input type=\"checkbox\" name=\"cnoList[]\" value=\"$cno\"></td>" .
	"<td>$sectid</td>" .
	"<td>$cno</td>" .
	"<td>$ctitle</td>" .
	"<td>$description</td>" .
	"<td>$credits</td>" .
	"<td>$max</td>" .
	"<td>$cur</td>" .
	"</tr>";
}
echo "</table>";
echo "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Enroll\">";
echo "</form>";

oci_free_statement($cursor);

// close the connection with oracle
oci_close ($connection);
?>
</body>
</html>

