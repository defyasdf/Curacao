<?php $data = explode(',',$this->session->userdata('level'));?>
	<aside id="sidebar" class="column">
	<?php if(in_array('1',$data)||in_array('6',$data)){?>
    <h3>New Items</h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="index.php/newproduct">New Product</a></li>			
		</ul>
    <?php }?>
    <h3>Content</h3>
		<ul class="toggle">
        	
        	<?php if(in_array('2',$data)||in_array('6',$data)){?>
				<li class="icn_search"><a href="index.php/contentsearch">Search Data</a></li>
            <?php }?>
            <?php if(in_array('3',$data)||in_array('6',$data)){?>
                <li class="icn_pending"><a href="index.php/pending">Pending Initial Data</a></li>
                <li class="icn_inprocess"><a href="index.php/inprocess">In Process Data</a></li>
            <?php }?>
            <?php if(in_array('4',$data)||in_array('6',$data)){?>
	            <li class="icn_pending"><a href="index.php/spenish">Pending Translation and review</a></li>
            <?php }?>
            <?php if(in_array('5',$data)||in_array('6',$data)){?>
				<li class="icn_tags"><a href="index.php/approved">Review and Submit</a></li>
            <?php }?>
            <?php if(in_array('6',$data)){?>
                <li class="icn_tags"><a href="index.php/magentoque">Product Queue</a></li>
                <li class="icn_archieve"><a href="index.php/archievelist">Archieve Data</a></li>
            <?php }?>
		</ul>
	     <h3>GWM Reporting</h3>
        <ul class="toggle">
        	<li class="icn_categories">
                <a href="http://www.icuracao.com/DataFeed/index.php/gwm_report">GWM Reports</a>
            </li>
        </ul>
<?php if(in_array('6',$data)){?>
	<h3>Admin</h3>
		<ul class="toggle">
        	<li class="icn_add_user"><a href="index.php/adduser">Add New User</a></li>
			<li class="icn_view_users"><a href="index.php/users">View Users</a></li>
            <li class="icn_view_users"><a href="index.php/uactivities">User activities</a></li>
            <li class="icn_settings"><a href="#">Options</a></li>
            <li class="icn_new_article"><a href="#">New EDI</a></li>
			<li class="icn_security"><a href="index.php/vendormanagement">Vendor Management</a></li>
            <li class="icn_categories"><a href="index.php/catmanagement">Category Management</a></li>
			<li class="icn_categories">
                <a href="http://www.icuracao.com/DataFeed/index.php/preapprovekpi">KPIs</a>
            </li>
   			<li class="icn_categories">
                <a href="http://www.icuracao.com/DataFeed/index.php/preapprovekpiapple">Apple KPIs</a>
            </li>
		</ul>
   <?php }?>
    <h3><a href="index.php/logout">Logout</a></h3>
		<ul class="toggle">
			
			
			
		</ul>		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2012 Website Admin</strong></p>
			<p>Developed by <a href="http://www.iCuracao.com">iCuracao</a></p>
		</footer>
        </aside>