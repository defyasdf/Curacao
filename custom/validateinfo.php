<?php 

$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';


$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);

$ssnmatch = 0;
$aggmatch = 0;
$pass = 1;
if(substr($_POST['ssn'],1,2)==substr($_POST['pressn'],0,2)){
	$ssnmatch = 1;	
}
$age = (int)date('Y')-(int)$_POST['pyear'];

$diff = $age-$_POST['preage'];



if($diff<0){
	$diff = $diff*(-1);
}
if($diff<=2){
	$aggmatch = 1;
}
if($ssnmatch == 0){
	$msg = 'SSN doesnt match our record please reinput it';
	$pass = 0;
}
if($aggmatch == 0){
	$msg = 'Age doesnt match our record please reinput it';
	$pass = 0;
}
if($ssnmatch == 0 && $aggmatch == 0){
	$msg = 'SSN and Age doesnt match our record please reinput it';
	$pass = 0;
}

if($pass == 1){
	$msg = '1';
	$q = "update preapproved set step1 = '1' where paID = ".$_POST['app'];
	mysql_query($q,$link);
	mysql_close();
}

echo $msg;

?>