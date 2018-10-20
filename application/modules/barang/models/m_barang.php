	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_barang extends CI_Model {

	public function list_data($length,$start,$search,$order,$dir){

		$order_by="ORDER BY a.id_barang";
		if($order!=0){
			$order_by="ORDER BY $order $dir";
		}
		
		// $where_clause="";
		//  if($search!=""){
		// 	$where_clause=" AND (	a.id_barang like '%$search%' OR 
		// 							a.nama_barang like '%$search%' OR
        //                             b.fullname like '%$search%' OR
        //                             h.fullname like '%$search%' OR
		// 							x.nama_vendor like '%$search%'
		// 						)";
		// }

		// $sql		= " SELECT  a.id_barang,
        //                         a.kode_barang,
        //                         a.nama_barang,
        //                         a.stock,
        //                         f.nama_merk,
        //                         e.nama_tipe,
        //                         c.nama_jenis,
        //                         d.nama_kategori,
        //                         g.nama_satuan,
		// 						x.nama_vendor,
        //                         COALESCE(b.fullname,'system') as created,
        //                         a.created_at,
        //                         COALESCE(h.fullname,'system') as updated,
        //                         a.updated_at
		// 				FROM t_barang as a
        //                 left join m_user  as b on a.created = b.id
        //                 inner join t_jenis as c on a.jenis_id = c.id_jenis
        //                 inner join t_kategori as d on a.kategori_id = d.id_kategori
        //                 inner join t_tipe as  e on a.tipe_id = e.id_tipe
        //                 inner join t_merk as f on a.merk_id = f.id_merk
        //                 inner join t_satuan as g on a.satuan_id = g.id_satuan
		// 				inner join t_vendor as x on a.vendor_id = x.id_vendor
        //                 left join m_user as h on a.updated = h.id
		// 				WHERE 1=1
		// 				$where_clause
		// 				$order_by";

		$where_clause="";
		 if($search!=""){
			$where_clause=" AND (	a.id_barang like '%$search%' OR 
									a.nama_barang like '%$search%' OR
                                    b.fullname like '%$search%' OR
									x.nama_vendor like '%$search%'
								)";
		}

		$sql		= " SELECT  a.id_barang,
                                a.kode_barang,
                                a.nama_barang,
                                a.stock,
                                -- f.nama_merk,
                                -- e.nama_tipe,
                                c.nama_jenis,
                                d.nama_kategori,
                                g.nama_satuan,
								x.nama_vendor,
                                COALESCE(b.fullname,'system') as created,
                                a.created_at,
                                COALESCE(h.fullname,'system') as updated,
                                a.updated_at
						FROM t_barang as a
                        left join m_user  as b on a.created = b.id
                        inner join t_jenis as c on a.jenis_id = c.id_jenis
                        inner join t_kategori as d on a.kategori_id = d.id_kategori
                        -- inner join t_tipe as  e on a.tipe_id = e.id_tipe
                        -- inner join t_merk as f on a.merk_id = f.id_merk
                        inner join t_satuan as g on a.satuan_id = g.id_satuan
						inner join t_vendor as x on a.vendor_id = x.id_vendor
                        left join m_user as h on a.updated = h.id
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
		
		$sql 	= "SELECT * FROM t_barang
					WHERE id_barang = ? ";
		$query 	= $this->db->query($sql, array($id));
		// echo $this->db->last_query();
		// exit();
		// die();
		$data 	= $query->row();
		
		return $data;

	}

	public function submit($action, $data){

		$this->db->trans_start();
			if($action == "Add"){

				$data["created"]	        = $this->session->userdata('user_id');
				$data["created_at"]         = date("Y-m-d h:m:s",time());
				
				$this->db->insert('t_barang',$data); 
		
			}else{

			
				$this->db->where('id_barang', $data["id_barang"]);
				$this->db->update('t_barang', $data); 
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

	public function delete($id_barang){

		$this->db->trans_start();
		$this->db->query("DELETE FROM t_barang WHERE id_barang = ?", array($id_barang));
		
		// $this->db->last_query();
		// exit();
		// die();

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