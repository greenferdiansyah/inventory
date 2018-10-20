<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_cabang extends CI_Model {

	public function list_data($length,$start,$search,$order,$dir){

		$order_by="ORDER BY id_cabang";
		if($order!=0){
			$order_by="ORDER BY $order $dir";
		}
		
		$where_clause="";
		 if($search!=""){
			$where_clause=" AND (	id_cabang like '%$search%' OR 
									nama_cabang like '%$search%'
								)";
		}

		$sql		= " SELECT *
						FROM t_cabang
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
					FROM t_cabang
					WHERE id_cabang 	= ? ";
		$query	= $this->db->query($sql, array($id));
		$data 	= $query->row();

		return $data;

	}

	public function submit($action, $data){

		$this->db->trans_start();
			if($action == "Add"){

				$data["created"]	        = $this->session->userdata('user_id');
				$data["created_at"]         = date("Y-m-d h:m:s",time());
				
				$this->db->insert('t_cabang',$data); 
		
			}else{

			
				$this->db->where('id_cabang', $data["id_cabang"]);
				$this->db->update('t_cabang', $data); 
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

	public function delete($id_tipe){

		$this->db->trans_start();
		$this->db->query("DELETE FROM t_tipe WHERE id_tipe = ?", array($id_type));
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