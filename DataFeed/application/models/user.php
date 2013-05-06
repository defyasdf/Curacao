<?php 

class User extends CI_Model {
	
	function users(){
		$this->db->select('*');
		$this->db->from('users');
		$q = $this->db->get();
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	} 

	function deleteuser(){

		if($this->db->delete('users', array('user_id' => $_GET['uid']))){
			
			 $data = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has deleted user' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $data); 
			
			$msg =  '<h4 class="alert_success">User has been deleted successfully</h4>';
		}else{
			$msg = '<h4 class="alert_error">User is not deleted successfully</h4>';
		}
		
		return $msg;
	}
	
	function insertuser(){
//		print_r($_POST);
		$value = '';
		for($i=0;$i<sizeof($_POST['aLevel']);$i++){
			if($i==(sizeof($_POST['aLevel'])-1)){
				$value .= $_POST['aLevel'][$i];
			}else{
				$value .= $_POST['aLevel'][$i].',';
			}
		}
		
		 $data = array(
               'fname' => $_POST['fName'],
               'lname' => $_POST['lName'],
               'email' => $_POST['email'],
			   'username' => $_POST['uName'],
			   'password' => $this->securepass($_POST['pass']),
			   'access_level' => $value
            );

		if($this->db->insert('users', $data)){
			
		 $data = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has created a user' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $data); 	
		
			$msg =  '<h4 class="alert_success">User has been inserted successfully</h4>';
		}else{
			$msg = '<h4 class="alert_error">User is not inserted successfully</h4>';
		}
		
		return $msg;
	}
	
	// Start functions for vendors
	
	function vendors(){
		$this->db->select('*');
		$this->db->from('vendormanagement');
		$q = $this->db->get();
		
		if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
        	return $data;
        }
		
	} 

	function deletevendor(){

		if($this->db->delete('vendormanagement', array('vmID' => $_GET['vid']))){
			
			 $data = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has deleted Vendor' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $data); 
			
			$msg =  '<h4 class="alert_success">Vendor has been deleted successfully</h4>';
		}else{
			$msg = '<h4 class="alert_error">Vendor is not deleted successfully</h4>';
		}
		
		return $msg;
	}
	
	function insertvendor(){
//		print_r($_POST);
		 $data = array(
               'vendorName' => $_POST['vName'],
			   'username' => $_POST['uName'],
			   'password' => $this->securepass($_POST['pass']),
			   'vendorID' => $_POST['vId']
            );

		if($this->db->insert('vendormanagement', $data)){
			
		 $data = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has created a vendor' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $data); 	
		
			$msg =  '<h4 class="alert_success">Vendor has been inserted successfully</h4>';
		}else{
			$msg = '<h4 class="alert_error">Vendor is not inserted successfully</h4>';
		}
		
		return $msg;
	}
	
	
	// End functions for vendors
	
	
	

	function securepass($pass){
		
		return md5($pass.'curacaosecurity');
	}
	
	function uactivities(){
		$this->db->select('*');
		$this->db->from('useractivity');
		
		$q = $this->db->get();
		
		$data = array();
		
		foreach($q->result_array() as $row){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('user_id', $row['userId']);		
			$q1 = $this->db->get();
			foreach($q1->result_array() as $row1){
				$data['user'][] = $row1['fname'].' '.$row1['lname'];
			}
			$data['activity'][] = $row['activity'];
			$data['date'][] = $row['date'];
			$data['time'][] = $row['time'];
			
		}
		return $data;
		
	}
	
	function chklogin(){
		
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username', $_POST['user']);	
		$this->db->where('password', $this->securepass($_POST['pass']));	
		$q = $this->db->get();
		
		if($q->num_rows() > 0) {
		
			foreach($q->result_array() as $row){
				$data[] = $row;
			}

            $newdata = array(
                   'user_id' =>$data[0]['user_id'], 
				   'fname'  => $data[0]['fname'],
				   'lname'     => $data[0]['lname'],
				   'level'     => $data[0]['access_level'],
                   'logged_in' => TRUE
               );
			
		   $this->session->set_userdata($newdata);
		   
		    $data = array(
               'userId' => $this->session->userdata('user_id'),
               'activity' => $this->session->userdata('fname').' '.$this->session->userdata('lname').' has logged in' ,
               'date' => date('Y-m-d'),
			   'time' => date('h:i:s A')
            );
		  $this->db->insert('useractivity', $data); 
		if($_POST['user']=='credit'){
			return 2;
		}elseif($_POST['user']=='roger'){
			return 3;
		}else{
			return 1;		
		}		  
        }else{
			return 0;
		}
		
	}
}

