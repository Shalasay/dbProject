<?
include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];

verify_session($sessionid, $formtype);

$clientid = $_GET["clientid"];
$crn = $_GET["crn"];
echo("clientid : ");
echo($clientid);
echo(" crn :");
echo($crn);

echo("<h1>Course Details</h1>");
$sql = "select * from p01gensection where crn = (select b.crn from p01section b where b.clientid ='$clientid' and b.crn = '$crn')";
$result_array = execute_sql_in_oracle($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$result = oci_execute($cursor);
if($result == false){
	display_oracle_error_message($cursor);
	die("SQL Execution problem.");
	echo("failed");
}	
else{
	echo "<table  border=1>";
		echo "<tr> <th>Student ID</th> <th>CRN</th> <th>Course Title</th> <th>Course Description</th> <th>Credit</th><th>Date</th> </tr>";
		{
			
		// Fetch the result from the cursor one by one
			while ($values = oci_fetch_array ($cursor)){
			$stid = $values[0];
			$crn = $values[1];
			$ctitle= $values[2];
			$description = $values[3];
			$credit = $values[4];
			$date = $values[5];
			$max = $values[6];
			$cur = $values[7];
			
			echo("<tr>" . 
			"<td style='text-align:center;'>$stid</td>  		
			<td style='text-align:center;' >$crn</td>
			<td style='text-align:center;'>$ctitle </td> 
			<td style='text-align:center;'>$description</td> 
			<td style='text-align:center;'>$credit </td> 
			<td style='text-align:center;'>$date</td>
			".
			"</tr>");
			}
		}	
		}
		

?>