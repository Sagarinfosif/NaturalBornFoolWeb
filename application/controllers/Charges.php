<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charges extends CI_Controller {

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
		$data['details'] = $this->db->get('charges')->result_array();
		$data['active'] = 'charges';
		$data['title'] = "Manage Charges";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/deliveryCharges/manageCharges');
		$this->load->view('admin/includes/footer');
	}
	
	public function addCharges(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'addcharges';
		$data['title'] = "Add Charges";
		if($this->input->post()){
			$this->form_validation->set_rules('slimit', 'Start Limit', 'trim|required');
			// $this->form_validation->set_rules('elimit', 'End Limit', 'trim|required');
			$this->form_validation->set_rules('type', 'Charges Type', 'trim|required');
			$this->form_validation->set_rules('charges', 'Charges', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/deliveryCharges/addCharges');
				$this->load->view('admin/includes/footer');
			}else{
				$details['slimit'] = $this->input->post('slimit');
				$details['elimit'] = $this->input->post('elimit');
				$details['charges'] = $this->input->post('charges');
				$details['type'] = $this->input->post('type');
				$details['created'] = date('y-m-d h:i:s');
				$insert = $this->Common_model->insert_data($details,'charges');
				if($insert){
					$this->session->set_flashdata('success', "Charges added Successfully");
					redirect(site_url().'/Charges');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/deliveryCharges/addCharges');
			$this->load->view('admin/includes/footer');
		}
	}

	public function editCharges(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'editcharges';
		$data['title'] = "Update Charges";
		if($this->input->post()){
			$this->form_validation->set_rules('slimit', 'Start Limit', 'trim|required');
			// $this->form_validation->set_rules('elimit', 'End Limit', 'trim|required');
			$this->form_validation->set_rules('type', 'Charges Type', 'trim|required');
			$this->form_validation->set_rules('charges', 'Charges', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('charges',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/deliveryCharges/editCharges');
				$this->load->view('admin/includes/footer');
			}else{
				$details['slimit'] = $this->input->post('slimit');
				$details['elimit'] = $this->input->post('elimit');
				$details['charges'] = $this->input->post('charges');
				$details['type'] = $this->input->post('type');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('charges',$details,'id',$id);
				if($update){
					$this->session->set_flashdata('success', "Charges Updated Successfully");
					redirect(site_url().'/Charges');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('charges',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/deliveryCharges/editCharges');
			$this->load->view('admin/includes/footer');
		}
	}

	public function deleteCharges(){
		$delete = $this->Common_model->delete('charges','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', "Charges Delete Successfully");
		}
		redirect(site_url().'/Charges');
	}
	
}