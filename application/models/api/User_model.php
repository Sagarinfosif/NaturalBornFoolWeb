<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model 
{
	
    public function post_list($like_id) 
	{	
		$sql =  $this->db->query ("SELECT p.*,u.name as user_name,u.image as user_image from posts as p left join users as u on u.id=p.user_id where like_id IN ($like_id)  group by p.id order by p.id desc ");
		//echo $this->db->last_query();die;
		return $sql->result_array();
	}
	public function post_comments($post_id)
	{
		$sql =  $this->db->query ("SELECT c.*,u.name as comment_user_name,u.image as comment_user_image from post_comments as c left join users as u on u.id=c.user_id where c.post_id = $post_id group by c.id");
		//echo $this->db->last_query();die;
		return $sql->result_array();
	}
	
	public function match_verifaction_token($id,$token,$table) 
	{	
		$sql =  $this->db->query ("SELECT * from $table where id = '$id' and otp = '$token'");
		return $sql->row_array();
	}
	
	public function user_login($table,$phone,$password) 
	{
	    
		$sql =  $this->db->query ("SELECT * from $table where phone = '$phone' and password = '$password'");	   
		return $sql->row_array();
	}
	
	public function provider_login($table,$phone,$password) 
	{
	    
		$sql =  $this->db->query ("SELECT * from $table where business_phone = '$phone' and password = '$password'");	   
		return $sql->row_array();
	}

	
	public function check_provider($phone,$id){
		$sql = $this->db->query('select * from provider_details where business_phone="'.$phone.'" and id <> "'.$id.'"');
		return $sql->row_array();
	}
	public function delete($table,$id){
		$this->db->where('id',$id);
		return $this->db->delete($table);
	}
	
	public function getUserAppointmentList($user_id){
		$sql = $this->db->query('select * from user_book_services where user_id= "'.$user_id.'" and (status = 2 or status = 5)');
		return $sql->result_array();
	}
	
	public function userLogin($table,$email,$password){
		$sql =  $this->db->query ("SELECT * from $table where `password` = '$password' and `email` = '$email'");
		return $sql->row_array();
	}
	
	public function getUserList($user_id){
		$sql =  $this->db->query ("SELECT * from userDetails where `id` <> $user_id ");
		return $sql->result_array();
	}
	
}
