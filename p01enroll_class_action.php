<?
include "p01utility_functions.php";

$connection = oci_connect ("gq051", "nhmrse", "gqiannew2:1521/pdborcl");
if ($connection == false){
   echo oci_error()."<BR>";
   exit;
}

$formtype = 's';
$sessionid =$_GET["sessionid"];
$sem = 2015;
verify_session($sessionid, $formtype);

$crn1=$_POST["crn1"];
$sectid1=$_POST["sectid1"];
$crn2=$_POST["crn2"];
$sectid2=$_POST["sectid2"];
$crn3=$_POST["crn3"];
$sectid3=$_POST["sectid3"];
$crn4=$_POST["crn4"];
$sectid4=$_POST["sectid4"];
$crn5=$_POST["crn5"];
$sectid5=$_POST["sectid5"];

echo($crn1);
echo ($sectid1);
$sql = "select stid from p01student natural join p01myclientsession where sessionid = '$sessionid'";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}
$values = oci_fetch_array($cursor);
$stid = $values[0];
oci_free_statement($cursor);

$error;///error string
$final_error;

$crn_string = "crn";
$sectid_string = "sectid";
$count = 1;

for($i = 1; $i < 6; $i++){
	$crn=$_POST["$crn_string".$i];
	$sectid=$_POST["$sectid_string".$i];
	echo($i)
	if(isset($crn) and trim($crn)!= ""){
		if(isset($sectid) and trim($sectid)!= ""){

			//check deadline
			$sql = "begin check_deadline(:crn, :sectid, :sem, :error); end;";
			//echo($sql);
			$cursor = oci_parse($connection, $sql);
			if($cursor == false){
				echo oci_error($connection)."<br>";
				exit;
			}
			oci_bind_by_name($cursor, ":error", &$error, 100);
			oci_bind_by_name($cursor, ":crn", &$crn, 6);
			oci_bind_by_name($cursor, ":sectid", &$sectid, 4);
			oci_bind_by_name($cursor, ":sem", &$sem, 4);
			oci_bind_by_name($cursor, ":stid", &$stid, 8);

			$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
			if($result == false){
				display_oracle_error_message($cursor);
				echo oci_error($cursor)."<BR>";
				exit;
			}
			oci_free_statement($cursor);
			//end deadline check
			//echo("past deadline");

			//check passed course
			if(isset($error) and trim($error)!=""){//previous errors so do not enroll
				//continue;
			}else{
				$sql = "begin check_passed_course(:crn, :stid, :error); end;";
				//echo($sql);
				$cursor = oci_parse($connection, $sql);
							if($cursor == false){
					echo oci_error($connection)."<br>";
					exit;
				}
				oci_bind_by_name($cursor, ":error", &$error, 100);
				oci_bind_by_name($cursor, ":crn", &$crn, 6);
				oci_bind_by_name($cursor, ":stid", &$stid, 8);

				$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
				if($result == false){
					display_oracle_error_message($cursor);
					echo oci_error($cursor)."<BR>";
					exit;
				}
				oci_free_statement($cursor);

			}
			//end passed course check
			//echo("past course");

			//check prereqs taken
			if(isset($error) and trim($error)!=""){//previous errors so do not enroll
				//continue;
			}else{
				$sql = "begin check_prereq(:crn, :sectid, :stid, :error); end;";
				//echo($sql);
				$cursor = oci_parse($connection, $sql);
				if($cursor == false){
					echo oci_error($connection)."<br>";
					exit;
				}
				oci_bind_by_name($cursor, ":error", &$error, 100);
				oci_bind_by_name($cursor, ":crn", &$crn, 6);
				oci_bind_by_name($cursor, ":sectid", &$sectid, 4);
				oci_bind_by_name($cursor, ":stid", &$stid, 8);
				$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
				if($result == false){
					display_oracle_error_message($cursor);
					echo oci_error($cursor)."<BR>";
					exit;
				}
				oci_free_statement($cursor);
			}
			//end  prereqs taken check
			//echo("taken prereqs");

			//check for seat and enroll
			if(isset($error) and trim($error)!=""){//previous errors so do not enroll
				//continue;
			}else{//no previous errors so check seat and enroll
				$sql = "begin check_seat_available(:crn, :sectid, :sem, :stid, :error); end;";
				//echo($sql);
				$cursor = oci_parse($connection, $sql);
				if($cursor == false){
					echo oci_error($connection)."<br>";
					exit;
				}
				oci_bind_by_name($cursor, ":error", &$error, 100);
				oci_bind_by_name($cursor, ":crn", &$crn, 6);
				oci_bind_by_name($cursor, ":sectid", &$sectid, 4);
				oci_bind_by_name($cursor, ":sem", &$sem, 4);
				oci_bind_by_name($cursor, ":stid", &$stid, 8);
				$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
				if($result == false){
					display_oracle_error_message($cursor);
					echo oci_error($cursor)."<BR>";
					exit;
				}
				oci_free_statement($cursor);
			}
			//end check for seat and enroll
			//echo("has seat and enrolled");
			if(isset($error) and trim($error)!=""){
				//echo($error);
				$final_error .= "<br>" . $error;
				$error = "";
			}
		}//end isset
	}//end isset

}//end for loop

if(isset($final_error) and trim($final_error)!= ""){
	echo("ERROR:");
	echo($final_error);
	die("<br>Click <A HREF = \"p01enroll.php?sessionid=$sessionid\">here</A> to go back.");
}

oci_close($connection);
Header("Location:p01enroll.php?sessionid=$sessionid");
?>