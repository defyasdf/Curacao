<script type="text/javascript">
    $(document).ready(function(){
        $(".catHeader").click(function(){
            $(this).next().toggle("slow"); 
        });
        $( ".catEachChild" ).sortable();
        $( ".catEachChild" ).disableSelection();
		
        var cat_id;
        $("#saveBTN").click(function(){
            $(".catEachChild").each(function(i,v){
                cat_id = $(v).data("catid");
                $(this).find(".catText").each(function(index,value){
                    $("<input />").attr({"type":"hidden",'name':"cat["+cat_id+"][]",'value':$(value).data("id")}).appendTo("#frm");
                });
            });
		    
            //$("#frm").submit();
			savedata();
			return false;
        });
    });
	function ToggleSubcat(id){
		$("#category_"+id).toggle('slow');
	}    
	function getProducts(id){
		$(".catHeader").html($("#category_name_"+id).html());
		$("#category_tree").hide('slow');
		$("#product_cat_id").val(id);
		var coupon_url = '/productposition/magento_product/magentoclass/productToCategory.php'
		jQuery.ajax({ 
				type: 'post',
				data: {'cId':id},
				url:  coupon_url,                    
				success: function(data) {
				   $("#box_responsive_one").attr('class','span8');	
				   $("#category_products").html(data);
				   $("#category_product_div").show('slow');
				   $(".catChild").show('slow');
				}.bind(this)
		 });
	}
	
	function savedata(){
		var coupon_url = '/productposition/magento_product/magentoclass/savepositions.php'
		var formdata = jQuery('form#frm').serializeArray();
		jQuery.ajax({ 
				type: 'post',
				data: formdata,
				url:  coupon_url,                    
				success: function(data) {
				  // $("#box_responsive_one").attr('class','span8');	
//				   $("#category_products").html(data);
//				   $("#category_product_div").show('slow');
//				   $(".catChild").show('slow');
				}.bind(this)
		 });
		 return false;
	}	
</script>