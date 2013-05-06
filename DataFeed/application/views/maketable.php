
<section id="main" class="column"> 
	<article class="module width_half">
    <div class="module_content">
    	<form action="" method="post">
        <fieldset><label>Copied Text</label>
        <textarea name="string" rows="40" cols="45"></textarea>
        </fieldset>
        <fieldset><label>Style</label>
        
        <select name="style">
            <option value="1">One Line</option>
            <option value="2">Two line</option>
        </select>
        </fieldset>
        <fieldset>
	        <input type="submit" value="Generate">
        </fieldset>
        </form>
		</div>
	</article>
      	<?php 
if(isset($_POST['string'])){ ?>
    <article class="module width_half">
  <?php
	
	
	$str = $_POST['string'];
	
	$s = preg_split ('/$\R?^/m', $str);
	
	$final_copy = '';
	
	if($_POST['style']=='1'){
		$final_copy .= '<table>';
		
		for($i=0;$i<sizeof($s);$i++){
			
				$final_copy .= '<tr>';
					$r = explode(':',$s[$i]);
					if(sizeof($r)==2){
						$final_copy .= '<td>'.$r[0].'</td><td>'.$r[1].'</td>';
					}else{
						
						$final_copy .= '<td>'.$r[0].'</td>';
					}
				$final_copy .= '</tr>';
			
		}
		$final_copy .= '</table>';
	}else{
			$arr = array();
			for($i=0;$i<sizeof($s);$i++){
				if(trim($s[$i])!=''){
					$arr[] = $s[$i];
				}
			}
			
			$final_copy .= '<table><tr>';
				for($j=0;$j<sizeof($arr);$j++){
					if($j%2==0){
						$final_copy .= '</tr><tr><td>'.$arr[$j].'</td>';
					}else{
						$final_copy .= '<td>'.$arr[$j].'</td>';
					}
				}
			$final_copy .= '</tr></table>';
	}



	
?>
 <div class="module_content">
<h2>Please Copy the code and paste it into final copy box</h2>
 <fieldset>
<textarea name="string" rows="40" cols="50" class="jhtmls">
	<?php echo $final_copy;?>
</textarea>

</fieldset>
</div>
	</article>
	<?php 
	}
?>
</section>