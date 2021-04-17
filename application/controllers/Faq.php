<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

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
		$data['details'] = $this->db->get('faq')->result_array();
		$data['active'] = 'faq';
		$data['title'] = "Manage FAQ's";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/faq/manageFaq');
		$this->load->view('admin/includes/footer');
	}
	
	public function addFaq(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'addfaq';
		$data['title'] = "Add FAQ's";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Question', 'trim|required');
			$this->form_validation->set_rules('ans', 'Answer', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/faq/addFaq');
				$this->load->view('admin/includes/footer');
			}else{
				$details['question'] = $this->input->post('title');
				$details['answer'] = $this->input->post('ans');
				$details['created'] = date('y-m-d h:i:s');
				$insert = $this->Common_model->insert_data($details,'faq');
				if($insert){
					$this->session->set_flashdata('success', "Faq's inserted Successfully");
					redirect(site_url().'/Faq');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/faq/addFaq');
			$this->load->view('admin/includes/footer');
		}
	}

	public function editFaq(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'editfaq';
		$data['title'] = "Update FAQ's";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Product Title', 'trim|required');
			$this->form_validation->set_rules('ans', 'Answer', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('faq',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/faq/editFaq');
				$this->load->view('admin/includes/footer');
			}else{
				$details['question'] = $this->input->post('title');
				$details['answer'] = $this->input->post('ans');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('faq',$details,'id',$id);
				if($update){
					$this->session->set_flashdata('success', "Faq's Updated Successfully");
					redirect(site_url().'/Faq');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('faq',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/faq/editFaq');
			$this->load->view('admin/includes/footer');
		}
	}

	public function deleteFaq(){
		$delete = $this->Common_model->delete('faq','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', "Faq's Delete Successfully");
		}
		redirect(site_url().'/Faq');
	}
	
}