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

}