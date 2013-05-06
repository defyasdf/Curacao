<?php
// Concatenating this because vB doesn't like <? tags in the code...
$content = '<' . '?xml version="1.0" encoding="utf-8"?>';
$content .= <<<END
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <soap:Header>
    <TAuthHeader MyAttribute="" xmlns="http://lacuracao.com/WebServices/eCommerce/">
      <UserName>mike</UserName>
      <Password>ecom12</Password>
    </TAuthHeader>
  </soap:Header>
  <soap:Body>
    <ItemQtyAvail xmlns="http://lacuracao.com/WebServices/eCommerce/">
      <cItem_ID>03M-E24-CS6124</cItem_ID>
      <cLocations>01,05</cLocations>
    </ItemQtyAvail>
  </soap:Body>
</soap:Envelope>
END;

$ch = curl_init();

$data = array(); // you aren't actually posting anything

curl_setopt($ch, CURLOPT_URL, 'https://exchangeweb.lacuracao.com:2007/ws1/test/eCommerce/Main.asmx?WSDL');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'SOAPAction: "http://lacuracao.com/WebServices/eCommerce/ItemQtyAvail"'
                                            ,'Content-Type: text/xml;charset=UTF-8'
                                            ,'User-Agent: Jakarta Commons-HttpClient/3.1'
                                            ));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$r = curl_exec($ch);


print_r($r);