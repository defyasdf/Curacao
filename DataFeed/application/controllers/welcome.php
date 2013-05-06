<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
			$this->load->library('session');
			$this->load->helper('url');
			
			if($this->session->userdata('logged_in')) {
				redirect('/home', 'refresh');
			}
			
			$data['msg'] = '';
			if(isset($_GET['error'])){
				$data['msg'] = '<h4 class="alert_error">Login Information is incurrect</h4>';
			}else{
				$data['msg'] = '';
			}
			$this->load->view('template_header');
			$this->load->view('template_sidebar');
			$this->load->view('welcome_message',$data);
			$this->load->view('template_footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */