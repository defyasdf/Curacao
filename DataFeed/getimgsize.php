<?php 
$img = getimagesize($_GET['img']);	
	
?>

<td style="margin-right:10px; border-right:2px solid #000;">   
                                        
        <img src="<?php echo $_GET['img']?>" width="60" height="60"><br />
        Width : <?php echo $img[0]?><br />
        Height :<?php echo $img[1]?>
        
    </td>