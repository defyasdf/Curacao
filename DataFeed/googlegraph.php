<?php 
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	$sql = 'SELECT status FROM `masterproducttable` GROUP BY status';
	$result = mysql_query($sql);
	$data = array();
	while($row = mysql_fetch_array($result)){
			$s = 'SELECT count(*) as data FROM `masterproducttable` where status = '.$row['status'];
			$r = mysql_query($s);
			$ro = mysql_fetch_array($r);
			
			$data[$row['status']] = $ro['data'];
	}
	
	
		$status = array('0'=>'In Queue (English ready from us)',
						'1'=>'Archieve',
						'2'=>'Pending',
						'3'=>'Raw',
						'4'=>'In Process',
						'5'=>'QA',
						'6'=>'Active',
						'7'=>'English ready From vIndia'
						);				
				
	
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      Google Visualization API Sample
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Status', 'Data'],
          <?php 
		  foreach($data as $d=>$v){
			echo "['".$status[$d]."',".$v."],";
		 }
		  
		  ?>
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('visualization')).
            draw(data,
                 {title:"Yearly Coffee Consumption by Country",
                  width:600, height:400,
                  hAxis: {title: "Year"}}
            );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="width: 600px; height: 400px;"></div>
  </body>
</html>