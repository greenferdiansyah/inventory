<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_satuan extends CI_Model {

	public function list_data($length,$start,$search,$order,$dir){

		$order_by="ORDER BY id_satuan";
		if($order!=0){
			$order_by="ORDER BY $order $dir";
		}
		
		$where_clause="";
		 if($search!=""){
			$where_clause=" AND (	id_satuan like '%$search%' OR 
									nama_satuan like '%$search%'
                                    -- b.fullname like '%$search%' OR
                                    -- c.fullname like '%$search%'
								)";
		}

		$sql		= " SELECT *
						FROM t_satuan
						WHERE 1=1
						$where_clause
						$order_by";
						
		$query		= $this->db->query($sql . " LIMIT $start, $length");

		$numrows	= $this->db->query($sql);
		$total		= $numrows->num_rows();
		
		return array("data"=>$query->result_array(),
					"total_data"=>$total
				);
	}


	public function detail_data($id){
		
		$sql 	=  "SELECT *
					FROM t_satuan
					WHERE id_satuan 	= ? ";
		$query	= $this->db->query($sql, array($id));
		$data 	= $query->row();

		return $data;

	}

	public function submit($action, $data){

		$this->db->trans_start();
			if($action == "Add"){

				$data["created"]	        = $this->session->userdata('user_id');
				$data["created_at"]         = date("Y-m-d h:m:s",time());
				
				$this->db->insert('t_satuan',$data); 
		
			}else{

			
				$this->db->where('id_satuan', $data["id_satuan"]);
				$this->db->update('t_satuan', $data); 
			}
		
		$this->db->trans_complete();
		$response	= $this->db->trans_status();

		if($response){
			$title 	= 	"success";
			$reason	= 	($action=="Add")?'Inserted':'Updated';
		}else{
			$title 	= 	"failed";
			$reason	= 	($action=="Add")?'Fail Insert':'Fail Update';
		}
		return array("status"=>$response,"title"=>$title,"reason"=>$reason);

	}

	public function delete($id_satuan){

		$this->db->trans_start();
		$this->db->query("DELETE FROM t_satuan WHERE id_satuan = ?", array($id_type));
		$this->db->trans_complete();
		
		$response	= $this->db->trans_status();

		if($response){
			$title 	= 	"success";
			$reason	= 	"Deleted";
		}else{
			$title 	= 	"failed";
			$reason	= 	"Error while deleted data";
		}
		return array("status"=>$response,"title"=>$title,"reason"=>$reason);
	}
	
}