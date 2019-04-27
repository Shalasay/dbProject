<?

include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];
$clientid = $_GET['clientid'];

verify_session($sessionid, $formtype);

// fetch cnoList argument.  
// note that cnoList is an array.
$cnoList = $_POST["cnoList"];
echo($cnoList[0]); echo"<BR";
// count the number of courses passed by multi.php
$numOfCno = count($cnoList);

// display the corresponding course numbers
// note that at this point, you can go on and do some more 
// complicated operations.


for($n=0; $n<$numOfCno; $n++){
	//echo "$cnoList[$n]<br>";
	echo("<br>n1: $n <br>");
		if($n == $numOfCno){
				break;
			}
			else{
				$sql = "select sectid, crn, ctitle, credit, sem from p01gensection where crn = '$cnoList[$n]'";
	 echo("$sql");
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
				echo("<br>n2: $n <br>");
			echo("=======Course to add into Section=========="); echo("<BR>");
			$sectid = $values[0];
			$crn = $values[1];
			$ctitle = $values[2];
			$credit = $values[3];
			$grade = 100;
			$date = $values[4];			
			
			echo("section id: $sectid"); echo "<br>";
			echo("course number: $cnoList[$n]");echo "<br>";
			echo("course title: $ctitle");echo "<br>";
			echo("credit: $credit");echo "<br>";
			echo("grade: $grade");echo "<br>";
			echo("client id: $clientid"); echo "<br>";
			echo"====end of information to add into section=======";
			echo("<br> n3: $n <br>");
			$sql = "insert into p01section values ('$sectid', '$crn' , '$ctitle', '$date', $credit, $grade , '$clientid')";
			echo $sql;
				echo("<br>n4: $n <br>");
			$result_array = execute_sql_in_oracle($sql);
			$result = $result_array["flag"];
			$cursor = $result_array["cursor"];
		
			if($result == false){
				display_oracle_error_message($cursor);
				die("SQL Execution problem.");
			}	
			else{
				echo("<h1> Successfull enrolled</h1>");
				echo("n5: $n <br>");
			}
			echo("n7: $n <br>");
		}
		echo("n8: $n <br>");
	}
	echo("n9: $n <br>");
}
}
	
echo("n10: $n <br>");
//end of 


//Main Menu button
oci_free_statement($cursor);
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
//Header("Location:p01stuwelcomepage.php?sessionid=$sessionid");

?>
