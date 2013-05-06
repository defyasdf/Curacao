<?php 
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	$mageFilename = '/var/www/html/app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$sql = 'SELECT * FROM `bfspecial`';
	$resu = mysql_query($sql);
	while($row = mysql_fetch_array($resu)){

		$_product = Mage::getModel('catalog/product');
		$productId = $_product->getIdBySku($row['productsku']);

		$_product->setStoreId(1)->load($productId);
		//$_product->load($row['product_id']);
		
		$_product->setSpecialPrice('');
		
		$_product->setSpecialFromDate('');
		$_product->setSpecialFromDateIsFormated(true);
		
		$_product->setSpecialToDate('');
		$_product->setSpecialToDateIsFormated(true);
		
		try {
			
		    $_product->save();
			echo ' Product has been updated in english ';
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$_product->setStoreId(3)->load($productId);	
		//$_product->load($productId);
		$_product->setSpecialPrice('');	
		$_product->setSpecialFromDate('');
		$_product->setSpecialFromDateIsFormated(true);
		$_product->setSpecialToDate('');
		$_product->setSpecialToDateIsFormated(true);
		
		try {
			
		    $_product->save();
			echo ' Product has been updated in spanish ';
			
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}	

	}
	
	
				 // Email Start
// End in december		 
	 // multiple recipients
$to  = 'mikeaz@icuracao.com'; // note the comma

// subject
$subject = 'Black Friday Cron At 8 AM';
// message
$message = '
<html>
<head>
  <title>Black Friday Cron At 8 AM</title>
  <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=us-ascii">
</head>
<body style="background:#FFFFFF; font-family:Arial, Tahoma, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#FFFFFF; font-family:Arial, Tahoma, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<center>
<table width="750" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td colspan="2">
            	
				<table width="750 "border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td><a href="http://www.icuracao.com" ><img src="https://www.icuracao.com/skin/adminhtml/default/default/images/logo_email.gif" alt="Curacao" style="margin-bottom:10px;" border="0"/></a></td></td>
							<td height="78" width="388"></td>
							<td></td>
						</tr>
					</tbody>
				</table>
                
                
                <table width="750 "border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td background="http://www.icuracao.com/DataFeed/marketing_images/email/navbar.png" style="padding: 7px 15px 3px 20px;">
								<table width="100% "border="0" cellspacing="0" cellpadding="0">
									<tbody>
										<tr>
											<td align="left" width="500">
                                                <span style="font-family: Arial, sans-serif; font-size: 16px; color: #444444; font-weight: bold;">Black Friday Cron At 8 AM</span>
											<td align="right">
												<a href="https://www.facebook.com/CuracaoUSA" target="_blank"><img src="http://www.icuracao.com/DataFeed/marketing_images/email/icn_fb.png" height="32" width="32" border="0" alt="Facebook"/></a>
												<a href="https://twitter.com/lacuracao" target="_blank"><img src="http://www.icuracao.com/DataFeed/marketing_images/email/icn_twitter.png" height="32" width="32" border="0" alt="Twitter"/></a>
												<a href="https://plus.google.com/107858241391949634093/posts" target="_blank"><img src="http://www.icuracao.com/DataFeed/marketing_images/email/icn_google.png" height="32" width="32" border="0" alt="Google+"/></a>
												
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
                     
			</td>
		</tr>
        <tr>
			<td colspan="2">&nbsp;</td>
		</tr>
         
		<tr>
			<td colspan="2" style="font-family: Arial, sans-serif; font-size: 12px; padding: 20px 20px 0 20px; border-top: 1px solid #CCCCCC; border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;">
				<h1 style="font-size:16px; font-weight:normal; line-height:22px; margin:0 0 11px 0;">Dear Mike,</h1>
				
				<p style="font-size:12px; line-height:16px; margin:0 0 16px 0;">Your Cron at 8 AM friday ran successfully please check and make sure it worked:  <b></b></p>
				
				<br />
				<p style="font-size:12px; line-height:16px; margin:0;">Thank you,</p>
				<p><strong>Curacao</strong></p>
				<p>Note: Promotion code valid from December 1, 2012 to December 31, 2012 for one online purchase per customer at iCuracao.com and must meet minimum dollar amount.</p>
            </td>
		</tr>
        
		<tr>
			<td colspan="2" style="border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC;">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="background-color: #CCCCCC;">&nbsp;</td>
		</tr>
	</tbody>	
</table>
</center>                              
</div>
</body>
</html>
';
	
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'From: customer Service <cservice@icuracao.com>' . "\r\n";
$headers .= 'Cc: sanju.comp@gmail.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);
	 
	 //Email Stop