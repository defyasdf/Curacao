<?php 
/**
* Example of simple product POST using Admin account via Magento REST API. OAuth authorization is used
*/
//session_start();
$callbackUrl = "http://www.icuracao.com/oauth_admin.php";
$temporaryCredentialsRequestUrl = "http://www.icuracao.com/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
$adminAuthorizationUrl = 'http://www.icuracao.com/admin/oauth_authorize';
$accessTokenRequestUrl = 'http://www.icuracao.com/oauth/token';
$apiUrl = 'http://www.icuracao.com/api/rest';
$consumerKey = 'af0og6u3n0w33av2jp9gh9y0t1wd42wu';
$consumerSecret = 'fkxo8dbbbjpqzymo22bzbhixdw37pwno';

if (!isset($_GET['oauth_token'])) {
   // $_SESSION['state'] = 0;
}
try {
    $authType =  OAUTH_AUTH_TYPE_URI;
    $oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
    $oauthClient->enableDebug();
 
    if (!isset($_GET['oauth_token'])) {
        $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
     //   $_SESSION['secret'] = $requestToken['oauth_token_secret'];
       // $_SESSION['state'] = 1;
        header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
        exit;
    }  else {
        echo 'Rest Works well';
		exit;
		$oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
        $resourceUrl = "$apiUrl/products";
        $productData = json_encode(array(
            'type_id'           => 'simple',
            'attribute_set_id'  => 4,
            'sku'               => 'simple' . uniqid(),
            'weight'            => 1,
            'status'            => 1,
            'visibility'        => 4,
            'name'              => 'Simple Product',
            'description'       => 'Simple Description',
            'short_description' => 'Simple Short Description',
            'price'             => 99.95,
            'tax_class_id'      => 0,
        ));
        $headers = array('Content-Type' => 'application/json');
        $oauthClient->fetch($resourceUrl, $productData, OAUTH_HTTP_METHOD_POST, $headers);
        print_r($oauthClient->getLastResponseInfo());
    }
} catch (OAuthException $e) {
    print_r($e);
}
?>