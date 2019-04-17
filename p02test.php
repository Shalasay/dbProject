<?
include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

echo("<title>Student Page</title>");

// Here we can generate the content of the welcome page
echo("Student Management Menu: <br />");
//echo("<UL><LI><A HREF=\"p01department.php?sessionid=$sessionid\">test</A></LI><LI><A HREF=\"p01employee.php?sessionid=$sessionid\">two</A></LI></UL>");




echo("<br />");
echo("<br />");
echo("Click <A HREF = \"p01logout_action.php?sessionid=$sessionid\">here</A> to Logout.");
echo("<br />");

echo("Click <A HREF = \"p01passChange.php?sessionid=$sessionid\">here</A> to Change Password.");

echo("<BR><BR><BR>");


// Form the query and execute it
$sql = "select * from p01student";
echo($sql);

echo("<BR><BR><BR>");
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

echo($result_array);
echo($result);
echo($cursor);
if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}


// Display the query results
echo "<table border=1>";
echo "<tr> <th>Student ID</th> <th>First Name</th> <th>Last Name</th> <th>Age</th> <th>Type</th> <th>Status</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $stid = $values[0];
  $fname = $values[1];
  $lname = $values[2];
  $stage = $values[3];
  $staddress = $values[4];
  $sstype = $values[5];
  $ststatus = $values[6];
 
  echo("<tr>" . 
    "<td>$stid</td> <td>$fname</td> <td>$lname</td> <td>stage </td> <td> staddress</td> <td>sttype </td> <td>ststatus </td>".
    " <td> <A HREF=\"p01acc_update.php?sessionid=$sessionid&clientid=$clientid&password=$password&type=$clienttype\">Update</A> </td> ".
	" <td> <A HREF=\"p01user_delete.php?sessionid=$sessionid&clientid=$clientid\">Delete</A> </td>
	<td>NULL</td> ".
    "</tr>");

}	
				

oci_free_statement($cursor);

?>