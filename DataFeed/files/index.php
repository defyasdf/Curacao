<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
	ini_set('max_execution_time', 300);
 	ini_set('memory_limit', '-1');
    # don't forget the library
    include('simple_html_dom.php');
    
    # this is the global array we fill with article information
    $articles = array();
	
	 $table = array();
	//$$ttd = array();	
    # passing in the first page to parse, it will crawl to the end
    # on its own
    
	getArticles('getproduct_html.htm');

//echo file_get_contents('getproduct_html.htm');

//exit;
	

function getArticles($page) {
    global $articles, $descriptions;
    
    $html = new simple_html_dom();
    $html->load_file($page);
    
    $items = $html->find('div[id=productcontainer]');  
    
    foreach($items as $post) {
        # remember comments count as nodes
       // $articles[] = array($post->outertext);
	   gettable($post->outertext);
    }
   
}


function gettable($page) {
    global $table;
    
    $html = new simple_html_dom();
    $html->load($page);
    
    $items = $html->find('table');  
    
    foreach($items as $post) {
        # remember comments count as nodes
       // $table[] = array($post->outertext);
		if(count(gettd($post->outertext))>0){
			 $table[] = gettd($post->outertext);
		}
    }
   
}

function gettd($page) {
  //  global $ttd;
    $ttd = array();
    $html = new simple_html_dom();
    $html->load($page);
    
    $items = $html->find('td[id=proinfo]');  
    
    foreach($items as $post) {
        # remember comments count as nodes
        $ttd[] = array($post->outertext);
    }
	
	
   return $ttd;
}
//$tabl = '<table><tr><td id="proinfo"><table><tr><td>kjshfk</td><td>jksdfka</td></tr></table></td></tr></table>';
/*echo '<pre>';
	print_r($table);
echo '</pre>';
*/
for($i=0;$i<sizeof($table);$i++){
	//for($j=0;$j<sizeof($table[$i]);$j++){
	//	echo '<div>product'.$i.'Name: '.$table[$i][1][0].'</div>';
		
		echo 'insert into spanishdata1 (product_name,product_descriptio,product_feature,product_specification) value ("'.htmlentities($table[$i][0][0],ENT_QUOTES).'","'.htmlentities($table[$i][2][0],ENT_QUOTES).'","'.htmlentities($table[$i][3][0],ENT_QUOTES).'","'.htmlentities($table[$i][4][0],ENT_QUOTES).'")';
	//}
	
	exit;

}



exit


?>

</body></html>