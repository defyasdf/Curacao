<?php $this->load->library('session');?>
<!doctype html>

<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Dashboard I Admin Panel</title>
	<base href="http://www.icuracao.com/DataFeed/">
	
    <link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />

     
	 <!--Load the AJAX API-->
	<script src="js/jquery.min.js"></script>
    <script src="js/thickbox.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="js/vendor/jquery.ui.widget.js"></script>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<link rel="Stylesheet" type="text/css" href="style/jqueryui/ui-lightness/jquery-ui-1.7.2.custom.css" />
    <script type="text/javascript">

	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>

 <script type="text/javascript">
 	
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
	
	 function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
            ['Campaigns', 'Visits'],
	      <?php 
		 foreach($content as $key=>$val){
			 
				echo "['".$key."',".$val['count']."],";
			 
		 }
		  
		  ?>
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('mainchart')).
            draw(data,
                 {title:"KPI report For campaign",
                  width:600, height:400,
                  hAxis: {title: "Visits"}}
            );
      }
      

      google.setOnLoadCallback(drawVisualization);

	  
    </script>

</head>


<body>

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.php"><img src="images/logo.png"></a></h1>
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