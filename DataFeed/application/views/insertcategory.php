<section id="main" class="column"> 
	<div id="notify">
    	<?php echo $msg;?>
    </div>
	<article class="module width_full">
		<header><h3 class="tabs_involved">iCuracao Categories</h3>
	
		</header><br />


        <a href="index.php/category">
        <button type="submit" class="btn btn-primary start" style="margin-left:15px;">
            <i class="icon-upload icon-white"></i>
            <span>Add New Category</span>
        </button>
        </a>

<br />
<br />
		<div class="tab_container">
			<?php echo $content;?>	
        </div></article>
 </section>