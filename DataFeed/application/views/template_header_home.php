<?php $this->load->library('session');

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
			if($row['status'] == 0 || $row['status'] == 7){
				$s = 'SELECT count(*) as data FROM `product_queue` where status = 0';
			}elseif($row['status']== 5){
				$s = "SELECT count(`product_sku`) as data FROM finalproductlist WHERE status = 0 and product_sku not in (select product_sku from finalproductlist where finalproductlist.status = 1)";
			}elseif($row['status']== 9){
				$s = 'SELECT count(*) as data FROM `finalproductlist` where status = 1';
			}else{	
				$s = 'SELECT count(distinct(product_upc)) as data FROM `masterproducttable` where status = '.$row['status'];
			}
			$r = mysql_query($s);
			$ro = mysql_fetch_array($r);
			
			$data[$row['status']] = $ro['data'];
	}
	
	
		$status = array('0'=>'In Queue (English ready )',
						'1'=>'Archieve',
						'2'=>'Pending',
						'3'=>'Raw',
						'4'=>'In Process',
						'5'=>'QA',
						'6'=>'Active',
						'8'=>'Brand with no authorization',
						'9'=>'Spanish Approved Data'
						);				
				
	
	


?>
<!doctype html>

<html lang="en">

<head>
	<title>Dashboard I Admin Panel</title>
	
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="http://www.icuracao.com/DataFeed/css/layout.css" type="text/css" media="screen" />
	
        
    
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
			  if($d != 7){
				echo "['".$status[$d]."',".$v."],";
			  }
		 }
		  
		  ?>
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('visualization')).
            draw(data,
                 {title:"Curaca Data Center Data Report",
                  width:600, height:400,
                  hAxis: {title: "Year"}}
            );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>

</head>


<body>

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.php"><img src="http://127.0.0.1/DataFeed/images/logo.png"></a></h1>
			<!--<h2 class="section_title">Dashboard</h2><div class="btn_view_site"><a href="http://www.medialoot.com">View Site</a></div>-->
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id="secondary_bar">
		<div class="user">
        <?php 
			if(isset($this->session->userdata)) {
		?>
			 	<p><?php echo $this->session->userdata('fname').' '.$this->session->userdata('lname') ?></p>
                
		<a class="logout_user" href="index.php/logout" title="Logout">Logout</a> 
        
        <?php }?>
		</div>
<!--		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.html">iCuracao Admin</a> <div class="breadcrumb_divider"></div> <a class="current">Dashboard</a></article>
		</div>-->
	</section><!-- end of secondary bar -->