<?php 
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	$s = 'SELECT count(*) as data FROM `masterproducttable` where status = 2';
	$r = mysql_query($s);
	$ro = mysql_fetch_array($r);
	
	$s1 = 'SELECT count(*) as data FROM `masterproducttable` where status = 4';
	$r1 = mysql_query($s1);
	$ro1 = mysql_fetch_array($r1);
	
	$s3 = 'SELECT count(*) as data FROM `finalproductlist` where status = 0';
	$r3 = mysql_query($s3);
	$ro3 = mysql_fetch_array($r3);
	
	$s4 = 'SELECT count(*) as data FROM `finalproductlist` where status = 1';
	$r4 = mysql_query($s4);
	$ro4 = mysql_fetch_array($r4);	
	
	
?>

	<section id="main" class="column">
			<h4 class="alert_info">Welcome to the iCuracao Data Feeder admin panel.</h4>
		
		<article class="module width_full">
			<header><h3>Stats</h3></header>
			<div class="module_content">
				<article class="stats_graph">
				
                <div id="visualization" style="width: 600px; height: 400px;"></div>
                
                
                
                </article>
				
				<article class="stats_overview">
					<div class="overview_today">
						<p class="overview_day">English (vIndia)</p>
						<p class="overview_count"><?php echo $ro['data']?></p>
						<p class="overview_type">Pending</p>
						<p class="overview_count"><?php echo $ro1['data']?></p>
						<p class="overview_type">In Process</p>
					</div>
					<div class="overview_previous">
						<p class="overview_day">Spanish </p>
						<p class="overview_count"><?php echo $ro3['data']?></p>
						<p class="overview_type">In process</p>
						<p class="overview_count"><?php echo $ro4['data']?></p>
						<p class="overview_type">Done</p>
					</div>
				</article>
				<div class="clear"></div>
			</div>
		</article>
	</section>

