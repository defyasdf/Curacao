<?php 
require_once '/var/www/html/DataFeed/src/apiClient.php';
require_once '/var/www/html/DataFeed/src/contrib/apiTranslateService.php';
require_once('/var/www/html/DataFeed/LanguageTranslator.php');

class Product extends CI_Model {
   
	  function kpi_report(){
		$data[] = array('landingpage'=>$this->getlanding_page(),'first_page'=>$this->getfirst_page(),'complete_first_page'=>$this->getfirst_page_complete(),'Auth_pass'=>$this->getauth_pass(),'Submit_app'=>$this->getsubmitapp(),'decline'=>$this->getdecline(),'approve'=>$this->getApprove(),'Pending'=>$this->getpending());
		  	 
		return $data;
	  }
	  function getlanding_page(){
			
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getfirst_page(){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			$this->db->where('first_page = ', '1');
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getfirst_page_complete(){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			$this->db->where('complete_first_page = ', '1');
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			
			return $q->num_rows(); 
	  }
	  
	   function getauth_pass(){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			$this->db->where('pass_auth = ', '1');
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	  
	   function getsubmitapp(){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$where = '`landing_page` = 1 AND (`pending_app` = 1 or `approve` = 1 or `decline` =1)';
			$this->db->where($where);
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	  
	   function getdecline(){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			$this->db->where('decline = ', '1');
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
		if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getpending(){
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			$this->db->where('pending_app = ', '1');
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	   function getApprove(){
			
			$this->db->select('*');
			$this->db->from('userActivitytrack');
			$this->db->where('landing_page = ', '1');
			$this->db->where('approve = ', '1');
			
			
			$dT = explode('/',$_POST['edate']);
			$dF = explode('/',$_POST['sdate']);
			
			if(trim($_POST['edate'])!=''){
				$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
				$this->db->where('created_date <= ', $to); 
				 
			}
		if(trim($_POST['sdate'])!=''){
				$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
				$this->db->where('created_date >= ', $from);
			}
			 
			
			$q = $this->db->get();
			return $q->num_rows(); 
	  }
	  
	   function auth_report(){
	    
		$dT = explode('/',$_POST['edate']);
		$dF = explode('/',$_POST['sdate']);
		
		$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
		$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
		
		$this->db->select('*');
		$this->db->from('curacao_cust_tracking');
		$this->db->where('checkoutdate >= ', $from);  
		$this->db->where('checkoutdate <= ', $to);  
		$q = $this->db->get();
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
    }
	
	 function credit_report(){
	    
		$dT = explode('/',$_POST['edate']);
		$dF = explode('/',$_POST['sdate']);
		
		$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
		$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
		
		$this->db->select('*');
		$this->db->from('credit_app');
		$this->db->where('applied_date >= ', $from);  
		$this->db->where('applied_date <= ', $to);  
		$q = $this->db->get();
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
    }

function exportcreditreport(){

		$to = $_GET['edate'];
		$from = $_GET['sdate'];
		
		$this->db->select('*');
		$this->db->from('credit_app');
		$this->db->where('applied_date >= ', $from);  
		$this->db->where('applied_date <= ', $to);  
		$q = $this->db->get();			
		$status = array(
						'0'=>'not applied',
						'1'=>'Approve',
						'2'=>'Pending',
						);	
						
		foreach($q->result_array() as $row){
			
			if($row['is_lexis_nexus_complete']){$l = 'yes'; }else{$l = 'no';}
			if($row['is_validate_address_complete']){$v = 'yes'; }else{$v = 'no';}
						
				$data[] = array("ID"=>$row['credit_id'], "First_Name"=>$row['firstname'], "Last_Name"=>$row['lastname'],  "Phone_1"=>$row['telephone'], "Phone_2"=>$row['telephone2'], "Email"=>$row['email_address'],"Street"=>$row['address1'].' '.$row['address2'],"City"=>$row['city'],"State"=>$row['state'],"Zip"=>$row['zipcode'], "Years_living"=>$row['res_year'],"Months_living"=>$row['res_month'],"SSN"=>$row['ssn'],"DOB"=>$row['dob'],"ID_number"=>$row['id_num'],"ID_type"=>$row['id_type'],"ID_EXP"=>$row['id_expire'],"Maiden_Name"=>$row['maiden_name'],"Company"=>$row['company'],"Work_phone"=>$row['work_phone'],"work_year"=>$row['work_year'],"work_month"=>$row['work_month'],"IP"=>$row['ip_address'],"Salary"=>$row['salary'],"Laxus_complete"=>$l,"Address_complete"=>$v,"Webapplication_status"=>$status[$row['is_web_customer_application_complete']],"Applied_Date"=>$row['applied_date']);
	
			}


	  $filename = "Credit_report_" . date('Ymd') . ".xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	  exit;
	}	
	
function exportauthreport(){

		$to = $_GET['edate'];
		$from = $_GET['sdate'];
		
		$this->db->select('*');
		$this->db->from('curacao_cust_tracking');
		$this->db->where('checkoutdate >= ', $from);  
		$this->db->where('checkoutdate <= ', $to);  
		$q = $this->db->get();			
		
						
		foreach($q->result_array() as $row){
			
						
				$data[] = array("ID"=>$row['trackId'], "custnumber"=>str_replace("_",'-',$row['cust_number']), "ccv"=>$row['ccv'],  "custzip"=>$row['cust_zip'], "custdob"=>$row['custdob'], "custssn"=>$row['cust_ssn'],"custmaiden"=>$row['cust_maiden'],"amount"=>$row['cust_amount'],"ARresponse"=>str_replace("_",'-',$row['ar_response']),"downpaymentReq"=>$row['downpayment_req'], "downpayment"=>$row['downpayment'],"OrderComplete"=>$row['order_complete'],"checkoutdate"=>$row['checkoutdate']);
	
			}


	  $filename = "Authentication_report_" . date('Ymd') . ".xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	  exit;
	}	
	function search_products(){
		
		$this->db->select('mpt_id, prduct_name, product_sku, product_upc, product_brand, product_source, status');
		$this->db->from('masterproducttable');
		$this->db->where('status != ', 1);
		if(strtolower($this->session->userdata('lname')) == 'vindia'){
			$this->db->where('status = ', 3);
		}
		$q = $this->db->get();
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has searched data' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	}
	
	function filtered_products(){
		$this->db->select('mpt_id, prduct_name, product_sku, product_upc, product_brand, product_source, status');
		$this->db->from('masterproducttable');
		$this->db->where('status != ', 1);
		
		if(isset($_GET['brandname'])){
			$this->db->where('product_brand', $_GET['brandname']);
		}
		
		if(isset($_GET['vendor'])){
			$this->db->where('product_source', $_GET['vendor']);
		}
		
		if(isset($_GET['upc'])){
			$this->db->where('product_upc', $_GET['upc']);
		}
		
		$q = $this->db->get();
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	}
	
	function cleandata($string){
		$s = explode(' ',trim(str_replace('<br>','',str_replace('\r\n','',$string))));
			for($i=0;$i<sizeof($s);$i++){
				if(trim($s[$i])==''){
					unset($s[$i]);
				}
			}
			$str = '';
			foreach($s as $v){
				if(trim($v)!=''){
					$str .= trim( preg_replace( '/\s+/', ' ', $v)).' ';
				}
			}
			
			return $str;
	}
	
	function getSortData(){
		
		$this->db->select('product_brand');
		$this->db->from('masterproducttable');
		$this->db->group_by('product_brand');
		$q = $this->db->get();
		foreach($q->result_array() as $row){
			$brand[] = $row['product_brand'];
		}
		
		$this->db->select('product_source');
		$this->db->from('masterproducttable');
		$this->db->group_by('product_source');
		$q = $this->db->get();
		foreach($q->result_array() as $row){
			$source[] = $row['product_source'];
		}
		
		$data = array();
		$data['brand'] = $brand;
		$data['source'] = $source;
		
		return $data;
		
	}
	
	function exportproducts(){
		$v = explode(',',$_GET['vals']);
		$data = array();
		for($i=0;$i<sizeof($v);$i++){

			$id = $v[$i];
			$this->db->select('*');
			$this->db->from('masterproducttable');
			$this->db->where('status !=', 1);
			$this->db->where('status !=', 5);
			$this->db->where('status !=', 6);
			$this->db->where('mpt_id', $id);
			
			$q = $this->db->get();
			
			foreach($q->result_array() as $row){
			
				$str = $this->cleandata($row['product_description']);
				$features = $this->cleandata($row['product_features']);
				$specs = $this->cleandata($row['product_specs']);			
						
				$data[] = array("name"=>$row['prduct_name'], "description"=>$str, "sku"=>$row['product_sku'],  "UPC"=>$row['product_upc'], "Inventorylevel"=>$row['product_inventory_level'], "Brand"=>$row['product_brand'],"imgpath"=>$row['product_img_path'],"features"=>$features,"source"=>$row['product_source'],"specs"=>$specs,"product_cost"=>$row['product_cost'],"product_retail"=>$row['product_retail'],"product_msrp"=>$row['product_msrp'],"product_map"=>$row['product_map'],"product_qty"=>$row['product_qty'],"priority"=>$row['priority']);
	
			}
		}
		
		
		
		
	  $filename = "product_data_" . date('Ymd') . ".xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	  exit;
	}
	
	
function exportprod(){
	
		$v = explode(',',$_GET['vals']);
		$data = array();
	
		for($i=0;$i<sizeof($v);$i++){

			$id = $v[$i];
			$this->db->select('*');
			$this->db->from('finalproductlist');
			$this->db->where('status !=', 2);
			$this->db->where('fpl_id', $id);
			
			$q = $this->db->get();
			
			foreach($q->result_array() as $row){
									
				$data[] = array("product_id"=>$row['fpl_id'], "spanishdata_id"=>$row['spenish_id'], "name"=>$row['prduct_name'], "sku"=>$row['product_sku'],  "UPC"=>$row['product_upc'], "Inventorylevel"=>$row['product_inventory_level'], "Brand"=>$row['product_brand'],"MSRP"=>$row['product_msrp'], "COST"=>$row['product_cost'], "Retail"=>$row['product_retail'], "Quantity"=>$row['product_qty'],"magento_category_id"=>$row['magento_category_id']);
	
			}
		}
	  $filename = "final_product_data_" . date('Ymd') . ".xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	  exit;
	}


	function exportallprods(){

			//$id = $v[$i];
			$this->db->select('*');
			$this->db->from('finalproductlist');
			//$this->db->where('status !=', 2);
			$this->db->where('status', '1');
			$q = $this->db->get();
			
			foreach($q->result_array() as $row){
									
				$data[] = array("product_id"=>$row['fpl_id'], "spanishdata_id"=>$row['spenish_id'], "name"=>$row['prduct_name'], "sku"=>$row['product_sku'],  "UPC"=>$row['product_upc'], "Inventorylevel"=>$row['product_inventory_level'], "Brand"=>$row['product_brand'],"MSRP"=>$row['product_msrp'], "COST"=>$row['product_cost'], "Retail"=>$row['product_retail'], "Quantity"=>$row['product_qty'],"magento_category_id"=>$row['magento_category_id']);
	
			}
		
	  $filename = "all_final_product_data_" . date('Ymd') . ".xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	  exit;
	}
	


	
	
	function pending_products(){
		
		$this->db->select('mpt_id, prduct_name, product_sku, product_upc, product_brand, product_source, status,priority,comment');
		$this->db->from('masterproducttable');
		$this->db->where('status', 2);
		$this->db->group_by('product_sku');
		$this->db->order_by("priority", "desc"); 
		$q = $this->db->get();
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has been in pending data' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	}
	
	function pending_transition_products(){
		
		$this->db->select('prduct_name, product_sku, product_upc, product_brand, product_source,comment');
		$this->db->from('finalproductlist');
		$this->db->where('status', 0);
		$this->db->group_by('product_upc');
		$this->db->order_by("priority", "desc"); 
		$q = $this->db->get();
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has been in pending transition product section' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
				
				$this->db->select('prduct_name, product_sku, product_upc');
				$this->db->from('finalproductlist');
				$this->db->where('status', 1);
				$this->db->where('product_upc', $row->product_upc);
				$q1 = $this->db->get();				
				if($q1->num_rows()==0){
                	$data[] = $row;
				}
            }
        	return $data;
        }
		
	}
	
	
	function translate($text){
		
		$string = file_get_contents('https://www.googleapis.com/language/translate/v2?key=AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ&q='.str_replace(' ','%20',trim ($text)).'&source=en&target=es');
		
		$json = json_decode($string, true);
		
		return $json['data']['translations'][0]['translatedText'];
	}
	
	
	function inprocess_products(){
		
		$this->db->select('mpt_id, prduct_name, product_sku, product_upc, product_brand, product_source, status');
		$this->db->from('masterproducttable');
		$this->db->where('status', 4);
		$this->db->group_by('product_sku');
		$this->db->order_by("priority", "desc"); 
		$q = $this->db->get();
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has been in in process section' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	}
	
	function archieved_products(){
		
		$this->db->select('mpt_id, prduct_name, product_sku, product_upc, product_brand, product_source, status');
		$this->db->from('masterproducttable');
		$this->db->where('status', 1);
		$q = $this->db->get();
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has accessed all archieved data' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		
        	return $data;
        }
		
	}
	
	function productwiz(){
	
		$data = array(
		   'status' => '4'
		);
		$this->db->where('product_upc', $_GET['sku']);
		$this->db->update('masterproducttable', $data);
		
		$this->db->select('*');
		$this->db->from('masterproducttable');
		$this->db->where('product_upc', $_GET['sku']);
//		$this->db->limit(1);
		$q = $this->db->get();
		
		if($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                //$data[''] = $row;
				
				$pname[] = $row['prduct_name'];
				$psku[] = $row['product_sku'];
				$pupc[] = $row['product_upc'];
				$pcost[] = $row['product_cost'];
				
				$pretail[] = $row['product_retail'];
				$pmsrp[] = $row['product_msrp'];
				$pmap[] = $row['product_map'];
				$pimg[] = $row['product_img_path'];
				$pId[] = $row['mpt_id'];
				$brand[] = $row['product_brand'];
				$pdesc[] = $row['product_description'];
				$pfeature[] = $row['product_features'];
				$pspecs[] = $row['product_specs'];
				
				$etilizeproid = $row['etilizeProId'];
				
				$sql = 'select vendorName from vendormanagement where vendorID = "'.$row['product_source'].'"';
				$re = mysql_query($sql);
				$ro = mysql_fetch_array($re);
				$psource[] = $ro['vendorName'];
            }

			$data['pname'] =	$pname;
			$data['psku'] = $psku;
			$data['pupc'] = $pupc;
			$data['pcost'] = $pcost;
			$data['pretail'] = $pretail;
			$data['pmsrp'] = $pmsrp;
			$data['pmap'] = $pmap;
			$data['psource'] = $psource;
			$data['pimg'] = $pimg;
			$data['brand'] = $brand;
			$data['pId'] = $pId;
			$data['pdesc'] = $pdesc;
			$data['pfeature'] = $pfeature;
			$data['pspecs'] = $pspecs;
			
			$data['etilizeName'] = '';
			$data['etilizeDesc'] = '';
			$data['etilizeFeature'] = '';
			$data['etilizeSpecs'] = '';
			$data['etilizeimg']	= '';		
			
			if($etilizeproid!='0'){
			
			$conn1 = mysql_connect('localhost','root','',true);
			mysql_select_db('etilizeproduct',$conn1);
			
				$s = 'SELECT a.name, pa.`displayvalue`
						FROM `productattribute` AS pa, attributenames AS a
						WHERE pa.`attributeid` = a.`attributeid`
						AND pa.`productid` ='.$etilizeproid.'
						AND pa.`localeid` =1
						GROUP BY pa.`attributeid` , `displayvalue`';
						
					$sr = mysql_query($s,$conn1);
					$specs = array();
					while($sro = mysql_fetch_array($sr)){
						if(array_key_exists($sro['name'],$specs)){
							$specs[$sro['name']] = $specs[$sro['name']].','.$sro['displayvalue'];
						}else{
							$specs[$sro['name']] = $sro['displayvalue'];
						}
						
					}
					
					$p = 'select description from productdescriptions where productid = "'.$etilizeproid.'" and localeid = "1" and isdefault = "1"';
					$pr = mysql_query($p,$conn1);
					$pro = mysql_fetch_array($pr);
								
					$b = 'SELECT p.`manufacturerid` , mf.name FROM `product` AS p, manufacturer AS mf WHERE p.`manufacturerid` = mf.`manufacturerid` AND p.productid ="'.$etilizeproid.'"' ;
					//$b = 'select name from manufacturer where productid = "'.$etilizeproid.'" and localeid = "1" and isdefault = "1"';
					
					$br = mysql_query($b,$conn1);
					$bro = mysql_fetch_array($br);
					
					
					$i = 'SELECT type FROM `productimages`WHERE `productid` = "'.$etilizeproid.'"';
					$ir = mysql_query($i,$conn1);
					$img = array();
					while($iro = mysql_fetch_array($ir)){
						
						$img[] = 'http://content.etilize.com/'.$iro['type'].'/'.$etilizeproid.'.jpg';					
						// To save the image please follow the line of code below
						/*$url = 'http://content.etilize.com/'.$iro['type'].'/'.$etilizeproid.'.jpg';
						$img = 'images/'.$etilizeproid.'.jpg';
						file_put_contents($img, file_get_contents($url));*/
					}
   					
					
				 $data['etilizeName'] = $pro['description'];
				 $data['etilizeDesc'] = $specs['Marketing Information'];
				 $data['etilizeFeature'] = $specs['Package Contents'];
				 
				 $new = $specs;
					
				  unset($new['Marketing Information']);
				  unset($new['Package Contents']);
				 
				 $data['etilizeSpecs'] = $new;
				 $data['etilizeimg']	= $img;	
				
					
			}
			

		
		
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has initiated a product wizard' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		
		
		return $data;

        }
	}
	
	function archieveproducts(){
		$v = explode(',',$_GET['vals']);
		$err = 0;
		for($i=0;$i<sizeof($v);$i++){
			
			$id = $v[$i];
			
			$data = array(
               'status' => '1'
            );
			
			$this->db->where('mpt_id', $id);
			if($this->db->update('masterproducttable', $data)){
				
				$err = 0;
			}else{
				$err = 1;
			}
		}	
		if($err == 0){	
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has archieved products' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = '<h4 class="alert_success">Products Archieved Successfully  <a href="index.php/contentsearch">Click here to see result</a></h4>';
		}else{
			$msg = '<h4 class="alert_error">Products are not Archieved successfully</h4>';
		}
		
		return $msg;
	}

   function resetproducts(){
		$v = explode(',',$_GET['vals']);
		$err = 0;
		for($i=0;$i<sizeof($v);$i++){
			
			$id = $v[$i];
			
			$data = array(
               'status' => '3'
            );
			
			$this->db->where('mpt_id', $id);
			if($this->db->update('masterproducttable', $data)){
				$err = 0;
			}else{
				$err = 1;
			}
		}	
		if($err == 0){	
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has reseted products' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = '<h4 class="alert_success">Products reseted Successfully  <a href="index.php/archievelist">Click here to see result</a></h4>';
		}else{
			$msg = '<h4 class="alert_error">Products are not reseted successfully</h4>';
		}
		
		return $msg;
	}


	
	function revertproducts(){
		
		if(isset($_POST['comment'])){
			$data = array(
			   'status' => '2',
			   'comment' => $_POST['comment']
			);
		}else{
			$data = array(
			   'status' => '2'
			);
		}
		
		
		
		$this->db->where('product_upc', $_POST['psku']);
		if($this->db->update('masterproducttable', $data)){
			
			$this->db->where('product_upc', $_POST['psku']);
			$this->db->delete('finalproductlist'); 
			
			$this->db->where('product_upc', $_POST['psku']);
			$this->db->delete('spenishdata'); 
			
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has reverted the product' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
				
			$msg = 1;
		}else{
			$msg = 0;
		}
		
		return $msg;
	}
	
	function deleteproducts(){
		$v = explode(',',$_GET['vals']);
		$err = 0;
		for($i=0;$i<sizeof($v);$i++){
			
			$id = $v[$i];
			
			$this->db->select('product_sku, product_upc, product_source');
			$this->db->from('masterproducttable');
			$this->db->where('mpt_id', $id);
			
			$q = $this->db->get();
			
			
			
			if($q->num_rows() > 0) {
				foreach ($q->result_array() as $row) {
					$data = array(
						   'upc' => $row['product_upc'] ,
						   'sku' => $row['product_sku'] ,
						   'vendor' => $row['product_source']
						);
						
						if($this->db->insert('deletedproducts', $data)){
							$err = 0;
						} 
				}
			}
					
			
			if($this->db->delete('masterproducttable', array('mpt_id' => $id))){
				$err = 0;
			}else{
				$err = 1;
			}
			
		}	
		if($err == 0){	
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has deleted products' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = '<h4 class="alert_success">Products Deleted Successfully  <a href="index.php/contentsearch">Click here to see result</a></h4>';
		}else{
			$msg = '<h4 class="alert_error">Products are not deleted successfully</h4>';
		}
		
		return $msg;
	}	
	
	function processproducts(){
		$v = explode(',',$_GET['vals']);
		$err = 0;
		for($i=0;$i<sizeof($v);$i++){
			
			$id = $v[$i];
			
			$data = array(
               'status' => '2',
			   'priority' => $_GET['priority']
            );
			
			$this->db->where('mpt_id', $id);
			$this->db->where('status', '3');
			if($this->db->update('masterproducttable', $data)){
				$err = 0;
			}else{
				$err = 1;
			}
		}	
		if($err == 0){	
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has process products' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = '<h4 class="alert_success">Products Processed Successfully  <a href="index.php/contentsearch">Click here to see result</a></h4>';
		}else{
			$msg = '<h4 class="alert_error">Products are not processed successfully</h4>';
		}
		
		return $msg;
	}
	
	
	
	function addtomagentoque(){
		$v = explode(',',$_GET['vals']);
		$err = 0;
		for($i=0;$i<sizeof($v);$i++){
			
			$id = $v[$i];
			
			$data = array(
               'status' => '2',
	         );
			$this->db->where('fpl_id', $id);
			if($this->db->update('finalproductlist', $data)){
				$err = 0;
				
				$da = array(
							 'finalproId'=>$id,
							 'mstatus'=> '0'
							);
							
			 $this->db->insert('magentoque', $da); 
				
			}else{
				$err = 1;
			}
		}	
		if($err == 0){	
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has added the product to magento' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = '<h4 class="alert_success">Products Added to queue Successfully  <a href="index.php/approved">Click here to see result</a></h4>';
		}else{
			$msg = '<h4 class="alert_error">Products not addedd successfully</h4>';
		}
		
		return $msg;
	}
	
	
	


	function translateplaintext($str){
	
		$client = new apiClient();
		$client->setApplicationName('Google Translate PHP Starter Application');
		
		// Visit https://code.google.com/apis/console?api=translate to generate your
		// client id, client secret, and to register your redirect uri.
		$client->setDeveloperKey('AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ');
		$service = new apiTranslateService($client);
		
		$translations = $service->translations->listTranslations($str, 'es');
		//print "<h1>Translations</h1><pre>" . print_r($translations, true) . "</pre>";
		
		
		return $translations['translations'][0]['translatedText'];
	
	}


	function splitstring($str){
		
		$client = new apiClient();
		$client->setApplicationName('Google Translate PHP Starter Application');
		
		// Visit https://code.google.com/apis/console?api=translate to generate your
		// client id, client secret, and to register your redirect uri.
		$client->setDeveloperKey('AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ');
		$service = new apiTranslateService($client);
		$s = preg_split ('/$\R?^/m', $str);
		$v = array();
		for($i=0;$i<sizeof($s);$i++){
			
			$translations = $service->translations->listTranslations($s[$i], 'es');
			
			$v[] = $translations['translations'][0]['translatedText'];
						
			//$v[] = $this->translate(htmlspecialchars_decode($s[$i]));
		}
		
		$string =  implode('<br>',$v);
		
		return $string;

	}


	function processhtml($htmlContent){
		
			
		if(str_replace("<","",str_replace(">","",str_replace("/","",substr(trim($htmlContent),0,5))))=='br'){
			return '';
		}
		$html_tag = substr(trim($htmlContent),1,strpos($htmlContent,'>'));
		
		
		
		$result = '';
//		if(str_replace(">","",$html_tag)=='table'){
		if(substr($html_tag,0,5)=='table'){
		
			$dom = new DOMDocument;
			$dom->loadHTML( $htmlContent );
			$rows = array();
			foreach( $dom->getElementsByTagName( 'tr' ) as $tr ) {
				$cells = array();
				foreach( $tr->getElementsByTagName( 'td' ) as $td ) {
					$cells[] = $td->nodeValue;
				}
				$rows[] = $cells;
			}
		}
			$result .= '<table>';
			for($i=0;$i<sizeof($rows);$i++){
				$result .= '<tr>';
				
				for($j=0;$j<sizeof($rows[$i]);$j++){
					
					$String = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $rows[$i][$j]);
					
					$result .= '<td>'.$this->translate($String).'</td>';
					//exit;
				}
					
				$result .= '</tr>';
			}
			
			$result .= '</table>';
			
			return $result;
	}




	function unapprove(){
		if(isset($_POST['comment'])){
			
			$data = array(
			   'status' => '0',
			   'comment' => $_POST['comment']
			);
		}else{
			$data = array(
			   'status' => '0'
			);
		}
		$this->db->where('fpl_id ', $_POST['fpt_id']);
		
		if($this->db->update('finalproductlist', $data)){
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has unapprove the product' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = 1;
		}else{
			$msg = 0;
		}
		return $msg;
	}
	function saveenglish(){
		$data = array(
						'prduct_name'=>$_POST['pName'],
						'product_description'=>$_POST['pDesc'],
						'product_sku'=>$_POST['psku'],
						'product_upc'=>$_POST['pupc'],
						'product_msrp'=>'something',
						'product_map'=>'some',
						'product_brand'=>$_POST['pbrand'],
						'product_img_path'=>$_POST['pimg'],
						'product_features'=>$_POST['pFeature'],
						'product_source' => '3512',
						'product_specs'=>$_POST['pSpecs']
					);
					
			if($this->db->insert('masterproducttable', $data)){
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has save english ready version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = 1;
		
		}else{
			$msg = 0;
		}
	
		return $msg;
	}
	function spanishready(){
	
		$data = array(
						'prduct_name'=>$_POST['pName'],
						'product_description'=>$_POST['pDesc'],
						'product_features'=>$_POST['pFeature'],
						'product_specs'=>$_POST['pSpecs']
					);
					
		$this->db->where('sppr_id', $_POST['sppr_id']);
		if($this->db->update('spenishdata', $data)){
			
		$data = array(
		   'status' => '1'
		);
			$this->db->where('fpl_id ', $_POST['fpt_id']);
			
		if($this->db->update('finalproductlist', $data)){
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has created spanish ready version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = 1;
		}
		}else{
			$msg = 0;
		}
	
		return $msg;
		
	}	
	
	function spanishsave(){
	
		$data = array(
						'prduct_name'=>htmlspecialchars($_POST['pName'],ENT_QUOTES),
						'product_description'=>htmlspecialchars($_POST['pDesc'],ENT_QUOTES),
						'product_features'=>htmlspecialchars($_POST['pFeature'],ENT_QUOTES),
						'product_specs'=>htmlspecialchars($_POST['pSpecs'],ENT_QUOTES)
					);
					
		$this->db->where('sppr_id', $_POST['sppr_id']);
		if($this->db->update('spenishdata', $data)){
			
		
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has saved spanish ready version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = 1;
		
		}else{
			$msg = 0;
		}
	
		return $msg;
		
	}	
	
	
	function engsave(){
	
		$data = array(
						'prduct_name'=>htmlspecialchars($_POST['pName'],ENT_QUOTES),
						'product_description'=>htmlspecialchars($_POST['pDesc'],ENT_QUOTES),
						'product_features'=>htmlspecialchars($_POST['pFeature'],ENT_QUOTES),
						'product_img_path'=>$_POST['img'],
						'product_specs'=>htmlspecialchars($_POST['pSpecs'],ENT_QUOTES)
					);
					
		$this->db->where('fpl_id', $_POST['fpt_id']);
		if($this->db->update('finalproductlist', $data)){
			
			$data = array(
						'product_img_path'=>$_POST['img']
					);
					
		$this->db->where('sppr_id', $_POST['sppr_id']);
		$this->db->update('spenishdata', $data);
			
			 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has saved English version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
			
			$msg = 1;
		
		}else{
			$msg = 0;
		}
	
		return $msg;
		
	}	
	
	function translatecontent(){
		
		return $this->splitdesc($_POST['tex']);
	
	}
	
	function translatecategory(){
		$sql = "SELECT * FROM `categories`";
	
		$resu = mysql_query($sql);
		echo '<table>';
		echo '<tr><td>English</td><td>Spanish</td></tr>';
		while($row = mysql_fetch_array($resu)){
			$q = 'update categories set spanish_name = "'.$this->splitdesc($row['name']).'" where id = '.$row['id'];
			if(mysql_query($q)){
				echo '<tr><td>'.$row['name'].'</td><td>'.$this->splitdesc($row['name']).'</td></tr>';
			}
		}
	}
	
	
	function splitdesc($desc){
		
		
 
		$yourApiKey = 'AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ';
	 
		$sourceData = $desc;
		$source = 'en';
	 
		$target = 'es';
	 
		$translator = new LanguageTranslator($yourApiKey);
	 
		$targetData = $translator->translate($sourceData, $target, $source);
		
		return $targetData;	
	
	}
	
	function translatedspanish(){
	
	$s = "SELECT * FROM `englishready`";
	$rs = mysql_query($s);
	while($ros = mysql_fetch_array($rs)){

	$sql = "INSERT INTO `finalproductlist` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`,  `product_user`, `product_specs`,`product_source`,`status`, `priority`) VALUES ('".$ros['prduct_name']."', '".$ros['product_description']."', '".$ros['product_sku']."', '".$ros['product_upc']."',  '".$ros['product_msrp']."', '".$ros['product_map']."', '".$ros['product_brand']."', 'http://www.lacuracao.com/images/".$ros['product_img_path']."', '".$ros['product_features']."',  '', '".$ros['product_specs']."','".$ros['product_source']."','12', '".$ros['priority']."')";


	if(mysql_query($sql)){
		
		
		$q = 'SELECT fpl_id FROM `finalproductlist` ORDER BY fpl_id DESC LIMIT 0 , 1';
		$r = mysql_query($q);
		$ro = mysql_fetch_array($r);
		$desc = '';
		$ftr = '';
		$spc = '';
			
	$es = "SELECT * FROM `spenishready` where product_sku = '".$ros['product_sku']."'";
	$ers = mysql_query($es);
	$es = mysql_fetch_array($ers);


		
 $sql1 = "INSERT INTO `spenishdata` (`eng_id`,`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`,  `product_user`, `product_specs`,`product_source`,`status`, `priority`) VALUES ('".$ro['fpl_id']."','".$es['prduct_name']."', '".$es['product_description']."', '".$ros['product_sku']."', '".$ros['product_upc']."', '".$es['product_msrp']."', '".$es['product_map']."', '".$es['product_brand']."', 'http://www.lacuracao.com/images/".$es['product_img_path']."', '".$es['product_features']."', '', '".$es['product_specs']."','".$es['product_source']."','12', '".$es['priority']."')";
		
		mysql_query($sql1);
		
		$q1 = "select * from spenishdata ORDER BY `sppr_id` DESC limit 0,1";
		$r01 = mysql_query($q1);
		$ro1 = mysql_fetch_array($r01);

		$q2	= "UPDATE `finalproductlist` SET `spenish_id` = '".$ro1['sppr_id']."' WHERE `fpl_id` =".$ro['fpl_id'];
		mysql_query($q2);
		
		$query = 'select mpt_id from masterproducttable where product_sku = "'.$ros['product_sku'].'"';
		$re = mysql_query($query);
		while($r = mysql_fetch_array($re)){
			$q = "UPDATE `masterproducttable` SET `status` = '5' WHERE `mpt_id` =".$r['mpt_id'];
			mysql_query($q);
		}
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has created english ready version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		  
		$q1 = "UPDATE `product_queue` SET `status` = '1' WHERE `pq_id` =".$ros['pq_id'];
		mysql_query($q1);
		
		$msg = '<h4 class="alert_success">A Product Created Successfully</h4>';
	}else{
		$msg = '<h4 class="alert_error">Product is not inserted successfully</h4>';
	}
	}
	
	return $msg;

	}

	
	
	function translatespanish(){
	
	//$s = "select * from product_queue where status = 0 and product_source = '3724' order by priority desc limit 0,420";
	$s = "SELECT * FROM `product_queue` where status = '0'";
	$rs = mysql_query($s);
	while($ros = mysql_fetch_array($rs)){
	
	echo $sql = "INSERT INTO `finalproductlist` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`,  `product_user`, `product_specs`,`product_source`,`status`, `priority`) VALUES ('".$ros['prduct_name']."', '".$ros['product_description']."', '".$ros['product_sku']."', '".$ros['product_upc']."',  '".$ros['product_msrp']."', '".$ros['product_map']."', '".$ros['product_brand']."', '".$ros['product_img_path']."', '".$ros['product_features']."',  '', '".$ros['product_specs']."','".$ros['product_source']."','0', '".$ros['priority']."')";


	if(mysql_query($sql)){
		
		
	    $q = 'SELECT fpl_id FROM `finalproductlist` ORDER BY fpl_id DESC LIMIT 0 , 1';
		$r = mysql_query($q);
		$ro = mysql_fetch_array($r);
		$desc = '';
		$ftr = '';
		$spc = '';
		
		if(trim($ros['product_features'])!=''){
			//if(substr(trim(htmlspecialchars_decode($ros['product_features']),ENT_QUOTES),0,1)=='<'){
				//$ftr =  htmlspecialchars($this->processhtml(htmlspecialchars_decode($ros['product_features'],ENT_QUOTES)));			
			//}else{
				$ftr = $this->splitdesc($ros['product_features']);			
			//}
		}
		
		//if(substr(trim(htmlspecialchars_decode($ros['product_specs']),ENT_QUOTES),0,1)=='<'){
			
			//if(substr(trim(htmlspecialchars_decode($ros['product_specs'],ENT_QUOTES)),0,1)=='<'){
				
				//$spc =  htmlspecialchars($this->processhtml(htmlspecialchars_decode($ros['product_specs'],ENT_QUOTES)),ENT_QUOTES);			
			//}else{
				
				$spc = $this->splitdesc($ros['product_specs']);
			//}
			
			
		//}
		
		
	
		
 $sql1 = "INSERT INTO `spenishdata` (`eng_id`,`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`,  `product_user`, `product_specs`,`product_source`,`status`, `priority`) VALUES ('".$ro['fpl_id']."','".$this->splitdesc(htmlspecialchars($ros['prduct_name'],ENT_QUOTES))."', '".$this->splitdesc(htmlspecialchars($ros['product_description'],ENT_QUOTES))."', '".$ros['product_sku']."', '".$ros['product_upc']."', '".$ros['product_msrp']."', '".$ros['product_map']."', '".$ros['product_brand']."', '".$ros['product_img_path']."', '".$ftr."', '', '".$spc."','".$ros['product_source']."','0', '".$ros['priority']."')";
		
		mysql_query($sql1) or die("not inserted due to : ");
		
		$q1 = "select * from spenishdata ORDER BY `sppr_id` DESC limit 0,1";
		$r01 = mysql_query($q1);
		$ro1 = mysql_fetch_array($r01);

		$q2	= "UPDATE `finalproductlist` SET `spenish_id` = '".$ro1['sppr_id']."' WHERE `fpl_id` =".$ro['fpl_id'];
		mysql_query($q2) or die("not updated final");
		
		$query = 'select mpt_id from masterproducttable where product_sku = "'.$ros['product_sku'].'"';
		$re = mysql_query($query);
		while($r = mysql_fetch_array($re)){
			$q = "UPDATE `masterproducttable` SET `status` = '5' WHERE `mpt_id` =".$r['mpt_id'];
			mysql_query($q) or die("not updated master");
		}
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has created english ready version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		  
		$q1 = "UPDATE `product_queue` SET `status` = '1' WHERE `pq_id` =".$ros['pq_id'];
		mysql_query($q1);
		
		$msg = '<h4 class="alert_success">A Product Created Successfully</h4>';
	}else{
		$msg = '<h4 class="alert_error">Product is not inserted successfully</h4>';
	}
	}
	
	return $msg;

	}
	
	
	function englishready(){
	
		$query = 'select priority from masterproducttable where product_upc = "'.$_POST['pupc'].'"';
		$re = mysql_query($query);
		$ro = mysql_fetch_array($re);
		
		$sql = "INSERT INTO `product_queue` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`, `product_source`, `product_user`, `product_specs`,`status`, `priority`) VALUES ('".htmlspecialchars($_POST['pName'],ENT_QUOTES)."', '".htmlspecialchars($_POST['pDesc'],ENT_QUOTES)."', '".$_POST['pSku']."', '".$_POST['pupc']."',  '".$_POST['pmsrp']."', '".$_POST['pMAP']."', '".$_POST['pBrand']."', '".$_POST['pimg1']."', '".htmlspecialchars($_POST['pFeature'],ENT_QUOTES )."', '', 'sanjay', '".htmlspecialchars($_POST['pSpecs'],ENT_QUOTES )."','0', '".$ro['priority']."')";

	if(mysql_query($sql)){
		
		
	/*	$q = 'SELECT fpl_id FROM `finalproductlist` ORDER BY fpl_id DESC LIMIT 0 , 1';
		$r = mysql_query($q);
		$ro = mysql_fetch_array($r);
		$desc = '';
		$ftr = '';
		$spc = '';
		if(trim($_POST['pFeature'])!=''){
			if(substr(trim(htmlspecialchars_decode($_POST['pFeature']),ENT_QUOTES),0,1)=='<'){
				$ftr =  htmlspecialchars($this->processhtml(htmlspecialchars_decode($_POST['pFeature'],ENT_QUOTES)));			
			}else{
				$ftr = $this->splitstring(htmlspecialchars($_POST['pFeature'],ENT_QUOTES));			
			}
		}
		if(substr(trim(htmlspecialchars_decode($_POST['pSpecs']),ENT_QUOTES),0,1)=='<'){
		
			if(substr(trim($_POST['pSpecs']),0,1)=='<'){
				$spc =  htmlspecialchars($this->processhtml(htmlspecialchars_decode($_POST['pSpecs'],ENT_QUOTES)),ENT_QUOTES);			
			}else{
				$spc = $this->splitstring(htmlspecialchars($_POST['pSpecs'],ENT_QUOTES));
			}
		}
		
		
		$sql1 = "INSERT INTO `spenishdata` (`eng_id`,`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`, `product_source`, `product_user`, `product_specs`,`status`) VALUES ('".$ro['fpl_id']."','".$this->translateplaintext(htmlspecialchars($_POST['pName'],ENT_QUOTES))."', '".$this->translateplaintext(htmlspecialchars($_POST['pDesc'],ENT_QUOTES))."', '".$_POST['pSku']."', '".$_POST['pupc']."', '".$_POST['pmsrp']."', '".$_POST['pMAP']."', '".$_POST['pBrand']."', '".$_POST['pimg1']."', '".$ftr."', '', 'sanjay', '".$spc."','0')";
		
		mysql_query($sql1);
		
		$q1 = "select * from spenishdata ORDER BY `sppr_id` DESC limit 0,1";
		$r01 = mysql_query($q1);
		$ro1 = mysql_fetch_array($r01);

		$q2	= "UPDATE `finalproductlist` SET `spenish_id` = '".$ro1['sppr_id']."' WHERE `fpl_id` =".$ro['fpl_id'];
		mysql_query($q2);
		*/
		$query = 'select mpt_id from masterproducttable where product_sku = "'.$_POST['pSku'].'"';
		$re = mysql_query($query);
		while($r = mysql_fetch_array($re)){
			$q = "UPDATE `masterproducttable` SET `status` = '7' WHERE `mpt_id` =".$r['mpt_id'];
			mysql_query($q);
		}
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has created english ready version' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		$msg = '<h4 class="alert_success">A Product Created Successfully</h4>';
	}else{
		$msg = '<h4 class="alert_error">Product is not inserted successfully</h4>';
	}
	
	return $msg;

	}
	
	function magentoque(){
		
		$this->db->select('prduct_name, product_sku, product_upc, product_brand, product_source, fpl_id, magento_product_id,mstatus');
		$this->db->from('finalproductlist');
		$this->db->join('magentoque', 'magentoque.finalproId = finalproductlist.fpl_id');
		$q = $this->db->get();
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has accessed final products' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	}
	
	function finalproducts(){
		
		$this->db->select('prduct_name, product_sku, product_upc, product_brand, product_source, fpl_id, magento_product_id, magento_category_id');
		$this->db->from('finalproductlist');
		$this->db->where('status', '1');
		$q = $this->db->get();
		
		 $act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has accessed final products' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
		
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	}

// Category Tree	
function createcategory(){
	if($_POST['levels']==0){
		$pId = $_POST['pId0'];
	}elseif($_POST['pId'.$_POST['levels']]==0){
		$pId = $_POST['pId'.($_POST['levels']-1)];
	}else{
		$pId = $_POST['pId'.$_POST['levels']];
	}
	
	$data = array(
               'name' => $_POST['cName'],
               'parent_id' => $pId 
            );
		  $this->db->insert('categories', $data); 
	
	$act = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has Created a Category' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $act); 
}

function updatecategory(){
	
	
	//exit;
	if(trim($_POST['parentcat'])==''){
		$pId = 0;
	}else{
		$pId = $_POST['parentcat'];
	}
	
	
	$data = array(
               'name' => $_POST['cName'],
               'parent_id' => $pId 
            );
	$this->db->where('id', $_POST['cId']);
	$this->db->update('categories', $data);

	$this->db->select('name,magento_category_id');
	$this->db->from('categories');
	$this->db->where('id', $_POST['cId']);
	$q = $this->db->get();
	if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        }
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://data.icuracao.com/updatecat.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
	
	$data = array(
		'name' => $data[0]->name,
		'cat_id' => $data[0]->magento_category_id
	);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$msg  = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	
//   $msg = file_get_contents('http://data.icuracao.com/updatecat.php?name='.$data[0]->name.'&cat_id='.$data[0]->magento_category_id);
	
/*	$proxy = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
	$sessionId = $proxy->login('curacaoapi', 'curacao');
	
	$newData = array('name'=>$data[0]->name);
	$newCategoryId = $data[0]->magento_category_id;
	$proxy->call($sessionId, 'category.update', array($newCategoryId, $newData));	
*/	

	
	
	//$msg = '';
	
	return $msg;
//		$this->db->update('categories', $data); 
}


function deletecat(){
		
		$sql = 'select magento_category_id from categories where id = '.$_GET['catid'];	
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
			
		$this->db->where('id', $_GET['catid']);
		$this->db->delete('categories', $data);
		
		
		$msg = file_get_contents('http://data.icuracao.com/deletecat.php?cat_id='.$row['magento_category_id']);
		
		return $msg;
}


function hasChild($parent_id)
  {
    $sql = "SELECT COUNT(*) as count FROM categories WHERE parent_id = ' " . $parent_id . " ' ";
    $qry = mysql_query($sql);
    $rs = mysql_fetch_array($qry);
    return $rs['count'];
  }
 // Category List 
  function CategoryTree($list,$parent,$append)
  {
    $list = '<li>'.$parent['name'].' -- '.$parent['magento_category_id'].'    <a href="index.php/editcat?catid='.$parent['id'].'">Edit</a> | <a href="index.php/deletecat?catid='.$parent['id'].'">Delete</a></li>';
    
    if ($this->hasChild($parent['id'])) // check if the id has a child
    {
      $append++; // this is our basis on what level is the category e.g. (child1,child2,child3)
      $list .= "<ul class='child child".$append." '>";
      $sql = "SELECT * FROM categories WHERE parent_id = ' " . $parent['id'] . " ' ";
      $qry = mysql_query($sql);
      $child = mysql_fetch_array($qry);
      do{
        $list .= $this->CategoryTree($list,$child,$append);
      }while($child = mysql_fetch_array($qry));
      $list .= "</ul>";
    }
    return $list;
  }
  function CategoryList()
  {
    $list = "";
    
    $sql = "SELECT * FROM categories WHERE (parent_id = 0 OR parent_id IS NULL)";
    $qry = mysql_query($sql);
    $parent = mysql_fetch_array($qry);
    $mainlist = "<ul class='parent'>";
    do{
      $mainlist .= $this->CategoryTree($list,$parent,$append = 0);
    }while($parent = mysql_fetch_array($qry));
    $list .= "</ul>";
    return $mainlist;
  }

//End Category List

//Select List

 function CategoryselectTree($list,$parent,$append)
  {
	  $q = 'select * from categories where id = '.$_GET['catid'];
	  $r = mysql_query($q);
	  $row = mysql_fetch_array($r);
	  if($parent['id']==$row['parent_id']){
		$list = '<li><input type="radio" name="parentcat" value="'.$parent['id'].'" checked="checked">'.$parent['name'].' -- '.$parent['magento_category_id'].'</li>';
	}else{
    	$list = '<li><input type="radio" name="parentcat" value="'.$parent['id'].'">'.$parent['name'].' -- '.$parent['magento_category_id'].'</li>';
	}
    
    if ($this->hasChild($parent['id'])) // check if the id has a child
    {
      $append++; // this is our basis on what level is the category e.g. (child1,child2,child3)
      $list .= "<ul class='child child".$append." '>";
      $sql = "SELECT * FROM categories WHERE parent_id = ' " . $parent['id'] . " ' ";
      $qry = mysql_query($sql);
      $child = mysql_fetch_array($qry);
      do{
        $list .= $this->CategoryselectTree($list,$child,$append);
      }while($child = mysql_fetch_array($qry));
      $list .= "</ul>";
    }
    return $list;
  }

function CategoryselectList(){
	$list = "";
    
    $sql = "SELECT * FROM categories WHERE (parent_id = 0 OR parent_id IS NULL)";
    $qry = mysql_query($sql);
    $parent = mysql_fetch_array($qry);
    $mainlist = "<ul class='parent'>";
    do{
      $mainlist .= $this->CategoryselectTree($list,$parent,$append = 0);
    }while($parent = mysql_fetch_array($qry));
    $list .= "</ul>";
    return $mainlist;
}

//End SElect List

function CategorySelect(){
	
	$sql = "SELECT * FROM categories where parent_id = 0";
    $qry = mysql_query($sql);
	$str = '<select name="pId0" id="pId0" onchange=selectcat("0")>';
	$str .= '<option value="0">Main Category</option>';
	while($row = mysql_fetch_array($qry)){
		$str .= '<option value="'.$row['id'].'">';
			$str.= $row['name'];
		$str .= '</option>';
	}
	$str .= '</select>';
	return $str;
}	



		
}
?>
