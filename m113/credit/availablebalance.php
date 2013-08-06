<?php

    ini_set('max_execution_time', 0);
    ini_set('display_errors', 1);
    ini_set("memory_limit","1024M");

    $mageFilename = '/var/www/m113/app/Mage.php';
    require_once $mageFilename;
    Varien_Profiler::enable();
    Mage::setIsDeveloperMode(true);
    umask(0);
    Mage::app('default');


    //GET SESSION DATA
    Mage::getSingleton('core/session', array('name'=>'frontend'));
    $session = Mage::getSingleton('customer/session', array('name'=>'frontend'));

	$customer = Mage::getModel('customer/customer')->load($session->getId() );


    if(! $session->isLoggedIn() ){
    	exit;
    } 

    //Set the soap client
    $proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
    $ns = 'http://lacuracao.com/WebServices/eCommerce/';
    // Set headers
    $headerbody = array('UserName' => 'mike',
                        'Password' => 'ecom12');
    //Create Soap Header.
    $header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);

    //set the Headers of Soap Client.
    $h = $proxy->__setSoapHeaders($header);


    $credit = $proxy->EComAcctCredit(array('cCust_ID'=>$customer->getcuracaocustid()));
    $result = $credit->EComAcctCreditResult;

    $cust_info = explode('|',$result);

?>
    <center>
        <h2 style="font-size: 16px; font-weight: bold; margin-top: 6px; margin-bottom: 0px; line-height: 25px;">
              Welcome <?php echo $customer->getFirstname().' '.$customer->getLastname();?>
        </h2>

        <p style="font-size:14px; font:arial;">Your available Curacao credit:</p>
        <p style="font-size:36px; font:arial; line-height:48px;">$<?php echo $cust_info[8];?></p>
    </center>