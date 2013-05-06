<?php
include('Net/SFTP.php');

$sftp = new Net_SFTP('ftp.gazaro.com');
if (!$sftp->login('lacuracao', 'XMQEGcBk8IDX')) {
    exit('Login Failed');
}
//echo 'Login ';
$sftp->put('uploads/some_data.csv', 'some_data.csv', NET_SFTP_LOCAL_FILE);
echo 'File Uploaded ok';
?>