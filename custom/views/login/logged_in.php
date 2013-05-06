<?php include('views/header/header.php'); ?>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->


        <!-- 
        If you want to make this "you are logged in"-box wider, simply ...        
        -->

        <div style="position:absolute; top:0; right:100px; background-color:#fff; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25); width:250px; height:50px;">
            <div id="login_avatar" style="width:50px; height:50px; float:left; margin:0; background-image: url('views/img/logo_flower.gif')">
                <!--<img id="login_avatar" src="views/img/ani_avatar_static_01.png" style="width:125px; height:125px;" />-->
            </div>
            <div style="width: 110px; height: 50px; float:left; margin:0; font-family: 'Droid Sans', sans-serif; color:#666666; font-size:12px; border:0; height:100%; line-height: 50px; padding-left:20px; padding-right: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                Hey, <?php echo $_SESSION['user_name']; ?>
            </div>
            <div class="login_logout">
                <a href="index.php?logout" style="width:49px; height:19px; padding-top: 31px; display:block; text-align: center; font-size:10px; font-family: 'Droid Sans', sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" >Logout</a>
            </div>            
            
           
        </div>

	<div id="middle_div">
    	<h2>List of Files</h2>
		<?php
		
			//path to directory to scan
			$directory = "./";
			 
			//get all image files with a .jpg extension.
			$images = glob($directory . "*.php");
			 
			//print each file name
			foreach($images as $image)
			{
				$pos = strpos($image, "get");
				if($_SESSION['user_name'] == 'snprajapati'){
					echo '<p><a href="'.$image.'">'.$image."</a></p>";
				}else{
				if($pos !== false){
					echo '<p><a href="'.$image.'">'.$image."</a></p>";
				}
				}
			}
		?>	
    </div>
		
        
<?php include('views/footer/footer.php'); ?>