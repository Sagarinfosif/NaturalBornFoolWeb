<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookings extends CI_Controller {

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
		$data1 = $this->db->order_by('id','desc')->get_where('userBookingOrder',array('orderStatus'=>'0'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$updatereadStatus = $this->db->query("update notificationBooking set readStatus = '1'");
		$data['active'] = 'newbookings';
		$data['title'] = "New Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/manageBookings');
		$this->load->view('admin/includes/footer');
	}

	public function accept(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data1 = $this->db->get_where('userBookingOrder',array('orderStatus'=>'1'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$data['active'] = 'acceptbookings';
		$data['title'] = "Accepted Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/acceptBookings');
		$this->load->view('admin/includes/footer');
	}

	public function rejected(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data1 = $this->db->get_where('userBookingOrder',array('orderStatus'=>'2','cancelType'=>'0'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$data['active'] = 'rejectbookings';
		$data['title'] = "Rejected Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/rejectBookings');
		$this->load->view('admin/includes/footer');
	}

	public function userCancelledOrders(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data1 = $this->db->get_where('userBookingOrder',array('orderStatus'=>'2','cancelType'=>'1'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$data['active'] = 'cancelbookings';
		$data['title'] = "User Cancelled Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/userCancelBookings');
		$this->load->view('admin/includes/footer');
	}

	public function fuel(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data1 = $this->db->get_where('userBookingOrder',array('orderStatus'=>'3'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$data['active'] = 'fuelbookings';
		$data['title'] = "Fueling in progress Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/fuelBookings');
		$this->load->view('admin/includes/footer');
	}

	public function deliver(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data1 = $this->db->get_where('userBookingOrder',array('orderStatus'=>'4'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$data['active'] = 'deliverbookings';
		$data['title'] = "Delivery in progress Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/deliveryBookings');
		$this->load->view('admin/includes/footer');
	}

	public function delivered(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data1 = $this->db->get_where('userBookingOrder',array('orderStatus'=>'5'))->result_array();
		foreach($data1 as $de){
			$user  = $this->db->get_where('userDetails',array('id'=>$de['userId']))->row_array();
			$product = $this->db->get_where('productList',array('id'=>$de['productId']))->row_array();
			$de['productTitle'] = $product['title'];
			$de['userName'] = $user['name'];
			$de['userPhone'] = $user['phone'];
			$data['details'][] = $de;
		}
		$data['active'] = 'delivered';
		$data['title'] = "Delivered Orders";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/deliveredBookings');
		$this->load->view('admin/includes/footer');
	}

	public function viewBooking(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$details = $this->db->get_where('userBookingOrder',array('id'=>$id))->row_array();
		$user  = $this->db->get_where('userDetails',array('id'=>$details['userId']))->row_array();
		$product = $this->db->get_where('productList',array('id'=>$details['productId']))->row_array();
		$details['productTitle'] = $product['title'];
		$details['userName'] = $user['name'];
		$details['userPhone'] = $user['phone'];
		$data['details'] = $details;
		$data['active'] = 'viewbooking';
		$data['title'] = "View Order";
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/bookings/viewBookings');
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

	public function updateStatus(){
		if($this->input->post()){
			$details['orderStatus'] = $this->input->post('status');
			$id = $this->input->post('orderId');
			$update = $this->Common_model->update('userBookingOrder',$details,'id',$id);
			if($update){
				$a = $this->notificationTesting($id);
				if($a){
					echo $this->input->post('status');
				}
			}
		}else{
			//echo "jh";die;
			$dd = $this->uri->segment(3);
			if($dd==0){
				$this->session->set_flashdata('success', "Status Updated Successfully");
				redirect(site_url().'/Bookings');
			}elseif($dd==1){
				$this->session->set_flashdata('success', "Status Updated Successfully");
				redirect(site_url().'/Bookings/accept');
			}elseif($dd==2){
				$this->session->set_flashdata('success', "Status Updated Successfully");
				redirect(site_url().'/Bookings/rejected');
			}elseif($dd==3){
				$this->session->set_flashdata('success', "Status Updated Successfully");
				redirect(site_url().'/Bookings/fuel');
			}elseif($dd==4){
				$this->session->set_flashdata('success', "Status Updated Successfully");
				redirect(site_url().'/Bookings/deliver');
			}elseif($dd==5){
				$this->session->set_flashdata('success', "Status Updated Successfully");
				redirect(site_url().'/Bookings/delivered');
			}
		}
	}

	public function notificationTesting($id){
		$order = $this->db->get_where('userBookingOrder',array('id'=>$id))->row_array();
		$reg_id = $this->db->get_where('userDetails',array('id'=>$order['userId'],'notificationStatus'=>'1'))->row_array();
		$regIDs = $reg_id['reg_id'];
		if($order['orderStatus']=='1'){
			$message = $reg_id['name']." "."Your order accepted";
			$type = 'accept';
		}elseif($order['orderStatus']=='2'){
			$message = $reg_id['name']." "."Your order is rejected";
			$type = 'reject';
		}elseif($order['orderStatus']=='3'){
			$message = $reg_id['name']." "."Your order in progress";
			$type = 'progress';
		}elseif($order['orderStatus']=='4'){
			$message = $reg_id['name']." "."Your order is on the way";
			$type = 'onTheWay';
		}elseif($order['orderStatus']=='5'){
			$message = $reg_id['name']." "."Your order is delivered successfully";
			$type = 'delivered';
		}else{
			$message = $reg_id['name']." "."Your order accepted";
			$type = 'accept';
		}
		$registrationIds =  array($regIDs);
		define('API_ACCESS_KEY', 'AAAAwGOedEY:APA91bFPToCnwZiEY9WDk1AglCOgEncjvRaCXILX1iHkyplckUf_ZG8a6hlwl6bdFe6XMxpOUJtp4wv2H6EPi70gKhXmhv9kMzS_K_7Ktr1x_oFPpy0NQaUq42-yaKWoRndNmMShg555');
		$msg = array(
			'message' 	=> $message,
			'title'		=> 'PetroWagon',
			'subtitle'	=> 'Response',
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon',
			'type'      => $type,
			'orderId' => $order['id']
		);
		$fields = array(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		$headers = array(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		//print_r($result);
		//die;
		curl_close( $ch );
		return 1;
	}

	public function deleteFaq(){
		$delete = $this->Common_model->delete('faq','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', "Faq's Delete Successfully");
		}
		redirect(site_url().'/Faq');
	}
	
}