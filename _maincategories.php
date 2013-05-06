<?php

	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	
	$sql = 'select * from categories where parent_id = 0';
	$result = mysql_query($sql);
	$data = array();
	while($row = mysql_fetch_array($result)){
		$sql1 = 'select * from categories where parent_id = '.$row['id'];
		$result1 = mysql_query($sql1);
		while($row1 = mysql_fetch_array($result1)){
			$data[$row['magento_category_id']][] = $row1['magento_category_id'];
		}
	}
	
	for($i=0;$i<sizeof($data[8]);$i++){
		
		$sqls = "SELECT * FROM `finalproductlist` WHERE `magento_category_id` = ".$data[8][$i];
		$resu = mysql_query($sqls);
		while($rows = mysql_fetch_array($resu)){	
			echo '<div>'.$rows['prduct_name'].'</div>';
			print_r($rows);
		}
		
	}
	
	
	
	function hasChild($parent_id)
  {
    $sql = "SELECT COUNT(*) as count FROM categories WHERE parent_id = ' " . $parent_id . " ' ";
    $qry = mysql_query($sql);
    $rs = mysql_fetch_array($qry);
    return $rs['count'];
  }
 // Category List 
  function CategoryTree($list,$parent,$append)
  {
	$list = array(); 
    
	$list[] = $parent['magento_category_id'];
	
//	$list = '<li>'.$parent['name'].' -- '.$parent['magento_category_id'].'    <a href="index.php/editcat?catid='.$parent['id'].'">Edit</a> | <a href="index.php/deletecat?catid='.$parent['id'].'">Delete</a></li>';
    
    if (hasChild($parent['id'])) // check if the id has a child
    {
      $append++; // this is our basis on what level is the category e.g. (child1,child2,child3)
    //  $list .= "<ul class='child child".$append." '>";
      $sql = "SELECT * FROM categories WHERE parent_id = ' " . $parent['id'] . " ' ";
      $qry = mysql_query($sql);
      $child = mysql_fetch_array($qry);
      do{
        $list[] = CategoryTree($list,$child,$append);
      }while($child = mysql_fetch_array($qry));
      //$list .= "</ul>";
    }
	
//	print_r($list);
	return $list;
  }
  function CategoryList()
  {
    $list = array();
    $mainlist = array();
    $sql = "SELECT * FROM categories WHERE (parent_id = 0 OR parent_id IS NULL)";
    $qry = mysql_query($sql);
    $parent = mysql_fetch_array($qry);
    //$mainlist = "<ul class='parent'>";
    do{
      $mainlist []= CategoryTree($list,$parent,$append = 0);
    }while($parent = mysql_fetch_array($qry));
    //$list .= "</ul>";
    return $mainlist;
  }

/*echo '<pre>';
print_r();
echo '</pre>';*/
$cat = CategoryList();
$data = array();
for($i=1;$i<sizeof($cat[0]);$i++){
		$data[$cat[0][0]][] = $cat[0][$i];
}

echo serialize ($data);
/*echo '<pre>';
print_r($data);
echo '</pre>';
*/