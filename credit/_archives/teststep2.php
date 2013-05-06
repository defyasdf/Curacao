<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript">
	  $(document).ready(function() {
		  $(".required").change(function(){
		   		if($("#formsubmit").val()=='1'){
					formvalidate();
				}
			
		   })
		$("#appform").submit(function(){
			var isFormValid = true;
			
			$(".required input").each(function(){
				if ($.trim($(this).val()).length == 0){
					$(this).addClass("highlight");
					isFormValid = false;
				}
				else{
					$(this).removeClass("highlight");
				}
			});
		
			if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			
			return isFormValid;
		});
	  });
	  function createcredit(){
		 var formdata = $('form').serializeArray()
		  $.ajax({
			  type: "POST",
			  url: "creditapplication.php",
			  data: formdata ,
			   success: function(data) {
					//alert(data)
					
					$("#appId").val(data);
			  }
			})
	  }
	  function formvalidate(){
		  
			 var isFormValid = true;
			$(".required").each(function(){
				if ($.trim($(this).val()).length == 0){
					$(this).addClass("highlight");
					isFormValid = false;
					$("#formsubmit").val('1');
				}
				else{
					$(this).removeClass("highlight");
				}
			});
		
			//if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
		
			return isFormValid;
			
			return false;
	}
	  function formvalidation(){

		  var isFormValid = true;
		  
	      $(".required").each(function(){
				if ($.trim($(this).val()).length == 0){
					$(this).addClass("highlight");
					isFormValid = false;
					$("#formsubmit").val('1');
				}
				else{
					$(this).removeClass("highlight");
				}
			});
		
			if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			
			if(isFormValid){
				var ch = $('input[name="agreeToTerms"]:checked').length
				if(ch==0){
					$("#formsubmit").val('1');
					isFormValid = false;
					alert("Please accept terms and conditions");
				}
				
				$("#formsubmit").val('');	
			}
			if($("#valiadd").val()=='0'){
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
<style type="text/css">
	body{
		font-family:helvetica;
		font-size:12px;
		font-weight:bold;
	}
	.validAdd{
		color:#88d24a;
	}
	.notvalidAdd{
		color:#990000;
	}
	input[type='text']{
		width:100%;
		height:27px;
		margin-top:0px;
		font-weight:normal;
	}
	select{height:27px;
		margin-top:0px;
		font-weight:normal;
	}
	#require{
		color:#900;
	}
	.highlight{

		border:1px solid #900;

	}
	span{
		font-size:12px;		
	}
</style>

  <div style="width:750px; overflow-y:scroll; overflow-x:hidden;">
        	<h2>Curacao Credit Application</h2>
    <fieldset>
	<div>
                For verification purposes, please provide the following information:  		
            </div>
            <br />
        <br />

        
        <form action="finalstep.php" method="post" id="appform">

            <input type="hidden" id="appId" value="<?php echo $_POST['appid']?>" name="appid" />
                <div style=" line-height:2">
                    <div style="width:330px; float:left; margin-right:20px;">
                        <div>
                        	<label>Employer Name<span id="require">*</span></label><br />
			                <input type="text" name="company" required class="required" />
                        </div>
                         <div>
                            <label>Time with Current Employer<span id="require">*</span></label><br />
            				
                            
                            <select name="emonths" required="required" class="required">
                            <option value="">&lt;MM&gt;</option>
                                <?php 
                                    for($i=0;$i<13;$i++){
                                        ?>
                                        <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php
                                    }
                                ?>
                            
                            </select>
                            
                            <select name="eyears" required="required" class="required">
                                <option value="">&lt;YYYY&gt;</option>
                                <?php 
                                    for($i=0;$i<100;$i++){
                                        ?>
                                        <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php
                                    }
                                ?>
                            
                            </select>
                            
                            
                        </div>
                        
                        <div id="phones">
                            <label>Work Phone<span id="require">*</span></label> <br />
            
                            <input type="text" size="3" required class="required" maxlength="3" name="warea" style="width:85px">
                            <input type="text" size="3" maxlength="3" required class="required" name="wlocal1" style="width:85px">
                            <input type="text" size="4" maxlength="4" required class="required" name="wlocal2" style="width:105px">
                        </div>
                        
                    </div>
                    
                    <div style="width:330px; float:left; ">
                        <div>
                        	<label>Monthly Income<span id="require">*</span></label><br />
			                <input type="text" name="salary" required class="required" />
                        </div>
                        
                        <div>
                        	<label>Mothers Maiden Name<span id="require">*</span></label><br />
			                <input type="text" name="maidenname" required class="required" />
                        </div>
                        
                    </div>
                    
                    
                    <div style="clear:both"></div>
                    
                    <div>
                    	<h2>E-Sign Consent and Terms and Conditions</h2>
                        <hr>
                    	
                        <p>
                        	You must read the E-sign consent of the <a href="">Terms and Conditions</a> prior to checking the box below.
                        </p>
                        <table style="margin-left:-6px;margin-top: 10px;">
                            <tbody>
                                <tr>
                                <td><input type="checkbox" name="agreeToTerms" id="agreeToTerms" required class="required"></td>
                                <td><span>
                                                I agree to have the Terms and Conditions presented electronically</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div align="center">Term and Conditions of Curacao</div>
                    <div style="height:110px; width:625px; overflow:scroll;">
                    	
                    </div><br>
<br>

                  <div align="center">
                        <input type="image" src="agree.jpg" style="width:156px;" onclick="return formvalidation()" />
                    </div>
               </div>
               
      </form>
        </fieldset>
        </div>