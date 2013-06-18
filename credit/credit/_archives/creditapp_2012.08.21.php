<?php include_once('credit_eng.php');?>

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
	input{
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
</style>

<div style="width:750px;">
<input type="hidden" id="formsubmit" value="0" />
<input type="hidden" id="valiadd" value="0" />

	<h2><?php echo TITLE?></h2>
    <fieldset>
	<div>
		<?php echo SUBTITLE?>  		
    </div>
    <br />
<br />

<form action="step2.php" method="post" id="appform">

<input type="hidden" id="appId" value="0" name="appid" />
    <div style=" line-height:2">
    	<div style="width:330px; float:left; margin-right:20px;">
       		<div>
                <label><?php echo FIRSTNAME;?><span id="require">*</span></label><br />
                <input type="text" name="fname" required="required" class="required" />
            </div>
            <div>
                <label><?php echo LASTNAME;?><span id="require">*</span></label><br />
                <input type="text" name="lname" required="required" class="required" />
            </div>
            
             <div>
                <label><?php echo EMAIL;?><span id="require">*</span></label><br />
                <input type="text" name="emailid" required="required" class="required" id="emailid" />
            </div>
            
            <div>
                <label><?php echo HOMEADDRESS1;?><span id="require">*</span></label><br />
                <input type="text" name="add1" required="required" id="add" class="required" />
            </div>
            <div>
                <label><?php echo HOMEADDRESS2;?></label><br />
                <input type="text" name="add2" />
            </div>
            <div>
                <label><?php echo APT;?></label><br />

                <input type="text" name="apt" style="width:105px" />
            </div>
            <div>
                <label><?php echo CITY;?><span id="require">*</span></label><br />

                <input type="text" name="city" required="required" class="required" style="width:105px"  />
            </div>
            <div>
                <label><?php echo State;?><span id="require">*</span></label><br />

              <select name="state"  required="required" class="required">
              <option value="">Choose State</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select>
            </div>
            <div>
                <label><?php echo Zip?><span id="require">*</span></label><br />

                <input type="text" name="zip" required="required" class="required" id="zip" style="width:105px" onchange="return validateaddress()" onkeyup="return checknumeric()" />
                
                <span id="addrvalidate"></span>
            </div>
       </div>	
        <div style="width:330px; float:left;">
       		<div>
               
                <label><?php echo TIME_LIVING?><span id="require">*</span></label><br />

                <select name="years" required="required" id="years" class="required">
                	<option value="">Years</option>
					<?php 
						for($i=0;$i<100;$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>
                
                <select name="months" required="required" class="required" id="months">
                <option value="">Months</option>
                	<?php 
						for($i=0;$i<12;$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>
                
            </div>
            <div id="phones">
                <label><?php echo HomePhone?><span id="require">*</span></label> <br />

                <input type="text" size="3" required="required" class="required" maxlength="3" name="area" style="width:85px">
				<input type="text" size="3" maxlength="3" required="required" class="required" name="local1" style="width:85px">
				<input type="text" size="4" maxlength="4" required="required" class="required" name="local2" style="width:105px">
            </div>
            <div id="cphones">
                <label><?php echo CellPhone?></label><br />

                <input type="text" name="cphone1" maxlength="3" size="3" style="width:85px" /><input type="text" maxlength="3" name="cphone2" size="3" style="width:85px" /><input type="text" maxlength="4" name="cphone3" size="4" style="width:105px" />
            </div>
            <div>
                <label><?php  echo DOB?><span id="require">*</span></label><br />

                <select name="dobM" required="required" class="required" onchange="return setupday()" id="dobM">
                <option value="">MM</option>
                	<?php 
						for($i=1;$i<13;$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>
                
                <select name="dobD" required="required" class="required" id="dobD">
                <option value="">DD</option>
                	<?php 
						for($i=1;$i<32;$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>

                <select name="dobY" id="dobY" required="required" class="required">
                <option value="">YYYY</option>
                	<?php 
						for($i=(date("Y")-18);$i>1900;$i--){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>
            </div>
           <div id="ssn">
                <label><?php echo SSN?><span id="require">*</span></label> <br />

                <input type="text" size="3" required="required" class="required" maxlength="3" name="ssn1" style="width:85px" id="ssn1">
				<input type="text" size="2" required="required" class="required" maxlength="2" name="ssn2" style="width:85px" id="ssn2">
				<input type="text" size="4" required="required" class="required" maxlength="4" name="ssn3" style="width:105px" id="ssn3">
            </div>
            <div>
                <label><?php echo IDN?><span id="require">*</span></label><br />

                <input type="text" name="id_number" required="required" class="required" id="idNum"  />
            </div>
            <div>
                <label><?php echo IDT?><span id="require">*</span></label><br />
					<select onchange="checkIDtype(this);" class="validate-select" id="id_type" name="id_type" required="required" class="required" name="id_type">
                        <option value="">Choose ID Type</option>
                        <option value="AU1">California ID/Licence</option>
                        <option value="AU2">Working Permit/Green Card</option>
                        <option value="AU4">Others States ID/Driver License</option>
				  </select>
            </div>
            
            <div>
                <label><?php echo IDE?><span id="require">*</span></label><br />

                <select name="idexpM" required="required" class="required">
                <option value="">MM</option>
                	<?php 
						for($i=1;$i<13;$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>
                
                <input type="hidden" name="idexpD" value="10" />
                
                <!--<select name="idexpD" required="required" class="required">
                <option value="">DD</option>
                	<?php 
						for($i=1;$i<32;$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>-->

                <select name="idexpY" required="required" class="required">
                <option value="">YYYY</option>
                	<?php 
					
				
						for($i=date("Y");$i<((int)date("Y")+10);$i++){
							?>
                            <option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
						}
					?>
                
                </select>
            </div>
            
            <div>
                <label><?php echo IDS?><span id="require">*</span></label><br />
	              <select disabled="" id="id_state" name="id_state">
								<option value="none">Choose State</option>
                                <option value="AL">AL-Alabama</option>
                                <option value="AK">AK-Alaska</option>
                                <option value="AS">AS-American Samoa</option>
                                <option value="AZ">AZ-Arizona</option>
                                <option value="AR">AR-Arkansas</option>
                                <option value="AF">AF-Armed Forces Africa</option>
                                <option value="AA">AA-Armed Forces Americas</option>
                                <option value="AC">AC-Armed Forces Canada</option>
                                <option value="AE">AE-Armed Forces Europe</option>
                                <option value="AM">AM-Armed Forces Middle East</option>
                                <option value="AP">AP-Armed Forces Pacific</option>
                                <option value="CO">CO-Colorado</option>
                                <option value="CT">CT-Connecticut</option>
                                <option value="DE">DE-Delaware</option>
                                <option value="DC">DC-District of Columbia</option>
                                <option value="FM">FM-Federated States Of Micronesia</option>
                                <option value="FL">FL-Florida</option>
                                <option value="GA">GA-Georgia</option>
                                <option value="GU">GU-Guam</option>
                                <option value="HI">HI-Hawaii</option>
                                <option value="ID">ID-Idaho</option>
                                <option value="IL">IL-Illinois</option>
                                <option value="IN">IN-Indiana</option>
                                <option value="IA">IA-Iowa</option>
                                <option value="KS">KS-Kansas</option>
                                <option value="KY">KY-Kentucky</option>
                                <option value="LA">LA-Louisiana</option>
                                <option value="ME">ME-Maine</option>
                                <option value="MH">MH-Marshall Islands</option>
                                <option value="MD">MD-Maryland</option>
                                <option value="MA">MA-Massachusetts</option>
                                <option value="MI">MI-Michigan</option>
                                <option value="MN">MN-Minnesota</option>
                                <option value="MS">MS-Mississippi</option>
                                <option value="MO">MO-Missouri</option>
                                <option value="MT">MT-Montana</option>
                                <option value="NE">NE-Nebraska</option>
                                <option value="NV">NV-Nevada</option>
                                <option value="NH">NH-New Hampshire</option>
                                <option value="NJ">NJ-New Jersey</option>
                                <option value="NM">NM-New Mexico</option>
                                <option value="NY">NY-New York</option>
                                <option value="NC">NC-North Carolina</option>
                                <option value="ND">ND-North Dakota</option>
                                <option value="MP">MP-Northern Mariana Islands</option>
                                <option value="OH">OH-Ohio</option>
                                <option value="OK">OK-Oklahoma</option>
                                <option value="OR">OR-Oregon</option>
                                <option value="PW">PW-Palau</option>
                                <option value="PA">PA-Pennsylvania</option>
                                <option value="PR">PR-Puerto Rico</option>
                                <option value="RI">RI-Rhode Island</option>
                                <option value="SC">SC-South Carolina</option>
                                <option value="SD">SD-South Dakota</option>
                                <option value="TN">TN-Tennessee</option>
                                <option value="TX">TX-Texas</option>
                                <option value="UT">UT-Utah</option>
                                <option value="VT">VT-Vermont</option>
                                <option value="VI">VI-Virgin Islands</option>
                                <option value="VA">VA-Virginia</option>
                                <option value="WA">WA-Washington</option>
                                <option value="WV">WV-West Virginia</option>
                                <option value="WI">WI-Wisconsin</option>
                                <option value="WY">WY-Wyoming</option>
                            </select>
            </div>
       </div>	
    </div>
    <div style="clear:both"></div>
    <br />
<br />

    <div align="center">
    	<input type="image" src="next.jpg" style="width:58px;" onclick="return formvalidation()" />
    </div>
    </form>
    </fieldset>
</div>
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript">
	  $(document).ready(function() {
		  $(".required").change(function(){
		   		/*if($("#formsubmit").val()=='1'){
					alert("hello");
					return false()
					formvalidate();
				}*/
				
				//alert($(this).val());
				
				createcredit()
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
	  
	  function setupday(){
			 var dtMonth = $("#dobM").val();
			 var lim;
			 var days;
			 if((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11)){
				lim = 30;
			} else if (dtMonth == 2){
				lim = 29;
			}else{
				lim = 31;
			}
			days += '<option value="">DD</option>';
			for(var i=1;i<=lim;i++){
				days += '<option value="'+i+'">'+i+'</option>';
			}
			  
			 $("#dobD").html(days); 
	 }
	  
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
		  var err = 'Please verify all the required fields (indicated by *)\n\r';
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
		
			
			if(isFormValid){
				if($('#years').val()=='0' && $("#months").val()=='0'){
					isFormValid = false;
					$('#years').addClass('highlight')
					$('#months').addClass('highlight')	
					err += 'Year and Month should not be blank \n\r'
				}
			
			}
			var dtYear = $("#dobY").val();
			 var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			 var dtDay = $("#dobD").val();
			var dtMo = $("#dobM").val();
			if(dtMo==2){
				//alert("hello");
				
				if (dtDay> 29 || (dtDay ==29 && !isleap)){
						 isFormValid = false;
						$('#dobD').addClass('highlight')
						$('#dobY').addClass('highlight')
						
						err += 'Please validate your DOB \n\r'
					 }
			}
			
			
			
			var id_num = $("#idNum").val();
			
			if(id_num.length==8){
				var first_id_num = id_num.substr(0,1);
				if($("#id_type").val()=='AU1'){
					if(first_id_num.match('^([A-Za-z])$'))
					{
						
					}else{
						$("#idNum").addClass('highlight')
						isFormValid = false;
						
						err += 'Please input currect ID \n\r'	
					}
				}
			}else{
				$("#idNum").addClass('highlight')
				isFormValid = false;
				
				err += 'Please input currect ID \n\r'	
			}
			if(isFormValid){
				if($('#ssn1').val()=='000' && $('#ssn2').val()=='000' && $('#ssn3').val()=='000'){
					isFormValid = false;
					$('#ssn1').addClass('highlight')
					$('#ssn2').addClass('highlight')	
					$('#ssn3').addClass('highlight')	
					err += 'Please input your currect SSN \n\r'
				}
			}
			if( !isValidEmailAddress($("#emailid").val()) ) {
				isFormValid = false;
				$('#emailid').addClass('highlight')
				
				err += 'Please input valid Email \n\r'
			}
			
			
			/*if($("#valiadd").val()=='0'){
				isFormValid = false;
			}
			*/
			
			
				createcredit()
			//alert($('form').serializeArray());
			
			if (!isFormValid) alert(err);
			
			if(isFormValid){
				if($("#valiadd").val() == 1){
					isFormValid = true
				}else{
					lastvalidateaddress()
					return false;
				}
				
			}
			
			
			return isFormValid;
			
			
	 }
	 function validateaddress(){
	   $("#addrvalidate").html("<img src='loading.gif'>")
		var st = $("#add").val();
		var z = $("#zip").val();
		var aid = $("#appId").val();
		$.ajax({
			  type: "POST",
			  url: "addressvalidation.php",
			  data: { street : st, zip: z, appid : aid},
			   success: function(data) {
					if(data=='1'){
						$('#addrvalidate').html("Valid Address");
						$("#addrvalidate").removeClass("notvalidAdd")
						$("#addrvalidate").addClass('validAdd')
						$("#valiadd").val('1');
						//$("#appform").submit();
						return true;
					}else{
						$('#addrvalidate').html(" Not Valid Address");
						$("#addrvalidate").removeClass("validAdd")
						$("#addrvalidate").addClass('notvalidAdd')
						$("#add").addClass("highlight");
						$("#zip").addClass("highlight");
						$("#valiadd").val('0')
						$("#formsubmit").val('1');
						return false;
					}
				
			  }
			})
	} 
	
	
	function lastvalidateaddress(){
	   $("#addrvalidate").html("<img src='loading.gif'>")
		var st = $("#add").val();
		var z = $("#zip").val();
		var aid = $("#appId").val();
		$.ajax({
			  type: "POST",
			  url: "addressvalidation.php",
			  data: { street : st, zip: z, appid : aid},
			   success: function(data) {
					if(data=='1'){
						$('#addrvalidate').html("Valid Address");
						$("#addrvalidate").removeClass("notvalidAdd")
						$("#addrvalidate").addClass('validAdd')
						$("#valiadd").val('1');
						$("#appform").submit();
						return true;
					}else{
						$('#addrvalidate').html(" Not Valid Address");
						$("#addrvalidate").removeClass("validAdd")
						$("#addrvalidate").addClass('notvalidAdd')
						$("#add").addClass("highlight");
						$("#zip").addClass("highlight");
						$("#valiadd").val('0')
						$("#formsubmit").val('1');
						return false;
					}
				
			  }
			})
	} 
	
	
	function checknumeric(){
		val=document.getElementById("zip");
		val.value=val.value.replace(/[^0-9]/g, "");
		
	}
	  
	function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}  
	  
</script>

<script type="text/javascript">
		var p;
		var p1;
		var p2;
		
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

		function next2(i) {
				return function() {
					//strip non-digits
					p2[i].value=p2[i].value.replace(/[^0-9]/g, "");
					
					//go forward one box when full, except when on the end box
					if(p2[i].value.length==p2[i].size && i<p2.length) p2[i+1].focus();
				}
			}
	
			function back2(i) {
				return function(e) {
					//go backward one when empty, except when on the first box
					if(e.keyCode==8 && p2[i].value.length==0 && i>0) p2[i-1].focus();
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
			
			p2=document.getElementById("cphones").getElementsByTagName("input");

			for(var i=0; i<p2.length; i++) {
				p2[i].onkeyup=next2(i);
				p2[i].onkeydown=back2(i);
			}
			
			
		}
		</script>
<script language="javascript">
<!--
	var idstate = document.getElementById("id_state");
	var idtype =  document.getElementById("id_type");
	if(idtype.options[idtype.selectedIndex].value=="AU4") idstate.disabled = false;
	
	function checkIDtype(obj) {
		if(obj.value =="AU4")
		{
		    idstate.disabled = false;
		    idstate.className = "validate-select";
		}
		else
		{
		    idstate.disabled = true;
		    idstate.className = "";
		}
	}
//-->
</script>
