<?
include "p01utility_functions.php";
$formtype = '';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);	

// Get the password and trim it , if its blank then replace with NULL
$password = trim($_POST["password"]);
if ($password == '') $password = 'NULL';

//echo("$sessionid <br>");
// echo("New Password: $password <br>");
// echo("Session: $sessionid");

	//select clientid if from p01myclientsession
	//$sql = "select clientid from p01myclientsession where sessionid='$sessionid'";  
	
	//select clientid after natural join of myclientsession with p01users
	//$sql = "select clientid from p01users natural join p01myclientsession where sessionid = '$sessionid'";
	
	//update password
	//$sql = "update p01users set password = '%password' where clientid = '$clientid'";
			
			//check if its null, return user to their respective main page
if($password == 'NULL'){	
								
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
		else{
	  if($values = oci_fetch_array($cursor)){
			  if(strcasecmp((string)$values[0], 'admin') != 0 && strcasecmp((string)$values[0], 'stuadmin') != 0){
				  echo("Password is left blank, unable to change.");
							echo("<br> Please try again.");
							echo("<FORM action=\"p01stuwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
								"</FORM>");  
								}
		  }
			  if(strcasecmp((string)$values[0], 'stu') != 0 && strcasecmp((string)$values[0], 'stuadmin') != 0){
				  echo("Password is left blank, unable to change.");
							echo("<br> Please try again.");
							echo("<FORM action=\"p01adminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
								"</FORM>"); 
				}	
			if(strcasecmp((string)$values[0], 'admin') != 0 && strcasecmp((string)$values[0], 'stu') != 0){
				  echo("Password is left blank, unable to change.");
							echo("<br> Please try again.");
							echo("<FORM action=\"p01stuadminwelcomepage.php?sessionid=$sessionid\" name=\"Main Page \" method=\"POST\"> " .
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Main Page\"> " .
								"</FORM>"); 
				}				
	}
}
}

else{
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
	  if($values = oci_fetch_array($cursor)){
		 
				$sql = "update p01users set password = '$password' where clientid = '$values[0]'";
					$result_array = execute_sql_in_oracle($sql);
					$result = $result_array["flag"];
					$cursor = $result_array["cursor"];
					$result = oci_execute($cursor);
					if($result == false){
					display_oracle_error_message($cursor);
					die("SQL Execution problem.");
					}	
					else{
						
							echo("<h1> Password change successful! </h1>");
							echo("CLIENT ID : $values[0] <br>");
							echo("New Password: $password <br>");	
							echo("<FORM action=\"p01login.html\" name=\"Main Page \" method=\"POST\"> " .
									"<br>".
									"Please Re-log back in!".
									"<INPUT type=\"submit\" name=\"btnSubmit\" value=\"Ok\"> " .
								"</FORM>"); 
					}
			}
		
	}
}

// Form the insertion sql string and run it.



?>
  




