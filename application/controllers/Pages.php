<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

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
		$data['details'] = $this->db->get('pages')->result_array();
		$data['active'] = 'pages';
		$data['title'] = "Manage Pages";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/pages/managePages');
		$this->load->view('admin/includes/footer');
	}

	public function editPages(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'editPages';
		$data['title'] = "Update Page";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Page Title', 'trim|required');
			$this->form_validation->set_rules('description', 'Page Description', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('pages',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/pages/editPage');
				$this->load->view('admin/includes/footer');
			}else{
				$details['name'] = $this->input->post('title');
				$details['description'] = $this->input->post('description');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('pages',$details,'id',$id);
				if($update){
					$this->session->set_flashdata('success', "Page Updated Successfully");
					redirect(site_url().'/Pages');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('pages',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/pages/editPage');
			$this->load->view('admin/includes/footer');
		}
	}


	public function page_set_status(){
		$check_status = $this->db->get_where('pages',array('id'=>$this->input->post('id')))->row_array();
		if($check_status['status']=='1'){
			$data = array('status'=>'2');
		}else{
			$data = array('status'=>'1');
		}
		$update = $this->db->update('pages',$data,array('id'=>$this->input->post('id')));
		if($update){	
			echo $data['status'];
		}else{
			echo $data['status'];
		}
	}

	
}