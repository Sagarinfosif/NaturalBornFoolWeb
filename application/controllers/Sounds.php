<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sounds extends CI_Controller {

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

	public function addSound(){
			$admin_details = $this->session->userdata('admin_details');
			$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
			$data['active'] = 'addSound';
			$data['title'] = "Add Sound";
			if($this->input->post()){
				$this->form_validation->set_rules('title', 'Name', 'trim|required');
				if(empty($_FILES["sound"]["name"]) || $_FILES["image"]["name"] == ""){
					$this->form_validation->set_rules('sound', 'Sound', 'required');
					$this->form_validation->set_rules('image', 'SoundImage', 'required');
				}
				if($this->form_validation->run() == FALSE){
					$this->load->view('admin/includes/header',$data);
					$this->load->view('admin/sounds/addsound');
					$this->load->view('admin/includes/footer');
				}else{
					$details['title'] = $this->input->post('title');
					$details['addedby'] = '1';
					$details['created'] = date('Y-m-d H:i:s');
					if(!empty($_FILES["sound"]["name"])){
						// $config['upload_path'] = 'uploads/sounds/';
						// $config['allowed_types'] = 'aac';
						// $config['file_name'] = $_FILES['sound']['name'];
						// //Load upload library and initialize configuration
						// $this->load->library('upload',$config);
						// $this->upload->initialize($config);
						// if($this->upload->do_upload('sound')){
						// $uploadData = $this->upload->data();
						// $details['sound'] = 'uploads/sounds/'.$uploadData['file_name'];
						// }else{
						// $details['sound'] = '';
						// }
						// }
						$name= time().'_'.$_FILES["sound"]["name"];
						$liciense_tmp_name=$_FILES["sound"]["tmp_name"];
						$error=$_FILES["sound"]["error"];
						$liciense_path='uploads/sounds/'.$name;
						move_uploaded_file($liciense_tmp_name,$liciense_path);
						$details['sound']= $liciense_path;
					}
                	else
                    {
                    	$details['sound']= '';
                    }
					if(!empty($_FILES["image"]["name"])){
						$name1= time().'_'.$_FILES["image"]["name"];
						$liciense_tmp_name1=$_FILES["image"]["tmp_name"];
						$error1=$_FILES["image"]["error"];
						$liciense_path1='uploads/sounds/images/'.$name1;
						move_uploaded_file($liciense_tmp_name1,$liciense_path1);
						$details['soundImg']= $liciense_path1;
					}
                	else
                    {
                    	$details['soundImg']= '';
                    }
					$insert = $this->db->insert('sounds',$details);
                	//echo $this->db->last_query();die;
					if($insert){
						$this->session->set_flashdata('success', "Sound added Successfully");
						redirect(site_url().'/Sounds/Sound');
					}
				}
			}
    		else{
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/sounds/addsound');
				$this->load->view('admin/includes/footer');
			}
		}

		public function Sound(){
			$config["base_url"] = site_url()."/Sounds/Sound";
			$coutData = $this->db->from("sounds")->count_all_results();
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
			$data['details'] = $this->db->query("select * from sounds order by id desc limit $npage,$p")->result_array();
			$data['active'] = 'manage';
			$data['title'] = 'Manage Sounds';
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/sounds/manageSound');
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
           	$data=$this->db->query("SELECT * from sounds where created between '$start' and '$end' and title like '%$pname%' or type like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($end)){
           	$data=$this->db->query("SELECT * from sounds where created between '$start' and '$end' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start) && !empty($pname)){
           	$data=$this->db->query("SELECT * from sounds where created = '$start' and title like '%$pname%' or type like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end) && !empty($pname)){
           	$data=$this->db->query("SELECT * from sounds where created = '$end' and title like '%$pname%' or type like '%$pname%' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($start)){
           	$data=$this->db->query("SELECT * from sounds where created = '$start' order by id desc")->result_array();
           	exit(json_encode($data));
        }elseif(!empty($end)){
            $data=$this->db->query("SELECT * from sounds where created = '$end' order by id desc")->result_array();
            exit(json_encode($data));
        }elseif(!empty($pname)){
            $data=$this->db->query("SELECT * from sounds where title like '%$pname%' or type like '%$pname%' order by id desc")->result_array();
              exit(json_encode($data));
        }else{
        	$data=$this->db->query("SELECT * from sounds order by id desc")->result_array();
            exit(json_encode($data));
        }
    }

		public function edit(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'manage';
		$data['title'] = "Edit Sound";
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Name', 'trim|required');
			// $this->form_validation->set_rules('type', 'Type', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('sounds',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/sounds/editSound');
				$this->load->view('admin/includes/footer');
			}else{
				$details1['title'] = $this->input->post('title');
				//$details1['type'] = $this->input->post('type');
				$details1['updated'] = date('Y-m-d H:i:s');
				if(!empty($_FILES["sound"]["name"])){
					// $config['upload_path'] = 'uploads/sounds/';
					// $config['allowed_types'] = 'aac';
					// $config['file_name'] = $_FILES['sound']['name'];
					// //Load upload library and initialize configuration
					// $this->load->library('upload',$config);
					// $this->upload->initialize($config);
					// if($this->upload->do_upload('sound')){
					// $uploadData = $this->upload->data();
					// $details1['sound'] = 'uploads/sounds/'.$uploadData['file_name'];
					// }else{
					// $details1['sound'] = '';
					// }
					$name= time().'_'.$_FILES["sound"]["name"];
					$liciense_tmp_name=$_FILES["sound"]["tmp_name"];
					$error=$_FILES["sound"]["error"];
					$liciense_path='uploads/sounds/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details1['sound']= $liciense_path;
				}
            	// else
             //    {
             //    	$details1['sound']= '';
             //    }
				if(!empty($_FILES["image"]["name"])){
					$name1= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name1=$_FILES["image"]["tmp_name"];
					$error1=$_FILES["image"]["error"];
					$liciense_path1='uploads/sounds/images/'.$name1;
					move_uploaded_file($liciense_tmp_name1,$liciense_path1);
					$details1['soundImg']= $liciense_path1;
				}
            	// else
             //    {
             //    	$details1['soundImg']= '';
             //    }
				$update = $this->db->update('sounds',$details1,array('id'=>$this->uri->segment(3)));
				if($update){
					$this->session->set_flashdata('success', "Sound Updated Successfully");
					redirect(site_url().'/Sounds/Sound');
				}
			}
		}else{
			$data['details'] = $this->db->get_where('sounds',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/sounds/editSound');
			$this->load->view('admin/includes/footer');
		}
	}

	public function delete(){
		$delete = $this->Common_model->delete('sounds','id',$this->uri->segment(3));
    	if($delete){
			$this->session->set_flashdata('success', "Sound Deleted Successfully");
			redirect(site_url().'/Sounds/Sound');
        }
	}

	public function viewVideo()
	{
			$config["base_url"] = site_url()."/Sounds/viewVideo";
			$coutData = $this->db->from("userVideos")->where("userVideos.soundId","$data1")->count_all_results();
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
			$data1 = $this->uri->segment(3);
			$datas = $this->db->query("select userVideos.*,sounds.title as st,sounds.sound,hashtag.hashtag,hashtag.id from userVideos left join sounds on sounds.id=userVideos.soundId left join hashtag on hashtag.id = userVideos.hashTag where userVideos.soundId=$data1 order by userVideos.id desc limit $npage,$p")->result_array();
			foreach($datas as $deta){
			$deta['hashtags'] = $this->hashTagName($deta['hashTag']);
			$dd[] = $deta;
			}
			$data['details'] = $dd;
			$data['active'] = 'manage';
			$data['title'] = 'Manage Sounds';
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/sounds/viewVideo');
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
}