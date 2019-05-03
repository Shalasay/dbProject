<?
include "p01utility_functions.php";

$formtype = 's';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);


//sets current schedule
$sql = 	"select ctitle, p01section.crn, sectid, sem, credit, enrollflag, 
s.stid from p01enrolledcourses join p01section on p01enrolledcourses.crn = p01section.crn 
join p01student s on s.stid = p01enrolledcourses.stid join p01myclientsession p on 
s.clientid = p.clientid where sessionid = '$sessionid' 	";

//echo($sql);
//echo"enrollflag 0";
$result_array = execute_sql_in_oracle($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if($result == false){
	display_oracle_error_message($cursor);
	die("SQL Execution problem.");
}	
echo ("
  	<form method=\"post\" action=\"p01enroll_class.php?sessionid=$sessionid\">
  	<input type=\"submit\" value=\"Enroll a Class\"> <br>
  	</form>
  	");
echo("Current Schedule");

echo "<table border=1>";
echo "<tr> <th>Title</th> <th>ClassID</th> <th>Section</th> <th>Semester</th> <th>credits</th> </tr>";
while ($values = oci_fetch_array($cursor)){
	$title = $values[0];
	$cid = $values[1];
	$sectionid = $values[2];
	$semseter = $values[3];
	$credits = $values[4];
	$sid = $values[6];

	echo("<tr>" .
		"<td>$title</td> <td>$cid</td> <td>$sectionid</td> <td>$semseter</td> <td>$credits</td>" .
		//" <td> <A HREF=\"enroll_drop_class.php?sessionid=$sessionid&cid=$cid&sid=$sid\">Drop Class</A> </td> ".
		"</tr>");
}
echo "</table>";
oci_free_statement($cursor);

//==============================================================
//free
//search classes by id

$currentdate = getdate();
$year = $currentdate['year'];
$month = $currentdate['mon'];
$day = $currentdate['mday'];

echo " Current Date : ";
echo ($year);
echo "-";
echo ($month);
echo "-";
echo ($day);
echo "<br>";


//back button
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
			echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
			"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
			"</FORM>"); 
		}
		
		else if($values[1] != 1){
			//echo $values[1] . " != " . $formtype . "<br>\n";
			echo("<FORM action=\"p01adminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
			"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Go Back\"> " .
			"</FORM>"); 
		}	
		
	}
	
}				
oci_free_statement($cursor);


?>

