<?
include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];

verify_session($sessionid, $formtype);

echo("<title>Student Page</title>");

// Here we can generate the content of the welcome page
echo("Student Management Menu: <br />");
//echo("<UL><LI><A HREF=\"p01department.php?sessionid=$sessionid\">test</A></LI><LI><A HREF=\"p01employee.php?sessionid=$sessionid\">two</A></LI></UL>");

echo("<h1>Student info</h1>");
$sql = "begin update_gpa('$sid'); " .
		"end;";
//echo($sql);
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}
//======================================================================
//get clientid for displaying student INFO of 
//ID, FIRST NAME, LAST NAME, AGE, ADDRESS, TYPE, UNDER PROBATION
$sql = "select clientid  " .
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
	if($values = oci_fetch_array($cursor)){
		
		$val = $values[0];
echo("<BR>");

		// Form the query and execute it
		$sql = "select * from p01student where clientid = '$val'";
		//echo($sql);

		//echo("<BR><BR><BR>");
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		$cursor = $result_array["cursor"];

		//echo($result_array);
		//echo($result);
		//echo($cursor);
		if ($result == false){
			display_oracle_error_message($cursor);
			die("Client Query Failed.");
		}
		// Display the query results
		echo "<table  border=1>";
		echo "<tr> <th>Student ID</th> <th>First Name</th> <th>Last Name</th> <th>Age</th> <th> Address</th><th>Type</th> <th>Under Probation</th> </tr>";
		{
			
		// Fetch the result from the cursor one by one
			while ($values = oci_fetch_array ($cursor)){
			$stid = $values[0];
			$fname = $values[1];
			$lname = $values[2];
			$stage = $values[3];
			$staddress = $values[4];
			$sstype = $values[5];
			$sstatus = $values[6];
			$stclientid = $values[7];
			
			echo("<tr>" . 
			"<td style='text-align:center;'>$stid</td>  
			<td style='text-align:center;'>$fname</td> 
			<td style='text-align:center;' >$lname</td>
			<td style='text-align:center;'>$stage </td> 
			<td style='text-align:center;'>$staddress</td> 
			<td style='text-align:center;'>$sstype</td> 
			<td style='text-align:center;'>$sstatus</td>".
			"</tr>");
			}
		}	
	}
	
}


echo("<br >");
echo("<br >");
echo("Click <A HREF = \"p01logout_action.php?sessionid=$sessionid\">here</A> to Logout.");
echo("<br >");

echo("Click <A HREF =\"p01passChange.php?sessionid=$sessionid\">here</A> to Change Password.");echo("<br >");

echo("Click <A HREF=\"p01enroll.php?sessionid=$sessionid&clientid=$val\"> here </A> to enroll.");echo("<br >");
echo("Click <A HREF=\"p01transcript.php?sessionid=$sessionid&stid \"> here </A> to see transcript.");



echo("<BR><BR><BR>");


//=========================================================================


oci_free_statement($cursor);
?>