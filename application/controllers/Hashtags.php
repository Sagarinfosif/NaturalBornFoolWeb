<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hashtags extends CI_Controller {

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

	public function addHash(){
			$admin_details = $this->session->userdata('admin_details');
			$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
			$data['active'] = 'addhash';
			$data['title'] = "Add Hashtags";
			if($this->input->post()){
				$this->form_validation->set_rules('title', 'Name', 'trim|required');
				if($this->form_validation->run() == FALSE){
					$this->load->view('admin/includes/header',$data);
					$this->load->view('admin/hashtags/addhash');
					$this->load->view('admin/includes/footer');
				}else{
					$details['hashtag'] = $this->input->post('title');
					$details['created'] = date('Y-m-d H:i:s');
					$insert = $this->Common_model->insert_data($details,'hashtag');
					if($insert){
						$this->session->set_flashdata('success', "Sound added Successfully");
						redirect(site_url().'/Hashtags/manage');
					}
				}
			}else{
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/hashtags/addhash');
				$this->load->view('admin/includes/footer');
			}
		}

		public function manage(){
			$config["base_url"] = site_url()."/Hashtags/manage";
			$coutData = $this->db->from("hashtag")->count_all_results();
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
			$data['details'] = $this->db->query("select * from hashtag order by id desc limit $npage,$p")->result_array();
			$data['active'] = 'managehash';
			$data['title'] = 'Manage Hashtags';
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/hashtags/manage');
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
           	$data=$this->db->query("SELECT * from hashtag where created between '$start' and '$end' and hashtag like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($end)){
           	$data=$this->db->query("SELECT * from hashtag where created between '$start' and '$end' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($pname)){
           	$data=$this->db->query("SELECT * from hashtag where created = '$start' and hashtag like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end) && !empty($pname)){
           	$data=$this->db->query("SELECT * from hashtag where created = '$end' and hashtag like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start)){
           	$data=$this->db->query("SELECT * from hashtag where created = '$start' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end)){
            $data=$this->db->query("SELECT * from hashtag where created = '$end' order by id desc")->result_array();
            exit(json_encode($data));
        }elseif(!empty($pname)){
            $data=$this->db->query("SELECT * from hashtag where hashtag like'%$pname%' order by id desc")->result_array();
              exit(json_encode($data));
        }else{
        	$data=$this->db->query("SELECT * from hashtag order by id desc")->result_array();
            exit(json_encode($data));
        }
    }

		public function edit(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'manage';
		$data['title'] = "Edit Hash";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Name', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('sounds',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/hashtags/edithash');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['hashtag'] = $this->input->post('title');
				//$details1['updated'] = date('Y-m-d H:i:s');
				$update = $this->Common_model->update('hashtag',$details1,'id',$this->uri->segment(3));
				if($update){
					$this->session->set_flashdata('success', "Hashtag Updated Successfully");
					redirect(site_url().'/Hashtags/manage');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('hashtag',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/hashtags/edithash');
			$this->load->view('admin/includes/footer');
		}
	}

	public function FavUser(){
		$hashTagId = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'manage';
		$data['title'] = "Favourite Hashtag User";
		$data['details'] = $this->db->query("SELECT users.username,users.name,users.image,users.email,users.phone FROM users LEFT JOIN favouriteHashTagList ON favouriteHashTagList.userId = users.id WHERE favouriteHashTagList.hashtagId = $hashTagId AND favouriteHashTagList.status = '1'")->result_array();
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/hashtags/favUser');
		$this->load->view('admin/includes/footer');

	}

	public function delete(){
		$delete = $this->Common_model->delete('hashtag','id',$this->uri->segment(3));
		redirect(site_url().'/Hashtags/manage');
	}

	public function viewVideo()
	{
			$data1 = $this->uri->segment(3);
			$config["base_url"] = site_url()."/Hashtags/viewVideo";
			$coutData = $this->db->query("select userVideos.*,hashtag.hashtag as hash from userVideos left join hashtag on hashtag.id=userVideos.hashTag where userVideos.hashTag like '%$data1%' order by id desc")->num_rows();
		    $config["total_rows"] = $coutData;
		    $config["per_page"] = 10;
		    $config["uri_segment"] = 4;
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
		    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
		    $npage =  ($page-1)*10;
		    $data["links"] = $this->pagination->create_links();
		    $p = $config["per_page"];
			$admin_details = $this->session->userdata('admin_details');
			$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
			
			$datas = $this->db->query("select userVideos.*,hashtag.hashtag,sounds.sound,sounds.id from userVideos left join hashtag on hashtag.id=userVideos.hashTag left join sounds on sounds.id = userVideos.soundId where userVideos.hashTag like '%$data1%' order by userVideos.id desc limit $npage,$p")->result_array();
			foreach($datas as $deta){
			$deta['hashtags'] = $this->hashTagName($deta['hashTag']);
			$dd[] = $deta;
			}
			$data['details'] = $dd;
			$data['active'] = 'managehash';
			$data['title'] = 'Manage Hashtags';
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/hashtags/viewVideo');
			$this->load->view('admin/includes/footer');
	}

	public function hashTagName($ids){
		$exp = explode(',',$ids);
		foreach($exp as $exps){
			$hashTitile = $this->db->get_where('hashtag',array('id' => $exps))->row_array();
			$hashTati[] = $hashTitile['hashtag'];
		}
		return $finalTitle = implode(',',$hashTati);
	}

	// public function hashTagName($data1){
	// 	$data1 = $this->uri->segment(3);
	// 	$exp = explode(',',$data1);
	// 	foreach($exp as $exps){
	// 		$hashTitile = $this->db->get_where('hashtag',array('id' => $exps))->row_array();
	// 		$hashTati[] = $hashTitile['hashtag'];
	// 	}
	// 	return $finalTitle = implode(',',$hashTati);
	// 	$this->load->view('admin/includes/header',$finalTitle);
	// 	$this->load->view('admin/hashtags/viewVideo');
	// 	$this->load->view('admin/includes/footer');
	// }
}