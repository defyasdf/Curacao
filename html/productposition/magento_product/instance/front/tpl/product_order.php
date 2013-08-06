<style type="text/css">

    .catParent{
        clear:both;
    }
    
    .catHeader{
        font-weight: bold;
        font-size:larger;
        cursor:pointer;
    }
    .catText{
        padding:10px;
        width:180px;
        float:left;
        height:250px;
        margin:10px;
        background-color: blanchedalmond;
        border:1px solid #DADADA;
        cursor:move;
    }
    .catChild{
        display:none;
    }
	#category_product_div{
		display:none;
	}
</style>

<div style="margin-top:10px">&nbsp;</div>
<div class="row-fluid sortable">

	

    <div class="span12">
        <div id="box_responsive_one">
           <div id="category_tree">
           		<?php echo file_get_contents('http://icuracao.com/custom/getcategory.php');?>
           </div>
           
            <div class="catParent" id="category_product_div">
                <div class="catHeader">Electronics</div>
                <div class="catChild">
                    <div class="catEachChild" data-catid="1" id="category_products">
                       <!-- <div class="catText" data-id="1">Product 1</div>
                        <div class="catText" data-id="2">Product 2</div>
                        <div class="catText" data-id="3">Product 3</div>
                        <div class="catText" data-id="4">Product 4</div>
                        <div class="catText" data-id="5">Product 5</div>-->
                    </div>

                </div>
            </div>
           <!-- <div class="catParent">
                <div class="catHeader">Computer Hardware</div>
                <div class="catChild">
                    <div class="catEachChild" data-catid="2">
                        <div class="catText" data-id="6">Product 6</div>
                        <div class="catText" data-id="7">Product 7</div>
                        <div class="catText" data-id="8">Product 8</div>
                        <div class="catText" data-id="9">Product 9</div>
                        <div class="catText" data-id="10">Product 10</div>
                    </div>
                </div>
            </div>-->	




            <div style="margin-top:20px;clear:both;">
                <input type="button" value="Save" style="width:100px;" id="saveBTN" /> 
            </div>

            <form method="post" id="frm" action="">
                <input type="hidden" name="save" value="1" />
                <input type="hidden" name="catId" value="" id="product_cat_id" />
                <input type="hidden" name="saveValues" value="1" id="saveValues"/>
                <input type="submit" name="sb" value="1" style="display:none" />
            </form> 


        </div>
    </div>
</div>
