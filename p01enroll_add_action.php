<?
include "p01utility_functions.php";

$formtype = 'stu';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);

$crn =$_GET["crn"];
$sectid=$_GET["sectid"];
$sem = $_GET["sem"];
//check for seat

$sql = "select max_size, cur_size from p01gensection where crn = '$crn' and sectid = $sectid and sem = $sem";
//echo($sql);
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

$values = oci_fetch_array($cursor);
$max = $values[0];
$cur = $values[1];

oci_close ($connection);



if(($max - $cur) > 0){
    //seat available get sid then enroll student

    $sql = "select stid from p01clientsession natural join p01student where sessionid = '$sessionid'";
    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false){
      display_oracle_error_message($cursor);
      die("Client Query Failed.");
    }

    $values = oci_fetch_array($cursor);
    $stid = $values[0];
    oci_close ($connection);

    $sql = "insert into p01enrolledcourses values ('$stid', '$crn', $sem, $sectid, 1, null)";
    //echo($sql);

    ini_set( "display_errors", 0);  

    $connection = oci_connect ("gq055", "ujkorp", "gqiannew2:1521/pdborcl");
    if($connection == false){
      // failed to connect
      display_oracle_error_message(null);
      die("Failed to connect");
    }

    $cursor = oci_parse($connection, $sql);

    if ($cursor == false) {
      display_oracle_error_message($connection);
      oci_close ($connection);
      // sql failed 
      die("SQL Parsing Failed");
    }

    $result = oci_execute($cursor);

    if ($result == false) {
      echo "<B>Class add Failed.</B> <BR />";
      if (is_null($cursor))
        $err = oci_error();
      else
        $err = oci_error($cursor);


      if($err['code'] == 1){
        echo("Currently enrolled in course or previously taken. <br>");
      } else{
        display_oracle_error_message($cursor);
      }
      
      oci_close ($connection);
      die("Click <A HREF = \"enroll.php?sessionid=$sessionid\">here</A> to go back.");

    }

    oci_close ($connection);  

    $return_array["flag"] = $result;
    $return_array["cursor"] = $cursor;

    //adds student
    $cur = $cur + 1;
    $sql = "update p01gensection set numstudents = $cur where crn = '$crn' and sectid = $sectid and sem = $sem";

    $result_array = execute_sql_in_oracle ($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($result == false){
      display_oracle_error_message($cursor);
      die("Client Query Failed.");
    }
    oci_commit ($connection);
    oci_close($connection);

    Header("Location:p01enroll.php?sessionid=$sessionid");

} else {

  echo("No seats available.");
  die("Click <A HREF = \"p01enroll.php?sessionid=$sessionid\">here</A> to go back.");

}



?>
