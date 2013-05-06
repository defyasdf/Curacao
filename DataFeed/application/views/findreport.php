<section id="main" class="column" style="width:100%">
	
<article class="module width_full">
		<div id="searchTop">
        <?php 
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			
			$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
			$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
		?>
        	<a href="index.php/exportcreditreport?edate=<?php echo $to ?>&sdate=<?php echo $from ?>">
            <button  type="reset" class="btn btn-warning cancel">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Export Data</span>
                            </button>
                      </a>
        </div>		
        
        <div class="tab_container">
        
        <table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th>ID</th> 
    				<th>First Name</th> 
    				<th>Last Name</th> 
    				<th>Phone 1</th> 
                    <th>Phone 2</th>
                    <th>Email</th>
                    <th>Street</th>
                    <th>City</th> 
    				<th>State</th> 
    				<th>Zip</th> 
    				<th>Years_living</th> 
    				<th>Months_living</th> 
                    <th>SSN</th>
                    <th>DOB</th>
                    <th>ID_number</th>
                    <th>ID_type</th> 
    				<th>ID_EXP</th> 
    				<th>Maiden Name</th> 
    				<th>Company</th> 
    				<th>Work_phone</th> 
                    <th>work_year</th>
                    <th>work_month</th>
                    <th>IP</th> 
    				<th>Salary</th> 
    				<th>Laxus_complete</th> 
    				<th>Address_complete</th> 
    				<th>Webapplication status</th>
                    <th>Applied Date</th> 
				</tr> 
			</thead> 
			<tbody> 
				

            <?php 
				
				$status = array(
								'0'=>'not applied',
								'1'=>'Approve',
								'2'=>'Pending',
								);				
				
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr> 
                    <td><?php echo $content[$i]->credit_id?></td> 
                    <td><?php echo $content[$i]->firstname?></td> 
                    <td><?php echo $content[$i]->lastname?></td> 
                    <td><?php echo $content[$i]->telephone?></td> 
                    <td><?php echo $content[$i]->telephone2?></td>  
                    <td><?php echo $content[$i]->email_address?></td>  
                    <td><?php echo $content[$i]->address1.' '.$content[$i]->address2?></td> 
                    <td><?php echo $content[$i]->city?></td> 
                    <td><?php echo $content[$i]->state?></td> 
                    <td><?php echo $content[$i]->zipcode?></td>  
                    <td><?php echo $content[$i]->res_year?></td>  
                    <td><?php echo $content[$i]->res_month?></td> 
                    <td><?php echo $content[$i]->ssn?></td> 
                    <td><?php echo $content[$i]->dob?></td> 
                    <td><?php echo $content[$i]->id_num?></td> 
                    <td><?php echo $content[$i]->id_type?></td>  
                    <td><?php echo $content[$i]->id_expire?></td>  
                    <td><?php echo $content[$i]->maiden_name?></td> 
                    <td><?php echo $content[$i]->company?></td> 
                    <td><?php echo $content[$i]->work_phone?></td> 
                    <td><?php echo $content[$i]->work_year?></td>  
                    <td><?php echo $content[$i]->work_month?></td>  
                    <td><?php echo $content[$i]->ip_address?></td> 
                    <td><?php echo $content[$i]->salary?></td> 
                    <td><?php if($content[$i]->is_lexis_nexus_complete){echo 'yes'; }else{echo 'no';}?></td> 
					<td><?php if($content[$i]->is_validate_address_complete){echo 'yes'; }else{echo 'no';}?></td> 
                    <td><?php echo $status[$content[$i]->is_web_customer_application_complete]?></td> 
					<td><?php echo $content[$i]->applied_date?></td> 
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
                    <th></th> 
    				<th></th> 
    				<th></th> 
    				<th></th> 
    				<th></th> 
                    <th></th>
                    <th></th>
                    <th></th> 
    				<th></th> 
    				<th></th> 
    				<th></th> 
    				<th></th> 
                    <th></th>
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
            
                   
</article>
</section>


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