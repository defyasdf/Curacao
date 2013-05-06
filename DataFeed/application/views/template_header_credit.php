<?php $this->load->library('session');?>
<!doctype html>

<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Dashboard I Admin Panel</title>
	<base href="http://www.icuracao.com/DataFeed/">
	
    <link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
   <!-- <link rel="stylesheet" href="themes/blue/style.css" type="text/css" media="print, projection, screen" />-->
    
    
    <script src="js/jquery.min.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="js/vendor/jquery.ui.widget.js"></script>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="js/load-image.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="js/canvas-to-blob.min.js"></script>
    <!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-image-gallery.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/jquery.fileupload.js"></script>
    <!-- The File Upload file processing plugin -->
    <script src="js/jquery.fileupload-fp.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="js/jquery.fileupload-ui.js"></script>
    <!-- The localization script -->
    <script src="js/locale.js"></script>
    <!-- The main application script -->
    <script src="js/main.js"></script>
    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
    <!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->

    
    <style type="text/css" title="currentStyle">
			@import "media/css/demo_page.css";
			@import "media/css/demo_table_jui.css";
			@import "examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		</style>
     
        
        
<!--    <script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>-->
<!--	<script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>-->
	<script src="js/hideshow.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>
<!--	<script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>\
-->	
	
	<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
     
	    $(document).ready(function() {
            
			var oTable = $('.tablesorter').dataTable( {
					"oLanguage": {
						"sSearch": "Search all columns:"
					},
					 "bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"sScrollY": "100%",
					"sScrollX": "100%",
					"sScrollXInner": "210%"
				} );
				
				/* Add a select menu for each TH element in the table footer */
				$("#footer div").each( function ( i ) {
					this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
					$('select', this).change( function () {
						oTable.fnFilter( $(this).val(), i );
					} );
				} );
			
			/*oTable = $('.tablesorter').dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
				"sScrollX": "100%",
				"sScrollXInner": "110%",
				"bScrollCollapse": true
            });*/
			
			
			
			
        } );
    </script>
	
    <script type="text/javascript" charset="utf-8">
			(function($) {
			/*
			 * Function: fnGetColumnData
			 * Purpose:  Return an array of table values from a particular column.
			 * Returns:  array string: 1d data array 
			 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
			 *           int:iColumn - the id of the column to extract the data from
			 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
			 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
			 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
			 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
			 */
			$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
				// check that we have a column id
				if ( typeof iColumn == "undefined" ) return new Array();
				
				// by default we only wany unique data
				if ( typeof bUnique == "undefined" ) bUnique = true;
				
				// by default we do want to only look at filtered data
				if ( typeof bFiltered == "undefined" ) bFiltered = true;
				
				// by default we do not wany to include empty values
				if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
				
				// list of rows which we're going to loop through
				var aiRows;
				
				// use only filtered rows
				if (bFiltered == true) aiRows = oSettings.aiDisplay; 
				// use all rows
				else aiRows = oSettings.aiDisplayMaster; // all row numbers
			
				// set up data array	
				var asResultData = new Array();
				
				for (var i=0,c=aiRows.length; i<c; i++) {
					iRow = aiRows[i];
					var aData = this.fnGetData(iRow);
					var sValue = aData[iColumn];
					
					// ignore empty values?
					if (bIgnoreEmpty == true && sValue.length == 0) continue;
			
					// ignore unique values?
					else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
					
					// else push the value onto the result data array
					else asResultData.push(sValue);
				}
				
				return asResultData;
			}}(jQuery));
			
			
			function fnCreateSelect( aData )
			{
				//alert(aData[0].substring(0,1));
				//return false;
				if(aData[0].substring(0,1)!='<'){
					var r='<select><option value=""></option>', i, iLen=aData.length;
					for ( i=0 ; i<iLen ; i++ )
					{
						r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
					}
					return r+'</select>';
				}else{
					return '<input type="checkbox">';
				}
			}
		</script>

	<script type="text/javascript" src="js/jquery.equalHeight.js"></script>
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
    /*  	  $(".tablesorter").tablesorter({ 
        // change the multi sort key from the default shift to alt button 
        sortMultiSortKey: 'altKey' 
    }); */
   	 } 
	);
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
    $(function(){
        $('.column').equalHeight();
    });
</script>

  <link rel="Stylesheet" type="text/css" href="style/jqueryui/ui-lightness/jquery-ui-1.7.2.custom.css" />

    <script type="text/javascript" src="scripts/jHtmlArea-0.7.0.js"></script>
    <link rel="Stylesheet" type="text/css" href="style/jHtmlArea.css" />
    
    <style type="text/css">
        /* body { background: #ccc;} */
        div.jHtmlArea .ToolBar ul li a.custom_disk_button 
        {
            background: url(images/disk.png) no-repeat;
            background-position: 0 0;
        }
        
        div.jHtmlArea { border: solid 1px #ccc; }
    </style>


<script type="text/javascript">    
         $(function() {
            $(".jhtmls").htmlarea(); // Initialize all TextArea's as jHtmlArea's with default values
			
         });
    </script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css" />
    
     <script>
    $(function() {
        $( "#datepicker" ).datepicker();
		$( "#datepicker1" ).datepicker();
    });
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