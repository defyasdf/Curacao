<?php 

include 'includes/config.php';

$sql = "INSERT INTO `users` (`fname`, `lname`, `email`, `username`, `password`, `access_level`) VALUES ('".$_GET['fname']."', '".$_GET['lname']."', '".$_GET['email']."', '".$_GET['username']."', '".$_GET['pass']."', '".$_GET['value']."')";

if(mysql_query($sql)){
	echo '<h4 class="alert_success">An User Created Successfully</h4>';
}

/*echo '<pre>';

print_r($_GET);

echo '</pre>';
*/
