// JavaScript Document

function showcontent(fname){
	$('#main').load(fname+'.html');
}

function editProduct(fname,id){
	$('#main').load(fname+'.php?pId='+id);
}

function selectcat(id){
	var val = $("#pId"+id).val();
	var level = parseInt(id)+1;
	
	$.ajax({
		  type: "POST",
		  url: "selectsubcat.php",
		  data: { cId: val, level:level}
		}).done(function( msg ) {
		  //alert( "Data Saved: " + msg );
		if(msg != '0'){	
			$("#catTree").append(msg);	 		
			$("#levels").val(level);
		}
		 
		});
	
	
	
	
}

function showProduct(fname){
	$('#main').load(fname+'.php');
}

function edituser(id){
	$('#main').load('edituser.php?uId='+id);
}

function  adduser(){
	var values = $('input:checkbox:checked').map(function () {
			return this.value;
	}).get(); // ["18", "55", "10"]
	
	var fname = $("#fName").val();
	var lname = $("#lName").val();
	var email = $("#email").val();	
	var username = $("#uName").val();	
	var pass = $("#pass").val();
	
	
	$('#notify').load('test1.php?value='+values+'&fname='+fname+'&lname='+lname+'&email='+email+'&username='+username+'&pass='+pass);
	
	return false;
}

function translateitem(id){
	$('#s'+id).htmlarea('updateHtmlArea','');
	var content = $('#'+id).val();
	$.ajax({
		  type: "POST",
		  url: "index.php/translate",
		  data: { tex: content }
		}).done(function( msg ) {
		  //alert( "Data Saved: " + msg );
		 if(msg != ''){
			$('#s'+id).val(msg);
			$('#s'+id).htmlarea();
			$('#s'+id).htmlarea('updateHtmlArea','');
			
		}else{
			alert("Not Translated")
		}
		
		 
		});
	
	

}


function  updateuser(id){
	var values = $('input:checkbox:checked').map(function () {
			return this.value;
	}).get(); // ["18", "55", "10"]
	
	var fname = $("#fName").val();
	var lname = $("#lName").val();
	var email = $("#email").val();	
	var username = $("#uName").val();	
	var pass = $("#pass").val();
	
	
	$('#notify').load('updateuser.php?value='+values+'&fname='+fname+'&lname='+lname+'&email='+email+'&username='+username+'&pass='+pass+'&uid='+id);
	
	return false;
}

function  addproduct(){
	
	var pName = $("#pName").val();
	var pDesc = $("#pDesc").val();
	var pSku = $("#pSku").val();	
	var pupc = $("#pupc").val();	
	var pCost = $("#pCost").val();
	var pRetail = $("#pRetail").val();
	var pmsrp = $("#pmsrp").val();	
	var pMAP = $("#pMAP").val();	
	var pQTY = $("#pQTY").val();
	var pIlevel = $("#pIlevel").val();
	var pBrand = $("#pBrand").val();	
	var pimg1 = $("#pimg1").val();	
	var pFeature = $("#pFeature").val();
	var pSource = $("#pSource").val();
	var pSpecs = $("#pSpecs").val();	
	
	
	
	$('#notify').load('newproduct.php?pname='+pName+'&pDesc='+pDesc+'&pSku='+pSku+'&pupc='+pupc+'&pCost='+pCost+'&pRetail='+pRetail+'&pmsrp='+pmsrp+'&pMAP='+pMAP+'&pQTY='+pQTY+'&pIlevel='+pIlevel+'&pBrand='+pBrand+'&pimg1='+pimg1+'&pFeature='+pFeature+'&pSource='+pSource+'&pSpecs='+pSpecs);

	
	return false;
}

function nextStep(step){
	$(".steps").hide('slow');
	$("#"+step).show('slow');
}

function addtoMagento(id){
	
	$("#notify").html('<img src="images/loading.gif">');
	$("#notify").load('addtomagento.php?pId='+id);
	return false;
}

function addtomagentoque(){

	if($(':checkbox.productcheck:checked').length>0){
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		alert(allVals)

		$("#notify").load('index.php/addtoque?vals='+allVals);
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
}

function chkuser(){
	$("#chkUser").html("<img src='images/load.gif' height='50' width='50'>");
	
	$.ajax({
		  type: "POST",
		  url: "userchk.php",
		  data: { uname: $('#uName').val() }
		}).done(function( msg ) {
		  //alert( "Data Saved: " + msg );
		 if(msg == 0){
			$("#chkUser").html('<h4 class="alert_success" style="margin:40px 1% 0">User Name Available</h4>');
		}else{
			$("#chkUser").html('<h4 class="alert_error" style="margin:40px 1% 0">User Name not Available Please choose different User Name</h4>');
			$("#err").val('0');
		}
		
		 
		});
}

function chkform(){
	
	if($("#err").val()=='0'){
		
		return false;
	}
//	return false;
}
function additem(type){
	
	$("#productPage").load(type+'.html');
	$("#productPage1").hide('slow');
	$("#productPage").show('slow');
}
function additemexcel(){
	$("#productPage").hide('slow');
	$("#productPage1").show('slow');
}

function processthefile(file){
	
	if($("input:radio[checked=checked]").val()!=1&& $("input:radio[checked=checked]").val()!=2&& $("input:radio[checked=checked]").val()!=3){
		$("#notify").html('<h4 class="alert_warning">Please select type of data upload</h4>')
		return false;
	}
	var value = $("input[@name=newupdate]:checked").val();
	$("#notify").html('<img src="images/search.gif">');
	if(value == 3){
		$("#notify").load('excel_reader/importengready.php?file='+file+'&type='+$("input:radio[checked=checked]").val());	
	}else{
		$("#notify").load('excel_reader/import.php?file='+file+'&type='+$("input:radio[checked=checked]").val());
	}
}

function processthefinalfile(file){
	$("#notify").html('<img src="images/search.gif">');
	$("#notify").load('excel_reader/importfinal.php?file='+file);
}


function selecteverything(){
	if($('#mainselect').is(':checked')){	
		$('.productcheck').attr('checked','checked');
		
	}else{
		$('.productcheck').attr('checked',false);
	}

}

function achieveAll(){
	$("#notify").html('<img src="images/search.gif">');
	//alert('this is archieve Section')
	if($(':checkbox.productcheck:checked').length>0){
	
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		$("#notify").load('index.php/archieve?vals='+allVals);
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
}

function exportAll(){
	if($(':checkbox.productcheck:checked').length>0){
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		//$("#notify").load('index.php/exportproducts?vals='+allVals);
		
		var url = 'index.php/exportproducts?vals='+allVals;
		
		$(location).attr('href',url);
		
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
}


function exportAllpro(){
	if($(':checkbox.productcheck:checked').length>0){
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		//$("#notify").load('index.php/exportproducts?vals='+allVals);
		
		var url = 'index.php/exportprod?vals='+allVals;
		
		$(location).attr('href',url);
		
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
}


function exportAllpros(){
	
		var url = 'index.php/exportprods';
		
		$(location).attr('href',url);

}



function resetAll(){
	$("#notify").html('<img src="images/search.gif">');
	//alert('this is archieve Section')
	if($(':checkbox.productcheck:checked').length>0){
	
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		$("#notify").load('index.php/reset?vals='+allVals);
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
}

function deleteAll(){
	$("#notify").html('<img src="images/search.gif">');
	var r=confirm("Are you sure you want to delete this product ?");
	if(r==true){
		if($(':checkbox.productcheck:checked').length>0){
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		$("#notify").load('index.php/delete?vals='+allVals);
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
	}
}

function setpriority(){
	if($(':checkbox.productcheck:checked').length>0){
		$("#ajaxcontentbg").show('slow')
		$("#prioritydiv").show('slow')
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
}

function processAll(){
	
	$("#ajaxcontentbg").hide('slow')
	$("#prioritydiv").hide('slow')
	
	$("#notify").html('<img src="images/search.gif">');
	if($(':checkbox.productcheck:checked').length>0){
		 var allVals = [];
				 $(':checkbox.productcheck:checked').each(function() {
				   allVals.push($(this).val());
				 });
		$("#notify").load('index.php/process?vals='+allVals+'&priority='+$("#priority").val());
	}else{
		$("#notify").html('<h4 class="alert_warning">Please select product</h4>')
	}
	return false;
}   

function filterdata(){
	$("#notify").html('<img src="images/search.gif">');
	var str = '';
	if($("#bname").val()!=''){
		//alert("brand name is blank");
		str += 'brandname='+$("#bname").val()+'&';
	}
	
	if($("#vname").val()!=''){
		//alert("brand name is blank");
		str += 'vendor='+$("#vname").val()+'&';
	}
	
	if($("#upc").val()!=''){
		//alert("brand name is blank");
		str += 'upc='+$("#upc").val()+'&';
	}
	if(str!=''){
		
		$('.tablesorter').dataTable( {
			"bProcessing": true,
			"sAjaxSource": 'index.php/filtereddata?'+str
		} );
		
		//$(".tab_container").load('index.php/filtereddata?'+str);
	}else{
		alert("Please provide one filter value")
		$("#notify").html('');
	}
	
	
	return false;
}

function placevalue(from,to){
//	alert($('#'+from).html())
	$('#'+to).val($('#'+from).val());
	
}
function placehtml(from,to){
//	alert($('#'+from).html())
	//$('#'+to).show();
	$('#'+to).val($('#'+from).html());
	$('#'+to).htmlarea();
	$('#'+to).htmlarea('updateHtmlArea','');
}

function chkforProcess(sku){
	
	$.ajax({
		  type: "POST",
		  url: "chkprocess.php",
		  data: { psku: sku }
		}).done(function( msg ) {
		 // alert( "Data Saved: " + msg );
		 if(msg == 1){
			alert('The product with SKU '+sku+' is already in process')
			location.reload();
			//$("#chkUser").html('<h4 class="alert_success" style="margin:40px 1% 0">User Name Available</h4>');
		}else{
			var url = "index.php/productwiz?sku="+sku;
			$(location).attr('href',url);
		}
		 
		});
		
		return false;
	
}

function setcomment(sku){
	$("#prsku").val(sku);
	$("#ajaxcontentbg").show('slow')
	$("#prioritydiv").show('slow')
	return false;
}

function revertproducts(){
	var r=confirm("Are you sure you want to revert this product ?");
	if(r==true){
		$.ajax({
			  type: "POST",
			  url: "index.php/revert",
			  data: { psku: $("#prsku").val(), comment:$("#comment").val() }
			}).done(function( msg ) {
			
			
			 if(msg == 0){
				alert('Product did not revert back')
				location.reload();
				
			}else{
				
				var url = "index.php/pending";
				$(location).attr('href',url);
			}
			 
			});
	}
		
		return false;
	
}


function revertproduct(sku){
	var r=confirm("Are you sure you want to revert this product ?");
	if(r==true){
		$.ajax({
			  type: "POST",
			  url: "index.php/revert",
			  data: { psku: sku }
			}).done(function( msg ) {
			
			 if(msg == 0){
				alert('Product did not revert back')
				location.reload();
				
			}else{
				
				var url = "index.php/pending";
				$(location).attr('href',url);
			}
			 
			});
	}
		
		return false;
	
}

function spanishready(){
	$.ajax({
		  type: "POST",
		  url: "index.php/spanishready",
		  data: { sppr_id: $('#sppr_id').val(), fpt_id: $('#fpt_id').val(), pName: $('#spName').val(), pDesc: $('#spDesc').val(), pFeature: $('#spFeature').val(), pSpecs: $('#spSpecs').val()}
		}).done(function( msg ) {
		
		 if(msg == 0){
			alert('Product did not updated')
			//location.reload();
			
		}else{
			alert('Product has been updated successfully');
			var url = "index.php/approved";
			$(location).attr('href',url);
		}
		 
		});
		
		return false;
}


function savespanish(){

	$.ajax({
		  type: "POST",
		  url: "index.php/spanishsave",
		  data: { sppr_id: $('#sppr_id').val(), fpt_id: $('#fpt_id').val(), pName: $('#spName').val(), pDesc: $('#spDesc').val(), pFeature: $('#spFeature').val(), pSpecs: $('#spSpecs').val()}
		}).done(function( msg ) {
		
		 if(msg == 0){
			alert('Product did not updated')
			//location.reload();
			
		}else{
			alert('Product has been updated successfully');
			
		}
		 
		});
		
		return false;
}


function saveeng(){

	$.ajax({
		  type: "POST",
		  url: "index.php/engsave",
		  data: { sppr_id: $('#sppr_id').val(), fpt_id: $('#fpt_id').val(), img:$("#pImg1").val(), pName: $('#pName').val(), pDesc: $('#pDesc').val(), pFeature: $('#pFeature').val(), pSpecs: $('#pSpecs').val()}
		}).done(function( msg ) {
		
		 if(msg == 0){
			alert('Product did not updated')
			//location.reload();
			
		}else{
			alert('Product has been updated successfully');
			
		}
		 
		});
		
		return false;
}



function saveenglish(){
	$.ajax({
		  type: "POST",
		  url: "saveenglish.php",
		  data: { pname:$("#pName").val(), pDesc: $('#pDesc').val(), psku:$("#pSku").val(), pupc:$("#pupc").val(),pmsrp:$("#pmsrp").val(),pmap:$("#pMAP").val(),pbrand:$("#pBrand").val(),pimg:$("#pImg1").val(), pFeature: $('#pFeature').val(), pSpecs: $('#pSpecs').val()}
		}).done(function( msg ) {
		 if(msg == 0){
			alert('Product did not updated')
		
		}else{

			alert('Product has been saved successfully');
			//var url = "index.php/approved";
			//$(location).attr('href',url);
		}
		 
		});
		
		return false;
}



function setunapprove(id,sku){
	$("#prsku").val(sku);
	$("#prid").val(id);
	$("#ajaxcontentbg").show('slow')
	$("#prioritydiv").show('slow')
	return false;
}
function alertmsg(msg){
	alert(msg);
}
function unapprove(){
	
	var r=confirm("Are you sure you want to unapprove this product ?");
	
	if(r==true){
	
			$.ajax({
				  type: "POST",
				  url: "index.php/unapprove",
				  data: { fpt_id: $("#prid").val(), psku: $("#prsku").val(), comment : $("#comment").val()}
				}).done(function( msg ) {
		
				 if(msg == 0){
					alert('Product did not updated')
					
				}else{
					alert('Product has been updated successfully');
					var url = "index.php/spenish";
					$(location).attr('href',url);
				}
				 
				});
			
		}
		
		return false;
	}

function addimgvalue(value){
	$('#step5').append('<input type="hidden" name="imgnames[]" value="'+value+'">');
}

function addimg(){
	
  $("#pImg1").val($("#pImg1").html()+','+$("#pImg2").val());
  
//  $('#pImg1').htmlarea();
 // $('#pImg1').htmlarea('updateHtmlArea','');	
  
  $.get("getimgsize.php", { img: $("#pImg2").val()},
   function(data){
    // alert("Data Loaded: " + data);
	$("#imgtabletr").append(data)
   });

	
	
}