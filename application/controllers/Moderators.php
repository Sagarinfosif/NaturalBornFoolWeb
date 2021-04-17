<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Moderators extends CI_Controller {



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
		$config["base_url"] = site_url()."/Moderators/manage";
		$coutData = $this->db->from("moderators")->count_all_results();
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

		$data['details'] = $this->db->query("select * from moderators order by id desc limit $npage,$p")->result_array();

		$data['active'] = 'moderators';

		$data['title'] = 'Manage Moderators';

		$this->load->view('admin/includes/header',$data);

		$this->load->view('admin/moderators/manage');

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
           	$data=$this->db->query("SELECT * from moderators where created between '$start' and '$end' and username like '%$pname%' or email like '%$pname%' or phone like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($end)){
           	$data=$this->db->query("SELECT * from moderators where created between '$start' and '$end' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($pname)){
           	$data=$this->db->query("SELECT * from moderators where created = '$start' and username like '%$pname%' or email like '%$pname%' or phone like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end) && !empty($pname)){
           	$data=$this->db->query("SELECT * from moderators where created = '$end' and username like '%$pname%' or email like '%$pname%' or phone like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start)){
           	$data=$this->db->query("SELECT * from moderators where created = '$start' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end)){
            $data=$this->db->query("SELECT * from moderators where created = '$end' order by id desc")->result_array();
            exit(json_encode($data));
        }elseif(!empty($pname)){
            $data=$this->db->query("SELECT * from moderators where username like'%$pname%' or email like '%$pname%' or phone like '%$pname%' order by id desc")->result_array();
              exit(json_encode($data));
        }else{
        	$data=$this->db->query("SELECT * from moderators order by id desc")->result_array();
            exit(json_encode($data));
        }
    }

	public function addModerators(){

		$admin_details = $this->session->userdata('admin_details');

		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);

		$data['active'] = 'addModerators';

		$data['title'] = "Add Moderators";

		if($this->input->post()){

			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[moderators.username]');

			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[moderators.email]');

			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|is_unique[moderators.phone]');

			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]');

			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');

			if($this->form_validation->run() == FALSE){

				$this->load->view('admin/includes/header',$data);

				$this->load->view('admin/moderators/add');

				$this->load->view('admin/includes/footer');

			}else{

				$details['username'] = $this->input->post('username');

				$details['email'] = $this->input->post('email');

				$details['phone'] = $this->input->post('phone');

				$details['password'] = md5($this->input->post('password'));

				$details['status'] = "Approved";

				$details['created'] = date('Y-m-d H:i:s');

				$insert = $this->Common_model->insert_data($details,'moderators');

				if($insert){

					$this->session->set_flashdata('success', "Moderators added Successfully");

					redirect(site_url().'/Moderators/manage');

				}

			}

		}else{

			$this->load->view('admin/includes/header',$data);

			$this->load->view('admin/moderators/add');

			$this->load->view('admin/includes/footer');

		}

	}

	

	public function view(){

		$admin_details = $this->session->userdata('admin_details');

		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);

		$data['active'] = 'moderators';

		$data['title'] = "View Moderators";

		$data['details'] = $this->db->get_where('moderators',array('id' => $this->uri->segment(3)))->row_array();

		$this->load->view('admin/includes/header',$data);

		$this->load->view('admin/moderators/view');

		$this->load->view('admin/includes/footer');

	}

	

	public function edit(){

		$admin_details = $this->session->userdata('admin_details');

		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);

		$data['active'] = 'moderators';

		$data['title'] = "Edit Moderators";

		if($this->input->post()){

			$this->form_validation->set_rules('username', 'Username', 'trim|required');

			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required');

			if($this->form_validation->run() == FALSE){

				$data['details'] = $this->db->get_where('moderators',array('id' => $this->uri->segment(3)))->row_array();

				$this->load->view('admin/includes/header',$data);

				$this->load->view('admin/moderators/edit');

				$this->load->view('admin/includes/footer');

			}else{

				$details1['username'] = $this->input->post('username');

				$details1['email'] = $this->input->post('email');

				$details1['phone'] = $this->input->post('phone');

				$details1['updated'] = date('Y-m-d H:i:s');

				$update = $this->Common_model->update('moderators',$details1,'id',$this->input->post('id'));

				if($update){

					$this->session->set_flashdata('success', "Moderators Updated Successfully");

					redirect(site_url().'/moderators/manage');

				}

			}

		}else{

			$data['details'] = $this->db->get_where('moderators',array('id' => $this->uri->segment(3)))->row_array();

			$this->load->view('admin/includes/header',$data);

			$this->load->view('admin/moderators/edit');

			$this->load->view('admin/includes/footer');

		}

	}

	

	public function delete(){

		$delete = $this->Common_model->delete('moderators','id',$this->uri->segment(3));

		redirect(site_url().'/moderators/manage');

	}

	

	public function status(){

		$details = $this->db->get_where('moderators',array('id' => $this->uri->segment(3)))->row_array();

		if($details['status'] == 'Approved'){

			$data['status'] = 'Pending';

		}

		else{

			$data['status'] = 'Approved';

		}

		$update = $this->Common_model->update('moderators',$data,'id',$this->uri->segment(3));

		if($update){

			//$this->session->set_flashdata('success', "User Updated Successfully");

			redirect(site_url().'/moderators/manage');

		}

	}



}

