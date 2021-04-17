<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time extends CI_Controller {

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
		$data['details'] = $this->db->get('deliveryTime')->result_array();
		$data['active'] = 'dtime';
		$data['title'] = "Manage Delivery Time";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/time/manageTime');
		$this->load->view('admin/includes/footer');
	}

	public function editTime(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'edittime';
		$data['title'] = "Update Delivery Time";
		if($this->input->post()){
			$this->form_validation->set_rules('stime', 'Delivery Start Time', 'trim|required');
			$this->form_validation->set_rules('etime', 'Delivery End Time', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('deliveryTime',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/time/editTime');
				$this->load->view('admin/includes/footer');
			}else{
				$details['stime'] = $this->input->post('stime');
				$details['etime'] = $this->input->post('etime');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('deliveryTime',$details,'id',$id);
				if($update){
					$this->session->set_flashdata('success', "Time Updated Successfully");
					redirect(site_url().'/Time');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('deliveryTime',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/time/editTime');
			$this->load->view('admin/includes/footer');
		}
	}
	
}