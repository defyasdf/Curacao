<?php
/*
$strServer = "ftp.gazaro.com";
$strServerPort = "22";
$strServerUsername = "lacuracao";
$strServerPassword = "XMQEGcBk8IDX";


$hostname = "gazaro.com";
$username = "lacuracao";
$password = "XMQEGcBk8IDX";


$conn_id = ftp_connect($hostname) or die("Couldnt connect");
	
	 // login with username and password
$login_result = ftp_login($conn_id, $username, $password);
	
echo "Login";*/

?>

<?php
$connection = ssh2_connect('ftp.gazaro.com', 22);
ssh2_auth_password($connection, 'lacuracao', 'XMQEGcBk8IDX');

ssh2_scp_send($connection, 'some_data.csv', '/uploads/some_data.csv', 0777);
?>