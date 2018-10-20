<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang extends CI_Controller {

	var $tenant_id;
	var $access; 


	function __construct() {

		parent::__construct();

		$this->load->library(array('encryption','drop_down','page_render'));
        $this->load->model("m_barang");
		$this->tenant_id 	= $this->session->userdata('tenant_id');
		$this->access 		= $this->page_render->page_auth_check('barang');
		

    }

	public function index(){

		if($this->session->userdata('is_logged_in') == true){
			if($this->access != 0){
				$parent_page	=  $this->uri->segment(1);
				$page			=  $this->uri->segment(1);
	
				$data			=	array(
											'page_content' 				=> $page,
											'parent_page'				=> $parent_page,
											'base_url'					=> base_url(),
											'page_url'					=> base_url().$page,
											'page_title'				=> "Barang",
											'tenant_id'					=> $this->tenant_id,
											
										);
	
				$this->parser->parse('master/content', $data); //default//
				
			}
		}else{
			redirect('login');
		}
	}	

	public function json_list(){

			$parent_page			=  $this->uri->segment(1);
			
			$draw					= $_REQUEST['draw'];
			$length					= $_REQUEST['length'];
			$start					= $_REQUEST['start'];
			$search					= $_REQUEST['search']["value"];
			$order 					= $_REQUEST['order'][0]["column"];
			$dir 					= $_REQUEST['order'][0]["dir"]; //descending or ascending//

			
			$data 					= $this->m_barang->list_data($length,$start,$search,$order,$dir);
			
			$output					= array();
			$output['draw']			= $draw;
			$output['recordsTotal']	= $output['recordsFiltered']=$data['total_data'];
			$output['data']			= array();
			$nomor_urut				= $start+1;
			
			foreach ($data['data'] as $row) {
				
				$id 		= $row['id_barang'];


				$button_action = "<center>
					<a href='main#".$parent_page."/form/".base64_encode($this->encryption->encrypt('Edit')).'/'.base64_encode($this->encryption->encrypt($id))."' class='btn btn-warning btn-xs'  title='Edit'>
						<i class='fa fa-pencil'></i>
					</a>
					<a onclick=del('".$id."') id=$id class='btn btn-danger btn-xs' title='Delete'><i class='fa fa-times' style='color: white;'></i></a></center>
					<a onclick=show_detail('".$id."') id =$id class='btn btn-info btn-xs' title='show_detail' style='color: white;'><i class='fa fa-pencil'></i></a></center>";
				
				$output['data'][]=array(
					$nomor_urut, 
					$row['kode_barang'],
					$row['nama_barang'],
					$row['nama_kategori'],
					$row['nama_jenis'],
					$row['nama_tipe'],
					$row['nama_merk'],
					// $row['nama_satuan'],
					$row['stock'],
					// $row['nama_vendor'],
					// $row['created'],
					// $row['created_at'],
					$button_action
				);
				$nomor_urut++;
			}
			echo json_encode($output);
	}
   

	public function form(){

		if($this->session->userdata('is_logged_in') == true){
			if($this->access != 0){

					$parent_page			= $this->uri->segment(1);
					$page					= $this->uri->segment(2);
					$action					= $this->encryption->decrypt(base64_decode($this->uri->segment(3)));
					$id						= $this->encryption->decrypt(base64_decode($this->uri->segment(4)));
					$show					= date('Y-m-d H:i:s');
					$timestamp 				= strtotime($show);
					$generate				= "ITM-".$timestamp;

        if($action != 'Edit'){
					$title					= 'Input Barang Baru';
					$id_barang	            = null;
					$nama_barang			= null;
					$kategori_id			= null;
					$jenis_id				= null;
					$merk_id				= null;
					$tipe_id				= null;
					$deskripsi				= null;
					$stock					= null;
					$vendor_id				= null;
					$satuan_id				= null;
					$kode_barang			= $generate;

        
        }else{

					$result			        = $this->m_barang->detail_data($id);
                    $title					= 'Edit Barang';
                    $id_barang	            = $result->id_barang;
                    $nama_kategori			= $result->nama_barang;
					$kategori_id			= $result->kategori_id;
					$jenis_id				= $result->jenis_id;
					$merk_id				= $result->merk_id;
					$tipe_id				= $result->tipe_id;
					$deskripsi				= $result->deskripsi;
					$stock					= $result->stock;
					$vendor_id				= $result->vendor_id;
					$kode_barang			= $result->kode_barang;
					$satuan_id				= $result->satuan_id;
				}

				//kategori dropdown
				$this->drop_down->select("id_kategori","nama_kategori");
				$this->drop_down->from("t_kategori");
				$this->drop_down->where("status = 1");
				$this->drop_down->order("nama_kategori", "ASC");
				$list_kategori	= $this->drop_down->build($kategori_id);

				//jenis dropdown
				$this->drop_down->select("id_jenis","nama_jenis");
				$this->drop_down->from("t_jenis");
				$this->drop_down->where("status = 1");
				$this->drop_down->order("nama_jenis", "ASC");
				$list_jenis		= $this->drop_down->build($jenis_id);

				//merk dropdown
				$this->drop_down->select("id_merk","nama_merk");
				$this->drop_down->from("t_merk");
				$this->drop_down->where("status = '1'");
				$this->drop_down->order("nama_merk", "ASC");
				$list_merk		= $this->drop_down->build($merk_id);

				//tipe dropdown
				$this->drop_down->select("id_tipe","nama_tipe");
				$this->drop_down->from("t_tipe");
				$this->drop_down->where("status = '1'");
				$this->drop_down->order("nama_tipe", "ASC");
				$list_tipe		= $this->drop_down->build($tipe_id);

				//vendor dropdown
				$this->drop_down->select("id_vendor","nama_vendor");
				$this->drop_down->from("t_vendor");
				$this->drop_down->where("status = '1'");
				$this->drop_down->order("nama_vendor", "ASC");
				$list_vendor	= $this->drop_down->build($vendor_id);

				//satuan dropdown
				$this->drop_down->select("id_satuan","nama_satuan");
				$this->drop_down->from("t_satuan");
				$this->drop_down->where("status = '1'");
				$this->drop_down->order("nama_satuan", "ASC");
				$list_satuan	= $this->drop_down->build($satuan_id);



				$data = array(
					'page'					=> $parent_page,
					'page_content'			=> $parent_page.'_'.$page,
					'base_url'				=> base_url(),
					'title'					=> $title,
                    'action'				=> $action==""?'Add':'Edit',
					'id_barang'				=> $id_barang,
					'kode_barang'			=> $kode_barang,
					'nama_barang'	        => $nama_barang,
					'kategori_id'			=> $kategori_id,
					'jenis_id'				=> $jenis_id,
					'merk_id'				=> $merk_id,
					'tipe_id'				=> $tipe_id,
					'deskripsi'				=> $deskripsi,
					'stock'					=> $stock,
					'vendor_id'				=> $vendor_id,
					'list_kategori'			=> $list_kategori,
					'list_jenis'			=> $list_jenis,
					'list_merk'				=> $list_merk,
					'list_tipe'				=> $list_tipe,
					'list_vendor'			=> $list_vendor,
					'list_satuan'			=> $list_satuan
				);

				$this->parser->parse('master/content', $data);
			}
		}else{
			redirect('login');
		}
	}

	public function form_submit(){

		$action		  = $this->input->post("action");
		$data         = array(
								'id_barang'				=> $this->input->post("id_barang"),
								'kode_barang'			=> $this->input->post("kode_barang"),
								'nama_barang'			=> $this->input->post("nama_barang"),
								'kategori_id'			=> $this->input->post("kategori_id"),
								'jenis_id'				=> $this->input->post("jenis_id"),
								'merk_id'				=> $this->input->post("merk_id"),
								'tipe_id'				=> $this->input->post("tipe_id"),
								'deskripsi'				=> $this->input->post("deskripsi"),
								'stock'					=> $this->input->post("stock"),
								'vendor_id'				=> $this->input->post("vendor_id"),
								'updated'				=> $this->session->userdata('user_id'),
								'satuan_id'				=> $this->input->post("satuan_id"),
				                'updated_at'	 		=> date("Y-m-d h:m:s",time())

						);
		
		$response	= $this->m_barang->submit($action, $data);

		echo json_encode(array("status"=> $response["status"], "title"=>$response["title"], "reason"=> $response["reason"]));
	}


	public function delete(){

		$id_kategori 		= $this->input->post("id_kategori");
		$response	        = $this->m_kategori->delete($id_kategori);
		echo json_encode(array("status"=> $response["status"], "title"=>$response["title"], "reason"=> $response["reason"]));
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
