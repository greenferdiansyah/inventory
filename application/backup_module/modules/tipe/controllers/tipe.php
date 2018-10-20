<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipe extends CI_Controller {

	var $tenant_id;
	var $access; 


	function __construct() {

		parent::__construct();

		$this->load->library(array('encryption','drop_down','page_render'));
        $this->load->model("m_tipe");
		$this->tenant_id 	= $this->session->userdata('tenant_id');
		$this->access 		= $this->page_render->page_auth_check('tipe');
		

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
											'page_title'				=> "Kategori",
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

			
			$data 			= $this->m_tipe->list_data($length,$start,$search,$order,$dir);
			
			$output					= array();
			$output['draw']			= $draw;
			$output['recordsTotal']	= $output['recordsFiltered']=$data['total_data'];
			$output['data']			= array();
			$nomor_urut				= $start+1;
			
			foreach ($data['data'] as $row) {
				
				$id 		= $row['id_tipe'];


				$button_action = "<center>
					<a href='main#".$parent_page."/form/".base64_encode($this->encryption->encrypt('Edit')).'/'.base64_encode($this->encryption->encrypt($id))."' class='btn btn-warning btn-xs'  title='Edit'>
						<i class='fa fa-pencil'></i>
					</a>
                	<a onclick=del('".$id."') id=$id class='btn btn-danger btn-xs' title='Delete'><i class='fa fa-times' style='color: white;'></i></a></center>";
				
				$output['data'][]=array(
					$nomor_urut, 
					$row['nama_tipe'],
					($row['status']==1)?"<label class='label label-success'>active</label>":"<label class='label label-danger'>deactive</label>",
					$row['created'],
					$row['created_at'],
					$row['updated'],
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
                $title					= 'Tambah Tipe';
                $id_tipe                = null;
				$nama_tipe			    = null;
                $status 				= null;
        
        }else{

				$result			        = $this->m_tipe->detail_data($id);
                $title					= 'Edit Tipe';
                $id_tipe                = $result->id_tipe;
                $nama_tipe	    		= $result->nama_tipe;
                $status 				= $result->status;			
		}

				$this->drop_down->select("option_id","option_name");
				$this->drop_down->from("m_option");
				$this->drop_down->where("option_type = 'opt_status'");
				$this->drop_down->order("sort", "ASC");
				$list_status	= $this->drop_down->build($status);

				$data = array(
					'page'					=> $parent_page,
					'page_content'			=> $parent_page.'_'.$page,
					'base_url'				=> base_url(),
					'title'					=> $title,
                    'action'				=> $action==""?'Add':'Edit',
                    'id_tipe'			    => $id_tipe,
                    'nama_tipe'             => $nama_tipe,
					'status'				=> $status,
					'list_status'			=> $list_status
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
								'id_tipe'   			=> $this->input->post("id_tipe"),
								'nama_tipe' 			=> $this->input->post("nama_tipe"),
								'status'				=> $this->input->post("status"),
                                'updated'				=> $this->session->userdata('user_id'),
				                'updated_at'	 		=> date("Y-m-d h:m:s",time())

						);
		
		$response	= $this->m_tipe->submit($action, $data);

		echo json_encode(array("status"=> $response["status"], "title"=>$response["title"], "reason"=> $response["reason"]));
	}


	public function delete(){
		$id_tipe 		    = $this->input->post("id_tipe");
		$response	        = $this->m_tipe->delete($id_tipe);
		echo json_encode(array("status"=> $response["status"], "title"=>$response["title"], "reason"=> $response["reason"]));
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
