<?php defined('BASEPATH') OR exit('No direct script access allowed');



require_once APPPATH . 'libraries/API_Controller.php';

use Twilio\Rest\Client;

class User extends API_Controller{



    public function __construct(){

        parent::__construct();

        date_default_timezone_set('Asia/Calcutta');

        $this->load->model('api/Common_Model');

        $this->load->model('api/User_model');

    }



    public function otpTesting(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['GET'],

        ]);

        $a = '+917087772970';

        require dirname(dirname(dirname(__FILE__))).'/libraries/twilio-php-master/Twilio/autoload.php';

        $sid    = "AC890b006ce26dc0309f096ebd124bbf16";

        $token  = "3c8bda38904b060537b8b57e466381ab";

        $twilio = new Client($sid, $token);

        $message = $twilio->messages

                    ->create($a, // to

                        array(

                            "from" => "+12403294295",

                            "body" => 'hello'

                        )

                    );

        print_r($message->sid);

    }



    public function demo(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

        $this->_apiConfig([

            /**

             * By Default Request Method `GET`

             */

            'methods' => ['POST'], // 'GET', 'OPTIONS'

            /**

             * Number limit, type limit, time limit (last minute)

             */

            'limit' => [5, 'ip', 'everyday'],

            /**

             * type :: ['header', 'get', 'post']

             * key  :: ['table : Check Key in Database', 'key']

             */

            'key' => ['POST', $this->key() ], // type, {key}|table (by default)

        ]);



        // return data

        $this->api_return(

            [

                'status' => true,

                "result" => "Return API Response",

            ],

        200);

    }



    /**

     * Check API Key

     *

     * @return key|string

     */



    private function key(){

        // use database query for get valid key

        return 1452;

    }



    public function UserSocialLogin(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

        $this->_apiConfig([

            'methods' => ['POST'],

        ]);

         $details = $this->db->get_where('userDetails', array('phone' => $this->input->post('phone')))->row_array();

        if(empty($details)){

            $data['otp'] =  rand(1000,9999);

            $data['name'] = $this->input->post('name');

            $data['email'] = $this->input->post('email');

            $data['phone'] = $this->input->post('phone');

            $data['social_id'] =$this->input->post('social_id');

            $data['device_type'] =$this->input->post('device_type');

            $data['reg_id'] =$this->input->post('reg_id');

             $data['notificationStatus'] = '1';

            $data['image'] =$this->input->post('image');

            $data['created'] = date('Y-m-d H:i:s');

            $details = $this->Common_Model->register('userDetails',$data);

            if($details){

                $insert_id = $this->db->insert_id();

                $datass['userId'] = $insert_id;

                $this->db->insert('userSettings', $datass);

                $userDetails = $this->db->get_where('userDetails', array('id' => $insert_id))->row_array();

                $payload = [

                    'phone' => $userDetails["phone"],

                    'reg_id' => $userDetails["reg_id"]

                ];

                // Load Authorization Library or Load in autoload config file

                $this->load->library('Authorization_Token');

                // generate a token

                $token = $this->authorization_token->generateToken($payload);

                // return data

                $userDetails['token'] = $token;

                $this->api_return(

                    [

                        'status' => true,

                        'message' =>'User register successfully',

                        "result" => $userDetails

                    ],

                200);

            }else{

                $this->api_return(

                    [

                        'status' => false,

                        'message' =>'Try after sometime'

                    ],

                400);

            }

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' =>'This phone is already exist'

                ],

            409);

        }

    }



    public function userCheckSocialId(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

        $this->_apiConfig([

            'methods' => ['POST'],

        ]);

        $check_social_id = $this->Common_Model->get_data_by_id('userDetails','social_id',$this->input->post('social_id'));

        if(!empty($check_social_id)){

            if($check_social_id['phoneVerifyStatus'] == '0'){

                $this->api_return(

                [

                    'status' => false,

                    "message"  => "Please verify your phone number"



                    ],

                401);

            }else{

                $datas = array('reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'),'updated'=>date('Y-m-d H:i:s'));

                $update = $this->Common_Model->update('userDetails',$datas,'id',$check_social_id['id']);

                $userDetails = $this->db->get_where('userDetails', array('id' => $check_social_id['id']))->row_array();

                $payload = [

                    'phone' => $userDetails["phone"],

                    'reg_id' => $userDetails["reg_id"]

                ];

                // Load Authorization Library or Load in autoload config file

                $this->load->library('Authorization_Token');

                // generate a token

                $token = $this->authorization_token->generateToken($payload);

                // return data

                $userDetails['token'] = $token;

                $this->api_return(

                    [

                        'status' => true,

                        'message' =>'User login successfully',

                        "result" => $userDetails

                    ],

                200);

            }

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' =>'Please create your account'

                ],

            404);

        }

    }



    public function forgotPassword(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $this->_apiConfig([

            'methods' => ['POST']

        ]);

        $email = $this->input->post('phone');

        $patientCheck = $this->db->get_where('userDetails',array('phone'=>$email))->row_array();

        if(!empty($patientCheck)){

            $data['otp'] = mt_rand(1000,9999);

            $up = $this->Common_Model->update('userDetails',$data,'id',$patientCheck['id']);

            if($up){

                $userDetails = $this->db->get_where('userDetails',array('phone'=>$this->input->post('phone')))->row_array();

                $payload = [

                    'phone' => $userDetails["phone"],

                    'reg_id' => $userDetails["reg_id"]

                ];

                // Load Authorization Library or Load in autoload config file

                $this->load->library('Authorization_Token');

                // generate a token

                $token = $this->authorization_token->generateToken($payload);

                // return data

                $userDetails['token'] = $token;

                $this->api_return(

                [

                        'status' => true,

                        'message' => "Otp sent successfully",

                        'result' => $userDetails

                    ],

                200);

            }

        }else{

            $this->api_return(

            [

                    'status' => false,

                    'message' => "Phone number doesn't exist"

                ],

            401);

        }

    }



    public function updatePassword(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $patientCheck = $this->db->get_where('userDetails',array('phone'=>$user_data['token_data']['phone']))->row_array();

        if(!empty($patientCheck)){

            $data['password'] = md5($this->input->post('password'));

            $data['otp'] = mt_rand(1000,9999);

            $update = $this->Common_Model->update('userDetails',$data,'id',$patientCheck['id']);

            if($update){

                $details = $this->db->get_where('userDetails',array('id'=>$patientCheck['id']))->row_array();

                $this->api_return(

                    [

                        'status' => true,

                        'message' =>'Password changed successfully'

                    ],

                200);

            }

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' =>'No details found'

                ],

            404);

        }

    }



    public function userChangePassword(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        //print_r($user_data['token_data']['phone']);die;

        $pass = $this->input->post('old_password');

        $patientCheck = $this->db->get_where('userDetails',array('password'=>md5($pass),'phone'=>$user_data['token_data']['phone']))->row_array();

        if(!empty($patientCheck)){

            $data['password'] = md5($this->input->post('new_password'));

            $update = $this->db->update('userDetails',$data,array('id'=>$patientCheck['id']));

            if($update){

                $this->api_return(

                [

                        'status' => true,

                        'message' => "Password changed successfully"

                    ],

                200);

            }

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "Old password doesn't match"

                ],

            404);

        }

    }



    public function userRegister(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

        $this->_apiConfig([

            'methods' => ['POST'],

        ]);

        if(!empty($this->input->post('email'))){

            $checkEmail = $this->db->get_where('userDetails',array('email' => $this->input->post('email')))->row_array();

                $details['email'] = $this->input->post('email');

        }

        $checkName = $this->db->get_where('userDetails',array('phone' => $this->input->post('phone')))->row_array();

        if(!empty($checkEmail)){

            $this->api_return(

                [

                    'status' => false,

                    'message' =>'This email is already exist'

                ],

            409);

        }elseif(!empty($checkName)){

            $this->api_return(

                [

                    'status' => false,

                    'message' =>'This phone is already exist'

                ],

            409);

        }else{

            $otp=rand(1000,9999);

            $details['name'] = $this->input->post('name');

            $details['usertype'] = $this->input->post('usertype');

            $details['password'] =  md5($this->input->post('password'));

            $details['phone'] = $this->input->post('phone');

            $details['reg_id'] =  $this->input->post('reg_id');

            $details['otp'] =  $otp;

            $details['notificationStatus'] = '1';

            $details['device_type'] = $this->input->post('device_type');

            $details['created'] = date('Y-m-d H:i:s');

            if(!empty($_FILES['picture']['name'])){

                $config['upload_path'] = 'uploads/user/';

                $config['allowed_types'] = 'jpg|jpeg|png|gif';

                $config['file_name'] = $_FILES['picture']['name'];

                $this->load->library('upload',$config);

                $this->upload->initialize($config);

                if($this->upload->do_upload('picture')){

                    $uploadData = $this->upload->data();

                    $details['image'] = 'uploads/user/'.$uploadData['file_name'];

                }

            }

            $data = $this->db->insert('userDetails',$details);

            if($data){

                $insert_id = $this->db->insert_id();

                $datass['userId'] = $insert_id;

                $this->db->insert('userSettings', $datass);

                $userDetails = $this->db->get_where('userDetails',array('id' => $insert_id))->row_array();

                $payload = [

                    'phone' => $userDetails["phone"],

                    'reg_id' => $userDetails["reg_id"]

                ];

                // Load Authorization Library or Load in autoload config file

                $this->load->library('Authorization_Token');

                // generate a token

                $token = $this->authorization_token->generateToken($payload);

                // return data

                $userDetails['token'] = $token;

                $this->api_return(

                    [

                        'status' => true,

                        'message' =>'User register successfully',

                        "result" => $userDetails

                    ],

                200);

            }else{

                $this->api_return(

                    [

                        'status' => false,

                        'message' =>'Try after sometime'

                    ],

                400);

            }

        }

    }



    public function login(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

        $this->_apiConfig([

            'methods' => ['POST'],

        ]);

        // you user authentication code will go here, you can compare the user with the database or whatever

        $email = $this->input->post('phone');

        //$data = $this->User_model->userLogin('userDetails',$email,md5($this->input->post('password')));

        $password = md5($this->input->post('password'));

        $data = $this->db->query("select * from userDetails where (email='$email' or phone='$email') and password = '$password'")->row_array();

        if(!empty($data)){

            if($data['phoneVerifyStatus'] == '0'){

                $this->api_return(

                [

                    'status' => false,

                    "message"  => "Please verify your phone number"



                    ],

                401);

            }else{

                $datas = array('reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'));

                $update = $this->db->update('userDetails',$datas,array('id'=>$data['id']));

                $userDetails = $this->db->get_where('userDetails',array('id' => $data['id']))->row_array();

                $payload = [

                    'phone' => $userDetails["phone"],

                    'reg_id' => $userDetails["reg_id"]

                ];

                // Load Authorization Library or Load in autoload config file

                $this->load->library('Authorization_Token');

                // generate a token

                $token = $this->authorization_token->generateToken($payload);

                // return data

                $userDetails['token'] = $token;

                $this->api_return(

                    [

                        'status' => true,

                        'message' =>'user login successfully',

                        "result" => $userDetails



                    ],

                200);

            }

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "Please create your account"

                ],

            404);

        }

    }



    public function userMatchVerificationToken(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        //print_r($user_data['token_data']['phone']);die;

        $token = $this->input->post('otp');

        $patientCheck = $this->db->get_where('userDetails',array('otp'=>$token,'phone'=>$user_data['token_data']['phone']))->row_array();

        if(!empty($patientCheck)){

            $data['phoneVerifyStatus'] = '1';

            $data['otp'] = mt_rand(1000,9999);

            $update = $this->db->update('userDetails',$data,array('id'=>$patientCheck['id']));

            if($update){

                $details = $this->db->get_where('userDetails',array('id'=>$patientCheck['id']))->row_array();

                $this->api_return(

                [

                        'status' => true,

                        'message' => "User account verified successfully",

                        'result' => $details

                    ],

                200);

            }

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "Otp doesn't match"

                ],

            404);

        }

    }



    public function resendVerificationToken(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $this->_apiConfig([

            'methods' => ['POST']

        ]);

        $data1 = $this->db->get_where('userDetails',array('phone'=>$this->input->post('phone')))->row_array();

        if(empty($data1)){

             $this->api_return(

                [

                        'status' => false,

                        'message' => "Phone number doesn't exist"

                    ],

                401);

        }else{

            $data['otp'] = mt_rand(1000,9999);

            $update = $this->db->update('userDetails',$data,array('phone'=>$this->input->post('phone')));

            if($update){

                $userDetails = $this->db->get_where('userDetails',array('phone'=>$this->input->post('phone')))->row_array();

                $payload = [

                    'phone' => $userDetails["phone"],

                    'reg_id' => $userDetails["reg_id"]

                ];

                // Load Authorization Library or Load in autoload config file

                $this->load->library('Authorization_Token');

                // generate a token

                $token = $this->authorization_token->generateToken($payload);

                // return data

                $userDetails['token'] = $token;

                $this->api_return(

                [

                        'status' => true,

                        'message' => "Otp sent successfully",

                        'result' => $userDetails

                    ],

                200);

            }

        }

    }



    public function logout(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $user_data = $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => true,

        ]);

        $data['reg_id'] = '';

        $update = $this->db->update('userDetails',$data,array('phone'=>$user_data['token_data']['phone']));

        if($update){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "user logged out successfully"

                ],

            200);

        }else{

            $this->api_return(

            [

                    'status' => false,

                    'message' => "Try after sometime"

                ],

            400);

        }

    }



    public function view(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        // return data

        $this->api_return(

            [

                'status' => true,

                "result" => [

                    'user_data' => $user_data['token_data']

                ],

            ],

        200);

    }



    public function getBanners(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => true,

        ]);

        $details = $this->db->get('banners')->result_array();

        if(!empty($details)){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $details

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function getAllProducts(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $search = strtolower($this->input->post("search"));

        // $start = $this->input->post("start");

        // $limit = $this->input->post("limit");

        if(!empty($this->input->post("categoryId"))){

            if(empty($search))

                $details = $this->db->get_where('manageProduct',array('categoryId'=>$this->input->post("categoryId")))->result_array();

            else

                $details = $this->db->query("SELECT * from manageProduct where categoryId='".$this->input->post("categoryId")."' and (lower(title) like '%$search%' or lower(type) like '%$search%')")->result_array();

        }else{

            if(empty($search))

                $details = $this->db->get('manageProduct')->result_array();

            else

                $details = $this->db->query("SELECT * from manageProduct where lower(title) like '%$search%' or lower(type) like '%$search%'")->result_array();

        }

        if(!empty($details)){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $details

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function getCategories(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]

        $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => true,

        ]);

        $details = $this->db->get('manageCategory')->result_array();

        if(!empty($details)){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $details

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function getProductDetails(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $pro = $this->input->post("productId");

        $details = $this->db->query("select manageProduct.* from manageProduct where id=$pro")->row_array();

        if(!empty($details)){

            $rating = $this->db->query("select avg(rating) as rating from ratingList where productId='$pro'")->row_array();

            if(!empty($rating)){

                 $details['rating'] = (string)$rating['rating'];

            }else{

                $details['rating'] = '0';

            }

            $deta = $this->db->get_where("productImages",array('productId'=>$pro))->result_array();

            $review = $this->db->select("ratingList.*,userDetails.name,ifnull(userDetails.image,'') as image")->join("userDetails","userDetails.id=ratingList.userId","left")->get_where("ratingList",array('productId'=>$pro))->result_array();

            $details['productImages'] = !empty($deta)?$deta:[];

            $details['reviews'] = !empty($review)?$review:[];

            $details['reviewCount'] = strval($this->db->get_where("ratingList",array('productId'=>$pro))->num_rows());

            $packages = $this->db->get_where("productPackages",array('productId'=>$pro))->result_array();

            $details['packages'] = !empty($packages)?$packages:[];

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $details

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function userUpdateProfile(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $details['name'] = $this->input->post('name');

        $details['email'] = $this->input->post('email');

        $details['phone'] = $this->input->post('phone');

        $details['address'] =  $this->input->post('address');

        if(!empty($_FILES['picture']['name'])){

            $config['upload_path'] = 'uploads/user/';

            $config['allowed_types'] = 'jpg|jpeg|png|gif';

            $config['file_name'] = $_FILES['picture']['name'];

            $this->load->library('upload',$config);

            $this->upload->initialize($config);

            if($this->upload->do_upload('picture')){

                $uploadData = $this->upload->data();

                $details['image'] = 'uploads/user/'.$uploadData['file_name'];

            }

        }

        $details['updated'] = date('Y-m-d H:i:s');

        $update = $this->db->update('userDetails',$details,array('phone'=>$user_data['token_data']['phone']));

        if($update){

            $data = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

            if(filter_var($data['image'], FILTER_VALIDATE_URL)){

               $data['image'] = $data['image'];

            }else{

                $data['image'] = base_url().$data['image'];

            }

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details updated successfully",

                    'result' => $data

                ],

            200);

        }else{

            $this->api_return(

            [

                    'status' => false,

                    'message' => "Try after sometime"

                ],

            400);

        }

    }



    public function getTopRatedProducts(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

           'requireAuthorization' => false,

        ]);

        $search = strtolower($this->input->post("search"));

        $start = $this->input->post("start");

        $limit = $this->input->post("limit");



        // $data = array();

        // $whre ='';

        // if(!empty($search)){

        //   $data[] = "doctorDetails.city = '$city'";

        // }

        // if(!empty($data)){

        //   $whre = 'and '.implode(' and ', $data);

        // }



        if($this->input->post('type')=='1'){

            $details = $this->db->query("SELECT manageProduct.*, ifnull((select avg(rating) from ratingList where productId=manageProduct.id),'0.00') as rating,(select count(*) from orders where productId=manageProduct.id) as count from manageProduct where lower(manageProduct.title) like '%$search%' having rating >=3 limit $start,$limit")->result_array();

        }elseif($this->input->post('type')=='2'){

            $details = $this->db->query("SELECT manageProduct.*, ifnull((select avg(rating) from ratingList where productId=manageProduct.id),'0.00') as rating,(select count(*) from orders where productId=manageProduct.id) as count from manageProduct where lower(manageProduct.title) like '%$search%' order by count desc limit $start,$limit")->result_array();

        }elseif($this->input->post('type')=='3'){

            $details = $this->db->query("SELECT manageProduct.*, ifnull((select avg(rating) from ratingList where productId=manageProduct.id),'0.00') as rating,(select count(*) from orders where productId=manageProduct.id) as count from manageProduct where lower(manageProduct.title) like '%$search%' order by manageProduct.sale_price desc limit $start,$limit")->result_array();

        }elseif($this->input->post('type')=='4'){

            $details = $this->db->query("SELECT manageProduct.*, ifnull((select floor(avg(rating)) from ratingList where productId=manageProduct.id),'0.00') as rating,(select count(*) from orders where productId=manageProduct.id) as count from manageProduct where lower(manageProduct.title) like '%$search%' order by manageProduct.sale_price asc limit $start,$limit")->result_array();

        }elseif($this->input->post('type')=='5'){

            $details = $this->db->query("SELECT manageProduct.*, ifnull((select avg(rating) from ratingList where productId=manageProduct.id),'0.00') as rating,(select count(*) from productPackages where productId=manageProduct.id) as count from manageProduct where lower(manageProduct.title) like '%$search%' having count>0 limit $start,$limit")->result_array();

        }else{

            $details = $this->db->query("SELECT manageProduct.*,ifnull((select avg(rating) from ratingList where productId=manageProduct.id),'0.00') as rating from manageProduct where lower(manageProduct.title) like '%$search%' limit $start,$limit")->result_array();

        }

        if(!empty($details)){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $details

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function userAddAddress(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $details['name'] = $this->input->post('name');

        $details['userId'] = $this->input->post('userId');

        $details['houseNo'] = $this->input->post('houseNo');

        $details['phone'] = $this->input->post('phone');

        $details['apartmentName'] =  $this->input->post('apartmentName');

        $details['street'] = $this->input->post('street');

        $details['area'] = $this->input->post('area');

        $details['landMark'] = $this->input->post('landMark');

        $details['city'] =  $this->input->post('city');

        $details['zipCode'] = $this->input->post('zipCode');

        $details['default_status'] = '1';

        $details['nickName'] =  $this->input->post('nickName');

        $details['created'] = date('Y-m-d H:i:s');

        $update = $this->db->insert('userAddress',$details);

        if($update){

            $id = $this->db->insert_id();

            $userId = $this->input->post('userId');

            $this->db->query("update userAddress set default_status='0' where userId= '$userId' and id <> $id");

            $data = $this->db->get_where("userAddress",array('id'=>$id))->row_array();

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details added successfully",

                    'result' => $data

                ],

            200);

        }else{

            $this->api_return(

            [

                    'status' => false,

                    'message' => "Try after sometime"

                ],

            400);

        }

    }



    public function getUserAddressList(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

           'requireAuthorization' => true,

        ]);

        $details = $this->db->get_where("userAddress",array('userId'=>$this->input->post("userId")))->result_array();

        if(!empty($details)){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $details

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function deleteUserAddress(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

           'requireAuthorization' => true,

        ]);

        $details = $this->db->delete("userAddress",array('id'=>$this->input->post("addressId")));

        if($details){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Address deleted successfully"

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "Try after sometime"

                ],

            400);

        }

    }



    public function userUpdateAddress(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $details['name'] = $this->input->post('name');

        $details['houseNo'] = $this->input->post('houseNo');

        $details['phone'] = $this->input->post('phone');

        $details['apartmentName'] =  $this->input->post('apartmentName');

        $details['street'] = $this->input->post('street');

        $details['area'] = $this->input->post('area');

        $details['landMark'] = $this->input->post('landMark');

        $details['city'] =  $this->input->post('city');

        $details['zipCode'] = $this->input->post('zipCode');

        $details['nickName'] =  $this->input->post('nickName');

        $details['default_status'] = '1';

        $details['updated'] = date('Y-m-d H:i:s');

        $update = $this->db->update('userAddress',$details,array('id'=>$this->input->post('addressId')));

        if($update){

            $id = $this->input->post('addressId');

            $userId = $this->input->post('userId');

            $this->db->query("update userAddress set default_status='0' where userId= '$userId' and id <> $id");

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details updated successfully"

                ],

            200);

        }else{

            $this->api_return(

            [

                    'status' => false,

                    'message' => "Try after sometime"

                ],

            400);

        }

    }



    public function productAddToCart(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $data1 = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

        $details = $this->db->get_where('cart',array('userId'=>$data1['id'],'productId'=>$this->input->post('productId'),'type'=>$this->input->post('type')))->row_array();

        if(empty($details)){

            $data['userId'] = $data1['id'];

            $data['productId'] = $this->input->post('productId');

            $data['quantity'] = $this->input->post('quantity');

            $data['type'] = $this->input->post('type');

            $data['created'] = date('Y-m-d H:i:s');

            $update = $this->db->insert('cart',$data);

        }else{

            if($this->input->post('addType')=='1')

                $data['quantity'] = $details['quantity']+1;

            elseif($this->input->post('addType')=='2')

                $data['quantity'] = $details['quantity']-1;

            else

                $data['quantity'] = $details['quantity']+$this->input->post('quantity');

            $data['updated'] = date('Y-m-d H:i:s');

            $update = $this->db->update('cart',$data,array('id'=>$details['id']));

        }

        if($update){

            $this->api_return([

                'status' => true,

                'message' => "Item added in cart",

                'result' => array('quantity'=>$data['quantity'])

            ],200);

        }else{

            $this->api_return([

                'status' => false,

                'message' => "Try after sometime"

            ],400);

        }

    }



    public function getCartList(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => true,

        ]);

        $data1 = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

        $details = $this->db->get_where("cart",array('userId'=>$data1['id']))->result_array();

        if(!empty($details)){

            $productPrice=0;

            $packagePrice=0;

            foreach($details as $deta){

                if($deta['type']=='1'){

                    $price =$this->db->query("SELECT cart.quantity*manageProduct.sale_price as total FROM `cart` left join manageProduct on manageProduct.id=cart.productId and manageProduct.status='1' where cart.id='".$deta['id']."' and cart.type='1'")->row_array();

                    $productPrice += $price['total'];

                    $dd['cartList'][] = $this->db->query("SELECT cart.*,manageProduct.title,manageProduct.sale_price,ifnull(manageProduct.image,'') as image,manageProduct.type as product_type,manageProduct.status,manageProduct.description FROM `cart` left join manageProduct on manageProduct.id=cart.productId where cart.id='".$deta['id']."' and cart.type='1'")->row_array();

                }else{

                    $pprice = $this->db->query("SELECT cart.quantity*productPackages.price as total FROM `cart` left join productPackages on productPackages.id=cart.productId and productPackages.stockStatus='1' where cart.id='".$deta['id']."' and cart.type='2'")->row_array();

                    $packagePrice += $pprice['total'];

                    $dd['cartList'][] = $this->db->query("SELECT cart.*,productPackages.package_name as title,productPackages.price as sale_price,ifnull(manageProduct.image,'') as image,manageProduct.type as product_type,productPackages.stockStatus as status,productPackages.description FROM `cart` left join productPackages on productPackages.id=cart.productId left join manageProduct on manageProduct.id=productPackages.productId where cart.id='".$deta['id']."' and cart.type='2'")->row_array();

                }

            }

            $dd['totalPrice'] = strval($packagePrice+$productPrice);

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $dd

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function removeItemCart(){

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([

            'methods' => ['POST'],

           'requireAuthorization' => true,

        ]);

        $details = $this->db->delete("cart",array('id'=>$this->input->post("cartId")));

        if($details){

            $this->api_return(

            [

                    'status' => true,

                    'message' => "Item removed successfully"

                ],

            200);

        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function placeOrders(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $tprice=0;

        $datas = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

        //$//details = $this->db->get_where("cart",array('userId'=>))->result_array();

        $id = $this->input->post('userId');

        $data = $this->db->query("SELECT cart.*,ifnull(productPackages.price,'') as sale_price FROM `cart` left join productPackages on productPackages.id=cart.productId and productPackages.stockStatus='1' left join manageProduct on manageProduct.id=productPackages.productId where cart.userId ='".$datas['id']."' and cart.type='2'")->result_array();

        $data1 = $this->db->query("SELECT cart.*,ifnull(manageProduct.sale_price,'') as sale_price FROM `cart` left join manageProduct on manageProduct.id=cart.productId and manageProduct.status='1' where cart.userId ='".$datas['id']."' and cart.type='1'")->result_array();

        $data = array_merge($data,$data1);

        if(!empty($data)){

            $address = $this->db->get_where("userAddress",array('id'=>$this->input->post('addressId')))->row_array();

            // if($this->input->post('payment_type')=='2')

            //     $orderId = $this->input->post("orderId");

            // else

            $orderId = "order".time().uniqid().$id;

            foreach($data as $data){

                if($data['sale_price']!=''){

                  $tprice += $data['quantity']*$data['sale_price'];

                    $details[] = array('orderId' => $orderId,

                        'userId' => $data['userId'],

                        'productId' => $data['productId'],

                        'quantity' => $data['quantity'],

                        'orderDate' => date('Y-m-d'),

                        'orderTime' => date('H:i:s'),

                        'total_price' => $data['quantity']*$data['sale_price'],

                        'price' => $data['sale_price'],

                        'type' => $data['type'],

                        'area' => $address['area'],

                        'phone' => $address['phone'],

                        'name' => $address['name'],

                        'city' => $address['city'],

                        'pincode' => $address['zipCode'],

                        'houseNo' => $address['houseNo'],

                        'apartmentName' => $address['apartmentName'],

                        'street' => $address['street'],

                        'landMark' => $address['landMark'],

                        'nickName' => $address['nickName'],

                        'payment_type' => $this->input->post('payment_type'),

                        'gift_status' => $this->input->post('gift_status'),

                        'created' => date('Y-m-d H:i:s')

                    );

                }

            }

            $update = $this->db->insert_batch('orders',$details);

            if($update){

                if($this->input->post('payment_type')=='2'){

                  $userId = $datas['id'];

                    $price = ($tprice*100); // converted into pence

                    $get_price = $price;

                    require_once dirname(dirname(dirname(__FILE__))).'/libraries/stripe/init.php';

                    \stripe\Stripe::setApiKey("sk_test_AzTuGHt4rlqt6Tlpm6sR4bGN00lN7cbXjk"); //Replace with your Secret Key

                    try{

                    $payment_success="success";

                    $token = $this->input->post('stripe_token');



                    $customer = \stripe\Customer::create(array(

                        "source" => $token,

                        "description" => $userId)

                    );

                    $charge=\stripe\Charge::create(array(

                        "amount" => $get_price, // amount in pence, again

                        "currency" => "USD",

                        "customer" => $customer->id)

                    );



                    } catch (\stripe\Error\ApiConnection $e) {

                    $payment_success = $e->getMessage();

                    } catch (\stripe\Error\InvalidRequest $e) {

                    echo "Network problem, perhaps try again";

                    } catch (\stripe\Error\Api $e) {

                    echo "there is an error in your card validity try in few minutes";

                    } catch (\stripe\Error\Card $e) {

                    echo "servers are down kindly try again in few minutes";

                    }

                    if($payment_success=="success"){

                        //print_r($customer->id);

                    $detail['orderId'] = $orderId;

                            $detail['userId'] = $userId;

                            $detail['customer_id'] = $customer->id;

                            $detail['transaction_id'] = $charge->id;

                            $detail['amount'] = $tprice;

                            $detail['created'] = date('Y-m-d H:i:s');

                            $this->db->insert('userTransaction',$detail);

                    }

                }

                $this->db->delete('cart',array('userId'=>$datas['id']));

                $dataas = $this->db->get_where('userDetails',array('id'=>$datas['id']))->row_array();

                $this->load->library('email');

                $config['mailtype'] = 'html';

                $this->email->initialize($config);

                $this->email->from('bagicha.infosif@gmail.com');

                $this->email->to($dataas['email']);

                $this->email->subject("Beermates - Regarding Order Details.");

                $message1 = "Hi ".$dataas['name']." your order is placed successfully <br/>";

                $this->email->message($message1);

                $send = $this->email->send();

                $this->api_return([

                    'status' => true,

                    'message' => "Order placed successfully",

                    'result' => array('orderId' => $orderId)

                ],200);

            }

        }else{

          $this->api_return(

          [

                  'status' => false,

                  'message' => "Try after sometime"

              ],

          400);

        }

    }



    public function getOrderList(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $data1 = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

        $data = array();

        $whre ='';

        if($this->input->post('status')=='1'){

          $data[] = "status in ('1','2','3')";

        }elseif($this->input->post('status')=='4'){

            $data[] = "status = '4'";

        }

        if(!empty($data1['id'])){

          $data[] = "userId = '".$data1['id']."'";

        }



        if(!empty($data)){

          $whre = 'and '.implode(' and ', $data);

        }



        $details = $this->db->query("select orders.orderId,orders.orderDate,orders.orderTime,concat(orders.name,', ',orders.phone,', ',orders.houseNo,', ',orders.apartmentName,', ',orders.street,', ',orders.landMark,', ',orders.area,', ',orders.city,', ',orders.pincode,', ',orders.nickName) as address,sum(total_price) as total_price from orders where id<>0 $whre group by orderId")->result_array();

        if(!empty($details)){

            foreach($details as $deta){

                //if($deta['type']=='1'){

                $order = $this->db->query("SELECT orders.*,concat(orders.name,', ',orders.phone,', ',orders.houseNo,', ',orders.apartmentName,', ',orders.street,', ',orders.landMark,', ',orders.area,', ',orders.city,', ',orders.pincode,', ',orders.nickName) as address,manageProduct.title,manageProduct.sale_price,ifnull(manageProduct.image,'') as image,manageProduct.type as product_type,manageProduct.description FROM `orders` left join manageProduct on manageProduct.id=orders.productId where orders.orderId='".$deta['orderId']."' and orders.type='1'")->result_array();

            //}else{

                $order1 = $this->db->query("SELECT orders.*,concat(orders.name,', ',orders.phone,', ',orders.houseNo,', ',orders.apartmentName,', ',orders.street,', ',orders.landMark,', ',orders.area,', ',orders.city,', ',orders.pincode,', ',orders.nickName) as address,productPackages.package_name as title,productPackages.price as sale_price,ifnull(manageProduct.image,'') as image,manageProduct.type as product_type,productPackages.description FROM `orders` left join productPackages on productPackages.id=orders.productId left join manageProduct on manageProduct.id=productPackages.productId where orders.orderId='".$deta['orderId']."' and orders.type='2'")->result_array();

                $deta['orderList'] = array_merge($order,$order1);

                $dd[] = $deta;

            }

            if(!empty($dd)){

                $this->api_return([

                    'status' => true,

                    'message' => "Details found successfully",

                    'result' => $dd

                ],200);

            }else{

                $this->api_return([

                    'status' => false,

                    'message' => "Try after sometime"

                ],400);

            }



        }else{

            $this->api_return(

                [

                    'status' => false,

                    'message' => "No details found"

                ],

            404);

        }

    }



    public function userSettings(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $data1 = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

        $checkData = $this->db->get_where('userSettings',array('userId'=>$data1['id']))->row_array();

        $data['productEmails'] = $this->input->post('productEmails');

        $data['marketingEmails'] = $this->input->post('marketingEmails');

        $datas['notificationStatus'] = $this->input->post('notificationStatus');

        $this->db->update('userDetails',$datas,array('id'=>$data1['id']));

       if(empty($checkData)){

            $data['userId'] = $data1['id'];

            $data['created'] = date('Y-m-d H:i:s');

            $update = $this->db->insert('userSettings', $data);

        }else{

            $data['updated'] = date('Y-m-d H:i:s');

            $update = $this->db->update('userSettings',$data,array('id'=>$checkData['id']));

        }

        if($update){

            $this->api_return([

                'status' => true,

                'message' => "Permission Assigned Successfully"

            ],200);

        }else{

            $this->api_return(

            [

                    'status' => false,

                    'message' => "Try after sometime"

                ],

            400);

        }

    }



  public function getUserSettings(){

      header("Access-Control-Allow-Origin: *");

      $user_data = $this->_apiConfig([

          'methods' => ['GET'],

          'requireAuthorization' => true,

      ]);

      $data1 = $this->db->get_where("userDetails",array('phone'=>$user_data['token_data']['phone']))->row_array();

      $deta = $this->db->get_where('userSettings',array('userId'=>$data1['id']))->row_array();

      if(!empty($deta)){

          $deta['notificationStatus'] = $data1['notificationStatus'];

          $this->api_return([

                  'status' => true,

                  'message' => "Details found successfully",

                  'result' => $deta

              ],200);

      }else{

            $this->api_return([

                'status' => false,

                'message' => "No details found"

            ],404);

        }

    }



    public function aboutUs(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => false,

        ]);

        $data['datas'] = $this->db->get_where('pages',array('id'=>1))->row_array();

        $this->load->view('template/about_us',$data);

    }



    public function help(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => false,

        ]);

        $data['datas'] = $this->db->get_where('pages',array('id'=>3))->row_array();

        $this->load->view('template/about_us',$data);

    }



    public function terms(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => false,

        ]);

        $data['datas'] = $this->db->get_where('pages',array('id'=>2))->row_array();

        $this->load->view('template/terms',$data);

    }



    public function privacyAndPolicy(){

        header("Access-Control-Allow-Origin: *");

        $user_data = $this->_apiConfig([

            'methods' => ['GET'],

            'requireAuthorization' => false,

        ]);

        $data['datas'] = $this->db->get_where('pages',array('id'=>4))->row_array();

        $this->load->view('template/privacy_and_policy',$data);

    }



  public function contacUs(){

    header("Access-Control-Allow-Origin: *");

    // API Configuration

    $user_data = $this->_apiConfig([

        'methods' => ['POST'],

        'requireAuthorization' => true,

    ]);

    $Check = $this->db->get_where('userDetails',array('phone'=>$user_data['token_data']['phone']))->row_array();

    //if(!empty($patientCheck)){

    $data['userId'] = $Check['id'];

    $data['name'] = $this->input->post('name');

    $data['email'] = $this->input->post('email');

    $data['subject'] = $this->input->post('subject');

    $data['longDesc'] = $this->input->post('description');

    $data['created'] = date("Y-m-d H:i:s");

    $update = $this->db->insert('contactUs',$data);

    if($update){

        $this->api_return(

            [

                'status' => true,

                'message' =>'Details submitted successfully'

            ],

        200);

    }else{

      $this->api_return(

      [

              'status' => false,

              'message' => "Try after sometime"

          ],

      400);

    }

  }



  public function testingStripe(){

    header("Access-Control-Allow-Origin: *");

    $user_data = $this->_apiConfig([

        'methods' => ['POST'],

        'requireAuthorization' => false,

    ]);

        $userId = 1;

        $price = (800*100); // converted into pence

        $get_price = $price;

        require_once dirname(dirname(dirname(__FILE__))).'/libraries/stripe/init.php';

        \stripe\Stripe::setApiKey("sk_test_yAyO9JQMC8joBqVRb8JnY515008JvsDaqx"); //Replace with your Secret Key

        try{

        $payment_success="success";

        $token = $this->input->post('token');



        $customer = \stripe\Customer::create(array(

            "source" => $token,

            "description" => $userId)

        );

        $charge=\stripe\Charge::create(array(

            "amount" => $get_price, // amount in pence, again

            "currency" => "USD",

            "customer" => $customer->id)

        );



        } catch (\stripe\Error\ApiConnection $e) {

        $payment_success = $e->getMessage();

        } catch (\stripe\Error\InvalidRequest $e) {

        echo "Network problem, perhaps try again";

        } catch (\stripe\Error\Api $e) {

        echo "there is an error in your card validity try in few minutes";

        } catch (\stripe\Error\Card $e) {

        echo "servers are down kindly try again in few minutes";

        }

        if($payment_success=="success"){

            print_r($customer->id);

        }

    }



  public function addCardDetails(){

    header("Access-Control-Allow-Origin: *");

    // API Configuration

    $user_data = $this->_apiConfig([

        'methods' => ['POST'],

        'requireAuthorization' => true,

    ]);

    $Check = $this->db->get_where('userDetails',array('phone'=>$user_data['token_data']['phone']))->row_array();

    $cardCheck = $this->db->get_where('userCardDetails',array('cardNumber'=>$this->input->post('cardNumber')))->row_array();

    if(empty($cardCheck)){

      $data['userId'] = $Check['id'];

      $data['firstname'] = $this->input->post('firstname');

      $data['lastname'] = $this->input->post('lastname');

      $data['cardName'] = $this->input->post('cardName');

      $data['cardNumber'] = $this->input->post('cardNumber');

      $data['expiryDate'] = $this->input->post('expiryDate');

      $data['created'] = date("Y-m-d H:i:s");

      $update = $this->db->insert('userCardDetails',$data);

      if($update){

          $this->api_return(

              [

                  'status' => true,

                  'message' =>'Details submitted successfully'

              ],

          200);

      }else{

        $this->api_return(

        [

                'status' => false,

                'message' => "Try after sometime"

            ],

        400);

      }

    }else{

      $this->api_return(

      [

              'status' => false,

              'message' => "Card Number is already exist"

          ],

      409);

    }

  }



  public function getCardDetails(){

    header("Access-Control-Allow-Origin: *");

    // API Configuration

    $user_data = $this->_apiConfig([

        'methods' => ['GET'],

        'requireAuthorization' => true,

    ]);

    $Check = $this->db->get_where('userDetails',array('phone'=>$user_data['token_data']['phone']))->row_array();

    $datas = $this->db->get_where("userCardDetails",array('userId'=>$Check['id']))->result_array();

    if(!empty($datas)){

        $this->api_return(

            [

                'status' => true,

                'message' =>'Details found successfully',

                'result'=>$datas

            ],

        200);

    }else{

      $this->api_return(

      [

              'status' => false,

              'message' => "No details found"

          ],

      404);

    }

  }



  public function deleteUserCardDetails(){

      header("Access-Control-Allow-Origin: *");

      $this->_apiConfig([

          'methods' => ['POST'],

         'requireAuthorization' => true,

      ]);

      $details = $this->db->delete("userCardDetails",array('id'=>$this->input->post("cardId")));

      if($details){

          $this->api_return(

          [

                  'status' => true,

                  'message' => "Card Information deleted successfully"

              ],

          200);

      }else{

          $this->api_return(

              [

                  'status' => false,

                  'message' => "Try after sometime"

              ],

          400);

      }

  }



  public function userUpdateCardDetails(){

    header("Access-Control-Allow-Origin: *");

    $this->_apiConfig([

        'methods' => ['POST'],

        'requireAuthorization' => true,

    ]);

    $data['firstname'] = $this->input->post('firstname');

    $data['lastname'] = $this->input->post('lastname');

    $data['cardName'] = $this->input->post('cardName');

    $data['cardNumber'] = $this->input->post('cardNumber');

    $data['expiryDate'] = $this->input->post('expiryDate');

    $data['updated'] = date('Y-m-d H:i:s');

    $update = $this->db->update('userCardDetails',$data,array('id'=>$this->input->post('cardId')));

    if($update){

      $this->api_return([

          'status' => true,

          'message' => "Details updated successfully"

      ],200);

    }else{

      $this->api_return([

          'status' => false,

          'message' => "Try after sometime"

      ],400);

    }

  }













// ****************************  Rajat    ************************



public function artistinfo(){

    header("Access-Control-Allow-Origin: *");

     $this->_apiConfig([

        'methods' => ['POST'],

        'requireAuthorization' => false,

    ]);    

   // $Check = $this->db->get_where('userDetails',array('phone'=>$user_data['token_data']['phone']))->row_array();

    $data['userId'] = $this->input->post('userId');

    $data['description'] = $this->input->post('description');

    $data['jobTitle'] = $this->input->post('jobTitle');

    $data['location'] = $this->input->post('location');

    $data['workExperience'] = $this->input->post('workExperience');

    $data['websiteUrl'] = $this->input->post('websiteUrl');

    $data['skill'] = $this->input->post('skill');

    $data['currentPosition'] = $this->input->post('currentPosition');

    $insert = $this->db->insert('artistinfo',$data);

    if($insert){

      $this->api_return([

          'status' => true,

          'message' => "Details inserted successfully"

      ],200);

    }else{

      $this->api_return([

          'status' => false,

          'message' => "Try after sometime"

      ],400);

    }

  }



    
    public function getArtistDesignationInfo(){
       header("Access-Control-Allow-Origin: *");
       $this->_apiConfig([
           'methods' => ['GET'],
           'requireAuthorization' => false,
       ]);
       
       $data['job'] = $this->db->get_where('getArtistDesignationInfo',array('type' => '0'))->result_array();
       $data['skill'] = $this->db->get_where('getArtistDesignationInfo',array('type' => '1'))->result_array();
       $data['currentPosition'] = $this->db->get_where('getArtistDesignationInfo',array('type' => '2'))->result_array();
       $this->api_return([
           'status' => true,
           'message' => "List found successfully",
           'result' => $data
       ],200);

      
    }

     public function uploadProducts(){

        header("Access-Control-Allow-Origin: *");

        // API Configuration

         $this->_apiConfig([

        'methods' => ['POST'],

        'requireAuthorization' => false,

        ]); 

    if(!empty($this->input->post())){

        $data['userId'] = $this->input->post('userId');

        $data['title'] = $this->input->post('title');

        $data['price'] = $this->input->post('price');

        $data['description'] = $this->input->post('description');

        if(!empty($_FILES['image']['name'])){

                    $config['upload_path'] = 'uploads/product/';

                    $config['allowed_types'] = 'jpg|jpeg|png|gif';

                    $config['file_name'] = $_FILES['image']['name'];

                    $this->load->library('upload',$config);

                    $this->upload->initialize($config);

                    if($this->upload->do_upload('image')){

                        $uploadData = $this->upload->data();

                        $details['images'] = 'uploads/product/'.$uploadData['file_name'];

                    }

                }

        $data['bidStatus'] = $this->input->post('bidStatus');

        $insert = $this->db->insert('product',$data);

        if($insert){

          $this->api_return([

              'status' => true,

              'message' => "Details inserted successfully"

          ],200);

        }else{

          $this->api_return([

              'status' => false,

              'message' => "Try after sometime"

          ],400);

        }

    }
    else{
        $this->api_return([
            'status' => false,
            'message' => "Please enter values"
        ],400);

    }

    

       

    }



}

