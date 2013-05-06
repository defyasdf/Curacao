<section id="main" class="column"> 

	<article class="module width_full">
		<header><h3 class="tabs_involved">iCuracao Users</h3>
	
		</header>

		<div class="tab_container">
<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>First Name</th> 
    				<th>Last Name</th> 
    				<th>Email</th> 
    				<th>User Name</th> 
                    <th>Access Level</th>
                    <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
<?php 		
	$arr = array(
			'1' => 'Upload Data',
			'2' => 'Search Data',
			'3' => 'Process Data',
			'4' => 'Translate Data',
			'5' => 'Push Data to magento',
			'6' => 'General Admin'
		 );
		
	if(sizeof($content)>0){
		for($i=0;$i<sizeof($content);$i++){
?>				
            
            <tr> 
                <td></td>
                <td><?php echo $content[$i]->fname?></td> 
                <td><?php echo $content[$i]->lname?></td> 
                <td><?php echo $content[$i]->email?></td> 
                <td><?php echo $content[$i]->username?></td> 
                <td><?php 
						$ac = explode(',',$content[$i]->access_level);
						foreach($ac as $value){
							echo '<div>'.$arr[$value].'</div>';
						}
				?></td> 
                <td><input type="image" src="images/icn_edit.png" onClick="return edituser('<?php echo $content[$i]->user_id?>')" title="Edit"><a href="index.php/deleteuser?uid=<?php echo $content[$i]->user_id?>"><input type="image" src="images/icn_trash.png" title="Trash"></a></td> 
            </tr> 
				
             <?php }
			 
		}else{
			
			?>
				<tr>
                	<td colspan="7">There is no user available</td>
                </tr>
			<?php	
			}
			?>
			</tbody> 
			</table>
            </div></article>
 </section>