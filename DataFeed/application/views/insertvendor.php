<section id="main" class="column"> 
	<div id="notify">
    	<?php echo $msg;?>
    </div>
	<article class="module width_full">
		<header><h3 class="tabs_involved">iCuracao Vendors</h3>
	
		</header>

		<div class="tab_container">
<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>Vendor Name</th> 
    				<th>User Name</th> 
                    <th>Vendor Id</th>
                    <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
<?php 		
	
	if(sizeof($content)>0){
		for($i=0;$i<sizeof($content);$i++){
?>				
            
            <tr> 
                <td></td>
                <td><?php echo $content[$i]->vendorName?></td> 
                <td><?php echo $content[$i]->username?></td> 
                <td><?php echo $content[$i]->vendorID?></td>               
                <td><input type="image" src="images/icn_edit.png" onClick="return edituser('<?php echo $content[$i]->vmID?>')" title="Edit"><a href="index.php/deletevendor?vid=<?php echo $content[$i]->vmID?>"><input type="image" src="images/icn_trash.png" title="Trash"></a></td> 
            </tr> 
				
             <?php }
			 
		}else{
			
			?>
				<tr>
                	<td>no data</td>
                	<td>no data</td>
                    <td>no data</td>
                    <td>no data</td>
                    <td>no data</td>
                </tr>
			<?php	
			}
			?>
			</tbody> 
			</table>
            </div></article>
 </section>