document.observe('dom:loaded', changetoplink);

var base_url = '';

var G_AJAX_allow = 0;
var G_AJAX_alloweffect = 0;
var G_AJAX_speedeffect = 0;
var duration_sec = 5;

var defaultvalue = true;

var counterrun = false;

function stringsize(a)
{
	i = 0;
	while(a[i])
	{
		i++; 
		if(i > 100) return 0;
	}
	
	return i;
}

function counterajaxlogin()
{
	if(!counterrun)
		return;

    counter_ajaxlogin_div=document.getElementById('counter_ajaxlogin_div');
    sec=duration_sec;
    if(sec<=0)
    {
		showmessage = true;
		display_divmessage();
		counterrun = false;
    }
    else
    {
        counter_ajaxlogin_div.innerHTML="<span style='color:grey;'>"+sec+"sec</span>";
    }
    duration_sec=duration_sec-1;

    window.setTimeout("counterajaxlogin()",999);

}

function changetoplink()
{
	try{
		
		G_AJAX_allow = $('ajax_login_base_url').readAttribute('allow_ajaxlogin');
		G_AJAX_alloweffect = $('ajax_login_base_url').readAttribute('allow_effect');
		G_AJAX_speedeffect = $('ajax_login_base_url').readAttribute('vitesse_effect');
		
		if(G_AJAX_allow == 1)
		{
			base_url = $('ajax_login_base_url').readAttribute('base_url');
			a = $$("a[href='"+base_url+"customer/account/login/']")[0];
			a.href = '#5#';
			a.writeAttribute('onClick', 'display_divlogin(true);');
		}
		
		
		base_url = $('ajax_login_base_url').readAttribute('base_url');
    }catch(e){}
}

function testemail(a) 
{
	if((a.indexOf('@',0)==-1) || (a.indexOf('.',0)==-1)) 
		return true;
	else
		return false;
}

function display_divlogin(show, effect)
{
	if(!effect)
		effect = true;

	if(!show)
	{
		if(effect)
			ajaxloginfadeout("divajaxlogin");
		else
			$("divajaxlogin").hide();
	}
	else
	{
		$("divajaxlogin").show();
		if(effect)
			ajaxloginfadein("divajaxlogin");		
	}
}

function display_divcreate(show, effect)
{
	if(!effect)
		effect = true;

	if(!show)
	{
		if(effect)
			ajaxloginfadeout("divajaxcreate");
		else
			$("divajaxcreate").hide();
	}
	else
	{
		$("divajaxcreate").show();
		if(effect)
			ajaxloginfadein("divajaxcreate");
	}
}

function display_divforgot(show, effect)
{
	if(!effect)
		effect = true;

	if(!show)
	{
		if(effect)
			ajaxloginfadeout("divajaxforgot"); 
		else
			$("divajaxforgot").hide();
	}
	else
	{
		$("divajaxforgot").show();
		if(effect)
			ajaxloginfadein("divajaxforgot");
	}
}

function display_divload(show, effect)
{
	if(!effect)
		effect = true;

	if(!show)
	{
		if(effect)
			ajaxloginfadeout("divajaxload"); 
		else	
			$("divajaxload").hide();
	}
	else
	{
		$("divajaxload").show();
		if(effect)
			ajaxloginfadein("divajaxload");
	}
}

function display_divforgoterror(show)
{
	if(!show){$("divajaxforgoterror").hide();}
	else{$("divajaxforgoterror").show();}
}

function display_divloginerror(show)
{
	if(!show){$("divajaxloginerror").hide();}
	else{$("divajaxloginerror").show();}
}

function display_divcreateerror(show)
{
	if(!show){$("divajaxcreateerror").hide();}
	else{$("divajaxcreateerror").show();}
}



function display_divmessage(show)
{
	if(!show)
	{
		ajaxloginfadeout("divajaxmessage"); 
	}
	else
	{
		$("divajaxmessage").show();
		ajaxloginfadein("divajaxmessage");
	}
}


function ajaxloginfadeout(id)
{
	if(G_AJAX_alloweffect == 1)
	{
		new Effect.Fade(id, {afterFinish:function(){
		$(id).hide();
		},duration:G_AJAX_speedeffect, from:1.0, to:0.0});
	}
	else
	{
		$(id).hide();
	}
} 

function ajaxloginfadein(id)
{
	if(G_AJAX_alloweffect == 1)
	{
		new Effect.Fade(id, {duration:G_AJAX_speedeffect, from:0.0, to:1.0});
	}
} 

function ajaxlogin()
{
	pass__ = $("ajaxpass").value;
	email__ = $("ajaxemail").value;

	if(testemail(email__))
	{
		message_error = 'Invalid email address.<br>'; test1 = true;

		display_divloginerror(true);

		$('divajaxloginerror').update(message_error);
		
		return 0;	
	}



	var date = new Date();
	date.setTime(date.getTime()+(10000));
	document.cookie = 'ajax1235=ok; expires='+date+'; path=/';



		display_divlogin(false);
		display_divload(true);
	
	url_ajax = base_url+"customer/account/loginPost/";
	new Ajax.Request(url_ajax, {
		method: 'post',
  		parameters: {'login[password]': pass__, 'login[username]': email__},
   		onSuccess: function(transport){
   		
   			 if(transport.responseText.match('login_ok'))
   			 {
						display_divload(false, defaultvalue);
						display_divmessage(true, defaultvalue);
				

						base_url = $('ajax_login_base_url').readAttribute('base_url');
						a = $$("a[href='#5#']")[0];
						a.href = base_url+'customer/account/logout/';

						a.title = 'Log Out';
						a.update('Log Out');
				
				
						a.writeAttribute('onClick', '');

						var message_split = transport.responseText.split('[1m2e3s4s5a6g7e8]');
						var messagehtml = message_split[1].split('[/1m2e3s4s5a6g7e8]');
						var message_ok = messagehtml[0];

						$('divajaxmessage').update('<div onclick="display_divmessage(false);" class="ajaxlogin-quit-buttun" style="float:right;" title="Quit"></div>'
						+message_ok
						+'<div id="counter_ajaxlogin_div"></div>');
						duration_sec = 5;
						counterajaxlogin();

						createCookie('ajax1235',"",-1);
   			 }
				else
   			 {
						display_divload(false, defaultvalue);
						display_divloginerror(true, defaultvalue);
						display_divlogin(true, defaultvalue);
				

						var message_split = transport.responseText.split('[1m2e3s4s5a6g7e8]');
						var messagehtml = message_split[1].split('[/1m2e3s4s5a6g7e8]');
						var message_error = messagehtml[0];

						$('divajaxmessage').update('<div onclick="display_divmessage(false);" class="ajaxlogin-quit-buttun" style="float:right;" title="Quit"></div>'
						+message_error+'<div id="counter_ajaxlogin_div"></div>');
						duration_sec = 5;
						counterajaxlogin();

						createCookie('ajax1235',"",-1);
			}
    }
    });
}

function ajaxcreate()
{
	password__ = $("ajaxpassword").value;				password_empty = false;
	confirmation__ = $("ajaxconfirmation").value;		confirmation_empty = false;
	is_subscribed__ = $("ajaxis_subscribed").value;
	email__ = $("ajaxemail_address").value;				email_empty = false;
	lastname__ = $("ajaxlastname").value;				lastname__empty = false;
	firstname__ = $("ajaxfirstname").value;				firstname_empty = false;
	
	if(password__ == '') password_empty = true;
	if(confirmation__ == '') confirmation_empty = true;
	if(email__ == '') email_empty = true;
	if(lastname__ == '') lastname__empty = true;
	if(firstname__ == '') firstname_empty = true;
	
	if(password_empty || 
	confirmation_empty || 
	email_empty || 
	lastname__empty || 
	firstname_empty)
	{
		display_divcreateerror(true);
	
		$("ajaxpassword").writeAttribute('style', 'border:1px solid grey;');
		$("ajaxconfirmation").writeAttribute('style', 'border:1px solid grey;');
		$("ajaxemail_address").writeAttribute('style', 'border:1px solid grey;');
		$("ajaxlastname").writeAttribute('style', 'border:1px solid grey;');
		$("ajaxfirstname").writeAttribute('style', 'border:1px solid grey;');	
		
		if(password_empty) $("ajaxpassword").writeAttribute('style', 'border:1px dashed red;');
		if(confirmation_empty) $("ajaxconfirmation").writeAttribute('style', 'border:1px dashed red;');
		if(email_empty) $("ajaxemail_address").writeAttribute('style', 'border:1px dashed red;');
		if(lastname__empty) $("ajaxlastname").writeAttribute('style', 'border:1px dashed red;');
		if(firstname_empty) $("ajaxfirstname").writeAttribute('style', 'border:1px dashed red;');

		return false;
	}
	
	message_error = ''; test1 = false; test2 = false;
	if(password__ != confirmation__)
	{
		message_error = message_error + 'Please make sure your passwords match.<br>'; test1 = true;
		
		display_divcreateerror(true);

		$('divajaxcreateerror').update(message_error);
		
		return 0;		
	}
	
	if(testemail(email__))
	{
		message_error = message_error + 'Invalid email address.<br>'; test1 = true;
		
		display_divcreateerror(true);

		$('divajaxcreateerror').update(message_error);
		
		return 0;	
	}
	
	if(stringsize(password__) < 6)
	{
		message_error = message_error + 'Password minimal length must be more 6<br>'; test2 = true;
		
		display_divcreateerror(true);	

		$('divajaxcreateerror').update(message_error);
		
		return 0;		
	}
	
	var date = new Date();
	date.setTime(date.getTime()+(10000));
	document.cookie = 'ajax1235=ok; expires='+date+'; path=/';


	display_divcreate(false);
	display_divload(true);
	
	url_ajax = base_url+"customer/account/createpost/";
	new Ajax.Request(url_ajax, {
		method: 'post',
  		parameters: {'password': password__, 'confirmation': confirmation__, 'is_subscribed': is_subscribed__, 'email': email__, 'lastname': lastname__, 'firstname': firstname__},
   		onSuccess: function(transport){
   		
   			 if(transport.responseText.match('create_ok'))
   			 {
						display_divload(false, defaultvalue);
						display_divmessage(true, defaultvalue);

						if(!transport.responseText.match('create_conf_ok'))
						{
							base_url = $('ajax_login_base_url').readAttribute('base_url');
							a = $$("a[href='#5#']")[0];
							a.href = base_url+'customer/account/logout/';
							a.title = 'Log Out';
							a.update('Log Out');
							a.writeAttribute('onClick', '');
						}
				


						var message_split = transport.responseText.split('[1m2e3s4s5a6g7e8]');
						var messagehtml = message_split[1].split('[/1m2e3s4s5a6g7e8]');
						var message_ok = messagehtml[0];
				
						$('divajaxmessage').update('<div onclick="display_divmessage(false);" class="ajaxlogin-quit-buttun" style="float:right;" title="Quit"></div>'			
						+message_ok						
						+'<div id="counter_ajaxlogin_div"></div>');
						duration_sec = 5;
						counterajaxlogin();

						createCookie('ajax1235',"",-1);
				 }
   			 else
   			 {
						display_divload(false, defaultvalue);
						display_divcreateerror(true, defaultvalue);
						display_divcreate(true, defaultvalue);
				
						var message_split = transport.responseText.split('[1m2e3s4s5a6g7e8]');
						var messagehtml = message_split[1].split('[/1m2e3s4s5a6g7e8]');
						var message_error = messagehtml[0];

				
						$('divajaxcreateerror').update(message_error);

						createCookie('ajax1235',"",-1);
   			 }
    }
    });
}


function ajaxforgot()
{
	email__ = $("ajaxemail_address_forgot").value;				email_empty = false;

	if(email__ == '') email_empty = true;
	
	if(email_empty)
	{
		display_divforgoterror(true);
		
		$("ajaxemail_address_forgot").writeAttribute('style', 'border:1px dashed red;');
		
		return false;
	}

	if(testemail(email__))
	{
		message_error = 'Invalid email address.<br>'; test1 = true;
		
		display_divforgoterror(true);			
		
		display_div();

		$('divajaxforgoterror').update(message_error);
		
		return 0;	
	}

	var date = new Date();
	date.setTime(date.getTime()+(10000));
	document.cookie = 'ajax1235=ok; expires='+date+'; path=/';

	display_divforgot(false);
	display_divload(true);
	
	
	url_ajax = base_url+"customer/account/forgotpasswordpost/";
	new Ajax.Request(url_ajax, {
		method: 'post',
  		parameters: {'email': email__},
   		onSuccess: function(transport){
   		
   			 if(transport.responseText.match('forgot_ok'))
   			 {
						display_divload(false, defaultvalue);
						display_divmessage(true, defaultvalue);
				
						var message_split = transport.responseText.split('[1m2e3s4s5a6g7e8]');
						var messagehtml = message_split[1].split('[/1m2e3s4s5a6g7e8]');
						var message_ok = messagehtml[0];

						$('divajaxmessage').update('<div onclick="display_divmessage(false);" class="ajaxlogin-quit-buttun" style="float:right;" title="Quit"></div>'
						+message_ok			
						+'<div id="counter_ajaxlogin_div"></div>');
						duration_sec = 5;
						counterajaxlogin();

						createCookie('ajax1235',"",-1);
   			 }
   			 else
   			 {
						display_divload(false, defaultvalue);
						display_divforgoterror(true, defaultvalue);		
						display_divforgot(true, defaultvalue);			
				
						var message_split = transport.responseText.split('[1m2e3s4s5a6g7e8]');
						var messagehtml = message_split[1].split('[/1m2e3s4s5a6g7e8]');
						var message_ko = messagehtml[0];

						$('divajaxforgoterror').update(message_ko);

						createCookie('ajax1235',"",-1);
   			 }
    }
    });
}
