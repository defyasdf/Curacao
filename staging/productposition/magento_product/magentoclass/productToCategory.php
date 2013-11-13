<?php
	// INI setting
		ini_set('max_execution_time', 0);
		ini_set('display_errors', 1);
		ini_set("memory_limit","1024M");
		ini_set('apc.cache_by_default','Off');
	// DB settings
		$server = '192.168.100.121';
		$user = 'curacaodata';
		$pass = 'curacaodata';
		$db = 'curacao_magento';
		$link = mysql_connect($server,$user,$pass);
		mysql_select_db($db,$link) or die("No DB");	

	//Magento Class Setting		
		$mageFilename = '/var/www/upgrade/app/Mage.php';
		require_once $mageFilename;
		Varien_Profiler::enable();
		Mage::setIsDeveloperMode(true);
		umask(0);
		Mage::app('default'); 
		$currentStore = Mage::app()->getStore()->getId();
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	//Codding Logic
	
		$sql = 'select * from catalog_category_product where category_id = "'.$_REQUEST['cId'].'" ORDER BY `position` ASC';
		$result = mysql_query($sql,$link) or die(mysql_error());
		/*$_testproductCollection = Mage::getResourceModel('catalog/product_collection')
		->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
		->addAttributeToFilter('category_id', array('in' => $_REQUEST['cId']))
		->addAttributeToSelect('*')
		->addAttributeToSort('position')
//		->setPageSize(3) // Use this for paging	
		->addAttributeToFilter('status', 1);
		$_testproductCollection->load();*/
		while($row = mysql_fetch_array($result)){
	//	foreach($_testproductCollection as $product){ 	
		
			$_product = new Mage_Catalog_Model_Product();
		    $_product->load($row['product_id']);
			
			$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
			if($_product->getStatus() == '1' && $qtyStock > 0)//if product is in stock, store product id
			{
				 $image  = Mage::getModel('catalog/product_media_config')
            ->getMediaUrl( $_product->getImage() );
			   ?>
			    <div class="catText" data-id="<?php echo $_product->getId()?>">
                	<div>
                    	<img src="<?php echo $image?>" width="154" height="154" />
                    </div>
                    <div>
                    	<?php echo substr($_product->getName(),0,50);?>
                    </div>
                    <div>
                    	<?php 
							$start_ts = strtotime($_product->getSpecialFromDate());
							$end_ts = strtotime($_product->getSpecialToDate());
							$user_ts = strtotime(date('Y-m-d'));
						
							if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){		
								if($_product->getSpecialPrice()== ''){
									$_price = $_product->getPrice(); 
								}else{
									$_price = $_product->getSpecialPrice(); 		
								}
							}else{
								$_price = $_product->getPrice(); 
							}
							
							
							echo '<strong>$'.number_format($_price,2).'</strong>';
						?>
                    </div>
                
                </div>
			   <?php
			}
	
		}