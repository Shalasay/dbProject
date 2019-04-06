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
  Account Type: <input type=\"text\" size=\"20\" maxlength=\"50\" name=\"q_clienttype\">
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
//$q_password = $_POST["q_password"];
$q_clienttype = $_POST["q_clienttype"];

$whereClause = " 1=1 ";

if (isset($q_clientid) and $q_clientid!= "") { 
  $whereClause .= " and clientid like '$q_clientid%'"; 
}

//if (isset($q_password) and $q_password!= "") { 
//  $whereClause .= " and password like '%$q_password%'"; 
//}

if (isset($q_clienttype) and $q_clienttype!= "") { 
  $whereClause .= " and clienttype like '%$q_clienttype%'"; 
}
// Form the query and execute it
$sql = "select clientid, password, clienttype from p01users where $whereClause order by clientid";
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
echo "<tr> <th>Username</th> <th>Password</th> <th>Account Type</th> <th>Update</th> <th>Delete</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $clientid = $values[0];
  $password = $values[1];
  $clienttype = $values[2];
  echo("<tr>" . 
    "<td>$clientid</td> <td>$password</td> <td>$clienttype</td> ".
    " <td> <A HREF=\"p01acc_update.php?sessionid=$sessionid&clientid=$clientid&password=$password&type=$clienttype\">Update</A> </td> ".
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
	  $sql = "select clienttype " .
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
				if(strcasecmp((string)$values[0], 'admin') != 0 && strcasecmp((string)$values[0], 'stuadmin') != 0){
				  echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
								"</FORM>"); 
								}
								else if(strcasecmp((string)$values[0], 'stu') != 0 && strcasecmp((string)$values[0], 'stuadmin') != 0){
				  echo("<FORM action=\"p01adminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
								"</FORM>"); 
				}	
			else if(strcasecmp((string)$values[0], 'admin') != 0 && strcasecmp((string)$values[0], 'stu') != 0){
				  echo("<FORM action=\"p01stuadminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
								"</FORM>"); 
				}
		  }
			 
	}					
		
oci_free_statement($cursor);

?>



