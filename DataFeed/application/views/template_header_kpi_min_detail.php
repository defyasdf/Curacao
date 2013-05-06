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
     

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);
	  google.setOnLoadCallback(drawChart1);
      google.setOnLoadCallback(drawChart2);
      google.setOnLoadCallback(drawChart3);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
	<?php foreach($content as $key=>$val){?>
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
         ['First Page', <?php echo $val['firstpage']?>],
		  ['Drop from landing page', <?php echo $val['count']-$val['firstpage']?>]
        ]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $val['count']?> <?php echo $key;
		if(isset($_GET['keyword'])){
					echo ' For keyword "'.str_replace('plssgn','+',$_GET['keyword']).'"';
				}
		?> campaign Visit',
                       'width':600,
                       'height':500};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
	   function drawChart1() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Complete First Page', <?php echo $val['completefirstpage']?>],
		  ['Drop from landing page', <?php echo $val['firstpage']-$val['completefirstpage']?>]
        ]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $val['firstpage']?>  First Page Visit',
                       'width':600,
                       'height':500};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
	
	
   function drawChart2() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Pass Authentication', <?php echo $val['authpass']?>],
		  ['dont pass authentication', <?php echo $val['completefirstpage']-$val['authpass']?>]
        ]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $val['completefirstpage']?> First Page Complition',
                       'width':600,
                       'height':500};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }



	 function drawChart3() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Panding', <?php echo $val['pending']?>],
		  ['Approve', <?php echo $val['approve']?>],
		  ['Decline', <?php echo $val['decline']?>]

		]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $val['authpass']?> Authentication Pass',
                       'width':600,
                       'height':500};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div3'));
        chart.draw(data, options);
      }
	
	<?php }?>		  
	  
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