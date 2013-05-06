<?php include_once('_includes/dictionary_eng.php');?>
<?php include_once('_includes/mage_inc.php'); ?>
<?php

	$_error = '';

	Mage::getSingleton('core/session', array('name' => 'frontend'));
	$customer = Mage::getModel('customer/customer');
	$customer->setWebsiteId(Mage::app()->getWebsite()->getId());


	$customer_session = Mage::getSingleton('customer/session');

	$_fname = '';
	$_lname = '';
	$_email = '';
	
	if(  Mage::getSingleton('customer/session')->isLoggedIn() ){

		$c_id = Mage::getSingleton('customer/session')->getId();
		
		$customer = Mage::getModel('customer/customer')->load($c_id);		

		$_fname = $customer->getFirstname();
		$_lname = $customer->getLastname();
		$_email = $customer->getEmail();
		
		$customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
		if ($customerAddressId){
		   $address = Mage::getModel('customer/address')->load($customerAddressId);
		} 
	} else {
		$_email = $_GET['email'];
	}
?>
<html>
	<head>
		<title>Credit Application</title>		
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/skeleton.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/styles.css" media="all" />
        <link href="css/overridecredit.css" rel="stylesheet">
	</head>
	<body>
		<div class="container small">

			<input type="hidden" id="formsubmit" value="0" />
			<input type="hidden" id="valiadd" value="0" />
			
			<h2><?php echo TITLE?></h2>
			
		    <fieldset>
				<div class="messages"><p><?php echo SUBTITLE?></p></div>
				<form action="creditapp_step2.php" method="post" id="appform">
					<input type="hidden" id="appId" value="0" name="appid" />
					
				    <div class="col2-set">
				    	<div class="col ten columns alpha">
				       		<div class="field">
				                <label for="fname"><?php echo FIRSTNAME;?><span id="require">*</span></label>
				                <input type="text" name="fname" required class="required" value="<?php echo($_fname)?>" />
				            </div>
				            <div class="field">
				                <label for="lname"><?php echo LASTNAME;?><span id="require">*</span></label>
				                <input type="text" name="lname" required class="required" value="<?php echo($_lname)?>"/>
				            </div>
				            
				             <div class="field">
				                <label for="emailid"><?php echo EMAIL;?><span id="require">*</span></label>
				                <input type="text" name="emailid" required class="required" id="emailid" value="<?php echo($_email)?>"/>
				            </div>

<?php
	if( ! Mage::getSingleton('customer/session')->isLoggedIn() ){
?>
				             <div class="field">
				                <label for="psswd"><?php echo $PASSWORD_LABEL;?><span id="require">*</span></label>
				                <input type="password" name="psswd" required class="required" id="psswd" />
				            </div>
				             <div class="field">
				                <label for="psswd"><?php echo $CONFIRM_PSSWD_LABEL;?><span id="require">*</span></label>
				                <input type="password" name="psswd2" required class="required" id="psswd2"/>
				            </div>
<?php
	}
?>

				            <div class="field">
				                <label for="add1"><?php echo HOMEADDRESS1;?><span id="require">*</span></label>
				                <input type="text" name="add1" required id="add" class="required" value="<?php if($customer_session->isLoggedIn()) echo($address->getData('street'))?>" />
				            </div>
				            <div class="fieldset">
					            <div class="field">
					                <label for="add2"><?php echo HOMEADDRESS2;?></label>
					                <input type="text" name="add2" />
					            </div>
							</div>
							<div class="clearfix"></div>
				            <div class="field">
				                <label for="city"><?php echo CITY;?><span id="require">*</span></label>
				                <input type="text" name="city" required class="required" value="<?php if($customer_session->isLoggedIn()) echo($address->getCity())?>"/>
				            </div>
				            <div class="field">
				                <label for="state"><?php echo State;?><span id="require">*</span></label>
								<select name="state"  required="required" class="required" id="state">
									<option value=""><?php echo CHOOSE?></option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
								</select>
				            </div>
				            <div class="field">
				                <label for="zip"><?php echo Zip?><span id="require">*</span></label>
				                <input type="text" name="zip" required class="required" id="zip" style="width:105px" onChange="return validateaddress()" onKeyUp="return checknumeric()" value="<?php if($customer_session->isLoggedIn()) echo($address->getPostcode())?>"/>		                
				                <span id="addrvalidate"></span>
				            </div>
				    	</div>
				    	<div class="col ten columns omega">

							<div class="field">
				                <label for="years"><?php echo TIME_LIVING?><span id="require">*</span></label>
				                <select name="years" required="required" id="years" class="required split">
				                	<option value="">Years</option>
									<?php for($i=0;$i<100;$i++){ ?>
				                            <option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				                <select name="months" required="required" class="required split" id="months">
					                <option value="">Months</option>
					                <?php for($i=0;$i<12;$i++){ ?>
									<option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				            </div>
							<div class="clearfix"></div>
				            <div id="phones" class="phones">
				                <label for="phones"><?php echo HomePhone?><span id="require">*</span></label>
				                <input type="text" size="3" maxlength="3" required class="required three" name="area"/>
								<input type="text" size="3" maxlength="3" required class="required three" name="local1"/>
								<input type="text" size="4" maxlength="4" required class="required four"  name="local2"/>
				            </div>
				           <div class="clearfix"></div>
				            <div id="cphones">
				                <label for="cphone1"><?php echo CellPhone?></label>
				                <input type="text" size="3" maxlength="3" name="cphone1" class="three" />
				                <input type="text" size="3" maxlength="3" name="cphone2" class="three" />
				                <input type="text" size="4" maxlength="4" name="cphone3" class="four" />
				            </div>
				           <div class="clearfix"></div>
				            
				            <div class="field">
				                <label for="dobM"><?php  echo DOB?><span id="require">*</span></label>
				                <select name="dobM" required="required" class="required" onChange="return setupday()" id="dobM">
				                	<option value="">MM</option>
				                	<?php  for($i=1;$i<13;$i++){ ?>
		                            <option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				                
				                <select name="dobD" required="required" class="required" id="dobD">
					                <option value="">DD</option>
				                	<?php for($i=1;$i<32;$i++){ ?>
		                            <option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				
				                <select name="dobY" id="dobY" required="required" class="required">
					                <option value="">YYYY</option>
				                	<?php for($i=(date("Y")-18);$i>1900;$i--){ ?>
									<option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				            </div>
				           <div class="clearfix"></div>
				           <div id="ssn" class="ssn">
				                <label for="ssn"><?php echo SSN?><span id="require">*</span></label>
				                <input type="text" size="3" required class="required three" 	maxlength="3" name="ssn1" id="ssn1">
								<input type="text" size="2" required class="required two" 	maxlength="2" name="ssn2" id="ssn2">
								<input type="text" size="4" required class="required four" 	maxlength="4" name="ssn3" id="ssn3">
				            </div>
				           <div class="clearfix"></div>
				            <div class="field">
				                <label for="id_number"><?php echo IDN?><span id="require">*</span></label>
				                <input type="text" name="id_number" required class="required" id="idNum"  />
				            </div>
                            <div class="clearfix"></div>
				            <div class="field">
				                <label for="id_type"><?php echo IDT?><span id="require">*</span></label>
								<select onchange="checkIDtype(this);" class="validate-select" id="id_type" name="id_type" required="required" class="required" name="id_type">
									<option value=""><?php echo CHOOSEIDTYPE?></option>
									<option value="AU1"><?php echo CALIID?></option>
									<option value="AU2"><?php echo GREENCARD?></option>
									<option value="AU4"><?php echo OTHER?></option>
								</select>
				            </div>
				            <div class="clearfix"></div>
				            <div class="field">
				                <label for="idexpM"><?php echo IDE?><span id="require">*</span></label>		
				                <select name="idexpM" required="required" class="required split">
					                <option value="">MM</option>
				                	<?php for($i=1;$i<13;$i++){	?>
									<option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				                
				                <input type="hidden" name="idexpD" value="10" />
				                <select name="idexpY" required="required" class="required split">
				                	<option value="">YYYY</option>
				                	<?php for($i=date("Y");$i<((int)date("Y")+10);$i++){ ?>
									<option value="<?php echo $i?>"><?php echo $i?></option>
									<?php } ?>
				                </select>
				            </div>
				            <div class="clearfix"></div>

				            <div class="field">
								<label for="id_state"><?php echo IDS?><span id="require">*</span></label>
					            <select disabled="" id="id_state" name="id_state">
									<option value="none"><?php echo CHOOSE?></option><option value="AL">AL-Alabama</option><option value="AK">AK-Alaska</option><option value="AS">AS-American Samoa</option><option value="AZ">AZ-Arizona</option><option value="AR">AR-Arkansas</option><option value="AF">AF-Armed Forces Africa</option><option value="AA">AA-Armed Forces Americas</option><option value="AC">AC-Armed Forces Canada</option><option value="AE">AE-Armed Forces Europe</option><option value="AM">AM-Armed Forces Middle East</option><option value="AP">AP-Armed Forces Pacific</option><option value="CO">CO-Colorado</option><option value="CT">CT-Connecticut</option><option value="DE">DE-Delaware</option><option value="DC">DC-District of Columbia</option><option value="FM">FM-Federated States Of Micronesia</option><option value="FL">FL-Florida</option><option value="GA">GA-Georgia</option><option value="GU">GU-Guam</option><option value="HI">HI-Hawaii</option><option value="ID">ID-Idaho</option><option value="IL">IL-Illinois</option><option value="IN">IN-Indiana</option><option value="IA">IA-Iowa</option><option value="KS">KS-Kansas</option><option value="KY">KY-Kentucky</option><option value="LA">LA-Louisiana</option><option value="ME">ME-Maine</option><option value="MH">MH-Marshall Islands</option><option value="MD">MD-Maryland</option><option value="MA">MA-Massachusetts</option><option value="MI">MI-Michigan</option><option value="MN">MN-Minnesota</option><option value="MS">MS-Mississippi</option><option value="MO">MO-Missouri</option><option value="MT">MT-Montana</option><option value="NE">NE-Nebraska</option><option value="NV">NV-Nevada</option><option value="NH">NH-New Hampshire</option><option value="NJ">NJ-New Jersey</option><option value="NM">NM-New Mexico</option><option value="NY">NY-New York</option><option value="NC">NC-North Carolina</option><option value="ND">ND-North Dakota</option><option value="MP">MP-Northern Mariana Islands</option><option value="OH">OH-Ohio</option><option value="OK">OK-Oklahoma</option><option value="OR">OR-Oregon</option><option value="PW">PW-Palau</option><option value="PA">PA-Pennsylvania</option><option value="PR">PR-Puerto Rico</option><option value="RI">RI-Rhode Island</option><option value="SC">SC-South Carolina</option><option value="SD">SD-South Dakota</option><option value="TN">TN-Tennessee</option><option value="TX">TX-Texas</option><option value="UT">UT-Utah</option><option value="VT">VT-Vermont</option><option value="VI">VI-Virgin Islands</option><option value="VA">VA-Virginia</option><option value="WA">WA-Washington</option><option value="WV">WV-West Virginia</option><option value="WI">WI-Wisconsin</option><option value="WY">WY-Wyoming</option>
								</select>
							</div>
				    	</div>
				    </div>
		           <div class="clearfix"></div>
				    <div class="nineteen columns" align="right" style="">
				    	<br/>
				    	<button class="button" onClick="return formvalidation()">Next &gt;</button>
                        <span style="display:none" class="please-wait" id="please-wait">
				        <img class="v-middle" alt="" src="http://data.icuracao.com/skin/frontend/enterprise/curacao-responsive/images/opc-ajax-loader.gif"> Loading next step...    </span>
                        
				    </div>
			    </form>
		    </fieldset>
		
		</div>
		<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script type="text/javascript">

			jQuery(document).ready(function () {
				jQuery('#framewrapper', window.parent.document).attr({'height':700});
				var region = "<?php if($customer_session->isLoggedIn()) echo($address->getRegion())?>";
				jQuery("#state option:contains("+region+")").prop('selected', true);
			
			    frameResize();
			        
			    jQuery(".required").change(function () {
			        /*if(jQuery("#formsubmit").val()=='1'){
							alert("hello");
							return false()
							formvalidate();
						}*/
			        //alert(jQuery(this).val());
			        createcredit();
			    });
			
			    jQuery("#appform").submit(function () {
			        var isFormValid = true;
			
			        jQuery(".required input").each(function () {
			            if (jQuery.trim(jQuery(this).val()).length == 0) {
			                jQuery(this).addClass("validation-failed");
			                isFormValid = false;
			            } else {
			                jQuery(this).removeClass("validation-failed");
			            }
			        });
			
			        if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			
			        return isFormValid;
			    });
			});
			
			
			function frameResize(){
				
			}
			
			
			function setupday() {
			    var dtMonth = jQuery("#dobM").val();
			    var lim;
			    var days;
			    if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11)) {
			        lim = 30;
			    } else if (dtMonth == 2) {
			        lim = 29;
			    } else {
			        lim = 31;
			    }
			    days += '<option value="">DD</option>';
			    for (var i = 1; i <= lim; i++) {
			        days += '<option value="' + i + '">' + i + '</option>';
			    }
			
			    jQuery("#dobD").html(days);
			}
			
			function createcredit() {
			    var formdata = jQuery('form').serializeArray()
			    jQuery.ajax({
			        type: "POST",
			        url: "creditapplication.php",
			        data: formdata,
			        success: function (data) {
			            //alert(data)
			
			            jQuery("#appId").val(data);
			        }
			    })
			}
			
			function formvalidate() {
			
			    var isFormValid = true;
			    jQuery(".required").each(function () {
			        if (jQuery.trim(jQuery(this).val()).length == 0) {
			            jQuery(this).addClass("validation-failed");
			            isFormValid = false;
			            jQuery("#formsubmit").val('1');
			        } else {
			            jQuery(this).removeClass("validation-failed");
			        }
			    });
			
			    //if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
			
			    return isFormValid;
			
			    return false;
			}
			
			function formvalidation() {
			
			    var isFormValid = true;
			    var err = 'Please verify all the required fields (indicated by *)\n\r';
			    jQuery(".required").each(function () {
			        if (jQuery.trim(jQuery(this).val()).length == 0) {
			            jQuery(this).addClass("validation-failed");
			            isFormValid = false;
			            jQuery("#formsubmit").val('1');
			        } else {
			            jQuery(this).removeClass("validation-failed");
			        }
			    });
			
			
			    if (isFormValid) {
			        if (jQuery('#years').val() == '0' && jQuery("#months").val() == '0') {
			            isFormValid = false;
			            jQuery('#years').addClass('validation-failed')
			            jQuery('#months').addClass('validation-failed')
			            err += 'Year and Month should not be blank \n\r'
			        }
			
			    }
			    var dtYear = jQuery("#dobY").val();
			    var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			    var dtDay = jQuery("#dobD").val();
			    var dtMo = jQuery("#dobM").val();
			    if (dtMo == 2) {
			        //alert("hello");
			
			        if (dtDay > 29 || (dtDay == 29 && !isleap)) {
			            isFormValid = false;
			            jQuery('#dobD').addClass('validation-failed')
			            jQuery('#dobY').addClass('validation-failed')
			
			            err += 'Please validate your DOB \n\r'
			        }
			    }
			
			
			
			    var id_num = jQuery("#idNum").val();
			
			    if (id_num.length == 8) {
			        var first_id_num = id_num.substr(0, 1);
			        if (jQuery("#id_type").val() == 'AU1') {
			            if (first_id_num.match('^([A-Za-z])$')) {
			
			            } else {
			                jQuery("#idNum").addClass('validation-failed')
			                isFormValid = false;
			
			                err += 'Please input currect ID \n\r'
			            }
			        }
			    } else {
			        jQuery("#idNum").addClass('validation-failed')
			        isFormValid = false;
			
			        err += 'Please input currect ID \n\r'
			    }
			    if (isFormValid) {
			        if (jQuery('#ssn1').val() == '000' && jQuery('#ssn2').val() == '000' && jQuery('#ssn3').val() == '000') {
			            isFormValid = false;
			            jQuery('#ssn1').addClass('validation-failed')
			            jQuery('#ssn2').addClass('validation-failed')
			            jQuery('#ssn3').addClass('validation-failed')
			            err += 'Please input your currect SSN \n\r'
			        }
			    }
			    if (!isValidEmailAddress(jQuery("#emailid").val())) {
			        isFormValid = false;
			        jQuery('#emailid').addClass('validation-failed')
			
			        err += 'Please input valid Email \n\r'
			    }
			
			
			    /*if(jQuery("#valiadd").val()=='0'){
							isFormValid = false;
						}
						*/
			
			
			    createcredit()
			    //alert(jQuery('form').serializeArray());
			
			    if (!isFormValid) alert(err);
			
			    if (isFormValid) {
			        if (jQuery("#valiadd").val() == 1) {
			            isFormValid = true
			        } else {
			            lastvalidateaddress()
			            return false;
			        }
			
			    }
				
				if(isFormValid){
					jQuery("#please-wait").show()
				}
			
			    return isFormValid;
			
			
			}
			
			function validateaddress() {
			    jQuery("#addrvalidate").html("<img src='../skin/frontend/enterprise/curacao-responsive/images/opc-ajax-loader.gif'>")
			    var st = jQuery("#add").val();
			    var z = jQuery("#zip").val();
			    var aid = jQuery("#appId").val();
			    jQuery.ajax({
			        type: "POST",
			        url: "addressvalidation.php",
			        data: {
			            street: st,
			            zip: z,
			            appid: aid
			        },
			        success: function (data) {
			            if (data == '1') {
			                jQuery('#addrvalidate').html("Valid Address");
			                jQuery("#addrvalidate").removeClass("notvalidAdd")
			                jQuery("#addrvalidate").addClass('validAdd')
			                jQuery("#valiadd").val('1');
			                //jQuery("#appform").submit();
			                return true;
			            } else {
			                jQuery('#addrvalidate').html(" Not Valid Address");
			                jQuery("#addrvalidate").removeClass("validAdd")
			                jQuery("#addrvalidate").addClass('notvalidAdd')
			                jQuery("#add").addClass("validation-failed");
			                jQuery("#zip").addClass("validation-failed");
			                jQuery("#valiadd").val('0')
			                jQuery("#formsubmit").val('1');
			                return false;
			            }
			
			        }
			    })
			}
			
			
			function lastvalidateaddress() {
			    jQuery("#addrvalidate").html("<img src='../skin/frontend/enterprise/curacao-responsive/images/opc-ajax-loader.gif'>")
			    var st = jQuery("#add").val();
			    var z = jQuery("#zip").val();
			    var aid = jQuery("#appId").val();
			    jQuery.ajax({
			        type: "POST",
			        url: "addressvalidation.php",
			        data: {
			            street: st,
			            zip: z,
			            appid: aid
			        },
			        success: function (data) {
			            if (data == '1') {
			                jQuery('#addrvalidate').html("Valid Address");
			                jQuery("#addrvalidate").removeClass("notvalidAdd")
			                jQuery("#addrvalidate").addClass('validAdd')
			                jQuery("#valiadd").val('1');
			                jQuery("#appform").submit();
			                return true;
			            } else {
			                jQuery('#addrvalidate').html(" Not Valid Address");
			                jQuery("#addrvalidate").removeClass("validAdd")
			                jQuery("#addrvalidate").addClass('notvalidAdd')
			                jQuery("#add").addClass("validation-failed");
			                jQuery("#zip").addClass("validation-failed");
			                jQuery("#valiadd").val('0')
			                jQuery("#formsubmit").val('1');
			                return false;
			            }
			
			        }
			    })
			}
			
			
			function checknumeric() {
			    val = document.getElementById("zip");
			    val.value = val.value.replace(/[^0-9]/g, "");
			
			}
			
			function isValidEmailAddress(emailAddress) {
			    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
			    return pattern.test(emailAddress);
			}
			
			
			var p;
			var p1;
			var p2;
			
			// ERROR BELOW -- should tab to next index - throws error / 
			function next(i) {
			    return function () {
			        //strip non-digits
			        p[i].value = p[i].value.replace(/[^0-9]/g, "");
			
			        //go forward one box when full, except when on the end box
			        if (p[i].value.length == p[i].size && i < p.length) p[i + 1].focus();
			    }
			}
			
			function back(i) {
			    return function (e) {
			        //go backward one when empty, except when on the first box
			        if (e.keyCode == 8 && p[i].value.length == 0 && i > 0) p[i - 1].focus();
			    }
			}
			
			function next2(i) {
			    return function () {
			        //strip non-digits
			        p2[i].value = p2[i].value.replace(/[^0-9]/g, "");
			
			        //go forward one box when full, except when on the end box
			        if (p2[i].value.length == p2[i].size && i < p2.length) p2[i + 1].focus();
			    }
			}
			
			function back2(i) {
			    return function (e) {
			        //go backward one when empty, except when on the first box
			        if (e.keyCode == 8 && p2[i].value.length == 0 && i > 0) p2[i - 1].focus();
			    }
			}
			
			
			function next1(i) {
			    return function () {
			        //strip non-digits
			        p1[i].value = p1[i].value.replace(/[^0-9]/g, "");
			
			        //go forward one box when full, except when on the end box
			        if (p1[i].value.length == p1[i].size && i < p1.length) p1[i + 1].focus();
			    }
			}
			
			function back1(i) {
			    return function (e) {
			        //go backward one when empty, except when on the first box
			        if (e.keyCode == 8 && p1[i].value.length == 0 && i > 0) p1[i - 1].focus();
			    }
			}
			
			window.onload = function () {
			    p = document.getElementById("phones").getElementsByTagName("input");
			
			    for (var i = 0; i < p.length; i++) {
			        p[i].onkeyup = next(i);
			        p[i].onkeydown = back(i);
			    }
			
			    p1 = document.getElementById("ssn").getElementsByTagName("input");
			
			    for (var i = 0; i < p1.length; i++) {
			        p1[i].onkeyup = next1(i);
			        p1[i].onkeydown = back1(i);
			    }
			
			    p2 = document.getElementById("cphones").getElementsByTagName("input");
			
			    for (var i = 0; i < p2.length; i++) {
			        p2[i].onkeyup = next2(i);
			        p2[i].onkeydown = back2(i);
			    }
			
			}
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
		</script>
	</body>
</html>



