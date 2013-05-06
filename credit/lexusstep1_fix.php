<?php include_once('_includes/dictionary_eng.php');?>
<?php include_once('_includes/db.php'); ?>

<html>
	<head>
		<title>Credit Application</title>		
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/skeleton.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/styles.css" media="all" />
	</head>
	<body>
		<div class="container small">
	    	<div class="col twenty columns">

				<h2>Data Verification</h2>
				<fieldset>
					<input type="hidden" id="formsubmit" value="0" />
					<div class="messages">For verification purposes, please provide the following information:</div>
				
					
					<form action="finalstep.php" method="post" id="appform">
					
						<input type="hidden" id="appId" value="<?php echo $_POST['appid']?>" name="appid" />
						
                        <div class="col2-set">
                        <div class="col ten columns alpha">
				   		<div class="field">
							<div>
								<label>Employer Name<span id="require">*</span></label>
								<input type="text" name="company" required class="required" />
							</div>
                        </div>
						<div>
			       		<div class="field">	
							<label>Time with Current Employer<span id="require">*</span></label>		
							<select name="emonths" required="required" class="required">
								<option value="">&lt;MM&gt;</option>
								<?php  for($i=0;$i<13;$i++){ ?>
								<option value="<?php echo $i?>"><?php echo $i?></option>
								<?php } ?>
							</select>
							
							<select name="eyears" required="required" class="required">
								<option value="">&lt;YYYY&gt;</option>
								<?php  for($i=0;$i<100;$i++){ ?>
								<option value="<?php echo $i?>"><?php echo $i?></option>
								<?php } ?>				
							</select>
						</div>
                        </div>
						</div>
                        <div class="col ten columns omega">
			       		<div class="field">
							<div id="phones">
                            <label>Work Phone<span id="require">*</span></label>
							
                             <input type="text" size="3" maxlength="3" required class="required three" name="warea"/>
                            <input type="text" size="3" maxlength="3" required class="required three" name="wlocal1"/>
                            <input type="text" size="4" maxlength="4" required class="required four"  name="wlocal2"/>
                            </div>
                            
                            <!--<input type="text" size="3" required class="required" maxlength="3" name="warea" style="width:85px">
							<input type="text" size="3" maxlength="3" required class="required" name="wlocal1" style="width:85px">
							<input type="text" size="4" maxlength="4" required class="required" name="wlocal2" style="width:105px">-->
						</div>
			       		<div class="clearfix"></div>
                    	<div class="field">
							<label>Monthly Income<span id="require">*</span></label>
							<input type="text" name="salary" required class="required" />
						</div>
			       		<div class="field">
							<label>Mothers Maiden Name<span id="require">*</span></label>
							<input type="text" name="maidenname" required class="required" />
						</div>
						</div>
                        </div>

			       		<div class="field">
							<h2>E-Sign Consent and Terms and Conditions</h2>
							<hr>	
							<p>You must read the E-sign consent of the <a href="">Terms and Conditions</a> prior to checking the box below.</p>
							<table style="margin-left:-6px;margin-top: 10px;">
								<tbody>
									<tr>
										<td><input type="checkbox" name="agreeToTerms" id="agreeToTerms" required class="required"></td>
										<td><span>I agree to have the Terms and Conditions presented electronically</span></td>
									</tr>
								</tbody>
							</table>
						</div>
			       		<div class="field">
							<div align="center">Term and Conditions of Curacao</div>
							<div style="height:150px; overflow-y:scroll; overflow-x:hidden">
							<?php 
								echo file_get_contents('caengterm.php');
							?>
                            </div>
						</div>
			
						
						<input type="image" src="agree.jpg" style="width:156px;" onClick="return formvalidation()" />
			
			
					
					</form>
				</fieldset>


			</div>
		</div>
		
<script type="text/javascript" src="js/jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
	  JQuery(document).ready(function() {
		  JQuery(".required").change(function(){
		   		if(JQuery("#formsubmit").val()=='1'){
					formvalidate();
				}
			
		   })
		JQuery("#appform").submit(function(){
			var isFormValid = true;
			
			JQuery(".required input").each(function(){
				if (JQuery.trim(JQuery(this).val()).length == 0){
					JQuery(this).addClass("highlight");
					isFormValid = false;
				}
				else{
					JQuery(this).removeClass("highlight");
				}
			});
		
			if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			
			return isFormValid;
		});
	  });
	  function createcredit(){}
	  function formvalidate(){
		  
			 var isFormValid = true;
			JQuery(".required").each(function(){
				if (JQuery.trim(JQuery(this).val()).length == 0){
					JQuery(this).addClass("highlight");
					isFormValid = false;
					JQuery("#formsubmit").val('1');
				}
				else{
					JQuery(this).removeClass("highlight");
				}
			});
		
			//if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			return isFormValid;
			return false;
	}
	function formvalidation(){

		var isFormValid = true;
		  
	      JQuery(".required").each(function(){
				if (JQuery.trim(JQuery(this).val()).length == 0){
					JQuery(this).addClass("highlight");
					isFormValid = false;
					JQuery("#formsubmit").val('1');
				}
				else{
					JQuery(this).removeClass("highlight");
				}
			});
		
			if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			
			if(isFormValid){
				var ch = JQuery('input[name="agreeToTerms"]:checked').length
				if(ch==0){
					JQuery("#formsubmit").val('1');
					isFormValid = false;
					alert("Please accept terms and conditions");
				}
				
				JQuery("#formsubmit").val('');	
			}
			if(JQuery("#valiadd").val()=='0'){
				isFormValid = false;
			}
			
			return isFormValid;
	}
</script>
<script type="text/javascript">
	var p;
	
	function next(i) {
		return function() {
			//strip non-digits
			p[i].value=p[i].value.replace(/[^0-9]/g, "");
			
			//go forward one box when full, except when on the end box
			if(p[i].value.length==p[i].size && i<p.length) p[i+1].focus();
		}
	}
	
	function back(i) {
		return function(e) {
			//go backward one when empty, except when on the first box
			if(e.keyCode==8 && p[i].value.length==0 && i>0) p[i-1].focus();
		}
	}
	
	function next1(i) {
		return function() {
			//strip non-digits
			p1[i].value=p1[i].value.replace(/[^0-9]/g, "");
			
			//go forward one box when full, except when on the end box
			if(p1[i].value.length==p1[i].size && i<p1.length) p1[i+1].focus();
		}
	}
	
	function back1(i) {
		return function(e) {
			//go backward one when empty, except when on the first box
			if(e.keyCode==8 && p1[i].value.length==0 && i>0) p1[i-1].focus();
		}
	}

	window.onload=function() {
		p=document.getElementById("phones").getElementsByTagName("input");
	
		for(var i=0; i<p.length; i++) {
			p[i].onkeyup=next(i);
			p[i].onkeydown=back(i);
		}
		
		p1=document.getElementById("ssn").getElementsByTagName("input");
	
		for(var i=0; i<p1.length; i++) {
			p1[i].onkeyup=next1(i);
			p1[i].onkeydown=back1(i);
		}
		
		
	}
</script>

	</body>
</html>