<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

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
		$data['details'] = $this->db->get('productList')->result_array();
		$data['active'] = 'product';
		$data['title'] = 'Manage Products';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/product/manageProduct');
		$this->load->view('admin/includes/footer');
	}
	
	public function addProduct(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'addproduct';
		$data['title'] = 'Add Product';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Product Title', 'trim|required');
			$this->form_validation->set_rules('price', 'Price', 'trim|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			if (empty($_FILES['image']['name'])){
    			$this->form_validation->set_rules('image', 'Product Image', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/product/addProduct');
				$this->load->view('admin/includes/footer');
			}else{
				$details['title'] = $this->input->post('title');
				$details['price'] = $this->input->post('price');
				$details['description'] = $this->input->post('description');
				$details['created'] = date('y-m-d h:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->insert_data($details,'productList');
				if($insert){
					$this->session->set_flashdata('success', 'Product Insert Successfully');
					redirect(site_url().'/Product');
				}
			}
		}else{
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/product/addProduct');
			$this->load->view('admin/includes/footer');
		}
	}



	public function editProduct(){
		$id = $this->uri->segment(3);
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['active'] = 'editproduct';
		$data['title'] = 'Update Product';
		if($this->input->post()){
			$this->form_validation->set_rules('title', 'Product Title', 'trim|required');
			$this->form_validation->set_rules('price', 'Price', 'trim|required');
			//$this->form_validation->set_rules('description', 'Description', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['details'] = $this->db->get_where('productList',array('id' => $this->uri->segment(3)))->row_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/product/editProduct');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['title'] = $this->input->post('title');
				$details['price'] = $this->input->post('price');
				$details['description'] = $this->input->post('description');
				$details['updated'] = date('y-m-d h:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$update = $this->Common_model->update('productList',$details,'id',$id);
				if($update){
					$this->session->set_flashdata('success', 'Product Updated Successfully');
					redirect(site_url().'/Product');
				}
			}
		}
		else{
			$data['details'] = $this->db->get_where('productList',array('id' => $this->uri->segment(3)))->row_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/product/editProduct');
			$this->load->view('admin/includes/footer');
		}
	}

	public function deleteCategory(){
		$delete = $this->Common_model->delete('productCategory','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'Product Category Delete Successfully.');
		}
		redirect(site_url().'/Category');
	}
	
	public function manageSubCategory(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('admin','id',$admin_details['admin_id']);
		$data['details'] = $this->Common_model->getSubCategory1();
		$data['active'] = 'subCategory1';
		$data['title'] = 'Manage Sub Category';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/product/manageSubCategory');
		$this->load->view('admin/includes/footer');
	}

	public function addSubCategory(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('vendorDetails','id',$admin_details['admin_id']);
		$data['active'] = 'subCategory1';
		$data['title'] = 'Add Sub Category';
		if($this->input->post()){
			$this->form_validation->set_rules('vendorId', 'Vendor Category', 'trim|required');
			$this->form_validation->set_rules('categoryId', 'Product Category', 'trim|required');
			$this->form_validation->set_rules('subCategoryTitle', 'Title', 'trim|required');
			if (empty($_FILES['image']['name'])){
    		$this->form_validation->set_rules('image', 'Sub Category Image', 'required');
			}
			if($this->form_validation->run() == FALSE){
				$data['vendors'] = $this->db->get_where('vendorCategory')->result_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/product/addSubCategory');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['vendorId'] = $this->input->post('vendorId');
				$details['categoryId'] = $this->input->post('categoryId');
				$details['subCategoryTitle'] = $this->input->post('subCategoryTitle');
				$details['created'] = date('y-m-d h:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$insert = $this->Common_model->insert_data($details,'productSubCategory');
				if($insert){
					$this->session->set_flashdata('success', 'Product Sub Category Insert Successfully');
					redirect(site_url().'/category/manageSubCategory');
				}
			}
		}
		else{
			$data['vendors'] = $this->db->get_where('vendorCategory')->result_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/product/addSubCategory');
			$this->load->view('admin/includes/footer');
		}
	}

	public function editSubCategory(){
		$admin_details = $this->session->userdata('admin_details');
		$data['admin'] = $this->Common_model->get_data_by_id('vendorDetails','id',$admin_details['admin_id']);
		$data['active'] = 'subCategory1';
		$data['title'] = 'Update Sub Category';
		if($this->input->post()){
			$this->form_validation->set_rules('vendorId', 'Vendor Category', 'trim|required');
			$this->form_validation->set_rules('categoryId', 'Product Category', 'trim|required');
			$this->form_validation->set_rules('subCategoryTitle', 'Title', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$data['subCategoryDetails'] = $this->db->get_where('productSubCategory',array('id' => $this->uri->segment(3)))->row_array();
				$data['categoryDetails'] = $this->db->get_where('productCategory',array('vendorId'=>$data['subCategoryDetails']['vendorId']))->result_array($data['categoryDetails']);
				$data['vendors'] = $this->db->get_where('vendorCategory')->result_array();
				$this->load->view('admin/includes/header',$data);
				$this->load->view('admin/product/editSubCategory');
				$this->load->view('admin/includes/footer');
			}
			else{
				$details['vendorId'] = $this->input->post('vendorId');
				$details['categoryId'] = $this->input->post('categoryId');
				$details['subCategoryTitle'] = $this->input->post('subCategoryTitle');
				$details['updated'] = date('y-m-d h:i:s');
				if(!empty($_FILES["image"]["name"])){
					$name= time().'_'.$_FILES["image"]["name"];
					$liciense_tmp_name=$_FILES["image"]["tmp_name"];
					$error=$_FILES["image"]["error"];
					$liciense_path='uploads/product/'.$name;
					move_uploaded_file($liciense_tmp_name,$liciense_path);
					$details['image']=$liciense_path;
				}
				$update = $this->Common_model->update('productSubCategory',$details,'id',$this->input->post('id'));
				if($update){
					$this->session->set_flashdata('success', 'Product Sub Category Update Successfully');
					redirect(site_url().'/Category/manageSubCategory');
				}
			}
		}
		else{
			$data['subCategoryDetails'] = $this->db->get_where('productSubCategory',array('id' => $this->uri->segment(3)))->row_array();
			$data['categoryDetails'] = $this->db->get_where('productCategory',array('vendorId'=>$data['subCategoryDetails']['vendorId']))->result_array($data['categoryDetails']);
			$data['vendors'] = $this->db->get_where('vendorCategory')->result_array();
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/product/editSubCategory');
			$this->load->view('admin/includes/footer');
		}
	}

	public function deleteSubCategory(){
		$delete = $this->Common_model->delete('productSubCategory','id',$this->uri->segment(3));
		if($delete){
			 $this->session->set_flashdata('success', 'Product Sub Category Delete Successfully.');
		}
		redirect(site_url().'/Category/manageSubCategory');
	}
	
	public function getProductCategory(){
		$vendorId = $this->input->post('vendorId');
		$data['productCategory'] = $this->db->get_where('productCategory',array('vendorId'=>$vendorId))->result_array();
		// print_r();
		// die;
		$this->load->view('admin/product/getProductCategory',$data);
	}

	public function product_set_status(){
		$check_status = $this->db->get_where('productList',array('id'=>$this->input->post('id')))->row_array();
		if($check_status['status']=='1'){
			$data = array('status'=>'2');
		}else{
			$data = array('status'=>'1');
		}
		$update = $this->db->update('productList',$data,array('id'=>$this->input->post('id')));
		if($update){	
			echo $data['status'];
		}else{
			echo $data['status'];
		}
	}



}