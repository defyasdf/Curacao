<?php
	// define some variables
	$local_file = 'testfile.xlsx';
	$server_file = 'testfile.xlsx';
	$ftp_server = 'lb-ftp.logictec.com';
	$ftp_user_name = 'MobileEdgeUser';
	$ftp_user_pass = 'M0b1l3Edg3Us3r';		
	// set up basic connection
	$conn_id = ftp_connect($ftp_server);
	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	// try to download $server_file and save to $local_file
	if (ftp_chdir($conn_id, "IN")) {
		echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
		if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
			echo "Successfully written to $local_file\n";
			ftp_delete($conn_id, $local_file);
			if (ftp_chdir($conn_id, "/")) {
				echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
			} else { 
				echo "Couldn't change directory\n";
			}
			if (ftp_chdir($conn_id, "OUT")) {
				echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
				if (ftp_put($conn_id, $server_file, $local_file, FTP_BINARY)) {
					echo "successfully uploaded $server_file\n";
					
				 } else {
					echo "There was a problem while uploading $file\n";
				}
			
			} else { 
				echo "Couldn't change directory\n";
			}
			
			
		} else {
			echo "There was a problem\n";
		}
	} else { 
		echo "Couldn't change directory\n";
	}	
	// close the connection
	ftp_close($conn_id);
	
	

?>