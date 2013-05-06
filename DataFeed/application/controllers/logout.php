<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
			$this->load->helper('url');
			$this->load->library('session');
			
			 $data = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has logged out' ,
               'date' => date('Y-m-d'),
			   'time' => date('g:i a')
            );
			
			$this->load->database();
			
		  $this->db->insert('useractivity', $data); 
			
			$array_items = array('fname' => '', 'lname' => '', 'level' => '', 'logged_in' => '');
			
			$this->session->unset_userdata($array_items);
			
				 redirect('/', 'refresh');
	
			
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */