<div id="ajaxcontentbg">
	
</div>

<div id="prioritydiv">

   	<form>
    <fieldset>
    <label>Set Priority to group</label>
           	<select name="priority" id="priority">
            	<option value="1">Low</option>
                <option value="2">Medium</option>
                <option value="3">Normal</option>
                <option value="4">High</option>
                <option value="5">Critical</option>
                
            </select>
        </form><br />
<br />

         <button onclick="return processAll()" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                    <i class="icon-trash icon-white"></i>
                    <span>Process Content</span>
                </button>
        </fieldset><br />
<br />

    </div>

<section id="main" class="column">
	
<article class="module width_full">
		<header><h3 class="tabs_involved">Search Products</h3>
	
		</header>
		<div id="notify">
    	
    </div>
        <div id="searchTop">
        <?php if(strtolower($this->session->userdata('lname')) != 'vindia'){?>
        	<button class="btn btn-warning cancel" type="reset" onclick="return achieveAll()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Archieve</span>
                            </button>
			
            <button class="btn btn-danger delete" type="button" onclick="return deleteAll()" style="margin-right:25px;">
                                <i class="icon-trash icon-white"></i>
                                <span>Delete</span>
            </button>
            <?php }?>
            <button class="btn btn-success" type="button" onclick="return exportAll()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Export Data</span>
           </button>	
            
            
           <!-- 
            <strong style="margin-left:15px;">  Filter For :</strong>
           <select id="brandnames">
           		<option value="">Brand Names</option>
				<?php
					for($i=0;$i<sizeof($d['brand']);$i++){?>
						<option value="<?php echo $d['brand'][$i]?>"><?php echo $d['brand'][$i]?></option>
                  <?php
					}
				?>
            </select>
            <select id="Sources">
           		<option value="">Sources</option>
				<?php
					for($i=0;$i<sizeof($d['source']);$i++){?>
						<option value="<?php echo $d['source'][$i]?>"><?php echo $d['source'][$i]?></option>
                  <?php
					}
				?>
            </select>-->
            
            <!--<button onclick="return filterdata()" class="btn btn-success">
                    <i class="icon-trash icon-white"></i>
                    <span>Filter</span>
                </button>
            -->
        </div>
			 <strong style="margin-left:15px;">  Filter Data :</strong><br />

        	<div id="footer">
            
            	<fieldset style="display:none">
	                <div></div>
                </fieldset>
               <article class="module width_half">
                <fieldset>
                	<label>Product Name</label>
	                <div></div>
                </fieldset>
                <fieldset>
                	<label>Product SKU</label>
	                <div></div>
                </fieldset>
                <fieldset>
                	<label>Product UPC</label>
	                <div></div>
                </fieldset>
                </article>
               <article class="module width_half">
                <fieldset>
                	<label>Product Brand</label>
	                <div></div>
                </fieldset>
                <fieldset>
                	<label>Product Source</label>
	                <div></div>
                </fieldset>
                <fieldset>
                	<label>Product Status</label>
	                <div></div>
                </fieldset>
                </article>
            </div>
            
        </div>
        <div class="clear"></div><br />
<br />

		<div id="searchdata">
        	
        
        </div>
        
        <div class="tab_container">
        
        <table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><input type="checkbox" onclick="return selecteverything()" id="mainselect" /></th> 
    				<th>Product Name</th> 
    				<th>Product SKU</th> 
    				<th>Product UPC</th> 
    				<th>Brand</th> 
                    <th>Source</th>
                    <th>Status</th>
<!--                    <th>Action</th>-->
				</tr> 
			</thead> 
			<tbody> 
				

            <?php 
				
				$status = array(
								'0'=>'In Queue',
								'1'=>'Archieve',
								'2'=>'Pending',
								'3'=>'Raw',
								'4'=>'In Process',
								'5'=>'QA',
								'6'=>'Active'
								);				
				
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr> 
                <td><input type="checkbox" value="<?php echo $content[$i]->mpt_id?>" name="products[]" class="productcheck" /></td>
                <td><?php echo $content[$i]->prduct_name?></td> 
                <td><?php echo $content[$i]->product_sku?></td> 
                <td><?php echo $content[$i]->product_upc?></td> 
                <td><?php echo $content[$i]->product_brand?></td> 
               <td><?php //echo $content[$i]->product_source;
					
					$sql = 'select vendorName from vendormanagement where vendorID = '.$content[$i]->product_source;   
					$re = mysql_query($sql);
					$row = mysql_fetch_array($re);
			   	
					echo $row['vendorName'];
			   
			   ?></td>  
               <td><?php echo $status[$content[$i]->status]?></td>  
              <!--  <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"></td> -->
            </tr> 
                				
				<?php
				}
				}else{
			
			?>
				<tr>
                	<td colspan="7">No Product is Panding</td>
                </tr>
			<?php	
			}
			?>
            <tfoot>
            	<th></th> 
    				<th></th> 
    				<th></th> 
    				<th></th> 
    				<th></th> 
                    <th></th>
                    <th></th>
            </tfoot>
			</tbody>
            
            </table>
            </div>
            
            <?php if(strtolower($this->session->userdata('lname')) != 'vindia'){?>
             <div id="searchBottom">
                <button onclick="return setpriority()" class="btn btn btn-primary">
                    <i class="icon-trash icon-white"></i>
                    <span>Process Content</span>
                </button>
			</div>            
            <?php }?>
</article>
</section>

<script type="text/javascript">
	 $(document).ready(function() {
		 
		$("#brandnames").change(function(){
			$('input[type = text]').focus();
			$('input[type = text]').val($("#brandnames").val()+' '+$("#Sources").val())

		})
		
		$("#Sources").change(function(){
			$('input[type = text]').focus();
			$('input[type = text]').val($('input[type = text]').val()+' '+$("#Sources").val())

		})
	})
	
</script>

<script type="text/javascript" charset="utf-8">
			(function($) {
			/*
			 * Function: fnGetColumnData
			 * Purpose:  Return an array of table values from a particular column.
			 * Returns:  array string: 1d data array 
			 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
			 *           int:iColumn - the id of the column to extract the data from
			 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
			 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
			 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
			 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
			 */
			$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
				// check that we have a column id
				if ( typeof iColumn == "undefined" ) return new Array();
				
				// by default we only wany unique data
				if ( typeof bUnique == "undefined" ) bUnique = true;
				
				// by default we do want to only look at filtered data
				if ( typeof bFiltered == "undefined" ) bFiltered = true;
				
				// by default we do not wany to include empty values
				if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
				
				// list of rows which we're going to loop through
				var aiRows;
				
				// use only filtered rows
				if (bFiltered == true) aiRows = oSettings.aiDisplay; 
				// use all rows
				else aiRows = oSettings.aiDisplayMaster; // all row numbers
			
				// set up data array	
				var asResultData = new Array();
				
				for (var i=0,c=aiRows.length; i<c; i++) {
					iRow = aiRows[i];
					var aData = this.fnGetData(iRow);
					var sValue = aData[iColumn];
					
					// ignore empty values?
					if (bIgnoreEmpty == true && sValue.length == 0) continue;
			
					// ignore unique values?
					else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
					
					// else push the value onto the result data array
					else asResultData.push(sValue);
				}
				
				return asResultData;
			}}(jQuery));
			
			
			function fnCreateSelect( aData )
			{
				//alert(aData[0].substring(0,1));
				//return false;
				if(aData[0].substring(0,1)!='<'){
					var r='<select><option value=""></option>', i, iLen=aData.length;
					for ( i=0 ; i<iLen ; i++ )
					{
						r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
					}
					return r+'</select>';
				}else{
					return '<input type="checkbox">';
				}
			}
			
			

		</script>