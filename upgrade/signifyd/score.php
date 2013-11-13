<?php

/**
 * Signifyd API response test, provides an example to fetch 
 * case related information.
 */

require_once(dirname(__FILE__) . '/response.php');

/* Your API key */
$key = "ylZoijL0irgmG1WO1HV6sAXcw";

/* The order ID */
//$order = "100003647";
$order = $_REQUEST['ord'];

/* URL Endpoint for case information */
$url = "https://api.signifyd.com/v2/orders/".$order."/case"; 

$response = array();

/* Get case data via the REST API */
$response = request($url, $key, 'application/json');

/* Decode the JSON */
$raw_response = array_filter(json_decode($response["raw_response"], true));

/**
 * Parse the response to extract the score
 */
$score = "Scoring in Progress";
if (!empty($raw_response)) {
	if (array_key_exists('status', $raw_response)) {
		if ($raw_response["status"] != "PROCESSING") {
			if (array_key_exists('adjustedScore', $raw_response)) {
				$score = round($raw_response["adjustedScore"]);
			}
		}
	}
}

//echo "\n//////////////// Case Score /////////////\n";

/* Output the score */
echo ("$score");

?>
