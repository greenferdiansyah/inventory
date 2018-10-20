<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kategori extends CI_Model {

	public function list_data($length,$start,$search,$order,$dir){

		$order_by="ORDER BY a.id_kategori";
		if($order!=0){
			$order_by="ORDER BY $order $dir";
		}
		
		$where_clause="";
		 if($search!=""){
			$where_clause=" AND (	a.id_kategori like '%$search%' OR 
									a.nama_kategori like '%$search%' OR
                                    b.fullname like '%$search%' OR
                                    c.fullname like '%$search%'
								)";
		}

		$sql		= " SELECT a.id_kategori,
                               a.nama_kategori,
                               a.status,
                               b.fullname as created,
                               a.created_at,
                               c.fullname as updated,
                               a.updated_at
						FROM t_kategori as a
                        left join m_user as b on a.created = b.id
                        left join m_user as c on a.updated = c.id
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
					FROM t_kategori
					WHERE id_kategori 	= ? ";
		$query	= $this->db->query($sql, array($id));
		$data 	= $query->row();

		return $data;

	}

	public function submit($action, $data){

		$this->db->trans_start();
			if($action == "Add"){

				$data["created"]	        = $this->session->userdata('user_id');
				$data["created_at"]         = date("Y-m-d h:m:s",time());
				
				$this->db->insert('t_kategori',$data); 
		
			}else{

			
				$this->db->where('id_kategori', $data["id_kategori"]);
				$this->db->update('t_kategori', $data); 
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

	public function delete($id_kategori){

		$this->db->trans_start();
		$this->db->query("DELETE FROM t_kategori WHERE id_kategori = ?", array($id_kategori));
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