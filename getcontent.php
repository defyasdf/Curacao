<?php 
	ini_set('display_errors', 1);
	$url = trim('http://www.lacuracao.com/images/products/966/156497-3-1-99..jpg');
	echo $url;
	$image = 'media/images/'.str_replace('/','_','cur-7896-32188-sanjay').'_.jpg';
	file_put_contents($image, file_get_contents($url));