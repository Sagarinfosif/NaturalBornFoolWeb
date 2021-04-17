<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
	
	public function stitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('homelist',array('id' => 1))->row_array();
		$data['active'] = 'stitle';
		$data['title'] = 'Manage Title';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/studyvisa/managetitle');
		$this->load->view('admin/includes/footer'); 
	}
	
	public function editstitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'stitle';
		$data['title'] = 'Update Title';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/updatetitle');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['title'] = $this->input->post('title');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$update = $this->Common_model->update('homelist',$details,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', 'Title Updated Successfully');
					redirect(site_url().'/Home/stitle');
				}
			}
		}
		else{
			$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/updatetitle');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function scountry(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
		$data['active'] = 'scountry';
		$data['title'] = 'Manage Country';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/studyvisa/managecountry');
		$this->load->view('admin/includes/footer'); 
	}
	
	public function addscountry(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'scountry';
		$data['title'] = 'Add Country';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Country', 'trim|required');
			if (empty($_FILES['image']['name'])){
    			$this->form_validation->set_rules('image', 'Country Image', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/addcountry');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['status'] = '0';
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/country/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->insert_data($details,'countrylist');
				if($insert){
					$this->session->set_flashdata('success', 'Country Insert Successfully');
					redirect(site_url().'/Home/scountry');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/addcountry');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function editscontry(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'scountry';
		$data['title'] = 'Update Country';
		if($this->input->post()){
			$this->form_validation->set_rules('title', ' Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('countrylist',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/editcountry');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/country/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->update('countrylist',$details,'id',$this->input->post('id'));
				if($insert){
					$this->session->set_flashdata('success', 'Country Updated Successfully');
					redirect(site_url().'/Home/scountry');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('countrylist',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/editcountry');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function deletescountry(){
		$delete = $this->Common_model->delete('countrylist','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'Category Delete Successfully.');
		}
		redirect(site_url().'/Home/scountry');
	}
	
	public function suniversity(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->Common_model->getUniversity();
		$data['active'] = 'suniversity';
		$data['title'] = 'Manage University';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/studyvisa/manageuniversity');
		$this->load->view('admin/includes/footer');
	}
	
	public function addsuniversity(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'suniversity';
		$data['title'] = 'Add University';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'University', 'trim|required');
			$this->form_validation->set_rules('countryId', 'Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/adduniveristy');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['countryId'] = $this->input->post('countryId');
				$details['status'] = '0';
				$insert = $this->Common_model->insert_data($details,'university');
				if($insert){
					$this->session->set_flashdata('success', 'University Insert Successfully');
					redirect(site_url().'/Home/suniversity');
				}
			}
		}else{
			$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/adduniveristy');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function editsuniversity(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'suniversity';
		$data['title'] = 'Update University';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'University', 'trim|required');
			$this->form_validation->set_rules('countryId', 'Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
				$data['details'] = $this->db->get_where('university',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/edituniveristy');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['countryId'] = $this->input->post('countryId');
				$insert = $this->Common_model->update('university',$details,'id',$this->input->post('id'));
				if($insert){
					$this->session->set_flashdata('success', 'University Updated Successfully');
					redirect(site_url().'/Home/suniversity');
				}
			}
		}else{
			$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
			$data['details'] = $this->db->get_where('university',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/edituniveristy');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function deletesuniversity(){
		$delete = $this->Common_model->delete('university','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'University Delete Successfully.');
		}
		redirect(site_url().'/Home/suniversity');
	}

	public function sschool(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->Common_model->getSchool();
		$data['active'] = 'sschool';
		$data['title'] = 'Manage School';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/studyvisa/manageschool');
		$this->load->view('admin/includes/footer');
	}
	
	public function addsschool(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'sschool';
		$data['title'] = 'Add School';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'School', 'trim|required');
			$this->form_validation->set_rules('countryId', 'Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/addschool');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['countryId'] = $this->input->post('countryId');
				$details['status'] = '0';
				$insert = $this->Common_model->insert_data($details,'school');
				if($insert){
					$this->session->set_flashdata('success', 'School Insert Successfully');
					redirect(site_url().'/Home/sschool');
				}
			}
		}else{
			$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/addschool');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function editsschool(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'sschool';
		$data['title'] = 'Update School';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'School', 'trim|required');
			$this->form_validation->set_rules('countryId', 'Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
				$data['details'] = $this->db->get_where('school',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/editschool');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['countryId'] = $this->input->post('countryId');
				$insert = $this->Common_model->update('school',$details,'id',$this->input->post('id'));
				if($insert){
					$this->session->set_flashdata('success', 'School Updadted Successfully');
					redirect(site_url().'/Home/sschool');
				}
			}
		}else{
			$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
			$data['details'] = $this->db->get_where('school',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/editschool');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function deletesschool(){
		$delete = $this->Common_model->delete('school','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'School Delete Successfully.');
		}
		redirect(site_url().'/Home/sschool');
	}
	
	public function sstudies(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->Common_model->getStudies();
		$data['active'] = 'sstudies';
		$data['title'] = 'Manage Studies';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/studyvisa/managestudies');
		$this->load->view('admin/includes/footer');
	}
	
	public function addsstudies(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'sstudies';
		$data['title'] = 'Add Studies';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Studies', 'trim|required');
			$this->form_validation->set_rules('countryId', 'Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/addstudies');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['countryId'] = $this->input->post('countryId');
				$details['status'] = '0';
				$insert = $this->Common_model->insert_data($details,'studies');
				if($insert){
					$this->session->set_flashdata('success', 'Studies Insert Successfully');
					redirect(site_url().'/Home/sstudies');
				}
			}
		}else{
			$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/addstudies');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function editsstudies(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'sstudies';
		$data['title'] = 'Update Studies';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Studies', 'trim|required');
			$this->form_validation->set_rules('countryId', 'Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
				$data['details'] = $this->db->get_where('studies',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/studyvisa/editstudies');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['countryId'] = $this->input->post('countryId');
				$details['status'] = '0';
				$insert = $this->Common_model->update('studies',$details,'id',$this->input->post('id'));
				if($insert){
					$this->session->set_flashdata('success', 'Studies Updated Successfully');
					redirect(site_url().'/Home/sstudies');
				}
			}
		}else{
			$data['country'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '0'))->result_array();
			$data['details'] = $this->db->get_where('studies',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/studyvisa/editstudies');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function deletesstudies(){
		$delete = $this->Common_model->delete('studies','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'Studies Delete Successfully.');
		}
		redirect(site_url().'/Home/sstudies');
	}
	
	public function ttitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('homelist',array('id' => 2))->row_array();
		$data['active'] = 'ttitle';
		$data['title'] = 'Manage Title';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/tourist/managetitle');
		$this->load->view('admin/includes/footer'); 
	}
	
	public function editttitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'ttitle';
		$data['title'] = 'Update Title';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/tourist/updatetitle');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['title'] = $this->input->post('title');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$update = $this->Common_model->update('homelist',$details,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', 'Title Updated Successfully');
					redirect(site_url().'/Home/ttitle');
				}
			}
		}
		else{
			$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/tourist/updatetitle');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function tcountry(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->order_by('id','desc')->get_where('countrylist',array('status' => '1'))->result_array();
		$data['active'] = 'tcountry';
		$data['title'] = 'Manage Country';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/tourist/managecountry');
		$this->load->view('admin/includes/footer'); 
	}

	public function addtcountry(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'tcountry';
		$data['title'] = 'Add Country';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Country', 'trim|required');
			if (empty($_FILES['image']['name'])){
    			$this->form_validation->set_rules('image', 'Country Image', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/tourist/addcountry');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['status'] = '1';
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/country/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->insert_data($details,'countrylist');
				if($insert){
					$this->session->set_flashdata('success', 'Country Insert Successfully');
					redirect(site_url().'/Home/tcountry');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/tourist/addcountry');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function edittcontry(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'tcountry';
		$data['title'] = 'Update Country';
		if($this->input->post()){
			$this->form_validation->set_rules('title', ' Country', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('countrylist',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/tourist/editcountry');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/country/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->update('countrylist',$details,'id',$this->input->post('id'));
				if($insert){
					$this->session->set_flashdata('success', 'Country Updated Successfully');
					redirect(site_url().'/Home/tcountry');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('countrylist',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/tourist/editcountry');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function deletetcountry(){
		$delete = $this->Common_model->delete('countrylist','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'Category Delete Successfully.');
		}
		redirect(site_url().'/Home/tcountry');
	}
	
	public function ftitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('homelist',array('id' => 3))->row_array();
		$data['active'] = 'ftitle';
		$data['title'] = 'Manage Title';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/file/managetitle');
		$this->load->view('admin/includes/footer'); 
	}
	
	public function edifttitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'ftitle';
		$data['title'] = 'Update Title';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/file/updatetitle');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['title'] = $this->input->post('title');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$update = $this->Common_model->update('homelist',$details,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', 'Title Updated Successfully');
					redirect(site_url().'/Home/ftitle');
				}
			}
		}
		else{
			$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/file/updatetitle');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function ititle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('homelist',array('id' => 4))->row_array();
		$data['active'] = 'ititle';
		$data['title'] = 'Manage Title';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/immigration/managetitle');
		$this->load->view('admin/includes/footer'); 
	}
	
	public function ediittitle(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'ititle';
		$data['title'] = 'Update Title';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/immigration/updatetitle');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['title'] = $this->input->post('title');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$update = $this->Common_model->update('homelist',$details,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', 'Title Updated Successfully');
					redirect(site_url().'/Home/ititle');
				}
			}
		}
		else{
			$data['details'] = $this->db->get_where('homelist',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/immigration/updatetitle');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function inews(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->order_by('id','desc')->get_where('news')->result_array();
		$data['active'] = 'inews';
		$data['title'] = 'Manage News';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/home/immigration/managenews');
		$this->load->view('admin/includes/footer'); 
	}
	
	public function addinews(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'inews';
		$data['title'] = 'Add News';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Heading', 'trim|required');
			$this->form_validation->set_rules('description', 'News', 'trim|required');
			if (empty($_FILES['image']['name'])){
    			$this->form_validation->set_rules('image', 'News Image', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/immigration/addnews');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['description'] = $this->input->post('description');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/country/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->insert_data($details,'news');
				if($insert){
					$this->session->set_flashdata('success', 'News Insert Successfully');
					redirect(site_url().'/Home/inews');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/immigration/addnews');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function editnews(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'inews';
		$data['title'] = 'Update News';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Heading', 'trim|required');
			$this->form_validation->set_rules('description', 'News', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('news',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/home/immigration/editnews');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['description'] = $this->input->post('description');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/country/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->update('news',$details,'id',$this->input->post('id'));
				if($insert){
					$this->session->set_flashdata('success', 'News Updated Successfully');
					redirect(site_url().'/Home/inews');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('news',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/home/immigration/editnews');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function deletenews(){
		$delete = $this->Common_model->delete('news','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'News Delete Successfully.');
		}
		redirect(site_url().'/Home/inews');
	}

}