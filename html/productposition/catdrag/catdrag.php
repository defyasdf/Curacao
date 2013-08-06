<?php
if (isset($_REQUEST['save'])) {
    print "<pre>";
   	 print_r($_REQUEST);
    print "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Drag Category</title>
	<link href="css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
	    body{
		font-family:tahoma;
		background-color: aliceblue;
	    }
	    .catHeader{
		font-weight: bold;
		font-size:larger;
		cursor:pointer;
	    }
	    .catText{
		padding:10px;
		margin:10px;
		background-color: blanchedalmond;
		border:1px solid #DADADA;
		cursor:move;
	    }
	    .catChild{
		display:none;
	    }
	</style>
	<script type="text/javascript">
	    $(document).ready(function(){
		$(".catHeader").click(function(){
		    $(this).next().toggle("slow"); 
		});
		$( ".catEachChild" ).sortable();
		$( ".catEachChild" ).disableSelection();
		
		var cat_id;
		$("#saveBTN").click(function(){
		    $(".catEachChild").each(function(i,v){
			cat_id = $(v).data("catid");
			$(this).find(".catText").each(function(index,value){
			    $("<input />").attr({"type":"hidden",'name':"cat["+cat_id+"][]",'value':$(value).data("id")}).appendTo("#frm");
			});
		    });
		    
		    $("#frm").submit();
		});
	    });
	    
	</script>
    </head>
    <body>

	<div class="catParent">
	    <div class="catHeader">Electronics</div>
	    <div class="catChild">
		<div class="catEachChild" data-catid="1">
		    <div class="catText" data-id="1">Product 1</div>
		    <div class="catText" data-id="2">Product 2</div>
		    <div class="catText" data-id="3">Product 3</div>
		    <div class="catText" data-id="4">Product 4</div>
		    <div class="catText" data-id="5">Product 5</div>
		</div>

	    </div>
	</div>
	<!--<div class="catParent">
	    <div class="catHeader">Computer Hardware</div>
	    <div class="catChild">
		<div class="catEachChild" data-catid="2">
		    <div class="catText" data-id="6">Product 6</div>
		    <div class="catText" data-id="7">Product 7</div>
		    <div class="catText" data-id="8">Product 8</div>
		    <div class="catText" data-id="9">Product 9</div>
		    <div class="catText" data-id="10">Product 10</div>
		</div>
	    </div>
	</div>	-->




	<div style="margin-top:20px;clear:both;">
	    <input type="button" value="Save" style="width:100px;" id="saveBTN" /> 
	</div>

	<form method="post" id="frm" action="">
	    <input type="hidden" name="save" value="1" />
	    <input type="hidden" name="saveValues" value="1" id="saveValues"/>
	    <input type="submit" name="sb" value="1" style="display:none" />
	</form> 
    </body>
</html>