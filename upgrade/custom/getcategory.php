<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$mageFilename = '/var/www/upgrade/app/Mage.php';
		
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	
	
	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$rootCatId = Mage::app()->getStore()->getRootCategoryId();

	function getTreeCategories($parentId, $isChild){
		$allCats = Mage::getModel('catalog/category')->getCollection()
					->addAttributeToSelect('*')
					->addAttributeToFilter('is_active','1')
					->addAttributeToFilter('parent_id',array('eq' => $parentId))
					->addAttributeToSort('position', 'asc'); 
		$html = '';			
		$class = ($isChild) ? "sub-cat-list" : "cat-list";
		$html .= '<ul class="'.$class.'" id="category_'.$parentId.'">';
		foreach($allCats as $category)
		{
			$html .= '<li><span onclick = "return ToggleSubcat(\''.$category->getId().'\')" id="category_name_'.$category->getId().'">'.$category->getName().'</span> <span class="saperator">|</span> <span class="productLink" onclick = "return getProducts(\''.$category->getId().'\')">Show Products</span>';
			$subcats = $category->getChildren();
			if($subcats != ''){ 
				$html .= getTreeCategories($category->getId(), true);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	$rootCatId = 2;
	$catlistHtml = getTreeCategories($rootCatId, false);
	
	echo $catlistHtml;
?>
<style type="text/css">
	.sub-cat-list{
		display:none;
	}
	ul.sub-cat-list li span{
		font-size:14px !important;
	}
	
	
	ul.sub-cat-list li span, ul.cat-list li span{
		
		cursor:pointer;
	}
	.productLink{
		color:#03F;
		font-weight:normal !important;
		font-size:12px !important;
	}
	ul.cat-list li span{
		font-weight: bold;
        font-size:larger;
	}
	.saperator{
		margin-left:15px;
		margin-right:15px;
	}
</style>
