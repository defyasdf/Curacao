<?php 

include 'includes/config.php';


$sql = "UPDATE `icuracaoproduct`.`users` SET `fname` = '".$_GET['fname']."',
											`lname` = '".$_GET['lname']."',
											`email` = '".$_GET['email']."',
											`username` = '".$_GET['username']."',
											`password` = '".securepass($_GET['pass'])."',
											`access_level` = '".$_GET['value']."' WHERE `user_id` =".$_GET['uid'];

//$sql = "INSERT INTO `users` (`fname`, `lname`, `email`, `username`, `password`, `access_level`) VALUES ('".$_GET['fname']."', '".$_GET['lname']."', '".$_GET['email']."', '".$_GET['username']."', '".$_GET['pass']."', '".$_GET['value']."')";

if(mysql_query($sql)){
	echo '<h4 class="alert_success">User Updated Successfully</h4>';
}

function securepass($pass){
	
	return md5($pass.'curacaosecurity');
}



/*echo '<pre>';

print_r($_GET);

echo '</pre>';
*/
