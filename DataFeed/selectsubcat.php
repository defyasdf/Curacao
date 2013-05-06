<?php

include 'includes/config.php';

$sql = "SELECT * FROM categories where parent_id = ".$_POST['cId'];
$qry = mysql_query($sql);
if(mysql_num_rows($qry)>0){
$str = "<div>&nbsp;</div>";	
$str .= '<select name="pId'.$_POST['level'].'" id="pId'.$_POST['level'].'" onchange=selectcat("'.$_POST['level'].'")>';
$str .= '<option value="0">Choose Category</option>';
while($row = mysql_fetch_array($qry)){
	$str .= '<option value="'.$row['id'].'">';
		$str.= $row['name'];
	$str .= '</option>';
}
$str .= '</select>';

echo $str;
}else{
		echo 0;
}
