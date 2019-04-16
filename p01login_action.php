<?
include "p01utility_functions.php";

// Get the client id and password and verify them
$clientid = $_POST["clientid"];
$password = $_POST["password"];

$sql = "select clientid " .
       "from p01users " .
       "where clientid='$clientid'
         and password ='$password'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if($values = oci_fetch_array ($cursor)){
  oci_free_statement($cursor);

  // found the client
  $clientid = $values[0];

  // create a new session for this client
  $sessionid = md5(uniqid(rand()));

  // store the link between the sessionid and the clientid
  // and when the session started in the session table

  $sql = "insert into p01myclientsession " .
    "(sessionid, clientid, sessiondate) " .
    "values ('$sessionid', '$clientid', sysdate)";

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    display_oracle_error_message($cursor);
    die("Failed to create a new session");
  }
  else {
    // insert OK - we have created a new session
    //header("Location:p01welcomepage.php?sessionid=$sessionid");
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
	 }else{
	   if($values = oci_fetch_array($cursor)){
		   if($values[0] == 1){
			   if($values[1] == 0){
				   header("Location:p01adminwelcomepage.php?sessionid=$sessionid");
			    }else{
					header("Location:p01stuadminwelcomepage.php?sessionid=$sessionid");
				}
		   }else if($values[0] == 0){
			   if($values[1] == 1){
				   header("Location:p01stuwelcomepage.php?sessionid=$sessionid");
				}else{
					die ('Login failed.  Click <A href="p01login.html">here</A> to go back to the login page.');
				}
		   }
	   }
	 }
  }
}
else { 
  // client username not found
  die ('Login failed.  Click <A href="p01login.html">here</A> to go back to the login page.');
} 
?>