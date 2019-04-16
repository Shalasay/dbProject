<?
include "p01utility_functions.php";
$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);	

echo("<TITLE>Account Management</TITLE>");
echo("<h1> Account Management</h1>");
// create the dropdown list for the departments in the query section.

	//link to adding new account
		echo("
  <form method=\"post\" action=\"p01manage.php?sessionid=$sessionid\">
  Username: <input type=\"text\" size=\"10\" maxlength=\"20\" name=\"q_clientid\"> 
  Admin flag: <input type=\"text\" size=\"20\" maxlength=\"50\" name=\"q_aflag\">
  Student flag: <input type=\"text\" size=\"20\" maxlength=\"50\" name=\"q_sflag\">
  <input type=\"submit\" value=\"Search\">
  </form>

  <form method=\"post\" action=\"p01manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Clear\">
  </form>

  <form method=\"post\" action=\"p01user_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New User\">
  </form>
  ");
  
	// Interpret the query requirements
$q_clientid = $_POST["q_clientid"];
$q_aflag = $_POST["q_aflag"];
$q_sflag = $_POST["q_sflag"];

$whereClause = " 1=1 ";

if (isset($q_clientid) and $q_clientid!= "") { 
  $whereClause .= " and clientid like '$q_clientid%'"; 
}


if (isset($q_aflag) and $q_aflag!= "") {
  $whereClause .= " and aflag like '%$q_aflag%'"; 
}
if (isset($q_sflag) and $q_sflag!= "") {
  $whereClause .= " and sflag like '%$q_sflag%'"; 
}
// Form the query and execute it
$sql = "select clientid, password, aflag, sflag from p01users where $whereClause order by clientid";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}


// Display the query results
echo "<table border=1>";
echo "<tr> <th>Username</th> <th>Password</th> <th>Admin Flag</th> <th>Student Flag</th> <th>Update</th> <th>Delete</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $clientid = $values[0];
  $password = $values[1];
  $aflag = $values[2];
  $sflag = $values[3];
  echo("<tr>" . 
    "<td>$clientid</td> <td>$password</td> <td>$aflag</td> <td>$sflag</td> ".
    " <td> <A HREF=\"p01acc_update.php?sessionid=$sessionid&clientid=$clientid&password=$password&aflag=$aflag&sflag=$sflag\">Update</A> </td> ".
	" <td> <A HREF=\"p01user_delete.php?sessionid=$sessionid&clientid=$clientid\">Delete</A> </td> ".
    "</tr>");
}					


//Main Page button
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



