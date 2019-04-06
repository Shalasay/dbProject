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