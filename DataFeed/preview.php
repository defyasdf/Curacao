<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php
include 'includes/config.php';
if($_GET['source']=='spenish'){
	$sql = "select * from spenishdata where sppr_id = ".$_GET['id'];
}elseif($_GET['source']=='english'){
	$sql = "select * from finalproductlist where fpl_id = ".$_GET['id'];
}
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
?>
<div style="width:800px; height:700px; overflow:scroll">
	<div style="float:left; width:200px; margin-right:10px;">
    	<?php if(trim($row['product_img_path'])!=''){ 
			$img = explode(',',$row['product_img_path']);
			
			$pimg = getimagesize($img[0]);
		?>
		<div>
        	<img src="<?php echo $img[0]?>" width="200">
            <br />
												Width : <?php echo $pimg[0]?><br />
												Height :<?php echo $pimg[1]?>
            
        </div>
		<?php }?>
    </div>
    <div style="float:left; width:530px;">
    <?php if(trim($row['product_img_path'])!=''){ 
		for($i=1;$i<sizeof($img);$i++){
			
			$pimg = getimagesize($img[$i]);
			
		?>
		<div style="width:110px; float:left">
        	<img src="<?php echo $img[$i]?>" width="100">
            <br />
												Width : <?php echo $pimg[0]?><br />
												Height :<?php echo $pimg[1]?>
            
        </div>
	
	<?php
			
		}
	 } ?>
    	
    
    </div>
    <div style="clear:both"></div>
    <div>
    	<h2><?php echo $row['prduct_name']?></h2>
        <p><?php echo htmlspecialchars_decode($row['product_description'],ENT_QUOTES)?></p>
    </div>
    <div style="clear:both"></div>
    
	
    <div class="tab_container">
			<div id="tab1" class="tab_content">
                <h2>Specifications</h2>
                <div>
                    <?php echo htmlspecialchars_decode($row['product_specs'],ENT_QUOTES)?>
                </div>
		    </div>
            <div id="tab2" class="tab_content">
            	<h2>Features</h2>
                
                <div>
                    <?php echo htmlspecialchars_decode($row['product_features'],ENT_QUOTES)?>
                </div>
                
            </div>    
    
    </div>
</div>