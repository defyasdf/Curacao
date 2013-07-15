<html>
<head>
<style>
.lightbox {
	background-color: #ffffff;
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: 630px;
	height: 380px;
}
#TB_ajaxContent{
	padding:25px 5px 15px;
}
#TB_window a img{
	display:none;
}
#TB_ajaxContent{
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	padding:10px;
}
.clear10 {
	height: 10px;
	clear: both;
}

.lightbox button {
	border: 0;
	background-color: #f26722;
	padding: 7px 20px;
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	box-shadow: 0 1px 1px #555555;
	-webkit-box-shadow: 0 1px 1px #555555;
	-moz-box-shadow: 0 1px 1px #555555;
	float:none;
}

.lightbox button.blue {
	background-color: #008ecf;
}

button span {	
	color: #ffffff;
	font-family: Arial, sans-serif;
	font-size: 12px;
	text-decoration: none;
}

span {
	font-size: 14px;
	font-family: Arial, sans-serif;
	color: #ffffff;
}

span.restrictions {
	font-size: 11px;
	font-family: Arial, sans-serif;
	color: #ffffff;
	float: left;
	line-height:12px;
}	

form .labels {
	font-size: 12px;
	color: #ffffff;
	font-family: Arial, sans-serif;
	float: left;
	line-height: 35px;
}

form .fields {
	margin-left: 20px;
	float: left;
}

form input {
	font-size: 14px;
	color: #999999;
	font-family: Arial, sans-serif;
	width: 280px;
	margin-bottom: 10px;
	box-shadow: inset 1px 1px 1px 0px #dddddd;
	-webkit-box-shadow: inset 1px 1px 1px 0px #dddddd;
	-moz-box-shadow: inset 1px 1px 1px 0px #dddddd;
	padding: 3px;
}

form input.skinny {
	height: 25px;
	margin:0 0 -4px;
	width: 285px;
}

form input.fat {
	height: 35px;
	margin: 10px 0 0 0;
	width: 285px;
}

#lb_joinnow {
	background: url(/lighbox_html/lb_joinnow_bg.jpg) no-repeat;
	width: 630px;
	height: 380px;
}

#lb_tv {
	background: url(lb_tv_bg.jpg) no-repeat;
	width: 630px;
	height: 380px;
}

#lb_dell {
	background: url(lb_dell_bg.jpg) no-repeat;
	width: 630px;
	height: 380px;
}

#lb_joinnow img {
	margin: 30px 0 0 250px;
}

#lb_tv img {
	margin: 40px 0 0 320px;
}

#lb_dell img {
	margin: 40px 0 0 320px;
}

#lb_joinnow form {
	margin: 15px 0 0 250px;
	width: 280px;
	text-align: center;
}

#lb_tv form {
	margin: 20px 0 0 325px;
	width: 280px;
}

#lb_dell form {
	margin: 20px 0 0 325px;
	width: 280px;
}

#lb_joinnow .thankyou {
	padding: 20px 0 0 200px;

}

#lb_tv .thankyou {
	padding: 20px 0 0 325px;
	width: 280px;
}

#lb_dell .thankyou {
	padding: 20px 0 0 325px;
	width: 280px;
}

#lb_joinnow h1 {
	font-size: 24px;
	font-weight: bold;
	font-family: Arial, sans-serif;
	color: #ffffff;
}

#lb_tv h1 {
	font-size: 24px;
	font-weight: bold;
	font-family: Arial, sans-serif;
	color: #ffffff;
}

#lb_dell h1 {
	font-size: 24px;
	font-weight: bold;
	font-family: Arial, sans-serif;
	color: #ffffff;
}

#lb_joinnow span.code {
	font-size: 40px;
	font-weight: bold;
	font-family: Arial, sans-serif;
	color: #ffffff;
}
div.validation-advice{
	height: 15px;
	margin-top: -35px;
	overflow: hidden;
	position: absolute;
}
</style>
</head>
<body>
	<div class="lightbox">
		<div id="lb_joinnow">
        	<span class="restrictions" onClick="countinueShop()" style="float:right;margin:5px; cursor:pointer;">Close [X]</span>
			<img src="/lighbox_html/joinnow_offer.png" /><br />
				<form>
                <span class="restrictions">*$100 store credit will apply to same day purchases of $499 or more. To redeem your store credit, enter the coupon code below at checkout.</span>
                <div class="clear10"></div><span>Coupon Code:</span>
                <div class="clear10"></div>
				<span class="code">
                	<?php 
						$coupon = file_get_contents('https://www.icuracao.com/onestepcheckout/ajax/createautosignupcoupon/');
						$code = json_decode($coupon);
						echo $code->code;
					?>
                </span>
				<div class="clear10"></div>
				<a href="#" onClick="countinueShop()"><button><span>SHOP NOW</span></button></a>
                </form>
		</div>
    
</body>
<html>