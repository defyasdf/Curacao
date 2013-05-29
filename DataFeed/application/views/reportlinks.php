<section id="main" class="column"> 

	<div id="notify">
    	
    </div>
<article class="module width_full">
			<header><h3>Curacao Report</h3></header>
				
                <div id="notify"></div>
            
            	<div class="module_content">
                	
                    <div id="middle_div">
                    <h2>List of Files</h2>
                    <ul>
                    	<li>
                        	<a href="http://icuracao.com/custom/_getorder_collection_withdate.php?sdate=<?php echo $_POST['sdate']?>&edate=<?php echo $_POST['edate']?>">Get Orders</a>
                        </li>
                        <li>
                        	<a href="http://icuracao.com/custom/_getorder_item_collection.php?sdate=<?php echo $_POST['sdate']?>&edate=<?php echo $_POST['edate']?>">Get Orders with Items</a>
                        </li>
                        <li>
                        	<a href="http://icuracao.com/custom/_getorder_item_collection_margin.php?sdate=<?php echo $_POST['sdate']?>&edate=<?php echo $_POST['edate']?>">Get Orders with Items and margin</a>
                        </li>
                        <li>
                        	<a href="http://icuracao.com/custom/getauthenticationreport.php?sdate=<?php echo $_POST['sdate']?>&edate=<?php echo $_POST['edate']?>">Get Authentication Report</a>
                        </li>                        
                    </ul>
					<?php
                    /*
                        //path to directory to scan
                        $directory = "../custom/";
                        //get all image files with a .jpg extension.
                        $images = glob($directory . "*.php");
                         
                        //print each file name
                        foreach($images as $image)
						
                        {
                            $pos = strpos($image, "get");
                            
                            if($pos !== false){
                                echo '<p><a href="'.$image.'">'.$image."</a></p>";
                            }
                        }
						
						*/
                    ?>	
                </div>
                               
                
				</div>
			
		</article><!-- end of post new article -->
        
 </section>