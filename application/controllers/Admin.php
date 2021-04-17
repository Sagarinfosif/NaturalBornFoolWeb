<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('admin/Admin_model');
        $this->load->model('admin/Common_model');

    }
    public function dashboard(){
        error_reporting(E_ALL);
        if(!$this->session->userdata('admin_details'))
        {
            redirect( site_url() . "/admin/login");
            exit;
        }
        $admin_details = $this->session->userdata('admin_details');
        $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
        $data['user'] = $this->db->get_where('users')->num_rows();
        $data['topEagUser'] = $this->db->order_by('id','desc')->limit('8','0')->get_where('users')->result_array();
        $data['videos'] = $this->db->get_where('userVideos')->num_rows();
        $data['mode'] = $this->db->get_where('moderators')->num_rows();

//             code for graphs starts

//        for user joined
        for($i = 29; $i >= 0; $i --){
            $getDate =  date('Y-m-d', strtotime('-'.$i .'days'));
            $countuser = $this->db->query("select * from users where date(created) = '$getDate'")->num_rows();
            //$countuser['created'] = $getDate;

            $dateList[] = $countuser;
        }
        $data['chartUserCount'] = implode(',',$dateList);

//			for user follow

        $followed = $this->db->query("SELECT users.username,count(userFollow.userId) as following FROM userFollow JOIN users ON userFollow.followingUserId = users.id WHERE userFollow.status = '1' GROUP by userFollow.followingUserId ORDER BY following DESC LIMIT 10")->result_array();
        if (!empty($followed)){
            foreach ($followed as $follow) {
                $username[] = $follow['username'];
                $follow_count[] = $follow['following'];
            }

            $data['labels'] = implode('\',\'',$username);
            $data['datasets'] = implode(',',$follow_count);

        }

//			graph for most blocked user

        $blocked = $this->db->query("SELECT users.username,count(blockUser.userId) as blocked FROM blockUser JOIN users ON blockUser.blockUserId = users.id  GROUP by blockUser.blockUserId ORDER BY blocked DESC LIMIT 10")->result_array();

        if (!empty($blocked)){
            foreach ($blocked as $block) {
                $block_username[] = $block['username'];
                $block_count[] = $block['blocked'];
            }

            $data['block_labels'] = implode('\',\'',$block_username);
            $data['block_datasets'] = implode(',',$block_count);

        }

//			graph for most viewed videos


        $view_video = $this->db->query("SELECT COUNT(userId) as total_views,videoId FROM viewVideo GROUP by videoId ORDER BY total_views DESC limit 10")->result_array();
        if (!empty($view_video)){
            foreach ($view_video as $view) {
                $view_video_id[] = $view['videoId'];
                $view_video_count[] = $view['total_views'];
            }

            $data['views_video_labels'] = implode('\',\'',$view_video_id);
            $data['views_video_datasets'] = implode(',',$view_video_count);
        }

//			most liked videos

        $liked = $this->db->query("SELECT COUNT(userId) as total_likes,videoId FROM videoLikeOrUnlike GROUP BY videoId ORDER BY total_likes DESC limit 10")->result_array();
        if (!empty($liked)){
            foreach ($liked as $like) {
                $like_video_id[] = $like['videoId'];
                $like_count[] = $like['total_likes'];
            }

            $data['like_labels'] = implode('\',\'',$like_video_id);
            $data['like_datasets'] = implode(',',$like_count);
        }

//			most commented video


        $comments = $this->db->query("SELECT COUNT(id) as total_comment,videoId FROM `videoComments` GROUP BY videoId ORDER BY total_comment DESC limit 10")->result_array();
        if (!empty($comments)){
            foreach ($comments as $comment) {
                $comment_video_id[] = $comment['videoId'];
                $comment_count[] = $comment['total_comment'];
            }

            $data['comment_labels'] = implode('\',\'',$comment_video_id);
            $data['comment_datasets'] = implode(',',$comment_count);
        }


//        most videos upload

        $uploaded = $this->db->query("SELECT COUNT(userVideos.id) as total_videos,users.username FROM `userVideos` JOIN users ON users.id = userVideos.userId GROUP BY userId ORDER BY total_videos DESC limit 10")->result_array();
        if (!empty($uploaded)){
            foreach ($uploaded as $upload) {
                $upload_video_username[] = $upload['username'];
                $upload_count[] = $upload['total_videos'];
            }

            $data['upload_labels'] = implode('\',\'',$upload_video_username);
            $data['upload_datasets'] = implode(',',$upload_count);
        }



//			graph code ends

        //$data['users'] = $this->db->query("select count(id) as user from users")->row_array();
        $data['active'] = 'dashboard';
        $data['title'] = 'Dashboard';
        $this->load->view('admin/includes/header',$data);
        $this->load->view('admin/adminPages/dashboard');
        $this->load->view('admin/includes/footer');
    }

    public function login(){
        if($this->input->post()){
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.')
            );
            if ($this->form_validation->run() == FALSE)
            {
                $this->load->view('admin/adminPages/login');
            }
            else{
                $result = $this->Admin_model->login();
                if(!empty($result)){
                    $sess_array = array(
                        'admin_id' => $result['id'],
                        'email' => $result['email'],
                    );
                    $this->session->set_userdata('admin_details', $sess_array);
                    $otp = rand(1000,9999);
                    $mess = 'Your Verification Code Is: '.$otp;
                    $mobile = '+91'.$result['phone'];
                    $created = date('Y-m-d+H:s:i');
                    $newData = array('otp'=>$otp);
                    $aid = $result['id'];
                    $this->db->update('admin',$newData,array('id'=>$aid));
                    // $this->Ad_Model->update('admin',$newData,'id',$aid);
                    // $url = "https://api.msg91.com/api/v5/otp?authkey=346870A5GnDowb5fab687dP1&template_id=5fabb934c00b37344b06d88b&mobile=$mobile&invisible=1&otp=$otp";
                    // $ch = curl_init();
                    // curl_setopt($ch,CURLOPT_URL,$url);
                    // curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    // $output=curl_exec($ch);
                    // curl_close($ch);
                    redirect(site_url().'/admin/verifyOtp');
                }
                else{
                    $this->session->set_flashdata('error', ' Invalid Login Details, Please Try Again!');
                    redirect(site_url().'/admin/login');
                }
            }
        }
        else{
            $this->load->view('admin/adminPages/login');
        }
    }

    public function edit_profile(){
        if(!$this->session->userdata('admin_details'))
        {
            redirect( site_url() . "/admin/login");
            exit;
        }
        $admin_details = $this->session->userdata('admin_details');
        $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
        $data['active'] = 'edit_profile';
        $data['title'] = 'Edit Profile';
        if($this->input->post()){
            if($this->input->post('type') =='profile'){
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('name', 'Name', 'required');
                $this->form_validation->set_rules('phone', 'Phone', 'required');
                $this->form_validation->set_rules('designation', 'Designation', 'required');
                $this->form_validation->set_rules('education', 'Education', 'required');
                $this->form_validation->set_rules('location', 'Location', 'required');
                if($this->form_validation->run() == FALSE){
                    $data['activeTab'] = 'profile';
                    $this->load->view('admin/includes/header',$data);
                    $this->load->view('admin/adminPages/profile');
                    $this->load->view('admin/includes/footer');
                }
                else{
                    $details['name'] = $this->input->post('name');
                    $details['email'] = $this->input->post('email');
                    $details['phone'] = $this->input->post('phone');
                    $details['designation'] = $this->input->post('designation');
                    $details['education'] = $this->input->post('education');
                    $details['address'] = $this->input->post('location');
                    if(!empty($_FILES["image"]["name"])){
                        $name= time().'_'.$_FILES["image"]["name"];
                        $liciense_tmp_name=$_FILES["image"]["tmp_name"];
                        $error=$_FILES["image"]["error"];
                        $liciense_path='uploads/admin/'.$name;
                        move_uploaded_file($liciense_tmp_name,$liciense_path);
                        $details['image']=$liciense_path;
                    }
                    $update = $this->Common_model->update('admin',$details,'id',$admin_details['admin_id']);
                    if($update){
                        $this->session->set_flashdata('success', 'Profile Update Successfully');
                        redirect(site_url().'/admin/edit_profile');
                    }
                }
            }
            else{
                $this->form_validation->set_rules('oldPassword', 'Old Password', 'required');
                $this->form_validation->set_rules('newPassword', 'New Password', 'required');
                $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[newPassword]');
                if($this->form_validation->run() == FALSE){
                    $data['activeTab'] = 'changePass';
                    $this->load->view('admin/includes/header',$data);
                    $this->load->view('admin/adminPages/profile');
                    $this->load->view('admin/includes/footer');
                }
                else{
                    $checkPassword = $this->db->get_where('admin',array('id' => $admin_details['admin_id'],'password' => md5($this->input->post('oldPassword'))))->row_array();
                    if(!empty($checkPassword)){
                        $pass['password'] = md5($this->input->post('newPassword'));
                        $update = $this->Common_model->update('admin',$pass,'id',$admin_details['admin_id']);
                        if($update){
                            $this->session->set_flashdata('passSuccess', 'Profile Update Successfully');
                            redirect(site_url().'/admin/edit_profile');
                        }
                    }
                    else{
                        $this->session->set_flashdata('oldPass', 'Old Password Does Not Match');
                        redirect(site_url().'/admin/edit_profile');
                    }
                }
            }
        }
        else{
            $data['activeTab'] = 'profile';
            $this->load->view('admin/includes/header',$data);
            $this->load->view('admin/adminPages/profile');
            $this->load->view('admin/includes/footer');
        }
    }
    public function change_password(){
        if(!$this->session->userdata('admin_details')){
            redirect(site_url('admin'));
            exit;
        }
        $admin_details = $this->session->userdata('admin_details');
        $admin_name = $admin_details['username'];
        $user_id = $admin_details['admin_id'];
        if($this->input->post()){

            $old_password=$this->input->post('old_password');
            $new_password=$this->input->post('new_password');

            $admin_info=$this->Admin_model->chngpass($admin_name,$old_password,$user_id);
            if(empty($admin_info)){
                $this->session->set_flashdata("error", "Old Password Does't Match");
                redirect( site_url() . "admin/admin/change_password" );
            }else{
                $result=$this->Admin_model->chng_pass($admin_name,$new_password);

                if ($result) {
                    $this->session->set_flashdata("message", "Password Change Successfully");
                }else {
                    $this->session->set_flashdata("error", "Password Can't Change ");
                }
                redirect( site_url() . "admin/admin/change_password" );
            }
        }
        else{
            $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
            $data['active'] = 'change_password';
            $data['title'] = 'Change Password';
            $this->load->view('admin/includes/header',$data);
            $this->load->view('admin/admin_pages/change_password');
            $this->load->view('admin/includes/footer');
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect( site_url() . "/admin/login");
    }

    public function testing(){
        if($this->input->post()){
            echo "<pre>";
            print_r($this->input->post());
            die;
        }
        else{
            $this->load->view('admin/adminPages/testing');
        }
    }


    public function chartData(){
        //  $last15Date = date('Y-m-d', strtotime('-15 days'));
        for($i = 14; $i >= 0; $i --){
            $getDate =  date('Y-m-d', strtotime('-'.$i .'days'));
            $countuser = $this->db->query("select count(id) as userCount,created from users where date(created) = '$getDate'")->row_array();
            $countuser['created'] = $getDate;
            echo "<pre>";
            print_r($countuser);
            echo "<br>";
        }
    }

    public function enterPhone(){
        $data['title'] = "Enter Phone";
        if($this->input->post()){
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $this->load->view('admin/adminPages/enterPhone',$data);
            }else{
                $phone = $this->input->post('phone');
                $adminPhone = $this->db->get_where('admin',array('phone'=>$phone))->num_rows();
                if(!empty($adminPhone)){
                    redirect(site_url().'/admin/verifyOtp');
                }else{
                    redirect(site_url().'/admin/enterPhone');
                }
            }
        }else{
            $this->load->view('admin/adminPages/enterPhone',$data);
        }
    }


    public function verifyOtp(){
        $data['title'] = "verify Otp";
        $admin_details = $this->session->userdata('admin_details');
        $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
        
        
        if($this->input->post()){
            $this->form_validation->set_rules('otp', 'Otp', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $this->load->view('admin/adminPages/verifyOtp',$data);
            }else{
                $otp = $this->input->post('otp');
                $adminotp = $this->db->get_where('admin',array('otp'=>$otp))->row_array();
                if(!empty($adminotp)){
                    redirect(site_url().'/admin/dashboard');
                }else{
                    $this->session->set_flashdata("error", "Otp not matched.");
                    redirect(site_url().'/admin/verifyOtp');
                }
            }
        }else{
            $this->load->view('admin/adminPages/verifyOtp',$data);
        }
    }


    public function enterNewPassword(){
        $data['title'] = "verify Otp";
        if($this->input->post()){
            $this->form_validation->set_rules('otp', 'Otp', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $this->load->view('admin/adminPages/enterNewPassword',$data);
            }else{
                $details['password'] = $this->input->post('password');
                $update = $this->Common_model->update('admin',$details,'id',$admin_details['admin_id']);
                if($update){
                    redirect(site_url().'/admin/login');
                }else{
                    redirect(site_url().'/admin/enterNewPassword');
                }
            }
        }else{
            $this->load->view('admin/adminPages/enterNewPassword',$data);
        }
    }

    public function adminNumber(){
        $admin_details = $this->session->userdata('admin_details');
        $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
        $data['details'] = $this->db->order_by('id','desc')->get_where('admin')->result_array();
        $data['active'] = 'adminNumber';
        $data['title'] = "Manage Admin Numbers";
        $this->load->view('admin/includes/header',$data);
        $this->load->view('admin/adminPages/adminNumber');
        $this->load->view('admin/includes/footer');
    }

    public function editNumber(){
        $id = $this->uri->segment(3);
        $admin_details = $this->session->userdata('admin_details');
        $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
        $data['active'] = 'adminNumber';
        $data['title'] = "Update Number";
        $data['details'] = $this->db->get_where('admin',array('id' => $this->uri->segment(3)))->row_array();
        if($this->input->post()){
            $this->form_validation->set_rules('phone', 'Phone number', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $this->load->view('admin/includes/header',$data);
                $this->load->view('admin/adminPages/editNumber');
                $this->load->view('admin/includes/footer');
            }else{
                $details['phone'] = $this->input->post('phone');
                $update = $this->Common_model->update('admin',$details,'id',$id);
                if($update){
                    $this->session->set_flashdata('success', "Number Updated Successfully");
                    redirect(site_url().'/admin/adminNumber');
                }
            }
        }else{
            $this->load->view('admin/includes/header',$data);
            $this->load->view('admin/adminPages/editNumber');
            $this->load->view('admin/includes/footer');
        }
    }

    public function addNumber(){
        $id = $this->uri->segment(3);
        $admin_details = $this->session->userdata('admin_details');
        $data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
        $data['active'] = 'adminNumber';
        $data['title'] = "Add Number";
        if($this->input->post()){
            $this->form_validation->set_rules('phone', 'Phone number', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $this->load->view('admin/includes/header',$data);
                $this->load->view('admin/adminPages/addNumber');
                $this->load->view('admin/includes/footer');
            }else{
                $details['phone'] = $this->input->post('phone');
                $insert = $this->db->insert('admin',$details);
                if($insert){
                    $this->session->set_flashdata('success', "Number Added Successfully");
                    redirect(site_url().'/admin/adminNumber');
                }
            }
        }else{
            $this->load->view('admin/includes/header',$data);
            $this->load->view('admin/adminPages/addNumber');
            $this->load->view('admin/includes/footer');
        }
    }


    public function deleteNumber(){
        $delete = $this->Common_model->delete('admin','id',$this->uri->segment(3));
        if($delete){
            $this->session->set_flashdata('success', "Number Deleted Successfully");
            redirect(site_url().'/admin/adminNumber');
        }

    }
}
