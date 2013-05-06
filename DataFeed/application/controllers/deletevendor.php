<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deletevendor extends CI_Controller {

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
		$this->load->model('User', '', TRUE);
		
		$msg  = $this->User->deletevendor();
		$content = $this->User->vendors();

		$data['content'] = $content;
		$data['msg'] = $msg;

		$this->load->view('template_header');
		if($this->session->userdata('logged_in')) {
			$this->load->view('sidebar_full');
			$this->load->view('deletevendor',$data);
						
		} else {
			redirect('/', 'refresh');
		} 
		
		$this->load->view('template_footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */