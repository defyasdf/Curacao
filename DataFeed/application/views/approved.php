<div id="ajaxcontentbg">
	
</div>

<div id="prioritydiv">

   	<form>
    <fieldset>
    <label>Write a Comment</label>
<textarea name="comment" id="comment"></textarea>


  </form>
  
  <br />
  
<br /><br />
<br />


         <button onclick="return unapprove()" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                    <i class="icon-trash icon-white"></i>
                    <span>Process Content</span>
                </button>
                
                
                
        </fieldset><br />
<br />
<input type="hidden" id="prsku" name="prsku" />
<input type="hidden" id="prid" name="prid" />
    </div>


<section id="main" class="column"> 
   
      <article class="module width_full">
         
		<header><h3 class="tabs_involved">Approved Products</h3>
	
		</header>
        
        	<div id="notify">
    	
    </div>
        <div id="searchTop">
        	 <button class="btn btn-success" type="button" onclick="return addtomagentoque()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Add to Magento</span>
           </button>	
           
             <button class="btn btn-success" type="button" onclick="return exportAllpro()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Export Selected Data</span>
           </button>
           
           <button class="btn btn-success" type="button" onclick="return exportAllpros()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Export All Data</span>
           </button>	
        </div>
        
        
         <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
            <link rel="stylesheet" href="css/jquery.fileupload-ui.css">
            <div class="container">
               
                <br>
                <!-- The file upload form used as target for the file upload widget -->
                <form id="fileupload" action="server/php/" method="POST" enctype="multipart/form-data">
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="span7" style="margin-left:60px;">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                                <i class="icon-plus icon-white"></i>
                                <span>Add files...</span>
                                <input type="file" name="files[]" multiple>
                            </span>
                            <button type="submit" class="btn btn-primary start">
                                <i class="icon-upload icon-white"></i>
                                <span>Start upload</span>
                            </button>
                            <button type="reset" class="btn btn-warning cancel">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Cancel upload</span>
                            </button>
                            <button type="button" class="btn btn-danger delete">
                                <i class="icon-trash icon-white"></i>
                                <span>Delete</span>
                            </button>
                            <input type="checkbox" class="toggle">
                        </div>
                        <!-- The global progress information -->
                        <div class="span5 fileupload-progress fade">
                            <!-- The global progress bar -->
                            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="bar" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress information -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <!-- The loading indicator is shown during file processing -->
                    <div class="fileupload-loading"></div>
                    <br>
                    <!-- The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
                </form>
                <br>
    
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
		<td class="start">
            
			<button class="btn btn btn-primary" onclick="return processthefinalfile('{%=file.name%}')">
                <i class="icon-trash icon-white"></i>
                <span>Process the File</span>
            </button>
            
        </td>
    </tr>
{% } %}
</script>
        
        
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
                    <th>Magento Category</th>
                    <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
 <?php 
				
				
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr <?php if($content[$i]->magento_product_id>0){?> bgcolor="#E2F6C5"<?php }?>> 
                <td><input type="checkbox" value="<?php echo $content[$i]->fpl_id?>" name="products[]" class="productcheck" /></td>
                <td><?php echo $content[$i]->prduct_name?></td> 
                <td><?php echo $content[$i]->product_sku?></td> 
                <td><?php echo $content[$i]->product_upc?></td> 
                <td><?php echo $content[$i]->product_brand?></td> 
               <td><?php echo $content[$i]->product_source?></td>  
               <td><?php echo $content[$i]->magento_category_id?></td>  
                <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"><?php if($content[$i]->magento_product_id==0){?><a href="" onclick="return addtoMagento('<?php echo $content[$i]->fpl_id?>')"><input type="image" src="images/icn_alert_success.png" title="Approve"></a><?php }?>
                <a href="" onclick="return setunapprove('<?php echo $content[$i]->fpl_id?>','<?php echo $content[$i]->product_sku?>')"><input type="image" src="images/icn_alert_error.png" title="Unapprove"></a>
                
                </td> 
            </tr> 
                				
				<?php
				}
				}else{
			
			?>
				<tr>
                	<td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                </tr>
			<?php	
			}
			?>
			</tbody>
            
            </table>
            </div></article>
                    
     
		<div class="spacer"></div>
	</section>

