<?php
	
	$template['active_group'] = 'default';
	$template['default']['template'] = 'template.php';
	
	$template['default']['regions'] = array(
											  'header',
											  'title',
											  'content',
											  'sidebar',
											  'footer',
											);
											
											
	$template['default']['regions']['header'] = array('content' => array('<h1>CI Rocks!</h1>'));
	$template['default']['regions']['footer'] = array('content' => array('<p id="copyright">© Our Company Inc.</p>'));											
?>