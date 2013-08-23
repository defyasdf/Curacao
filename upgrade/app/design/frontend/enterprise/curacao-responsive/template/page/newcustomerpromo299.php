<?php
/**
 * Template for Mage_Page_Block_Html
 
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->

<head>
	<?php
		if(!isset($_GET['hid'])){
		?>
			<script type="text/javascript">
            	window.location.replace("http://www.icuracao.com");
            </script>
		<?php
		}
	?>
	<?php echo $this->getChildHtml('head') ?>
	
    <style type="text/css">
    	p{
			text-transform:none;
			font-size:14px;
		}
		h1{
			text-transform:none;
		}
    </style>

    <link href="/skin/frontend/enterprise/curacao-responsive/css/gwm.css" rel="stylesheet">
    <link rel="stylesheet/less" type="text/css" href="/styles/style.less" />
	<script src="/js/less-1.4.2.min.js" type="text/javascript"></script>
    
</head>

<body<?php echo $this->getBodyClass()?' class="'.$this->getBodyClass().'"':'' ?>>

	<?php //echo $this->getChildHtml('after_body_start') ?>
	<!-- ClickTale Top part 
<script type="text/javascript">
var WRInitTime=(new Date()).getTime();
</script>
<!-- ClickTale end of Top part -->

	<header>
		
		<div class="container">
			<?php echo $this->getChildHtml('header') ?>
		</div><!--container-->
		
	</header>
	
	<div class="container">
	
		<div class="twenty columns breadcrumbs alpha">
			<?php echo $this->getChildHtml('breadcrumbs') ?>
		</div><!--breadcrumbs-->

		<div class="twenty columns alpha">
			<div class="wrapper">
              <?php 
			  			$ccode = '';
									if(Mage::getSingleton('core/session')->getCouponjoin100()){
										$ccode =  Mage::getSingleton('core/session')->getCouponjoin100();
									}else{
										$coupon = file_get_contents('https://www.icuracao.com/onestepcheckout/ajax/createautosignupcoupon/');
										$code = json_decode($coupon);
										$ccode = $code->code;
										Mage::getSingleton('core/session')->setCouponjoin100($code->code);
									}
								?>
   			  <!-- GWM Landing Page Code -->
              <div class="container">
			<div id="top-content">
			
				<div class="main-content">
					<img src="images/hotdealsnew299.png" usemap="#Map">
                    <map name="Map">
                      <area shape="rect" coords="16,300,163,395" href="http://www.icuracao.com/slr-s/nikon-d5200-dslr-camera-with-18-55mm-vr-lens-24-1-megapixel-black.html">
                      <area shape="rect" coords="290,264,488,393" href="http://www.icuracao.com/tablets/asus-eee-pad-tf300t-b1-bl-10-1-1gb-ddr3-32gb-android-4-0-ice-cream-sandwich-tablet-blue.html">
                      <area shape="rect" coords="119,182,284,298" href="http://www.icuracao.com/dell-inspiron-i14z-6001slv-ultrabook-14-8gb-ram-500gb-hdd-moon-silver.html">
                      <area shape="rect" coords="325,62,688,172" href="http://www.icuracao.com/all-tv-s/sharp-aquos-lc60le650u-60-led-1080p-120hz-wifi.html">
                      <area shape="rect" coords="332,36,666,76" href="http://www.icuracao.com/all-tv-s/sharp-aquos-lc60le650u-60-led-1080p-120hz-wifi.html">
                      <area shape="rect" coords="355,174,481,260" href="http://www.icuracao.com/all-tv-s/sharp-aquos-lc60le650u-60-led-1080p-120hz-wifi.html">
                    </map>
                    <div style="position: absolute; color: rgb(255, 255, 255); font-weight: bold; font-size: 19px; margin-top: -72px; right: 362px;"> 
                    	<?php echo $ccode;?>
                    </div>
			  </div>
				
				<div class="side-content">
					<a href="http://www.icuracao.com/credit-application"><img src="images/apply.png"></a>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div id="middle-content">
			
				<div class="side-content">
					<h2>Categories</h2>
					<ul>
						<li><a href="http://www.icuracao.com/fashion-beauty">Fashion &amp; Beauty</a></li>
						<li><a href="http://www.icuracao.com/watches">Watches</a></li>
						<li><a href="http://www.icuracao.com/handbags">Handbags</a></li>
						<!--<li><a href="#">Travel</a></li>-->
						<li><a href="http://www.icuracao.com/designer-611">Designer</a></li>
						<li><a href="http://www.icuracao.com/cosmetics">Cosmetics</a></li>
						<li><a href="http://www.icuracao.com/health-wellness">Health &amp; Wellness</a></li>
						<li><a href="http://www.icuracao.com/washer-dryers">Washers &amp; Dryers </a></li>
						<li><a href="http://www.icuracao.com/computers">Computers &amp; Tablets</a></li>
						<li><a href="http://www.icuracao.com/televisions-home-theater">TVs</a></li>
						<li><a href="http://www.icuracao.com/sound-bars">Sound Bars</a></li>
						<li><a href="http://www.icuracao.com/audio-mp3-s">Audio &amp; MP3's</a></li>
						<li><a href="http://www.icuracao.com/headphones">Headphones</a></li>
						<li><a href="http://www.icuracao.com/digital-cameras">Digital Cameras</a></li>
						<li><a href="http://www.icuracao.com/video-games">Video Games</a></li>
<!--						<li><a href="#">Games</a></li>-->
						<li><a href="http://www.icuracao.com/vacuums">Vacuums</a></li>
						<li><a href="http://www.icuracao.com/iron-steamers">Iron &amp; Steamers</a></li>
					</ul>
				</div>
				
				<div class="main-content">
					
					<div class="top-products">
						<h2>Save $100 On Any of Our Top Products</h2>
						<ul>
							<li>
								<a href="http://www.icuracao.com/canon-eos-rebel-reblt3i-1855-18-megapixel-digital-slr-camera-lcd-3.html"><img src="images/product-1.png"></a>
								<div class="product-name">Canon EOS Rebel REBLT3I/1855 1</div>
								<div class="price">$649.99</div>
								<div class="plan">As low as <span class="monthly-payment">$49</span>/mo</div>
								<a href="http://www.icuracao.com/canon-eos-rebel-reblt3i-1855-18-megapixel-digital-slr-camera-lcd-3.html"><img src="images/detailsbutton.png"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/sony-vaio-fit-15e-svf15213cxp-15-5-4-gb-500-gb-multi-touch-notebook-computer-pink.html"><img width="100" alt="Sony VAIO Fit 15E SVF15213CXP Notebook Computer 15.5" src="https://www.icuracao.com/media/catalog/product/s/v/svf15213cxpab.jpg"></a>
								<div class="product-name">Sony VAIO Fit 15E SVF15213CXP Notebook Computer 15.5</div>
								<div class="price">$729.99</div>
								<div class="plan">As low as <span class="monthly-payment">$55</span>/mo</div>
								<a href="http://www.icuracao.com/sony-vaio-fit-15e-svf15213cxp-15-5-4-gb-500-gb-multi-touch-notebook-computer-pink.html"><img src="images/detailsbutton.png"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/frigidaire-ffht2117ls-30-20-5-cu-ft-freestanding-top-freezer-refrigerator-stainless-steel.html"><img src="images/product-3.png"></a>
								<div class="product-name">Frigidaire FFHT2117LS 30" 21 cu.ft.</div>
								<div class="price">$699.95</div>
								<div class="plan">As low as <span class="monthly-payment">$52</span>/mo</div>
								<a href="http://www.icuracao.com/frigidaire-ffht2117ls-30-20-5-cu-ft-freestanding-top-freezer-refrigerator-stainless-steel.html"><img src="images/detailsbutton.png"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/lg-42ln5300-class-42-1080p-60hz-hdtv.html"><img src="images/product-4.png"></a>
								<div class="product-name">LG 42LN5300 Class 42" / 1080p / 60Hz - HDTV</div>
								<div class="price">$499.99</div>
								<div class="plan">As low as <span class="monthly-payment">$50</span>/mo</div>
								<a href="http://www.icuracao.com/lg-42ln5300-class-42-1080p-60hz-hdtv.html"><img src="images/detailsbutton.png"></a>
							</li>
						</ul>
						<div class="clear"></div>
					</div>
					
					<div class="featured-brands">
						<h2>Featured Brands</h2>
						<ul>
							<li>
								<a href="http://www.icuracao.com/headphones?tv_brand=39"><img src="images/Beats-By-Dr-Dre-Logo-cropped.jpg"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/all-tv-s?tv_brand=20"><img src="images/brand-lg.png"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/all-tv-s?tv_brand=207"><img src="images/brand-samsung.png"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/mattress?tv_brand=490"><img src="images/brand-serta.png"></a>
							</li>
						</ul>
						<div class="clear"></div>
						<ul>
							<li>
								<a href="http://www.icuracao.com/gps?tv_brand=531"><img src="images/Garminlogo-cropped.JPG"></a>
							</li>
							<li>
								<a href=" http://www.icuracao.com/mattress?tv_brand=453"><img src="images/brand-primo.png"></a>
							</li>
							<li>
								<a href=" http://www.icuracao.com/catalogsearch/result/?cat=0&q=gucci+"><img src="images/brand-gucci.png"></a>
							</li>
							<li>
								<a href="http://www.icuracao.com/slr-s?tv_brand=80"><img src="images/brand-nikon.png"></a>
							</li>
						</ul>
						<div class="clear"></div>
					</div>
					
				</div>

			</div>
			
			<div id="bottom-content">
				<img src="images/couponcode.png">
                <div style="color: rgb(255, 255, 255); font-size: 25px; font-style: italic; padding-bottom: 3px; padding-top: 2px; right: 12px; position: absolute; margin-top: -37px;">"<?php echo $ccode;?>"</div>
			</div>
			
	</div>
                    <!-- End GWM Landing Page Code -->        
                     
                <div class="clear">&nbsp;</div>
                
              
            </div>
		</div>
	
      <?php echo $this->getChildHtml('footer') ?>
    <?php echo $this->getChildHtml('before_body_end') ?>
        <?php echo $this->getAbsoluteFooter() ?>
	</div>        

		

</body>
</html>
<?php
	if(isset($_GET['hid'])){
		if(Mage::getSingleton('core/session')->getGwmid()){
			$server = '192.168.100.121';
			$user = 'curacaodata';
			$pass = 'curacaodata';
			$db = 'icuracaoproduct';
			
			
			$link = mysql_connect($server,$user,$pass);
			
			mysql_select_db($db,$link);
			
			$gwm = 'update gwmtracking set landingpage = "Home_Signup_Promo" where gwmId = '.Mage::getSingleton('core/session')->getGwmid();
			mysql_query($gwm);
			
			mysql_close($link);
		}
	}
?>