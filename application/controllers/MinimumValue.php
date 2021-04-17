<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MinimumValue extends CI_Controller {

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
		$data['details'] = $this->db->get('minimumValue')->result_array();
		$data['active'] = 'minimum';
		$data['title'] = "Manage Minimum Values";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/minimumValue/manageMinimumValue');
		$this->load->view('admin/includes/footer');
	}

	public function editValue(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'editValue';
		$data['title'] = "Update Minimum Values";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Minimum Price', 'trim|required');
			$this->form_validation->set_rules('ans', 'Minimum Quantity', 'trim|required');
			$this->form_validation->set_rules('text', 'Minimum Price Text', 'trim|required|strip_tags');
			$this->form_validation->set_rules('text1', 'Minimum Quantity Text', 'trim|required|strip_tags');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('minimumValue',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/minimumValue/editValue');
				$this->load->view('admin/includes/footer');
			}else{
				$details['minimumPrice'] = $this->input->post('title');
				$details['minimumQuantity'] = $this->input->post('ans');
				$details['priceText'] = $this->input->post('text');
				$details['quantityText'] = $this->input->post('text1');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('minimumValue',$details,'id',$id);
				if($update){
					$this->session->set_flashdata('success', "Data Updated Successfully");
					redirect(site_url().'/MinimumValue');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('minimumValue',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/minimumValue/editValue');
			$this->load->view('admin/includes/footer');
		}
	}
	
}