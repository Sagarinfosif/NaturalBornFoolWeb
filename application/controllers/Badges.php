<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Badges extends CI_Controller {

	public function __construct(){
		parent::__construct();
		error_reporting(0);
		$this->load->model('admin/Admin_model');
		$this->load->model('admin/Common_model');
		if(!$this->session->userdata('admin_details')){
			redirect( site_url() . "/admin/login");
			exit;
		}
	}

	public function index(){
    	$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('badges')->result_array();
		$data['active'] = 'badges';
		$data['title'] = 'Manage Crown';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/badges/manage');
		$this->load->view('admin/includes/footer');
	}

	public function getResult(){
    	$start = $this->input->post('s');
    	$end = $this->input->post('e');
    	$pname = $this->input->post('p');
    	$admin_details = $this->session->userdata('admin_details');
		$admin = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$admin = $admin_details['admin_id'];
		if(!empty($end) && !empty($pname) && !empty($start)){
           	$data=$this->db->query("SELECT * from badges where created between '$start' and '$end' and title like '%$pname%' or likes like '%$pname%' or followers like '%$pname' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($end)){
           	$data=$this->db->query("SELECT * from badges where created between '$start' and '$end' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($pname)){
           	$data=$this->db->query("SELECT * from badges where created = '$start' and title like '%$pname%' or likes like '%$pname%' or followers like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end) && !empty($pname)){
           	$data=$this->db->query("SELECT * from badges where created = '$end' and title like '%$pname%' or likes like '%$pname%' or followers like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start)){
           	$data=$this->db->query("SELECT * from badges where created = '$start' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end)){
            $data=$this->db->query("SELECT * from badges where created = '$end' order by id desc")->result_array();
            exit(json_encode($data));
        }elseif(!empty($pname)){
            $data=$this->db->query("SELECT * from badges where title like'%$pname%' or likes like '%$pname%' or followers like '%$pname%' order by id desc")->result_array();
              exit(json_encode($data));
        }else{
        	$data=$this->db->query("SELECT * from badges order by id desc")->result_array();
            exit(json_encode($data));
        }
    }

	public function add(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'badges';
		$data['title'] = "Add Crown";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('likes', 'Likes Count', 'trim|required');
			$this->form_validation->set_rules('followers', 'Followers Count', 'trim|required');
			$this->form_validation->set_rules('color', 'Color', 'trim|required');
			if(empty($_FILES["image"]["name"])){
				$this->form_validation->set_rules('image', 'Image', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/badges/add');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['title'] = $this->input->post('title');
				$details['likes'] = $this->input->post('likes');
				$details['followers'] = $this->input->post('followers');
				$details['totalFollowers'] = $this->input->post('totalFollowers');
				$details['color'] = $this->input->post('color');
				require APPPATH.'/libraries/vendor/autoload.php';
            	$s3 = new Aws\S3\S3Client([
					'version' => 'latest',
					'region'  => 'ap-south-1',
					'credentials' => [
						'key'    => 'AKIATH742IUEGPH3VIE3',
						'secret' => '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'
					]
				]);
				$bucket = 'photofit-public';

		    	$upload = $s3->upload($bucket, $_FILES['image']['name'], fopen($_FILES['image']['tmp_name'], 'rb'), 'public-read');
				$url = $upload->get('ObjectURL');
				if(!empty($url)){
					$details['image'] = $url;
				}
				else{
					$details['image'] = '';
				}
				$insert = $this->Common_model->insert_data($details,'badges');
					$this->session->set_flashdata('success', "Badges added Successfully");
					redirect(site_url().'/Badges');
			}
		}
		else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/badges/add');
			$this->load->view('admin/includes/footer');
		}
	}



	public function edit(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'badges';
		$data['title'] = "Edit Crown";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('likes', 'Likes Count', 'trim|required');
			$this->form_validation->set_rules('followers', 'Followers Count', 'trim|required');
			$this->form_validation->set_rules('color', 'Color', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('badges',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/badges/edit');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details1['title'] = $this->input->post('title');
				$details1['likes'] = $this->input->post('likes');
				$details1['followers'] = $this->input->post('followers');
				$details1['totalFollowers'] = $this->input->post('totalFollowers');
				$details1['color'] = $this->input->post('color');
				if(!empty($_FILES["image"]["name"])){
					require APPPATH.'/libraries/vendor/autoload.php';
					$s3 = new Aws\S3\S3Client([
						'version' => 'latest',
						'region'  => 'ap-south-1',
						'credentials' => [
                        	'key'    => 'AKIATH742IUEGPH3VIE3',
							'secret' => '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'
						]
					]);
					$bucket = 'photofit-public';
			    	$upload = $s3->upload($bucket, $_FILES['image']['name'], fopen($_FILES['image']['tmp_name'], 'rb'), 'public-read');
					$url = $upload->get('ObjectURL');
					if(!empty($url)){
						$details1['image'] = $url;
					}
					else{
						$details1['image'] = '';
					}
				}
				$update = $this->Common_model->update('badges',$details1,'id',$this->uri->segment(3));
				if($update){
					$this->session->set_flashdata('success', "Badges Updated Successfully");
					redirect(site_url().'/Badges');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('badges',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/badges/edit');
			$this->load->view('admin/includes/footer');
		}
	}

	public function delete(){
		$delete = $this->Common_model->delete('badges','id',$this->uri->segment(3));
		redirect(site_url().'/Badges');
	}


}
