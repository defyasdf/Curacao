<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://data.icuracao.com/custom/curltest_recieve.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);

$data = array(
    'name' => 'foo foo foo',
    'cat_id' => 'bar bar bar'
);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

print_r($output);