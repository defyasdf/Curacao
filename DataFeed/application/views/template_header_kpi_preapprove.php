<?php $this->load->library('session');?>
<!doctype html>

<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Dashboard I Admin Panel</title>
	<base href="http://www.icuracao.com/DataFeed/">
	
    <link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
	 <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);
	  google.setOnLoadCallback(drawChart1);
	 //  google.setOnLoadCallback(drawChart2);
	    google.setOnLoadCallback(drawChart3);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
         ['First Page', <?php echo $content['step1']?>],
		  ['Drop from landing page', <?php echo $content['count']-$content['step1']?>]
        ]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $content['count']?> Landing Page Visit',
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
          ['Complete Step 2 with ID', <?php echo $content['step2']?>],
		  ['Drop from Step 2 with ID', <?php echo $content['step1']-$content['step2']?>]
        ]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $content['step1']?> Visit',
                       'width':600,
                       'height':500};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
	
	
  
	 function drawChart3() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Panding', <?php echo $content['pending']?>],
		  ['Approve', <?php echo $content['approve']?>],
		  ['Decline', <?php echo $content['decline']?>]
        ]);

        // Set chart options
        var options = {'title':'Break Down for <?php echo $content['step2']?> Visit',
                       'width':600,
                       'height':500};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div3'));
        chart.draw(data, options);
      }

			  
	  
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