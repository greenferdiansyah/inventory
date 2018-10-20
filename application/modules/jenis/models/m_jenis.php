<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_jenis extends CI_Model {

	public function list_data($length,$start,$search,$order,$dir){

		$order_by="ORDER BY a.id_jenis";
		if($order!=0){
			$order_by="ORDER BY $order $dir";
		}
		
		$where_clause="";
		 if($search!=""){
			$where_clause=" AND (	a.id_jenis like '%$search%' OR 
									a.nama_jenis like '%$search%' OR
                                    b.fullname like '%$search%' OR
                                    c.fullname like '%$search%' OR
                                    e.nama_kategori like '%$search%'
								)";
		}

		$sql		= " SELECT a.id_jenis,
                               a.nama_jenis,
                               a.status,
                               b.fullname as created,
                               a.created_at,
                               c.fullname as updated,
                               a.updated_at,
                               e.nama_kategori
						FROM t_jenis as a
                        left join m_user as b on a.created = b.id
                        left join m_user as c on a.updated = c.id
                        left join t_kategori as e on a.kategori_id = e.id_kategori
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
					FROM t_jenis
					WHERE id_jenis 	= ? ";
		$query	= $this->db->query($sql, array($id));
		$data 	= $query->row();

		return $data;

	}

	public function submit($action, $data){

		$this->db->trans_start();
			if($action == "Add"){

				$data["created"]	        = $this->session->userdata('user_id');
				$data["created_at"]         = date("Y-m-d h:m:s",time());
				$this->db->insert('t_jenis',$data); 
		
			}else{

			
				$this->db->where('id_jenis', $data["id_jenis"]);
				$this->db->update('t_jenis', $data); 
			}
		// echo $this->db->last_query();
		// exit();
		// die();
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

	public function delete($id_jenis){

		$this->db->trans_start();
        $this->db->query("DELETE FROM t_jenis WHERE id_jenis = ?", array($id_jenis));
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