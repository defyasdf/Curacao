<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preapprovekpi extends CI_Controller {

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
		$this->load->model('Product', '', TRUE);
		//$content = $this->Product->kpi_report();
		//$data['content'] = $content;

		$this->load->view('template_header_credit');
		$this->load->view('sidebar_full');
		$this->load->view('kpi_report_preapprove');	
		$this->load->view('template_footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */