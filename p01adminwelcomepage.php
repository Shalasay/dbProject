<?
include "p01utility_functions.php";

$formtype = 'admin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);
echo("<title>Admin page</title>");


// Here we can generate the content of the welcome page
echo("Admin Management Menu: <br><br />");
//echo("<UL><LI><A HREF=\"p01department.php?sessionid=$sessionid\">test</A></LI><LI><A HREF=\"p01employee.php?sessionid=$sessionid\">two</A></LI></UL>");

echo("<form method=\"post\" action=\"p01logout_action.php?sessionid=$sessionid\"> 
		<input type=\"submit\" value=\"Logout\"> </form>");
echo("<br><form method=\"post\" action=\"p01passChange.php?sessionid=$sessionid\"> 
		<input type=\"submit\" value=\"Change Password\"> </form>");
echo("<br><form method=\"post\" action=\"p01manage.php?sessionid=$sessionid\"> 
		<input type=\"submit\" value=\"Manage Accounts\"> </form>");
// Button for adding a new student
echo("<br><form method=\"post\" action=\"admin_add_student.php?sessionid=$sessionid\"> 
		<input type=\"submit\" value=\"Add a new student\"> </form>");
?>