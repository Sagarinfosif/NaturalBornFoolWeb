<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserData extends CI_Controller {

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
		$data['admin'] = $this->db->get_where('admin',array('id'=>$admin_details['admin_id']))->row_array();
		$uniqueId = $this->db->get_where('uniqueId',array('id'=>1))->row_array();
		$x = $uniqueId['uniqueId'];
		$data['details'] = $this->db->query("SELECT uniqueId,SUM(A0) AS A0,SUM(A1) AS A1,SUM(A2) AS A2,SUM(A3) AS A3,SUM(A4) AS A4,SUM(A5) AS A5,SUM(A6) AS A6,SUM(A7) AS A7,SUM(A8) AS A8,SUM(A9) AS A9,SUM(B0) AS B0,SUM(B1) AS B1,SUM(B2) AS B2,SUM(B3) AS B3,SUM(B4) AS B4,SUM(B5) AS B5,SUM(B6) AS B6,SUM(B7) AS B7,SUM(B8) AS B8,SUM(B9) AS B9 FROM userData WHERE uniqueId = '$x'")->row_array();

		$data['userCount'] = $this->db->get_where('dataCount',array('uniqueId'=>$x))->row_array();
		$data['resultOut'] = $this->db->get_where('uniqueIdResult',array('uniqueId'=>$x))->row_array();

		$data['active'] = 'user';
		$data['title'] = 'Manage User';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/userData/manage');
	//	$this->load->view('admin/includes/footer');
	}

	public function resultA(){
		$uniqueId = $this->db->get_where('uniqueId',array('id'=>1))->row_array();
		$x = $uniqueId['uniqueId'];
		$data['resultA']= $this->input->post('valA');
		$update = $this->Common_model->update('uniqueIdResult',$data,'uniqueId',$x);
		if($update){
			echo "done";
		}
	}
	public function resultB(){
		$uniqueId = $this->db->get_where('uniqueId',array('id'=>1))->row_array();
		$x = $uniqueId['uniqueId'];
		$data['resultB']= $this->input->post('valB');
		$update = $this->Common_model->update('uniqueIdResult',$data,'uniqueId',$x);
		if($update){
			echo "done";
		}
	}




}
