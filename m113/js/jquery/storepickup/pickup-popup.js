jQuery(document).ready(function() {
	
	jQuery('.mButton').qtip({
		id: jQuery(this).attr('id'),
		content: {
			button: 'Close',
			title: 'Shipping and Availability',
			text: function(api) {
	            // Retrieve content from custom attribute of the $('.selector') elements.
				return jQuery('#ship-text-'+jQuery(this).attr('id')).html();
			}
		},
		position: {
			my: 'bottom left',
			at: 'right top',
			target: 'mouse',
			adjust: { mouse: false },
			viewport: jQuery(window)
		 },
		 hide: {
			event: false,
			inactive: 9990
         },
		 show: {
			 solo: true
         }
	});
	jQuery('.checkstore').click(function() {


	});
});
//---------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------
function showStoreFinderForm(id){
	
	var sku = jQuery("a[data-hasqtip='"+id+"']").attr("title");
	var form_str =	'<p>Enter your ZIP code or city and state to see where you can pick up this item.</p>' +
						'<form class="qtipform" name="qtip_form" id="qtip_form_'+id+'" action="">' +
							'<input type="hidden" name="clicked_ele_sku" id="clicked_ele_sku" value="'+ sku +'" />' +
							'<label>ZIP Code</label>' +
							'<input type="text" name="zip_code" id="zip_code" class="required" value="" />' +
							'<div class="center" style="display:none;">or </div>' +
							'<label style="display:none;">City</label>' +
							'<input type="text" name="City" id="City" class="required" value="" style="display:none;" />' +
							'<label style="display:none;">State</label>' +
							'<select style="display:none;" name="state_list" id="state_list" class="required" ><option type="text" value="">--Select State--</option><option value="1" type="text">Alabama</option><option value="2" type="text">Alaska</option><option value="3" type="text">American Samoa</option><option value="4" type="text">Arizona</option><option value="5" type="text">Arkansas</option><option value="6" type="text">Armed Forces Africa</option><option value="7" type="text">Armed Forces Americas</option><option value="8" type="text">Armed Forces Canada</option><option value="9" type="text">Armed Forces Europe</option><option value="10" type="text">Armed Forces Middle East</option><option value="11" type="text">Armed Forces Pacific</option><option value="12" type="text">California</option><option value="13" type="text">Colorado</option><option value="14" type="text">Connecticut</option><option value="15" type="text">Delaware</option><option value="16" type="text">District of Columbia</option><option value="17" type="text">Federated States Of Micronesia</option><option value="18" type="text">Florida</option><option value="19" type="text">Georgia</option><option value="20" type="text">Guam</option><option value="21" type="text">Hawaii</option><option value="22" type="text">Idaho</option><option value="23" type="text">Illinois</option><option value="24" type="text">Indiana</option><option value="25" type="text">Iowa</option><option value="26" type="text">Kansas</option><option value="27" type="text">Kentucky</option><option value="28" type="text">Louisiana</option><option value="29" type="text">Maine</option><option value="30" type="text">Marshall Islands</option><option value="31" type="text">Maryland</option><option value="32" type="text">Massachusetts</option><option value="33" type="text">Michigan</option><option value="34" type="text">Minnesota</option><option value="35" type="text">Mississippi</option><option value="36" type="text">Missouri</option><option value="37" type="text">Montana</option><option value="38" type="text">Nebraska</option><option value="39" type="text">Nevada</option><option value="40" type="text">New Hampshire</option><option value="41" type="text">New Jersey</option><option value="42" type="text">New Mexico</option><option value="43" type="text">New York</option><option value="44" type="text">North Carolina</option><option value="45" type="text">North Dakota</option><option value="46" type="text">Northern Mariana Islands</option><option value="47" type="text">Ohio</option><option value="48" type="text">Oklahoma</option><option value="49" type="text">Oregon</option><option value="50" type="text">Palau</option><option value="51" type="text">Pennsylvania</option><option value="52" type="text">Puerto Rico</option><option value="53" type="text">Rhode Island</option><option value="54" type="text">South Carolina</option><option value="55" type="text">South Dakota</option><option value="56" type="text">Tennessee</option><option value="57" type="text">Texas</option><option value="58" type="text">Utah</option><option value="59" type="text">Vermont</option><option value="60" type="text">Virgin Islands</option><option value="61" type="text">Virginia</option><option value="62" type="text">Washington</option><option value="63" type="text">West Virginia</option><option value="64" type="text">Wisconsin</option><option value="65" type="text">Wyoming</option></select>' +
							'<label class="error" id="error" style="display:none;color:red;"></label>' +
							'<input type="button" name="qtip_submit" id="qtip_submit" value="search" onclick="qtip_submit_click('+id+');"/>' +
						'</form>';
						
	var api = jQuery('#qtip-'+id).qtip('api');
	api.set('content.text', form_str);
	return false;
}
//---------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------
function qtip_submit_click(id) {
	
	var sku = jQuery('#clicked_ele_value').val();
	var city = jQuery('#City').val();
	var state_list = jQuery('#state_list').val();
	var zip_code = jQuery('#zip_code').val();
	
	if(zip_code == '' && state_list == '' && city == ''){
		jQuery('#error').html('Please Provide Zip code or City and State');
		jQuery('#error').show();
		return false;
	}
	
	jQuery('#error').hide();
	
	var form_data = jQuery("#qtip_form_"+id).serializeArray();
	
	jQuery.ajax({
		type:"POST",
		dataType:"html",
		data:form_data,
		url:"/pickup/index/index",
		beforeSend:function(){
			jQuery("#loading-mask").show();
		},
		success:function(result_data){
			// SWITCH THIS TO AN OVERLAY via fancybox or other (as it is too big for a qtip)?
			var api = jQuery('#qtip-'+id).qtip('api');
			api.set('content.text', result_data);
		},
	});
	return false;
}
//---------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------
function setsessionvar(store_id,sku,type){
	
	var getelms = '/?store_id='+store_id+'&sku='+sku+'&type='+type;
	jQuery.ajax({
		type:"POST",
		dataType:"html",
		data:'',
		url:"/pickup/index/setsessionval"+getelms,
		beforeSend:function(){
			jQuery("#loading-mask").show();
		},
		success:function(result_data){
			//alert(result_data)
			jQuery('.qtip.qtip-default').qtip('hide');
			jQuery('.mButton').html("Change");
			jQuery('#StoreLocation').html(result_data);
			jQuery('#StoreLocation').show();
			location.reload();
			//jQuery("#loading-mask").hide();
			//jQuery("#store_"+sku).val(result_data);
			//jQuery( "#ajax_success_element" ).dialog('destroy');
			//jQuery( "#ajax_success_element" ).html('');
//			location.reload();
		},
	});
}

