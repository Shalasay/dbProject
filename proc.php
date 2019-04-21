<?

include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];
$clientid = $_GET['clientid'];

verify_session($sessionid, $formtype);

// fetch cnoList argument.  
// note that cnoList is an array.
$cnoList = $_POST["cnoList"];

// count the number of courses passed by multi.php
$numOfCno = count($cnoList);
// display the corresponding course numbers
// note that at this point, you can go on and do some more 
// complicated operations.


for($n=0; $n<$numOfCno; $n++){
	//echo "$cnoList[$n]<br>";
	
	$sql = "select sectid, crn, ctitle, credit from p01gensection where crn = '$cnoList[$n]'";
	 ("$sql");
	$result_array = execute_sql_in_oracle($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	$result = oci_execute($cursor);
	if($result == false){
		display_oracle_error_message($cursor);
		die("SQL Execution problem.");
	}	
	else{
		if($values = oci_fetch_array($cursor)){
			
			$sectid = $values[0];
			$crn = $values[1];
			$ctitle = $values[2];
			$credit = $values[3];
			$grade = 100;
			//$date = TO_DATE('2019/01/14' , 'yyyy/mm/dd');
			//echo("Today is : <br>");
			echo($sectid);
			echo($cnoList[$n]);
			echo "<br>";
			echo($ctitle);echo "<br>";
			//$echo($date);
			echo($credit);echo "<br>";
			echo($grade);echo "<br>";
			echo($clientid); echo"<BR>";
			
			//insert into p01section values ('CMSC', '10001' , 'Beginning Programming' ,TO_DATE('2019/01/14', 'yyyy/mm/dd'), 3, 90, 'b');
			$sql = "insert into p01section values ('$sectid', '$crn' , '$ctitle', TO_DATE('2019/01/14', 'yyyy/mm/dd'), $credit, $grade , '$clientid')";
			echo $sql;
			$result_array = execute_sql_in_oracle($sql);
			$result = $result_array["flag"];
			$cursor = $result_array["cursor"];
			$result = oci_execute($cursor);
			if($result == false){
				display_oracle_error_message($cursor);
				die("SQL Execution problem.");
			}	
			else{
				echo("<h1> Successfull enrolled");
				
			}
			
		}
	}
	
}

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
		if($values[0] == 1){
			if($values[1] == 1){
				echo("<FORM action=\"p01stuadminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
				"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
				"</FORM>");
			}else{
				echo("<FORM action=\"p01adminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
				"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
				"</FORM>");
			}
		}else if($values[0] == 0){
			if($values[1] == 1){
				echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
				"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
				"</FORM>");
			}else{
				die ('An Error has occurred.  Click <A href="p01login.html">here</A> to go back to the login page.');
			}
		}
	}
}

oci_free_statement($cursor);


?>
