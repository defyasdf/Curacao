<section id="main" class="column"> 
	<article class="module width_full">
			
<br />

            <div id="notify">
                
            </div><br />
<br />

    </article>
<article class="module width_full">
			<header><h3>Step 1: Choose Type of insert</h3></header>
				
                <div id="notify"></div>
            
            	<div class="module_content">
               		
                        <article class="info_overview" onclick="return additem('manual')">
                               <div id="rev"> Manual Entry </div>                
                        </article>                       
					
                        <article class="info_overview" onclick="return additemexcel()">
                               <div id="rev"> Excel Upload </div>                
                        </article>                         
                    <div class="clear"></div>
                    <div class="clear"></div>
                    <div class="clear"></div>
				</div>
			
		</article><!-- end of post new article -->
        <article class="module width_full">
			<header><h3>Step 2: Choose type of data upload</h3></header>
			<br />
			<div style="margin-left:25px; margin-bottom:25px;">
            <div>
            	<input type="radio" name="newupdate" value="1"/> Insert New recodrs for vIndia
            </div>            
            
            <div>
            	<input type="radio" name="newupdate" value="2" /> Update recodrs
            </div>
            
            <div>
            	<input type="radio" name="newupdate" value="3" /> English Ready content
            </div>
            
			</div>
            
                                            
  		</article><!-- end of post new article -->
        <article class="module width_full" id="productPage" style="display:none">
        </article>
        <article class="module width_full" id="productPage1">
			<header><h3>Step 3: Upload the file and Process the data</h3></header>
            
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

            
			<button class="btn btn btn-primary" onclick="return processthefile('{%=file.name%}')">
                <i class="icon-trash icon-white"></i>
                <span>Process the File</span>
            </button>
            
        </td>
    </tr>
{% } %}
</script>

		</article>        
        
 </section>