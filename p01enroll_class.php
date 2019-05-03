<?
include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

//class search

$currentdate = getdate();
$year = $currentdate['year'];
$month = $currentdate['mon'];
$day = $currentdate['mday'];

echo "Class Search  -- Current Date : ";
echo ($year);
echo "-";
echo ($month);
echo "-";
echo ($day);
echo "<br>";


echo("
	<form method=\"post\" action=\"p01enroll_class.php?sessionid=$sessionid\">
	Class ID: <input type=\"text\" size=\"6\" maxlength=\"6\" name=\"q_crn\"> 
	Section ID: <input type=\"text\" size=\"6\" maxlength=\"6\" name=\"q_sectid\"> 
	<input type=\"submit\" value=\"Search\">
	</form>
	");
echo("
	<form method=\"post\" action=\"p01enroll_class_action.php?sessionid=$sessionid\">
	ClassID: <input type=\"text\" value = \"$crn1\" size=\"6\" maxlength=\"6\" name=\"crn1\"> 
	SectionID: <input type=\"text\" value = \"$sectid1\" size=\"6\" maxlength=\"6\" name=\"sectid1\"> <br>
	ClassID: <input type=\"text\" value = \"$crn2\" size=\"6\" maxlength=\"6\" name=\"crn2\"> 
	SectionID: <input type=\"text\" value = \"$sectid2\" size=\"6\" maxlength=\"6\" name=\"sectid2\"> <br>
	ClassID: <input type=\"text\" value = \"$crn3\" size=\"6\" maxlength=\"6\" name=\"crn3\"> 
	SectionID: <input type=\"text\" value = \"$sectid3\" size=\"6\" maxlength=\"6\" name=\"sectid3\"> <br>
	ClassID: <input type=\"text\" value = \"$crn4\" size=\"6\" maxlength=\"6\" name=\"crn4\"> 
	SectionID: <input type=\"text\" value = \"$sectid4\" size=\"6\" maxlength=\"6\" name=\"sectid4\"> <br>
	ClassID: <input type=\"text\" value = \"$crn5\" size=\"6\" maxlength=\"6\" name=\"crn5\"> 
	SectionID: <input type=\"text\" value = \"$sectid5\" size=\"6\" maxlength=\"6\" name=\"sectid5\"> <br>
	<input type=\"submit\" value=\"Add Classes\">
	</form>
	");
	
// Interpret the query requirements	
$q_crn = $_POST["q_crn"];
$q_sectid = $_POST["q_sectid"];

$whereClause = "1=1";

if(isset($q_crn) and trim($q_crn)!= ""){
	$whereClause .= " and crn like '%$q_crn%'";
}

if(isset($q_sectid) and trim($q_sectid)!= ""){
	$whereClause .= " and sectid like '%$q_sectid%'";
}

$sql = "select crn, ctitle, credit, sem, 
sectid, stime, max_size, cur_size, deadline from p01gensection natural join p01section where $whereClause";
//echo ($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

// Display the query results
echo "<table border=1>";
echo "<tr><th>Class id</th> <th>Title</th> <th>Section</th> <th>Semester</th> <th>Time</th> <th>Credits</th> <th>Max Seats</th> <th>Seats Left</th> <th>Enrollment Deadline</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $cid = $values[0];
  $title = $values[1];
  $credits = $values[2];
  $semester = $values[3];
  $sectionid = $values[4];
  $stime = $values[5];
  $maxstudents = $values[6];
  $numstudents = $values[7];
  $enrolldeadline = $values[8];

  $numstudents = ($maxstudents - $numstudents);

  echo("<tr style='text-align:center;'><td>$cid</td> 
  <td style='text-align:center;'>$title</td> 
  <td style='text-align:center;'>$sectionid</td> 
  <td style='text-align:center;'>$semester</td>
  <td style='text-align:center;'>$stime</td> 
  <td style='text-align:center;'>$credits</td>
  <td style='text-align:center;'>$maxstudents</td> 
  <td style='text-align:center;'>$numstudents</td>
  <td style='text-align:center;'> $enrolldeadline</td>".
  	//" <td> <A HREF=\"enroll_add_class_action.php?sessionid=$sessionid&cid=$cid&sectionid=$sectionid&semester=$semester\">Add Class</A> </td> ".
    "</tr>");
}

echo "</table>";	





	
	
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
			echo("<FORM action=\"p01enroll.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
			"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Back\"> " .
			"</FORM>"); 
		}
		
		else if($values[1] != 1){
			//echo $values[1] . " != " . $formtype . "<br>\n";
			echo("<FORM action=\"p01enroll.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
			"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Back\"> " .
			"</FORM>"); 
		}	
		
	}
	
}				
oci_free_statement($cursor);
?>