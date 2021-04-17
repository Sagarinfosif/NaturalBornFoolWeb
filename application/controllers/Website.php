<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

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
		$data['logo'] = $this->db->get_where('websiteImages',array('id' => '1'))->row_array();
		$data['active'] = 'logo';
		$data['title'] = 'Update Logo';
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'id', 'trim|required');
			if(empty($_FILES["image"]["name"])){
				$this->form_validation->set_rules('image', 'Image', 'trim|required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/websites/logo');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['updated'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image']= $liciense_path;
				}
				$update = $this->Common_model->update('websiteImages',$details1,'id','1');
				if($update){
					$this->session->set_flashdata('success', "Logo Updated Successfully");
					redirect(site_url().'/Website/logo');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/logo');
			$this->load->view('admin/includes/footer');
		}
	}


	public function bannerImage(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['logo'] = $this->db->get_where('websiteImages',array('id' => '2'))->row_array();
		$data['active'] = 'bannerImage';
		$data['title'] = 'Banner Image ';
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'id', 'trim|required');
			if(empty($_FILES["image"]["name"])){
				$this->form_validation->set_rules('image', 'Image', 'trim|required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/websites/bannerImage');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['updated'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image']= $liciense_path;
				}
				$update = $this->Common_model->update('websiteImages',$details1,'id','2');
				if($update){
					$this->session->set_flashdata('success', "Banner Image Updated Successfully");
					redirect(site_url().'/Website/bannerImage');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/bannerImage');
			$this->load->view('admin/includes/footer');
		}
	}


	public function socialLinks(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['links'] = $this->db->get_where('socialLinks',array('id' => '1'))->row_array();
		$data['active'] = 'socialLinks';
		$data['title'] = 'Social Links ';
		if($this->input->post()){
			
				$details1['facebook'] = $this->input->post('facebook');
				$details1['twitter'] = $this->input->post('twitter');
				$details1['instagram'] = $this->input->post('instagram');
				$details1['skype'] = $this->input->post('skype');
				$details1['googlePlus'] = $this->input->post('googlePlus');
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('socialLinks',$details1,'id','1');
				if($update){
					$this->session->set_flashdata('success', "Social Links Updated Successfully");
					redirect(site_url().'/Website/socialLinks');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/socialLinks');
			$this->load->view('admin/includes/footer');
		}
	}
	

	public function languages(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->db->get_where('languages')->result_array();
		$data['active'] = 'languages';
		$data['title'] = 'Manage Languages';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/websites/languages');
		$this->load->view('admin/includes/footer');
	}

	public function addLanguage(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'languages';
		$data['title'] = 'Add Language';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title ( In English ) ', 'trim|required');
			$this->form_validation->set_rules('title2', 'Title ( Own Language )', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/websites/addLanguage');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['title'] = $this->input->post('title');
				$details1['title2'] = $this->input->post('title2');
				$details1['created'] = date('Y-m-d H:i:s');
				$insert = $this->db->insert('languages',$details1);
				if($insert){
					$this->session->set_flashdata('success', "Language added Successfully");
					redirect(site_url().'/Website/addLanguage');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/addLanguage');
			$this->load->view('admin/includes/footer');
		}
	}
	
	public function editLanguage(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['links'] = $this->db->get_where('languages',array('id' => $this->uri->segment(3)))->row_array();
		$data['active'] = 'languages';
		$data['title'] = 'Update Language ';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Title ( In English ) ', 'trim|required');
			$this->form_validation->set_rules('title2', 'Title ( Own Language )', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/websites/editLanguage');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['title'] = $this->input->post('title');
				$details1['title2'] = $this->input->post('title2');
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('languages',$details1,'id',$this->uri->segment(3));
				if($update){
					$this->session->set_flashdata('success', "Language Updated Successfully");
					redirect(site_url().'/Website/languages');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/editLanguage');
			$this->load->view('admin/includes/footer');
		}
	}

	public function deleteLanguage(){
		$delete = $this->Common_model->delete('languages','id',$this->uri->segment(3));
		if($delete){
			$this->session->set_flashdata('success', "Language Deleted Successfully");
			redirect(site_url().'/Website/languages');
		}
		
	}

	public function youAreAStar(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['youAreAStar'] = $this->db->get_where('websiteImages',array('id' => '3'))->row_array();
		$data['active'] = 'youAreAStar';
		$data['title'] = 'You Are A Star';
		if($this->input->post()){
			
				$details1['title'] = $this->input->post('title');
				$details1['content'] = $this->input->post('content');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image']= $liciense_path;
				}
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','3');
				if($update){
					$this->session->set_flashdata('success', "Updated Successfully");
					redirect(site_url().'/Website/youAreAStar');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/youAreAStar');
			$this->load->view('admin/includes/footer');
		}
	}


	public function websiteVideo(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['websiteVideo'] = $this->db->get_where('websiteImages',array('id' => '9'))->row_array();
		$data['active'] = 'websiteVideo';
		$data['title'] = 'Website Video';
		if($this->input->post()){
				$details1['videoUrl1'] = $this->input->post('videoUrl1');
				$details1['videoUrl2'] = $this->input->post('videoUrl2');
				$details1['videoUrl3'] = $this->input->post('videoUrl3');
				$details1['videoUrl4'] = $this->input->post('videoUrl4');
				$details1['videoUrl5'] = $this->input->post('videoUrl5');
				$details1['videoUrl6'] = $this->input->post('videoUrl6');
				$details1['videoUrl7'] = $this->input->post('videoUrl7');
				$details1['videoUrl8'] = $this->input->post('videoUrl8');
				$details1['videoUrl9'] = $this->input->post('videoUrl9');
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','9');
				if($update){
					$this->session->set_flashdata('success', "Video Updated Successfully");
					redirect(site_url().'/Website/websiteVideo');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/websiteVideo');
			$this->load->view('admin/includes/footer');
		}
	}


	public function websiteImages(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['websiteImages'] = $this->db->get_where('websiteImages',array('id' => '4'))->row_array();
		$data['active'] = 'websiteImages';
		$data['title'] = 'Image Section';
		if($this->input->post()){
				if(!empty($_FILES["image1"]["name"])){
					$name= time().'_'.$_FILES["image1"]["name"];
					$liciense_tmp_name=$_FILES["image1"]["tmp_name"];
					$error=$_FILES["image1"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image1']= $liciense_path;
				}
				if(!empty($_FILES["image2"]["name"])){
					$name= time().'_'.$_FILES["image2"]["name"];
					$liciense_tmp_name=$_FILES["image2"]["tmp_name"];
					$error=$_FILES["image2"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image2']= $liciense_path;
				}
				if(!empty($_FILES["image3"]["name"])){
					$name= time().'_'.$_FILES["image3"]["name"];
					$liciense_tmp_name=$_FILES["image3"]["tmp_name"];
					$error=$_FILES["image3"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image3']= $liciense_path;
				}
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','4');
				if($update){
					$this->session->set_flashdata('success', "Images Updated Successfully");
					redirect(site_url().'/Website/websiteImages');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/websiteImage');
			$this->load->view('admin/includes/footer');
		}
	}


	public function theWordIsWatching(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['theWordIsWatching'] = $this->db->get_where('websiteImages',array('id' => '5'))->row_array();
		$data['active'] = 'theWordIsWatching';
		$data['title'] = 'THE WORLD IS WATCHING';
		if($this->input->post()){
				$details1['title'] = $this->input->post('title');
				$details1['subtitle'] = $this->input->post('subtitle');
				$details1['content'] = $this->input->post('content');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/websites'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['image']= $liciense_path;
				}
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','5');
				if($update){
					$this->session->set_flashdata('success', "Updated Successfully");
					redirect(site_url().'/Website/theWordIsWatching');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/theWordIsWatching');
			$this->load->view('admin/includes/footer');
		}
	}

	public function applinks(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['applinks'] = $this->db->get_where('websiteImages',array('id' => '6'))->row_array();
		$data['active'] = 'applinks';
		$data['title'] = 'App links';
		if($this->input->post()){
				$details1['playStore'] = $this->input->post('playStore');
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','6');
				if($update){
					$this->session->set_flashdata('success', "App links Updated Successfully");
					redirect(site_url().'/Website/applinks');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/applinks');
			$this->load->view('admin/includes/footer');
		}
	}


	public function email(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['email'] = $this->db->get_where('websiteImages',array('id' => '7'))->row_array();
		$data['active'] = 'email';
		$data['title'] = 'Email & Address';
		if($this->input->post()){
				$details1['email'] = $this->input->post('email');
				$details1['address'] = $this->input->post('address');
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','7');
				if($update){
					$this->session->set_flashdata('success', "Email & Address Updated Successfully");
					redirect(site_url().'/Website/email');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/email');
			$this->load->view('admin/includes/footer');
		}
	}

	public function footerContent(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['email'] = $this->db->get_where('websiteImages',array('id' => '8'))->row_array();
		$data['active'] = 'Content';
		$data['title'] = 'Footer Content';
		if($this->input->post()){
				$details1['footerContent1'] = $this->input->post('footerContent1');
				$details1['footerContent2'] = $this->input->post('footerContent2');
				$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('websiteImages',$details1,'id','8');
				if($update){
					$this->session->set_flashdata('success', "Updated Successfully");
					redirect(site_url().'/Website/footerContent');
				}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/websites/footerContent');
			$this->load->view('admin/includes/footer');
		}
	}


	public function privacyPolicy(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'privacyPolicy';
		$data['title'] = "Update Privacy Policy";
		$data['details'] = $this->db->get_where('pages',array('id' =>'1'))->row_array();
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Page Title', 'trim|required');
			$this->form_validation->set_rules('description', 'Page Description', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/pages/editPage');
				$this->load->view('admin/includes/footer');
			}else{
				$details['name'] = $this->input->post('title');
				$details['description'] = $this->input->post('description');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('pages',$details,'id','1');
				if($update){
					$this->session->set_flashdata('success', "Privacy Policy Updated Successfully");
					redirect(site_url().'/Website/privacyPolicy');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/pages/editPage');
			$this->load->view('admin/includes/footer');
		}
	}

	public function termsAndConditions(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'termsAndConditions';
		$data['title'] = "Update Terms And Conditions";
		$data['details'] = $this->db->get_where('pages',array('id' =>'2'))->row_array();
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Page Title', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/pages/editPage');
				$this->load->view('admin/includes/footer');
			}else{
				$details['name'] = $this->input->post('title');
				$details['description'] = $this->input->post('description');
				$details['updated'] = date('y-m-d h:i:s');
				$update = $this->Common_model->update('pages',$details,'id','2');
				if($update){
					$this->session->set_flashdata('success', "Terms And Conditions Updated Successfully");
					redirect(site_url().'/Website/termsAndConditions');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/pages/editPage');
			$this->load->view('admin/includes/footer');
		}
	}
}