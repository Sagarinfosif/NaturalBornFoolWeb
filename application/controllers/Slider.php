<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Slider extends CI_Controller {



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
		$config["base_url"] = site_url()."/Slider/manage";
		$coutData = $this->db->from("slider")->count_all_results();
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

		$data['details'] = $this->db->query("select * from slider order by id desc limit $npage,$p")->result_array();

		$data['active'] = 'slider';

		$data['title'] = 'Manage Slider';

		$this->load->view('admin/includes/header',$data);

		$this->load->view('admin/slider/manage');

		$this->load->view('admin/includes/footer');

	}

	public function getResult(){
    	$start = $this->input->post('s');
    	$end = $this->input->post('e');
    	$pname = $this->input->post('p');
    	$admin_details = $this->session->userdata('admin_details');
		$admin = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$admin = $admin_details['admin_id'];
		if(!empty($end) && !empty($pname) && !empty($start)){
           	$data=$this->db->query("SELECT * from slider where created between '$start' and '$end' or updated between '$start' and '$end' and title like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($end)){
           	$data=$this->db->query("SELECT * from slider where created between '$start' and '$end' or updated between '$start' and '$end' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($pname)){
           	$data=$this->db->query("SELECT * from slider where created = '$start' or updated = '$start' and title like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end) && !empty($pname)){
           	$data=$this->db->query("SELECT * from slider where created = '$end' or updated = '$end' and title like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start)){
           	$data=$this->db->query("SELECT * from slider where created = '$start' or upated = '$start' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end)){
            $data=$this->db->query("SELECT * from slider where created = '$end' or updated = '$end' order by id desc")->result_array();
            exit(json_encode($data));
        }elseif(!empty($pname)){
            $data=$this->db->query("SELECT * from slider where title like'%$pname%' order by id desc")->result_array();
              exit(json_encode($data));
        }else{
        	$data=$this->db->query("SELECT * from slider order by id desc")->result_array();
            exit(json_encode($data));
        }
    }

	public function add(){

		$admin_details = $this->session->userdata('admin_details');

		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);

		$data['active'] = 'addSlider';

		$data['title'] = "Add Slider";

		if($this->input->post()){

			$this->form_validation->set_rules('title', 'Title', 'trim|required|is_unique[subAdmin.username]');

			$this->form_validation->set_rules('description', 'Description', 'trim|required');

			if(empty($_FILES["image"]["name"]) || $_FILES["image"]["name"] == ""){

				$this->form_validation->set_rules('image', 'Picture', 'required');

			}

			if($this->form_validation->run() == FALSE){

				$this->load->view('admin/includes/header',$data);

				$this->load->view('admin/slider/add');

				$this->load->view('admin/includes/footer');

			}else{

				$details['title'] = $this->input->post('title');

				$details['description'] = $this->input->post('description');

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

				$insert = $this->Common_model->insert_data($details,'slider');

				if($insert){

					$this->session->set_flashdata('success', "Slider added Successfully");

					redirect(site_url().'/Slider/manage');

				}

			}

		}else{

			$this->load->view('admin/includes/header',$data);

			$this->load->view('admin/slider/add');

			$this->load->view('admin/includes/footer');

		}

	}

	

	

	public function edit(){

		$admin_details = $this->session->userdata('admin_details');

		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);

		$data['active'] = 'slider';

		$data['title'] = "Edit Slider";

		if($this->input->post()){

			$this->form_validation->set_rules('title', 'Title', 'trim|required');

			$this->form_validation->set_rules('description', 'Mobile', 'trim|required');

			if($this->form_validation->run() == FALSE){

				$data['details'] = $this->db->get_where('slider',array('id' => $this->uri->segment(3)))->row_array();

				$this->load->view('admin/includes/header',$data);

				$this->load->view('admin/slider/edit');

				$this->load->view('admin/includes/footer');

			}else{

				$details1['title'] = $this->input->post('title');

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

				$update = $this->Common_model->update('slider',$details1,'id',$this->input->post('id'));

				if($update){

					$this->session->set_flashdata('success', "Slider Updated Successfully");

					redirect(site_url().'/Slider/manage');

				}

			}

		}else{

			$data['details'] = $this->db->get_where('slider',array('id' => $this->uri->segment(3)))->row_array();

			$this->load->view('admin/includes/header',$data);

			$this->load->view('admin/slider/edit');

			$this->load->view('admin/includes/footer');

		}

	}

	

	public function delete(){

		$delete = $this->Common_model->delete('slider','id',$this->uri->segment(3));

		redirect(site_url().'/Slider/manage');

	}

	

	public function status(){

		$details = $this->db->get_where('slider',array('id' => $this->uri->segment(3)))->row_array();

		if($details['status'] == 'Approved'){

			$data['status'] = 'Pending';

		}

		else{

			$data['status'] = 'Approved';

		}

		$update = $this->Common_model->update('slider',$data,'id',$this->uri->segment(3));

		if($update){

			//$this->session->set_flashdata('success', "User Updated Successfully");

			redirect(site_url().'/Slider/manage');

		}

	}



}

