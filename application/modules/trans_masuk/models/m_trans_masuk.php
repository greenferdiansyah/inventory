<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_trans_masuk extends CI_Model {

	public function list_data($length,$start,$search,$order,$dir){

		$order_by="ORDER BY a.id_trans_masuk";
		if($order!=0){
			$order_by="ORDER BY $order $dir";
		}
		
		$where_clause="";
		 if($search!=""){
			$where_clause=" AND (	a.id_trans_masuk like '%$search%' OR 
									b.nama_barang like '%$search%' OR
                                    c.nama_vendor like '%$search%' OR
                                    jumlah like '%$search%' OR
                                    deskripsi like '%$search%'
                                    -- b.fullname like '%$search%' OR
                                    -- c.fullname like '%$search%'
								)";
		}

		$sql		= " SELECT a.*,b.nama_barang,b.kode_barang,
						c.nama_vendor,
						d.fullname,
						e.fullname as fullname_updated
						FROM t_transaksi_masuk as a
						INNER JOIN t_barang as b on a.barang_id = b.id_barang
						INNER JOIN t_vendor as c on a.vendor_id = c.id_vendor
						INNER JOIN m_user as d on a.created = d.id
						INNER JOIN m_user as e on a.updated = e.id
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
					FROM t_trans_masuk
					WHERE id_trans_masuk = ? ";
		$query	= $this->db->query($sql, array($id));
		$data 	= $query->row();

		return $data;

	}

	public function submit($action, $data){

		$this->db->trans_start();
			if($action == "Add"){

				$data["created"]	        = $this->session->userdata('user_id');
				$data["created_at"]         = date("Y-m-d h:m:s",time());
				
				$this->db->insert('t_transaksi_masuk',$data); 
		
			}else{

			
				$this->db->where('id_trans_masuk', $data["id_trans_masuk"]);
				$this->db->update('t_trans_masuk', $data); 
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

	public function delete($id_merk){

		$this->db->trans_start();
		$this->db->query("DELETE FROM t_merk WHERE id_merk = ?", array($id_merk));
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