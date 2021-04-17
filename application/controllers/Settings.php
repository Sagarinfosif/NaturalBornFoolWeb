<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

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

	public function logo(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('logo','id=1')->row_array();

		$data['active'] = 'logo';
		$data['title'] = 'Manage logo';
		
			if($this->input->post()){
				$this->form_validation->set_rules('hello', 'Logo', 'required');
              if (empty($_FILES['image']['name'])){
   				 $this->form_validation->set_rules('image', 'Logo', 'required');
			  }

              if ($this->form_validation->run()==FALSE){
    			$this->load->view('admin/includes/header',$data);
    		$this->load->view('admin/settings/logo');
    		$this->load->view('admin/includes/footer');

 			 }
              else{
                $details['updated'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/settings/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['img']= $liciense_path;
				}
				$update = $this->db->update('logo',$details,['id' => 1]);
				
				if($update){ 
					$this->session->set_flashdata('success', "Logo Updated Successfully");
					redirect(site_url().'/Settings/logo');
				}
              }
				
			}
			else
			{
			 
			$this->load->view('admin/includes/header',$data);
    		$this->load->view('admin/settings/logo');
    		$this->load->view('admin/includes/footer');
	}
	}

	public function length()
	{
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('logo','id=2')->row_array();
		$data['active'] = 'length';
		$data['title'] = 'Manage lenth';
		
			if($this->input->post()){
   				 $this->form_validation->set_rules('image', 'Length', 'required');
			 
              if ($this->form_validation->run()==FALSE){
    			$this->load->view('admin/includes/header',$data);
    			$this->load->view('admin/settings/length');
    			$this->load->view('admin/includes/footer');
 			 }
 			 else{
 			 	$details['img'] = $this->input->post('image');
 			 	$update = $this->db->update('logo',$details,['id'=>2]);
				if($update){ 
					$this->session->set_flashdata('success', "Length Updated Successfully");
					redirect(site_url().'/Settings/length');
					}
 				 }
 			}
 		else{
 			$this->load->view('admin/includes/header',$data);
    		$this->load->view('admin/settings/length');
    		$this->load->view('admin/includes/footer');
 		}
	}








	public function addSubAdmin(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'addSubAdmin';
		$data['title'] = "Add Sub Admin";
		if($this->input->post()){
			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[subAdmin.username]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[subAdmin.email]');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|is_unique[subAdmin.phone]');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
			if(empty($_FILES["image"]["name"]) || $_FILES["image"]["name"] == ""){
				$this->form_validation->set_rules('image', 'Picture', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/subAdmin/add');
				$this->load->view('admin/includes/footer');
			}else{
				$details['username'] = $this->input->post('username');
				$details['email'] = $this->input->post('email');
				$details['phone'] = $this->input->post('phone');
				$details['description'] = $this->input->post('description');
				$details['password'] = md5($this->input->post('password'));
				$details['status'] = "Approved";
				$details['created'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/users/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']= $liciense_path;
				}
				$insert = $this->Common_model->insert_data($details,'subAdmin');
				if($insert){
					$this->session->set_flashdata('success', "Sub Admin added Successfully");
					redirect(site_url().'/SubAdmin');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/subAdmin/add');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function view(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'subAdmin';
		$data['title'] = "View Sub Admin";
		$data['details'] = $this->db->get_where('subAdmin',array('id' => $this->uri->segment(3)))->row_array();
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/subAdmin/view');
		$this->load->view('admin/includes/footer');
	}
	
	public function edit(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'subAdmin';
		$data['title'] = "Edit Sub Admin";
		if($this->input->post()){
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required');
			$this->form_validation->set_rules('description', 'Mobile', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('subAdmin',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/subAdmin/edit');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['username'] = $this->input->post('username');
				$details1['email'] = $this->input->post('email');
				$details1['phone'] = $this->input->post('phone');
				$details1['description'] = $this->input->post('description');
				$details1['updated'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/users/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image']= $liciense_path;
				}
				$update = $this->Common_model->update('subAdmin',$details1,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', "Sub Admin Updated Successfully");
					redirect(site_url().'/SubAdmin');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('subAdmin',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/subAdmin/edit');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function delete(){
		$delete = $this->Common_model->delete('subAdmin','id',$this->uri->segment(3));
		redirect(site_url().'/SubAdmin');
	}
	
	public function status(){
		$details = $this->db->get_where('subAdmin',array('id' => $this->uri->segment(3)))->row_array();
		if($details['status'] == 'Approved'){
			$data['status'] = 'Pending';
		}
		else{
			$data['status'] = 'Approved';
		}
		$update = $this->Common_model->update('subAdmin',$data,'id',$this->uri->segment(3));
		if($update){
			//$this->session->set_flashdata('success', "User Updated Successfully");
			redirect(site_url().'/SubAdmin');
		}
	}

}
