<?
include "p01utility_functions.php";

$formtype = 'stuadmin';
$sessionid =$_GET["sessionid"];
verify_session($sessionid, $formtype);
echo("<title>Student-Admin Page</title>");

// Here we can generate the content of the welcome page
echo("Student-Admin Management Menu: <br />");
//echo("<UL><LI><A HREF=\"p01department.php?sessionid=$sessionid\">test</A></LI><LI><A HREF=\"p01employee.php?sessionid=$sessionid\">two</A></LI></UL>");

echo("<br />");
echo("Click <A HREF = \"p01adminwelcomepage.php?sessionid=$sessionid\">here</A> to log in as Admin.");
echo("<br />");
echo("Or <br />");
echo("Click <A HREF = \"p01stuwelcomepage.php?sessionid=$sessionid\">here</A> to log in as Student.");
echo("<br />");
echo("<br />");
echo("Click <A HREF = \"p01logout_action.php?sessionid=$sessionid\">here</A> to Logout.");


?>