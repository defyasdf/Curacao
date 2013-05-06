<div id="ajaxcontentbg">
	
</div>

<div id="prioritydiv">

   	<form>
    <fieldset>
    <label>Write a Comment</label>
<textarea name="comment" id="comment"></textarea>


  </form>
  
  <br />
  
<br /><br />
<br />


         <button onclick="return revertproducts()" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                    <i class="icon-trash icon-white"></i>
                    <span>Process Content</span>
                </button>
        </fieldset><br />
<br />
<input type="hidden" id="prsku" name="prsku" />
    </div>
<section id="main" class="column">
<article class="module width_full">
		<header><h3 class="tabs_involved">Pening Transation and review</h3>
	
		</header>

		<div class="tab_container">
        <table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>Product Name</th> 
    				<th>Product SKU</th> 
    				<th>Product UPC</th> 
    				<th>Brand</th> 
                    <th>Source</th>
                    <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
				

            <?php 
//				print_r($content);
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr> 
                <td></td>
                <td><?php if($content[$i]->comment!=''){?><img src="images/icn_alert_warning.png" title="<?php echo $content[$i]->comment?>"  onclick="return alertmsg('<?php echo $content[$i]->comment?>')"/><?php }?><?php echo $content[$i]->prduct_name?></td> 
                <td><?php echo $content[$i]->product_sku?></td> 
                <td><?php echo $content[$i]->product_upc?></td> 
                <td><?php echo $content[$i]->product_brand?></td> 
               <td><?php echo $content[$i]->product_source?></td>  
                <td><a href="" onclick="return setcomment('<?php echo $content[$i]->product_upc?>')"><input type="image" src="images/icn_alert_error.png" title="Unapprove"></a><a href="index.php/review?sku=<?php echo $content[$i]->product_sku?>"><input type="image" src="images/icn_alert_success.png" title="Approve"></a></td> 
            </tr> 
                				
				<?php
				}
				}else{
			
			?>
				<tr>
                	<td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                </tr>
			<?php	
			}
			?>
			</tbody>
            
            </table>
            </div>
</article>
</section>