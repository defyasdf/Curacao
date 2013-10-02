<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");

function ship_rate($dest_state = "", $dest_zip = "") {
        if (empty($dest_state) || empty($dest_zip)) {echo "0"; return; }
        $dest_state = strtoupper($dest_state);
        
        require_once('/var/www/m113/fedex/fedex_api_files/fedex.php');

        $fedex = new Fedex;
        $fedex->setServer("https://gatewaybeta.fedex.com/GatewayDC");
        $fedex->setAccountNumber(344057532); // You need your own
        $fedex->setMeterNumber(104632842);    // You need your own
        $fedex->setCarrierCode("FDXG");
        $fedex->setDropoffType("REGULARPICKUP");
        $fedex->setService('FEDEXGROUND', 'FedEx Ground');
        $fedex->setPackaging("YOURPACKAGING");
        $fedex->setWeightUnits("LBS");
        $fedex->setWeight(3);
        $fedex->setOriginStateOrProvinceCode("MO");
        $fedex->setOriginPostalCode(63110);
        $fedex->setOriginCountryCode("US");
        $fedex->setDestStateOrProvinceCode($dest_state);
        $fedex->setDestPostalCode($dest_zip);
        $fedex->setDestCountryCode("US");
        $fedex->setPayorType("SENDER");

        $price = $fedex->getPrice();

        // echo "<pre>";
        //         print_r($price);
        // echo "</pre>";
        
        // echo "Price:".$price->price->rate;
        
        // print_r($price);
        
        //    markup shipping to cover handling cost
        // the following example marks up shipping 180% of the shipping rate.
        
        // $rate = ($price->price->rate + ($price->price->rate * 0.8));

        $rate = $price->price->rate;
        
        echo $rate; // you can either echo (i use this for ajax requests) or return the rate
        
        return $rate;

    } 
	
	ship_rate("90015","90025");