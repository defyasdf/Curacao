<?php 

class Reports extends CI_Model {
	
	function getmineralreport(){
		$this->db->select('distinct(`campaign`) as campaign');
		$this->db->from('userActivitytrack');
		$this->db->where('campaign != ', '');
		$q = $this->db->get();			
		$data = array();
		foreach($q->result_array() as $row){
			$data[$row['campaign']]['count']=$this->getlanding_page($row['campaign']);
		
		}
		
		return $data;
	}
	
	function getmineralreportdetail(){
		
		$data = array();
		if(isset($_GET['keyword'])){
			$ky = str_replace('plssgn','+',$_GET['keyword']);
		}else{
			$KY = '';
		}
		$data[$_GET['campaign']]['count']=$this->getlanding_page($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['firstpage']=$this->getfirst_page($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['completefirstpage']=$this->getfirst_page_complete($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['authpass']=$this->getauth_pass($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['submitapp']=$this->getsubmitapp($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['approve']=$this->getApprove($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['pending']=$this->getpending($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['decline']=$this->getdecline($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['shoppingcart']=$this->getshoppingcart($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['checkout']=$this->getcheckout($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['finishcheckout']=$this->getfinishcheckout($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['totalamount']=$this->getTotalAmount($_GET['campaign'],$ky);
		$data[$_GET['campaign']]['cartamount']=$this->getCartAmount($_GET['campaign'],$ky);
		if(!isset($_GET['keyword'])){
			$data[$_GET['campaign']]['keyword']=$this->getKeywords($_GET['campaign']);
		}
	
		
		return $data;
	}
	
	 function getlanding_page($campaign,$keyword = ''){
			
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getfirst_page($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('first_page = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getfirst_page_complete($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('complete_first_page = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getauth_pass($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('pass_auth = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	  
	   function getsubmitapp($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$where = '`campaign` = "'.$campaign.'" AND (`pending_app` = 1 or `approve` = 1 or `decline` =1)';
			$this->db->where($where);
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	  
	   function getdecline($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('decline = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
		if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getpending($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('pending_app = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getApprove($campaign,$keyword = ''){
			
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('approve = ', '1');
			$this->db->where('campaign',$campaign);
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
// Shopping cart Entries	  	
	function getshoppingcart($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('shop = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
//Checkout Entries	  
	   function getcheckout($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('checkout = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	   function getCartAmount($campaign,$keyword = ''){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}	
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	  
//finishCheckout Entries	  
	   function getfinishcheckout($campaign,$keyword = ''){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('finishcheckout = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
// Get Total amount after checkout	  
	   function getTotalAmount($campaign,$keyword = ''){
			$this->db->select('sum(`checkoutAmount`) as totalcheckout');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('finishcheckout = ', '1');
			if($keyword!=''){
				$this->db->where('keyword',$keyword);
			}
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return $data[0]['totalcheckout']; 
	  }

// Get Total Key words	  
	   function getKeywords($campaign){
			$this->db->select('`keyword`');
			$this->db->select('count( * ) AS key_total');
			$this->db->from('userActivitytrack');
			$this->db->where('campaign',$campaign);
			$this->db->where('keyword !=','');
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
			if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			$this->db->group_by("keyword"); 
			
			$q = $this->db->get();			
			$data = array();
			foreach($q->result_array() as $row){
				$data[$row['keyword']]['count']=$row['key_total'];
			
			}
			
			return $data; 
	  }

	// Pre-approve Functions
	
	function getpreapprovereport(){
		
		$data = array();
		$data['count']=$this->getprecount();
		$data['step1']=$this->getstep1();
		$data['step2']=$this->getstep2();
		$data['approve']=$this->getpreApprove();
		$data['pending']=$this->getprepending();
		$data['decline']=$this->getpredecline();
		$data['shoppingcart']=$this->getpreshoppingcart();
		$data['checkout']=$this->getprecheckout();
		$data['finishcheckout']=$this->getprefinishcheckout();
		$data['totalamount']=$this->getpreTotalAmount();
		$data['cartamount']=$this->getpreCartAmount();
		$data['checkoutamount']=$this->getpreCheckAmount();
		$data['approvecreditline']=$this->getpreCreditline();
		
		return $data;
	}
	
	 function getprecount(){
			
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getstep1(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
		
			$this->db->where('step1 = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getstep2(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('step2 = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	  
	   function getpredecline(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('decline = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
		if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getprepending(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('pending = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getpreApprove(){
			
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('approve = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
// Shopping cart Entries	  	
	function getpreshoppingcart(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('shop = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
//Checkout Entries	  
	   function getprecheckout(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('checkout = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	   function getpreCartAmount(){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('shop = ', '1');
				
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	 function getpreCreditline(){
			$this->db->select('sum(`approvedcreditlimit`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('approve = ', '1');
				
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			$limit = (float)$data[0]['totalincart']/(int)$this->getpreApprove();
			return $limit; 
	  }
	  function getpreCheckAmount(){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('checkout = ', '1');
				
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	  
//finishCheckout Entries	  
	   function getprefinishcheckout(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('order_complete = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
// Get Total amount after checkout	  
	   function getpreTotalAmount(){
			$this->db->select('sum(`order_amount`) as totalcheckout');
			$this->db->from('preapproved');
			$this->db->where('order_complete = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return $data[0]['totalcheckout']; 
	  }
	
	
	// Apple Campaign
	
	// Pre-approve Functions
	
	function getapplepreapprovereport(){
		
		$data = array();
		$data['count']=$this->getappleprecount();
		$data['step1']=$this->getapplestep1();
		$data['step2']=$this->getapplestep2();
		$data['approve']=$this->getapplepreApprove();
		$data['pending']=$this->getappleprepending();
		$data['decline']=$this->getapplepredecline();
		$data['shoppingcart']=$this->getapplepreshoppingcart();
		$data['checkout']=$this->getappleprecheckout();
		$data['finishcheckout']=$this->getappleprefinishcheckout();
		$data['totalamount']=$this->getapplepreTotalAmount();
		$data['cartamount']=$this->getapplepreCartAmount();
		$data['checkoutamount']=$this->getapplepreCheckAmount();
		$data['approvecreditline']=$this->getapplepreCreditline();
		
		return $data;
	}
	
	 function getappleprecount(){
			
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getapplestep1(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->like('promo', 'ad', 'after'); 
			$this->db->where('step1 = ', '1');
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getapplestep2(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('step2 = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	  
	   function getapplepredecline(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('decline = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
		if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getappleprepending(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('pending = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getapplepreApprove(){
			
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('approve = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
// Shopping cart Entries	  	
	function getapplepreshoppingcart(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('shop = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
//Checkout Entries	  
	   function getappleprecheckout(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('checkout = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	   function getapplepreCartAmount(){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('shop = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
				
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	 function getapplepreCreditline(){
			$this->db->select('sum(`approvedcreditlimit`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('approve = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
				
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			$limit = (float)$data[0]['totalincart']/(int)$this->getapplepreApprove();
			return $limit; 
	  }
	  function getapplepreCheckAmount(){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('checkout = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
				
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	  
//finishCheckout Entries	  
	   function getappleprefinishcheckout(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('order_complete = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
// Get Total amount after checkout	  
	   function getapplepreTotalAmount(){
			$this->db->select('sum(`order_amount`) as totalcheckout');
			$this->db->from('preapproved');
			$this->db->where('order_complete = ', '1');
			$this->db->like('promo', 'ad', 'after'); 
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return $data[0]['totalcheckout']; 
	  }


	// Online Campaign
	
	// Pre-approve Functions
	
	function getonpreapprovereport(){
		
		$data = array();
		$data['count']=$this->getonprecount();
		$data['step1']=$this->getonstep1();
		$data['step2']=$this->getonstep2();
		$data['approve']=$this->getonpreApprove();
		$data['pending']=$this->getonprepending();
		$data['decline']=$this->getonpredecline();
		$data['shoppingcart']=$this->getonpreshoppingcart();
		$data['checkout']=$this->getonprecheckout();
		$data['finishcheckout']=$this->getonprefinishcheckout();
		$data['totalamount']=$this->getonpreTotalAmount();
		$data['cartamount']=$this->getonpreCartAmount();
		$data['checkoutamount']=$this->getonpreCheckAmount();
		$data['approvecreditline']=$this->getonpreCreditline();
		
		return $data;
	}
	
	 function getonprecount(){
			
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getonstep1(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
		
			$this->db->where('step1 = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getonstep2(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('step2 = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	  
	   function getonpredecline(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('decline = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
		if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getonprepending(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('pending = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getonpreApprove(){
			
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('approve = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
// Shopping cart Entries	  	
	function getonpreshoppingcart(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('shop = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
//Checkout Entries	  
	   function getonprecheckout(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('checkout = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);
			
			
			$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	   function getonpreCartAmount(){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('shop = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	 function getonpreCreditline(){
			$this->db->select('sum(`approvedcreditlimit`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('approve = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			$limit = (float)$data[0]['totalincart']/(int)$this->getonpreApprove();
			return $limit; 
	  }
	  function getonpreCheckAmount(){
			$this->db->select('sum(`amount`) as totalincart');
			$this->db->from('preapproved');
			$this->db->where('checkout = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return round($data[0]['totalincart'],2); 
	  }
	  
//finishCheckout Entries	  
	   function getonprefinishcheckout(){
			$this->db->distinct();
			$this->db->select('promo');
			$this->db->from('preapproved');
			$this->db->where('order_complete = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
// Get Total amount after checkout	  
	   function getonpreTotalAmount(){
			$this->db->select('sum(`order_amount`) as totalcheckout');
			$this->db->from('preapproved');
			$this->db->where('order_complete = ', '1');
			$where = "(`promo` LIKE 'aa%' OR `promo` LIKE 'ab%')";
			$this->db->where($where);$dT = explode('/',$_REQUEST['edate']);
			$dF = explode('/',$_REQUEST['sdate']);
			if(trim($_REQUEST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_REQUEST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();			
			$data = array();
			$data = $q->result_array();
			
			return $data[0]['totalcheckout']; 
	  }

	

}

