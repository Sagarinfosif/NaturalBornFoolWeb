<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

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

	public function manage(){
		$config["base_url"] = site_url()."/Payment/manage";
		$coutData = $this->db->from("testing")->count_all_results();
	    $config["total_rows"] = $coutData;
	    $config["per_page"] = 10;
	    $config["uri_segment"] = 3;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	    $config['reuse_query_string'] = TRUE;
	    $config['full_tag_open'] = "<ul class='pagination pull-right'>";
	    $config['full_tag_close'] = '</ul>';
	    $config['num_tag_open'] = '<li>';
	    $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = '<li class="active"><a href="#">';
	    $config['cur_tag_close'] = '</a></li>';
	    $config['prev_tag_open'] = '<li>';
	    $config['prev_tag_close'] = '</li>';
	    $config['first_tag_open'] = '<li>';
	    $config['first_tag_close'] = '</li>';
	    $config['last_tag_open'] = '<li>';
	    $config['last_tag_close'] = '</li>';
	    $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous';
	    $config['prev_tag_open'] = '<li>';
	    $config['prev_tag_close'] = '</li>';
	    $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
	    $config['next_tag_open'] = '<li>';
	    $config['next_tag_close'] = '</li>';
	    $this->pagination->initialize($config);
	    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
	    $npage =  ($page-1)*10;
	    $data["links"] = $this->pagination->create_links();
	    $p = $config["per_page"];
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->query("select * from testing order by id desc limit $npage,$p")->result_array();
		$data['active'] = 'payment';
		$data['title'] = 'Manage Subscription Payments';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/Payment/Payment');
		$this->load->view('admin/includes/footer');
	}
	
	public function ppvpayment(){
		$config["base_url"] = site_url()."/Payment/ppvpayment";
		$coutData = $this->db->from("testing")->count_all_results();
	    $config["total_rows"] = $coutData;
	    $config["per_page"] = 10;
	    $config["uri_segment"] = 3;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	    $config['reuse_query_string'] = TRUE;
	    $config['full_tag_open'] = "<ul class='pagination pull-right'>";
	    $config['full_tag_close'] = '</ul>';
	    $config['num_tag_open'] = '<li>';
	    $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = '<li class="active"><a href="#">';
	    $config['cur_tag_close'] = '</a></li>';
	    $config['prev_tag_open'] = '<li>';
	    $config['prev_tag_close'] = '</li>';
	    $config['first_tag_open'] = '<li>';
	    $config['first_tag_close'] = '</li>';
	    $config['last_tag_open'] = '<li>';
	    $config['last_tag_close'] = '</li>';
	    $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous';
	    $config['prev_tag_open'] = '<li>';
	    $config['prev_tag_close'] = '</li>';
	    $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
	    $config['next_tag_open'] = '<li>';
	    $config['next_tag_close'] = '</li>';
	    $this->pagination->initialize($config);
	    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
	    $npage =  ($page-1)*10;
	    $data["links"] = $this->pagination->create_links();
	    $p = $config["per_page"];
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->query("select * from testing order by id desc limit $npage,$p")->result_array();
		$data['active'] = 'ppvpayment';
		$data['title'] = 'Manage PPV Payment';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/Payment/ppvpayment');
		$this->load->view('admin/includes/footer');
	}
	
	public function revenue(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->order_by('id','desc')->get_where('testing')->result_array();
		$data['active'] = 'revenue';
		$data['title'] = 'Manage Revenue System';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/Payment/revenue');
		$this->load->view('admin/includes/footer');
	}
	
	public function add(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'addGems';
		$data['title'] = "Add Gems";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('count', 'Gems Count', 'trim|required');
			$this->form_validation->set_rules('price', 'Gems Price', 'trim|required');
			if(empty($_FILES["image"]["name"]) || $_FILES["image"]["name"] == ""){
				$this->form_validation->set_rules('image', 'Picture', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/Gems/add');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['price'] = $this->input->post('price');
				$details['count'] = $this->input->post('count');
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
				$insert = $this->Common_model->insert_data($details,'Gems');
				if($insert){
					$this->session->set_flashdata('success', "Gems added Successfully");
					redirect(site_url().'/Gems/manage');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/Gems/add');
			$this->load->view('admin/includes/footer');
		}
	}
	
	
	public function edit(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'Gems';
		$data['title'] = "Edit Gems";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('count', 'Gems Count', 'trim|required');
			$this->form_validation->set_rules('price', 'Gems Price', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('Gems',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/Gems/edit');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['title'] = $this->input->post('title');
				$details1['count'] = $this->input->post('count');
				$details1['price'] = $this->input->post('price');
				$details1['updated'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/users/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image']= $liciense_path;
				}
				$update = $this->Common_model->update('Gems',$details1,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', "Gems Updated Successfully");
					redirect(site_url().'/Gems/manage');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('Gems',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/Gems/edit');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function delete(){
		$delete = $this->Common_model->delete('Gems','id',$this->uri->segment(3));
		redirect(site_url().'/Gems/manage');
	}
	
	public function status(){
		$details = $this->db->get_where('Gems',array('id' => $this->uri->segment(3)))->row_array();
		if($details['status'] == 'Approved'){
			$data['status'] = 'Pending';
		}
		else{
			$data['status'] = 'Approved';
		}
		$update = $this->Common_model->update('Gems',$data,'id',$this->uri->segment(3));
		if($update){
			//$this->session->set_flashdata('success', "User Updated Successfully");
			redirect(site_url().'/Gems/manage');
		}
	}

}
