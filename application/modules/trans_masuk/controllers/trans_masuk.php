<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trans_masuk extends CI_Controller {

	var $tenant_id;
	var $access; 


	function __construct() {

		parent::__construct();

		$this->load->library(array('encryption','drop_down','page_render'));
        $this->load->model("m_trans_masuk");
		$this->tenant_id 	= $this->session->userdata('tenant_id');
		$this->access 		= $this->page_render->page_auth_check('trans_masuk');
		

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
											'page_title'				=> "Barang Masuk",
											'tenant_id'					=> $this->tenant_id,
											
										);
	
				$this->parser->parse('master/content', $data); //default//
				
			}
		}else{
			redirect('login');
		}
	}	

	public function json_list(){

			$parent_page	=  $this->uri->segment(1);
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$order 			= $_REQUEST['order'][0]["column"];
			$dir 			= $_REQUEST['order'][0]["dir"]; //descending or ascending//

			
			$data 			        = $this->m_trans_masuk->list_data($length,$start,$search,$order,$dir);
			
			$output					= array();
			$output['draw']			= $draw;
			$output['recordsTotal']	= $output['recordsFiltered']=$data['total_data'];
			$output['data']			= array();
			$nomor_urut				= $start+1;
			
			foreach ($data['data'] as $row) {
				
				$id 		= $row['id_trans_masuk'];


				$button_action = "<center>
					<a href='main#".$parent_page."/form/".base64_encode($this->encryption->encrypt('Edit')).'/'.base64_encode($this->encryption->encrypt($id))."' class='btn btn-warning btn-xs'  title='Edit'>
						<i class='fa fa-pencil'></i>
					</a>
                	<a onclick=del('".$id."') id=$id class='btn btn-danger btn-xs' title='Delete'><i class='fa fa-times' style='color: white;'></i></a></center>";
				
				$output['data'][]=array(
					$nomor_urut,
					$row['kode_barang'],
					$row['nama_barang'],
                    $row['nama_vendor'],
                    $row['jumlah'],
                    $row['deskripsi'],
					$row['fullname'],
					$row['created_at'],
					$row['fullname_updated'],
					$row['updated_at'],
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

        if($action != 'Edit'){
                $title					= 'Input Barang Masuk';
                $id_trans_masuk         = null;
				$barang_id			    = null;
                $vendor_id 				= null;
                $jumlah 				= null;
                $deskripsi              = null;
                $created 				= null;
                $created_at    			= null;
                $updated 				= null;
                $updated_at 			= null;
        
        }else{

				$result			        = $this->m_trans_masuk->detail_data($id);
                $title					= 'Edit Barang Masuk';
                $id_trans_masuk         = $result->id_trans_masuk;
                $barang_id	    		= $result->barang_id;
                $vendor_id 				= $result->vendor_id;
                $jumlah 				= $result->jumlah;
                $deskripsi              = $deskripsi;

		}

				$this->drop_down->select("id_barang","nama_barang");
				$this->drop_down->from("t_barang");
				//$this->drop_down->where("option_type = 'opt_barang_id'");
				$this->drop_down->order("id_barang", "ASC");
                $list_barang	= $this->drop_down->build($barang_id);
                
                $this->drop_down->select("id_vendor","nama_vendor");
				$this->drop_down->from("t_vendor");
				$this->drop_down->where("id != '0'");
				$this->drop_down->order("id_vendor", "ASC");
				$list_vendor	= $this->drop_down->build($vendor_id);

				$data = array(
					'page'					=> $parent_page,
					'page_content'			=> $parent_page.'_'.$page,
					'base_url'				=> base_url(),
					'title'					=> $title,
                    'action'				=> $action==""?'Add':'Edit',
                    'id_trans_masuk'	    => $id_trans_masuk,
                    'barang_id'             => $barang_id,
                    'vendor_id'				=> $vendor_id,
                    'jumlah'                => $jumlah,
                    'list_barang'			=> $list_barang,
                    'list_vendor'	        => $list_vendor,
                    'deskripsi'             => $deskripsi
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
								'id_trans_masuk'		=> $this->input->post("id_trans_masuk"),
                                'barang_id' 			=> $this->input->post("barang_id"),
                                'vendor_id'             => $this->input->post("vendor_id"),
                                'jumlah'                => $this->input->post("jumlah"),
                                'deskripsi'             => $this->input->post("deskripsi"),
                                'updated'				=> $this->session->userdata('user_id'),
				                'updated_at'	 		=> date("Y-m-d h:m:s",time())

						);
		
		$response	= $this->m_trans_masuk->submit($action, $data);

		echo json_encode(array("status"=> $response["status"], "title"=>$response["title"], "reason"=> $response["reason"]));
	}


	public function delete(){
		$id_merk 		    = $this->input->post("id_merk");
		$response	        = $this->m_merk->delete($id_merk);
		echo json_encode(array("status"=> $response["status"], "title"=>$response["title"], "reason"=> $response["reason"]));
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
