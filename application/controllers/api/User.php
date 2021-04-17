<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	USE Aws\S3\S3Client;
class User extends CI_Controller {
	public function __construct(){
		parent::__construct();
		error_reporting(0);
		$this->load->model('api/Common_Model');
		$this->load->model('api/User_model');
		date_default_timezone_set('Asia/Kolkata');
	}

	public function cancre(){
		require APPPATH.'/libraries/vendor/autoload.php';
		$s3 = new Aws\S3\S3Client([
				'version' => 'latest',
				'region'  => 'us-east-2',
				'credentials' => [
						'key'    => 'AKIASKU6EJBLLBL5FSOL',
						'secret' => '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'
				]
		]);
		$bucket = 'cancremedia';

  	$upload = $s3->upload($bucket, $_FILES['videoPath']['name'], fopen($_FILES['videoPath']['tmp_name'], 'rb'), 'public-read');
		print_r($upload);
		die;
		echo $url = $upload->get('ObjectURL');
	}



	public function broadcastLiveList(){
		$url="https://api.bambuser.com/broadcasts";
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type:application/json',
			'Authorization:Bearer 4qvsJE4jB8qV3QHpGH3vXk',
			'Accept:application/vnd.bambuser.v1+json'
		));
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$result=curl_exec($ch);
		$json = json_decode($result);
		$bData = $json->results;
		foreach($bData as $bDatas){
			if($bDatas->type == 'live'){
				$bId = $bDatas->id;
				$list =  $this->db->query("SELECT liveBroadcast.*,users.name,users.username,users.image FROM liveBroadcast  left join users on users.id = liveBroadcast.user_id where liveBroadcast.broadcast_id = '$bId'")->row_array();
				if(empty($list['image'])){
						$list['image'] = base_url().'uploads/no_image_available.png';
				}
				$finalData[] = $list;
			}
		}
		if(!empty($finalData)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['detials'] = $finalData;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No List Found';
		}
		echo json_encode($message);
	}

	public function stopLiveBroadcast(){
		$data['type'] = 'archived';
		$this->Common_Model->update('liveBroadcast',$data,'broadcast_id',$this->input->post('broadcast_id'));
		$message['success'] = '1';
		$message['message'] = 'Status Update succssfully';
		echo json_encode($message);
	}


	public function giftHistory(){
		$type = $this->input->post('type');
		$userId = $this->input->post('userId');
		$url = base_url();
		if($type == 'sent'){
			$lists =  $this->db->query("select users.username,users.image,users.phone,users.name,gift.title as giftTitle,gift.primeAccount as giftCoin,concat('$url',gift.image) as giftImage, userGiftHistory.giftUserId,userGiftHistory.created from userGiftHistory left JOIN users on users.id = userGiftHistory.giftUserId left join gift on gift.id = userGiftHistory.giftId where userGiftHistory.userId = $userId")->result_array();
		}
		else{
			$lists =  $this->db->query("select users.username,users.image,users.phone,users.name,gift.title as giftTitle,gift.primeAccount as giftCoin,concat('$url',gift.image) as giftImage		, userGiftHistory.userId,userGiftHistory.created from userGiftHistory left JOIN users on users.id = userGiftHistory.userId left join gift on gift.id = userGiftHistory.giftId where userGiftHistory.giftUserId = $userId")->result_array();
		}
		if(!empty($lists)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			foreach($lists as $list){
				if(empty($list['image'])){
					$list['image'] = base_url().'uploads/no_image_available.png';
				}
				$message['details'][] = $list;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No List found';
		}
		echo json_encode($message);
	}

	public function sendGift(){
		$data['userId'] = $this->input->post('userId');
		$data['giftUserId'] = $this->input->post('giftUserId');
		$data['giftId'] = $this->input->post('giftId');
		$data['coin'] = $this->input->post('coin');
		$data['created'] = date('Y-m-d H:i:s');
		$insert = $this->db->insert('userGiftHistory',$data);
		if(!empty($insert)){
			$loginUserDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			$loginUpdateCoin['coin'] = $loginUserDetails['coin'] - $this->input->post('coin');
			$this->Common_Model->update('users',$loginUpdateCoin,'id',$this->input->post('userId'));

			$giftUserDetails = $this->db->get_where('users',array('id' => $this->input->post('giftUserId')))->row_array();
			$giftUserUpdate['coin'] = $giftUserDetails['coin'] + $this->input->post('coin');
			$this->Common_Model->update('users',$giftUserUpdate,'id',$this->input->post('giftUserId'));

			$regId = $giftUserDetails['reg_id'];
			$mess = 'You received a gift from '.$loginUserDetails['username'];
			$this->notification($regId,$mess,'gift',$this->input->post('userId'),$this->input->post('giftUserId'));

			$notiMess['loginId'] = $this->input->post('userId');
			$notiMess['userId'] = $this->input->post('giftUserId');
			$notiMess['message'] = $mess;
			$notiMess['type'] = 'gift';
			$notiMess['notiDate'] = date('Y-m-d');
			$notiMess['created'] = date('Y-m-d H:i:s');
			$this->db->insert('userNotification',$notiMess);


			$message['success'] = '1';
			$message['message'] = 'Gift send successfully';
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function purchaseCoin(){
		$data['userId'] = $this->input->post('userId');
		$data['coin'] = $this->input->post('coin');
		$data['transactionId'] = $this->input->post('transactionId');
		$data['created'] = date('Y-m-d H:i:s');
		$insert = $this->db->insert('userCoinHistory',$data);
		if(!empty($insert)){
			$checkCoin = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			$updateCoin['coin'] = $this->input->post('coin') + $checkCoin['coin'];
			$update = $this->Common_Model->update('users',$updateCoin,'id',$this->input->post('userId'));
			$message['success'] = '1';
			$message['message'] = 'Coin added successfully';
			$message['coin'] = (string)$updateCoin['coin'];
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function gift(){
		$list = $this->db->order_by('primeAccount','asc')->get_where('gift',array('status' => 'Approved'))->result_array();
		if(!empty($list)){
			$message['success'] = '1';
			$message['message'] = 'List found Successfully';
			$coinList = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			$message['coin'] = (string)$coinList['coin'];
			foreach($list as $lists){
				if(empty($lists['image'])){
					$lists['image'] = base_url().'uploads/no_image_available.png';
				}
				else{
					$lists['image'] = base_url().$lists['image'];
				}
				$lists['merePaise'] = (string)$coinList['coin'];
				$message['details'][] = $lists;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No details found';
		}
		echo json_encode($message);
	}

	public function coinList(){
		$list = $this->db->order_by('price','asc')->get_where('coin',array('status' => 'Approved'))->result_array();
		if(!empty($list)){
			$message['success'] = '1';
			$message['message'] = 'List found Successfully';
			$message['key'] = 'rzp_test_qO24jkOPFRjBUz';
			$coinList = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			$message['coin'] = (string)$coinList['coin'];
			foreach($list as $lists){
				if(empty($lists['image'])){
					$lists['image'] = base_url().'uploads/no_image_available.png';
				}
				else{
					$lists['image'] = base_url().$lists['image'];
				}
				$message['details'][] = $lists;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No details found';
		}
		echo json_encode($message);
	}


	public function getLive(){
		$list = $this->db->order_by('id','desc')->get_where('liveBroadcast')->result_array();
		if(!empty($list)){
			$messgae['success'] = '1';
			$messgae['message'] = 'list found Successfully';
			$messgae['details'] = $list;
		}
		else{
			$messgae['success'] = '0';
			$messgae['message'] = 'No details found';
		}
		echo json_encode($messgae);
	}


	public function storeLiveBrodcasting(){
		if($this->input->post()){
			$url="https://api.bambuser.com/broadcasts/".$this->input->post('broadcast_id');
			$ch = curl_init();
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Will return the response, if false it print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Set the url
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type:application/json',
				'Authorization:Bearer 4qvsJE4jB8qV3QHpGH3vXk',
				'Accept:application/vnd.bambuser.v1+json'
			));
			curl_setopt($ch, CURLOPT_URL,$url);
			// Execute
			$result=curl_exec($ch);


			// Closing
			curl_close($ch);
			$resultss = json_decode($result);
			//print_r($resultss);die;
			$resultss->broadcast_id = $this->input->post('broadcast_id');
			$resultss->user_id = $this->input->post('userId');
			unset($resultss->id);
			$this->db->insert('liveBroadcast',$resultss);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				$message = [
					'message' => 'live broadcast create successfully',
					'success'=>'1'
				];
			}
		}
		else
		{
			$message = [
				'message' => 'please enter parameters', // Automatically generated by the model
			];

		}
			//header('Content-Type: application/json');
						echo json_encode($message);

	}

	public function getLiveBroadcast(){
		//$userId = $this->input->post('userId');
	//	$list =  $this->db->query("SELECT liveBroadcast.*,users.name,users.username,users.image,userFollow.followingUserId FROM liveBroadcast left join userFollow on userFollow.followingUserId = liveBroadcast.user_id left join users on users.id = userFollow.followingUserId where userFollow.userId = $userId and userFollow.status = '1' and liveBroadcast.type = 'live' order by liveBroadcast.id desc")->result_array();
		// $list =  $this->db->query("SELECT liveBroadcast.*,users.name,users.username,users.image FROM liveBroadcast  left join users on users.id = liveBroadcast.user_id where liveBroadcast.type = 'live' order by liveBroadcast.id desc")->result_array();
		// if(!empty($list)){
		// 	$message['success'] = '1';
		// 	$message['message'] = 'list found successfully';
		// 	foreach($list as $lists){
		// 		if(empty($lists['image'])){
		// 				$lists['image'] = base_url().'uploads/no_image_available.png';
		// 		}
		// 		$message['details'][] = $lists;
		// 	}
		// }
		// else{
		// 	$message['success'] = '0';
		// 	$message['message'] = 'No list found';
		// }
		// echo json_encode($message);


		$url="https://api.bambuser.com/broadcasts";
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type:application/json',
			'Authorization:Bearer 4qvsJE4jB8qV3QHpGH3vXk',
			'Accept:application/vnd.bambuser.v1+json'
		));
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$result=curl_exec($ch);
		$json = json_decode($result);
		$bData = $json->results;
		foreach($bData as $bDatas){
			if($bDatas->type == 'live'){
				$bId = $bDatas->id;
				$list =  $this->db->query("SELECT liveBroadcast.*,users.name,users.username,users.image FROM liveBroadcast  left join users on users.id = liveBroadcast.user_id where liveBroadcast.broadcast_id = '$bId'")->row_array();
				if(empty($list['image'])){
						$list['image'] = base_url().'uploads/no_image_available.png';
				}
				$finalData[] = $list;
			}
		}
		if(!empty($finalData)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $finalData;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No List Found';
		}
		echo json_encode($message);

	}



	public function Photofit(){
		$photofit = $this->db->get_where('badges')->result_array();
		if(!empty($photofit)){
			foreach($photofit as $list){
				$totalLikes = $list['likes'];
				$totalFollowers = $list['totalFollowers'];
				$userList = $this->db->query("SELECT * FROM `userProfileInformation` where likes >= $totalLikes and followers >= $totalFollowers ")->result_array();
				if(!empty($userList)){
					foreach($userList as $userL){
						$type['badge'] = $list['title'];
						$update = $this->Common_Model->update('users',$type,'id',$userL['userId']);
					}
				}
			}
		}
	}

	public function deleteAccountRequest(){
		$userId['userId'] = $this->input->post('userId');
		$userId['created'] = date('Y-m-d H:i:s');
		$this->db->insert('deleteAccountRequest',$userId);
		$message['success'] = '1';
		$message['message'] = 'Delete Account Request sent successfully';
		echo json_encode($message);
	}

	public function deleteAccountOtp(){
		$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		$otp = rand(1000,9999);
		if(!empty($getUserDetails['phone'])){
			$phone = $getUserDetails['phone'];
			$mess = 'HI+'.$getUserDetails['username'].',+your+OTP+for+Delete+Account+is:+'.$otp;
			$created = date('Y-m-d+H:s:i');
			$url = "http://164.52.195.161/API/SendMsg.aspx?uname=20180144&pass=2kSqRn9p&send=INFSMS&dest=$phone&msg=$mess&priority=1&schtm=$created";
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$output=curl_exec($ch);
			curl_close($ch);
			$var = 'Phone Number';
		}
		else{
			$var = 'Email';
		}
		$message['success'] = '1';
		$message['message'] = 'OTP sent on your '.$var;
		$message['otp'] = (string)$otp;
		echo json_encode($message);
	}

	public function privateAccountStatus(){
		$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		if($getUserDetails['privateAccount'] == 0){
			$finalData['privateAccount'] = false;
		}
		else{
			$finalData['privateAccount'] = true;
		}
		if($getUserDetails['followingUser'] == 1){
			$finalData['followingViewStatus'] = true;
		}
		else{
			$finalData['followingViewStatus'] = false;
		}
		if($getUserDetails['profilePhotoStatus'] == 1){
			$finalData['profilePhotoStatus'] = true;
		}
		else{
			$finalData['profilePhotoStatus'] = false;
		}
		if($getUserDetails['likeVideo'] == 0){
			$finalData['likeVideo'] = false;
		}
		else{
			$finalData['likeVideo'] = true;
		}
		$message['success'] = '1';
		$message['message'] = 'List found successfully';
		$message['details'] = $finalData;
		echo json_encode($message);
	}

	public function videoNotification($vId,$uId){
		$videoId = $vId;
		$userId = $uId;
		$lists = $this->db->get_where('userFollow',array('followingUserId' => $userId))->result_array();
		if(!empty($lists)){
			foreach($lists as $list){
				$loginUserDetails = $this->db->get_where('users',array('id' => $userId))->row_array();
				$getUserId = $this->db->get_where('users',array('id' => $list['userId']))->row_array();
				$regId = $getUserId['reg_id'];
				$mess = $loginUserDetails['username'].' uploaded new video';
				if($loginUserDetails['videoNotification'] == '1'){
					$this->notification($regId,$mess,'video',$list['userId'],$userId);
				}
				$notiMess['loginId'] = $userId;
				$notiMess['userId'] = $list['userId'];
				$notiMess['videoId'] = $videoId;
				$notiMess['message'] = $mess;
				$notiMess['type'] = 'video';
				$notiMess['notiDate'] = date('Y-m-d');
				$notiMess['created'] = date('Y-m-d H:i:s');
				$this->db->insert('userNotification',$notiMess);
			}
		}
		return $userId;
	}

	public function forgotPass(){
		$type = $this->input->post('type');
    	$emailPhone = $this->input->post('emailPhone');
    	$check = $this->db->get_where('users',array($type => $emailPhone))->row_array();
	    if(!empty($check)){
				$otp = rand(1000,9999);
				if($type == 'phone'){
					$mess = 'Your Verification Code Is: '.$otp;
					$mobile = $this->input->post('emailPhone');
					$created = date('Y-m-d+H:s:i');
					$url = "https://api.msg91.com/api/sendhttp.php?authkey=339739Aqv4rSuggd5f44f645P1&mobiles=$mobile&country=91&message=$mess&sender=CANCRE&route=4";
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL,$url);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					$output=curl_exec($ch);
					curl_close($ch);
				}
				else{
					$url = "http://cancre.in/SendEmail/sendEmail.php?otp=$otp&email=$emailPhone";
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$url);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
						$output=curl_exec($ch);
						curl_close($ch);
				}
      	$message['success'] = '1';
      	$message['message'] = 'Otp sent to your '.$type;
      	$message['otp'] = (string)$otp;
      }
    	else{
        	$message['success'] = '0';
        	$message['message'] = $type.' doesnt exists';
        }
    	echo json_encode($message);
    }

	public function updatePassword(){
   		$type = $this->input->post('type');
    	$emailPhone = $this->input->post('emailPhone');
    	$data['password'] = md5($this->input->post('password'));
    	$update = $this->Common_Model->update('users',$data,$type,$emailPhone);
 	   if(!empty($update)){
            $message['success'] = '1';
            $message['message'] = 'Password updated successfully';
        }
        else{
            $message['success'] = '0';
            $message['message'] = 'Please try after some time';
        }
        echo json_encode($message);
	}



	public function addFavoriteSounds(){
		if($this->input->post()){
        	$deta = $this->db->get_where("favouriteSoundList",array('soundId'=>$this->input->post('soundId'),'userId'=>$this->input->post('userId')))->row_array();
        	$data['userId'] = $this->input->post('userId');
        	$data['soundId'] = $this->input->post('soundId');
			if(empty($deta)){
            	$data['status'] = '1';
            	$data['created'] = date("Y-m-d H:i:s");
            	$in = $this->db->insert("favouriteSoundList",$data);
			}else{
				$data['status'] = ($deta['status']=='0')?'1':'0';
            	$data['updated'] = date("Y-m-d H:i:s");
            	$in = $this->db->update("favouriteSoundList",$data,array('soundId'=>$this->input->post('soundId'),'userId'=>$this->input->post('userId')));
			}
        	if($in){
            	$message['success'] = '1';
				$message['message'] = 'Added to favorites';
            }else{
            	$message['success'] = '0';
				$message['message'] = 'Please try again';
            }
		}else{
			$message['message'] = 'Please enter parameters';
        }
		echo json_encode($message);
	}

  	public function followerDelete(){
		$delte = $this->db->delete('userFollow',array('id' => $this->input->post('id')));
		if(!empty($delte)){
			$message['success'] = '1';
			$message['message'] = 'User Remove from list successfully';
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

  	public function otherFollowingList(){
		$search = $this->input->post('search');
		if($search == "" || $search == null || empty($search)){
			$search = "";
		}
      $lists = $this->Common_Model->followingUser($this->input->post('userId'),$search);
      if(!empty($lists)){
        $message['success'] = '1';
        $message['message'] = 'List found Successfully';
        foreach($lists as $list){
          	$checkStataus = $this->db->get_where('userFollow',array('followingUserId' => $list['followingUserId'],'userId' => $this->input->post('ownerId'),'status' => '1'))->row_array();
          	if(!empty($checkStataus)){
              $list['friendStatus'] = true;
            }
          	else{
          		$list['friendStatus'] = false;
            }
          	if(empty($list['image'])){
				$list['image'] =  base_url().'uploads/no_image_available.png';
			}
          $message['details'][] = $list;
        }
      }
      else{
      	$message['success'] = '0';
        $message['message'] = 'no list found';
      }
      echo json_encode($message);
    }

  	public function otherFollowerList(){
		$search = $this->input->post('search');
		if($search == "" || $search == null || empty($search)){
			$search = "";
		}
      $lists = $this->Common_Model->followerUser($this->input->post('userId'),$search);
      if(!empty($lists)){
        $message['success'] = '1';
        $message['message'] = 'List found Successfully';
        foreach($lists as $list){
          $checkStataus = $this->db->get_where('userFollow',array('followingUserId' => $this->input->post('ownerId'),'userId' => $list['userId'],'status' => '1'))->row_array();
          	if(!empty($checkStataus)){
              $list['friendStatus'] = true;
            }
          	else{
          		$list['friendStatus'] = false;
            }
          	if(empty($list['image'])){
				$list['image'] =  base_url().'uploads/no_image_available.png';
			}
          $message['details'][] = $list;
        }
      }
      else{
      	$message['success'] = '0';
        $message['message'] = 'no list found';
      }
      echo json_encode($message);
    }

	public function followingList(){
		$search = $this->input->post('search');
		if($search == "" || $search == null || empty($search)){
			$search = "";
		}
      $lists = $this->Common_Model->followingUser($this->input->post('userId'),$search);
      if(!empty($lists)){
        $message['success'] = '1';
        $message['message'] = 'List found Successfully';
        foreach($lists as $list){
          	$checkStataus = $this->db->get_where('userFollow',array('userId' => $list['followingUserId'],'followingUserId' => $this->input->post('userId'),'status' => '1'))->row_array();
          	if(!empty($checkStataus)){
              $list['friendStatus'] = true;
            }
          	else{
          		$list['friendStatus'] = false;
            }
          	if(empty($list['image'])){
				$list['image'] =  base_url().'uploads/no_image_available.png';
			}
          $message['details'][] = $list;
        }
      }
      else{
      	$message['success'] = '0';
        $message['message'] = 'no list found';
      }
      echo json_encode($message);
    }


	public function followerList(){
		$search = $this->input->post('search');
		if($search == "" || $search == null || empty($search)){
			$search = "";
		}
      $lists = $this->Common_Model->followerUser($this->input->post('userId'),$search);
      if(!empty($lists)){
        $message['success'] = '1';
        $message['message'] = 'List found Successfully';
        foreach($lists as $list){
          $checkStataus = $this->db->get_where('userFollow',array('userId' => $list['followingUserId'],'followingUserId' => $list['userId'],'status' => '1'))->row_array();
          	if(!empty($checkStataus)){
              $list['friendStatus'] = true;
            }
          	else{
          		$list['friendStatus'] = false;
            }
          	if(empty($list['image'])){
				$list['image'] = base_url().'uploads/no_image_available.png';
			}
          $message['details'][] = $list;
        }
      }
      else{
      	$message['success'] = '0';
        $message['message'] = 'no list found';
      }
      echo json_encode($message);
    }

	public function videoDelete(){
		$delte = $this->db->delete('userVideos',array('id' => $this->input->post('videoId')));
		if(!empty($delte)){
			$this->db->delete('videoComments',array('videoId' => $this->input->post('videoId')));
			$this->db->delete('videoLikeOrUnlike',array('videoId' => $this->input->post('videoId')));
			$this->db->delete('videoSubComment',array('videoId' => $this->input->post('videoId')));
			$this->db->delete('viewVideo',array('videoId' => $this->input->post('videoId')));
			$this->db->delete('userNotification',array('videoId' => $this->input->post('videoId')));
			$message['success'] = '1';
			$message['message'] = 'Video Delete successfully';
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}
	public function commentDelete(){
		$delte = $this->db->delete('videoComments',array('id' => $this->input->post('commentId')));
		if(!empty($delte)){
			$this->db->delete('videoCommentsLikeOrUnlike',array('commentId' => $this->input->post('commentId')));
			$checkCommentCount = $this->db->get_where('userVideos',array('id' =>$this->input->post('videoId')))->row_array();
			$checkSubCommentCount = $this->db->get_where('videoSubComment',array('commentId' =>$this->input->post('commentId')))->num_rows();
			if(!empty($checkSubCommentCount)){
				$finalCommentCount =  $checkCommentCount['commentCount'] + $checkSubCommentCount;
			}
			else{
				$finalCommentCount =  $checkCommentCount['commentCount'];
			}
			$upComment['commentCount'] = $checkCommentCount['commentCount'] - $finalCommentCount;
			$this->Common_Model->update('userVideos',$upComment,'id', $this->input->post('videoId'));
			$this->db->delete('videoSubComment',array('commentId' => $this->input->post('commentId')));
			$message['success'] = '1';
			$message['message'] = 'Comment Delete successfully';
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}


	public function getTime($time){
		$timeDiff = time() - strtotime($time);
		$nYears = (int)($timeDiff / (60*60*24*365));
		$nMonths = (int)(($timeDiff % (60*60*24*365)) / (60*60*24*30));
		$nDays = (int)((($timeDiff % (60*60*24*365)) % (60*60*24*30)) / (60*60*24));
		$nHours = (int)(((($timeDiff % (60*60*24*365)) % (60*60*24*30)) % (60*60*24)) / (60*60));
		$nMinutes = (int)((((($timeDiff % (60*60*24*365)) % (60*60*24*30)) % (60*60*24)) % (60*60)) / (60));
		$timeMsg = "";
		if($nYears > 0){
			$yearWord = "y";
			if($nYears == 1)
			{
				$yearWord = "y";
			}
			$timeMsg = "$nYears $yearWord";
		}
		elseif($nMonths > 0){
			$monthWord = "m";
			if($nMonths == 1)
			{
				$monthWord = "m";
			}
			$timeMsg = "$nMonths $monthWord";
		}
		elseif($nDays > 0){
			$dayWord = "d";
			if($nDays == 1)
			{
				$dayWord = "d";
			}
			$timeMsg = "$nDays $dayWord";
		}
		elseif($nHours > 0){
			$hourWord = "h";
			if($nHours == 1){
				$hourWord = "h";
			}
			$timeMsg = "$nHours $hourWord";
		}
		elseif($nMinutes > 0)
		{
			$minuteWord = "min";
			if($nMinutes == 1)
			{
				$minuteWord = "min";
			}
			$timeMsg = "$nMinutes $minuteWord";
		}
		else
		{
			$timeMsg = "just now";
		}
		return $timeMsg;
	}


	public function manavTime($time){
		$timeDiff = time() - strtotime($time);
		$nYears = (int)($timeDiff / (60*60*24*365));
		$nMonths = (int)(($timeDiff % (60*60*24*365)) / (60*60*24*30));
		$nDays = (int)((($timeDiff % (60*60*24*365)) % (60*60*24*30)) / (60*60*24));
		$nHours = (int)(((($timeDiff % (60*60*24*365)) % (60*60*24*30)) % (60*60*24)) / (60*60));
		$nMinutes = (int)((((($timeDiff % (60*60*24*365)) % (60*60*24*30)) % (60*60*24)) % (60*60)) / (60));
		$timeMsg = "";
		if($nYears > 0){
			$yearWord = "year";
			if($nYears == 1)
			{
				$yearWord = "year";
			}
			$timeMsg = "$nYears $yearWord";
		}
		elseif($nMonths > 0){
			$monthWord = "month";
			if($nMonths == 1)
			{
				$monthWord = "month";
			}
			$timeMsg = "$nMonths $monthWord";
		}
		elseif($nDays > 0){
			$dayWord = "day";
			if($nDays == 1)
			{
				$dayWord = "day";
			}
			$timeMsg = "$nDays $dayWord";
		}
		elseif($nHours > 0){
			$hourWord = "hour";
			if($nHours == 1){
				$hourWord = "hour";
			}
			$timeMsg = "$nHours $hourWord";
		}
		elseif($nMinutes > 0)
		{
			$minuteWord = "min";
			if($nMinutes == 1)
			{
				$minuteWord = "min";
			}
			$timeMsg = "$nMinutes $minuteWord";
		}
		else
		{
			$timeMsg = "just now";
		}
		return $timeMsg;
	}

	public function userNotification(){
    	$countMessage = $this->db->get_where('conversation',array('reciver_id' => $this->input->post('userId'),'readStatus' => 0))->num_rows();
		if(!empty($countMessage)){
			$message['messageCount'] = (string)$countMessage;
		}
		else{
			$message['messageCount'] = "0";
		}
		$upsttt['status'] = 1;
		$update = $this->Common_Model->update('userNotification',$upsttt,'userId',$this->input->post('userId'));
		$lists = $this->Common_Model->userNotification($this->input->post('userId'));
		if(!empty($lists)){
			$todayDate = date('Y-m-d');
			$datetime = new DateTime($todayDate);
			$datetime->modify('-1 day');
			$yestradyDate =  $datetime->format('Y-m-d');
			foreach($lists as $list){
				$message['success'] = '1';
				$message['message'] = 'Details Found Successfully';
				if($list['notiDate'] == $todayDate){
					$noti = $this->db->order_by('id','desc')->get_where('userNotification',array('notiDate' => $list['notiDate'],'userId' => $this->input->post('userId')))->result_array();
					foreach($noti as $notis){
						$checkFollow = $this->db->get_where('userFollow', array('userId' => $this->input->post('userId'),'followingUserId' => $notis['loginId']))->row_array();
						if(!empty($checkFollow)){
							if($checkFollow['status'] == '1'){
								$notis['followStatus'] = true;
							}
							else{
								$notis['followStatus'] = false;
							}
						}
						else{
							$notis['followStatus'] = false;
						}
						$notis['time'] = $this->manavTime($notis['created']);
						$userDetails = $this->db->get_where('users',array('id' => $notis['loginId']))->row_array();
						if(empty($userDetails['image'])){
							$notis['image'] = base_url().'uploads/no_image_available.png';
						}
                    	else{
                    		$notis['image'] = $userDetails['image'];
                        }
                    	$videoDetails = $this->db->get_where('userVideos',array('id' => $notis['videoId']))->row_array();
						if(empty($videoDetails['videoPath'])){
							$notis['video'] = '';
						}
                    	else{
                    		$notis['video'] = $videoDetails['videoPath'];
                        }
						$manav[] = $notis;
					}
					$data['day'] = 'Today';
					$data['listdetails'] = $manav;
					unset($manav);
				}
				elseif($list['notiDate'] == $yestradyDate){
					$noti = $this->db->get_where('userNotification',array('notiDate' => $list['notiDate'],'userId' => $this->input->post('userId')))->result_array();
					foreach($noti as $notis){
						$checkFollow = $this->db->get_where('userFollow', array('userId' => $this->input->post('userId'),'followingUserId' => $notis['loginId']))->row_array();
						if(!empty($checkFollow)){
							if($checkFollow['status'] == '1'){
								$notis['followStatus'] = true;
							}
							else{
								$notis['followStatus'] = false;
							}
						}
						else{
							$notis['followStatus'] = false;
						}
						$notis['time'] = $this->manavTime($notis['created']);
						$userDetails = $this->db->get_where('users',array('id' => $notis['loginId']))->row_array();
						if(empty($userDetails['image'])){
							$notis['image'] =  base_url().'uploads/no_image_available.png';
						}
                    	else{
                    		$notis['image'] = $userDetails['image'];
                        }
                   		$videoDetails = $this->db->get_where('userVideos',array('id' => $notis['videoId']))->row_array();
						if(empty($videoDetails['videoPath'])){
							$notis['video'] = '';
						}
                    	else{
                    		$notis['video'] = $videoDetails['videoPath'];
                        }
						$manav[] = $notis;
					}
					$data['day'] = 'Yesterday';
					$data['listdetails'] = $manav;
					unset($manav);
				}
				else{
					$noti = $this->db->get_where('userNotification',array('notiDate' => $list['notiDate'],'userId' => $this->input->post('userId')))->result_array();
					foreach($noti as $notis){
						$checkFollow = $this->db->get_where('userFollow', array('userId' => $this->input->post('userId'),'followingUserId' => $notis['loginId']))->row_array();
						if(!empty($checkFollow)){
							if($checkFollow['status'] == '1'){
								$notis['followStatus'] = true;
							}
							else{
								$notis['followStatus'] = false;
							}
						}
						else{
							$notis['followStatus'] = false;
						}
						$notis['time'] = $this->manavTime($notis['created']);
						$userDetails = $this->db->get_where('users',array('id' => $notis['loginId']))->row_array();
						if(empty($userDetails['image'])){
							$notis['image'] =  base_url().'uploads/no_image_available.png';
						}
                    	else{
                    		$notis['image'] = $userDetails['image'];
                        }
                    	$videoDetails = $this->db->get_where('userVideos',array('id' => $notis['videoId']))->row_array();
						if(empty($videoDetails['videoPath'])){
							$notis['video'] = '';
						}
                    	else{
                    		$notis['video'] = $videoDetails['videoPath'];
                        }
						$manav[] = $notis;
					}
					$date11=date_create($list['notiDate']);
					$dateTitle =  date_format($date11,"d M Y");
					$data['day'] = $this->manavTime($list['created']);;
					$data['listdetails'] = $manav;
					unset($manav);
				}

				$message['details'][] = $data;
				unset($data);
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No List found';
		}
		echo json_encode($message);
	}


	public function notification($regId,$message,$type,$loginId,$userId){
   		$checkMuteNotifiaton = $this->db->get_where('muteUserNotification',array('userId' => $userId,'muteId' => $loginId,'status' => '1'))->row_array();
    	if(empty($checkMuteNotifiaton)){
		$registrationIds =  array($regId);
		define('API_ACCESS_KEY', 'AAAAgSo3kQs:APA91bG0vlTjQ2JAJ8xuVXVrSnAcHYrHV31-xwIT3X6b7Gvo6oCoNaf0LkG6koZrzTUr2QpSQ8ZZUT4sbfk71wSNDNr4gkINuudR0humcpkivp-l6U1HG_bpNYKJirdpr8Miw6UctU-z');
		 $msg = array(
			 'message' 	=> $message,
			 'title'		=> 'Cancre',
			 'type'		=> $type,
			 'subtitle'	=> $type,
			 'loginId' => $loginId,
			 'userId' => $userId,
			 'vibrate'	=> 1,
			 'sound'		=> 1,
			 'largeIcon'	=> 'large_icon',
			 'smallIcon'	=> 'small_icon',
		 );
		 $fields = array(
			 'registration_ids' 	=> $registrationIds,
			 'data'			=> $msg
		 );
		 $headers = array(
			 'Authorization: key=' . API_ACCESS_KEY,
			 'Content-Type: application/json'
		 );
		 $curl = curl_init();

		 curl_setopt_array($curl, array(
			 CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
			 CURLOPT_RETURNTRANSFER => true,
			 CURLOPT_CUSTOMREQUEST => "POST",
			 CURLOPT_POSTFIELDS => json_encode($fields),
			 CURLOPT_HTTPHEADER => $headers

		 ));

		 $response = curl_exec($curl);
		 $err = curl_error($curl);
		 curl_close($curl);
        }
	}

	public function userFollow(){
		$check_like =  $this->db->get_where('userFollow', array('userId' => $this->input->post('userId'),'followingUserId' => $this->input->post('followingUserId')))->row_array();
		if(!empty($check_like)){
			if($check_like['status'] == '0'){
				$status = '1';
			}
			else{
				$status = '0';
			}
			$data = array(
				'userId' => $this->input->post('userId'),
				'followingUserId' => $this->input->post('followingUserId'),
				'status' => $status,
				'updated' => date('y-m-d h:i:s')
			);
			$update = $this->Common_Model->update('userFollow',$data,'id',$check_like['id']);
		}
		else{
			$status = '1';
			$data = array(
				'userId' => $this->input->post('userId'),
				'followingUserId' => $this->input->post('followingUserId'),
				'status' => $status,
				'created' => date('y-m-d h:i:s')
			);
			$insert = $this->db->insert('userFollow', $data);
			$insert_id = $this->db->insert_id();
		}
		$likeInformation = $this->db->get_where('userProfileInformation',array('userId' => $this->input->post('followingUserId')))->row_array();
		if(empty($check_like)){
			$userProfile['followers'] = 1 + $likeInformation['followers'];
			$message123 = 'user following successfully';
			$sendStatus = true;
		}
		else{
			if($status == '0'){
				$userProfile['followers'] = $likeInformation['followers'] - 1;
				$message123 = 'user unfollowing successfully';
				$sendStatus = false;
			}
			else{
				$userProfile['followers'] = 1 + $likeInformation['followers'];
				$message123 = 'user following successfully';
				$sendStatus = true;
			}
		}
		$UserDetails = $this->db->get_where('users',array('id' => $this->input->post('followingUserId')))->row_array();
		if($status == '1'){
			$loginUserDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			$mess = $loginUserDetails['username']." started following you";
			$regId = $UserDetails['reg_id'];
			if($loginUserDetails['followersNotification'] == '1'){
				$this->notification($regId,$mess,'follow',$this->input->post('userId'),$this->input->post('followingUserId'));
			}
			$notiMess['loginId'] = $this->input->post('userId');
			$notiMess['userId'] = $this->input->post('followingUserId');
			$notiMess['message'] = $mess;
			$notiMess['type'] = 'follow';
			$notiMess['notiDate'] = date('Y-m-d');
			$notiMess['created'] = date('Y-m-d H:i:s');
			$this->db->insert('userNotification',$notiMess);

			$upFollowStatus['followerCount'] = $UserDetails['followerCount'] + 1;
		}
		else{
			$upFollowStatus['followerCount'] = $UserDetails['followerCount'] - 1;
		}

	    $this->Common_Model->update('users',$upFollowStatus,'id',$this->input->post('followingUserId'));


		$update = $this->Common_Model->update('userProfileInformation',$userProfile,'id',$likeInformation['id']);
		$likeCount = $this->db->get_where('userFollow', array('followingUserId' => $this->input->post('followingUserId'),'status'=> '1'))->num_rows();
		$successmessage = array(
				'success'=>'1',
				'message' => $message123,
				'following_status'=>$sendStatus,
				'following_count'=>(string)$likeCount
		);
		echo json_encode($successmessage);
	}

	// public function userInfo(){
	// 	$countNotification = $this->db->get_where('userNotification',array('userId' => $this->input->post('userId'),'status' => 0))->num_rows();
	// 	if(!empty($countNotification)){
	// 		$message['notificationCount'] = (string)$countNotification;
	// 	}
	// 	else{
	// 		$message['notificationCount'] = '0';
	// 	}
	// 	$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
  //   	$checkMuteNotification =  $this->db->get_where('muteUserNotification',array('userId' => $this->input->post('loginId'),'muteId' =>  $this->input->post('userId'),'status' => '1'))->row_array();
	// 	if(!empty($checkMuteNotification)){
  //       	$finalData['muteStatus'] = true;
  //       }
  //   	else{
  //       	$finalData['muteStatus'] = false;
  //       }
	// 	$finalData['username'] = $getUserDetails['username'];
	// 	$finalData['name'] = $getUserDetails['name'];
	// 	$finalData['phone'] = $getUserDetails['phone'];
	// 	$finalData['bio'] = $getUserDetails['bio'];
	// 	if($getUserDetails['followingUser'] == 1){
	// 		$finalData['followingViewStatus'] = false;
	// 	}
	// 	else{
	// 		$finalData['followingViewStatus'] = true;
	// 	}
	// 	if($getUserDetails['profilePhotoStatus'] == 1){
	// 		$finalData['profilePhotoStatus'] = false;
	// 	}
	// 	else{
	// 		$finalData['profilePhotoStatus'] = true;
	// 	}
	// 	if(empty($getUserDetails['image'])){
	// 		$finalData['image'] = base_url().'uploads/no_image_available.png';
	// 	}
  //   	else{
  //   		$finalData['image'] = $getUserDetails['image'];
  //       }
  //   	if(!empty($getUserDetails['video'])){
	// 		$finalData['video'] = $getUserDetails['video'];
	// 	}
  //   	else{
  //   		$finalData['video'] = '';
  //       }
	// 	if($getUserDetails['privateAccount'] == 0){
	// 		$finalData['privateAccount'] = false;
	// 	}
	// 	else{
	// 		$finalData['privateAccount'] = true;
	// 	}
	// 	if($getUserDetails['likeVideo'] == 0){
	// 		$finalData['likeVideo'] = false;
	// 	}
	// 	else{
	// 		$finalData['likeVideo'] = true;
	// 	}
	// 	$list =  $this->db->get_where('userProfileInformation',array('userId' => $this->input->post('userId')))->row_array();
	// 	if(!empty($list)){
	// 		$finalData['followers'] = $list['followers'];
	// 		$finalData['likes'] = $list['likes'];
	// 		$finalData['videoCount'] = $list['videoCount'];
	// 	}
	// 	else{
	// 		$finalData['followers'] = "0";
	// 		$finalData['likes'] = "0";
	// 		$finalData['videoCount'] = "0";
	// 	}
	//
	// 	$countFollwers = $this->db->get_where('userFollow',array('userId' => $this->input->post('userId'),'status' => '1'))->num_rows();
	// 	if(!empty($countFollwers)){
	// 			$finalData['following'] = (string)$countFollwers;
	// 	}
	// 	else{
	// 			$finalData['following'] = "0";
	// 	}
	//
	// 	$videoLikeCount  = $this->db->get_where('videoLikeOrUnlike',array('userId' => $this->input->post('userId'),'status' => '1'))->num_rows();
	// 	if(!empty($videoLikeCount)){
	// 		$finalData['likedVideoCount'] = (string)$videoLikeCount;
	// 	}
	// 	else{
	// 		$finalData['likedVideoCount'] = "0";
	// 	}
	//
	// 	$checkFollow = $this->db->get_where('userFollow', array('userId' => $this->input->post('loginId'),'followingUserId' => $this->input->post('userId')))->row_array();
	// 	if(!empty($checkFollow)){
	// 		$finalData['followStatus'] = $checkFollow['status'];
	// 	}
	// 	else{
	// 		$finalData['followStatus'] = '0';
	// 	}
	// 	$message['success'] = '1';
	// 	$message['message'] = 'Details found successfully';
	// 	$message['details'] = $finalData;
	// 	echo json_encode($message);
	// }

	public function userInfo(){
		$countNotification = $this->db->get_where('userNotification',array('userId' => $this->input->post('userId'),'status' => 0))->num_rows();
		if(!empty($countNotification)){
			$message['notificationCount'] = (string)$countNotification;
		}
		else{
			$message['notificationCount'] = '0';
		}
		$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
    	$checkMuteNotification =  $this->db->get_where('muteUserNotification',array('userId' => $this->input->post('loginId'),'muteId' =>  $this->input->post('userId'),'status' => '1'))->row_array();
		if(!empty($checkMuteNotification)){
        	$finalData['muteStatus'] = true;
        }
    	else{
        	$finalData['muteStatus'] = false;
        }
		$finalData['username'] = $getUserDetails['username'];
		$finalData['name'] = $getUserDetails['name'];
		$finalData['phone'] = $getUserDetails['phone'];
		$finalData['bio'] = $getUserDetails['bio'];
		if($getUserDetails['followingUser'] == 1){
			$finalData['followingViewStatus'] = false;
		}
		else{
			$finalData['followingViewStatus'] = true;
		}
		if($getUserDetails['profilePhotoStatus'] == 1){
			$finalData['profilePhotoStatus'] = false;
		}
		else{
			$finalData['profilePhotoStatus'] = true;
		}
		if(empty($getUserDetails['image'])){
			$finalData['image'] = base_url().'uploads/no_image_available.png';
		}
    	else{
    		$finalData['image'] = $getUserDetails['image'];
        }
    	if(!empty($getUserDetails['video'])){
			$finalData['video'] = $getUserDetails['video'];
		}
    	else{
    		$finalData['video'] = '';
        }
		if($getUserDetails['privateAccount'] == 0){
			$finalData['privateAccount'] = false;
		}
		else{
			$finalData['privateAccount'] = true;
		}
		if($getUserDetails['likeVideo'] == 0){
			$finalData['likeVideo'] = false;
		}
		else{
			$finalData['likeVideo'] = true;
		}
			$mmUserId = $this->input->post('userId');
		$list =  $this->db->get_where('userProfileInformation',array('userId' => $this->input->post('userId')))->row_array();
		$countLikeString =  $this->db->query("SELECT sum(likeCount) as likeCountAdd from userVideos where userId = $mmUserId")->row_array();
		$countvideoString =  $this->db->query("SELECT count(id) as totalVid from userVideos where userId = $mmUserId")->row_array();
		if(!empty($list)){
			$finalData['followers'] = $list['followers'];
			if(!empty($countLikeString)){
				$finalData['likes'] = $countLikeString['likeCountAdd'];
			}
			else{
				$finalData['likes'] = '0';
			}
			$finalData['videoCount'] = $countvideoString['totalVid'];
		}
		else{
			$finalData['followers'] = "0";
			$finalData['likes'] = "0";
			$finalData['videoCount'] = "0";
		}
		$checkFollow = $this->db->get_where('userFollow', array('userId' => $this->input->post('loginId'),'followingUserId' => $this->input->post('userId')))->row_array();
		if(!empty($checkFollow)){
			$finalData['followStatus'] = $checkFollow['status'];
		}
		else{
			$finalData['followStatus'] = '0';
		}

		$countFollwers = $this->db->get_where('userFollow',array('userId' => $this->input->post('userId'),'status' => '1'))->num_rows();
		if(!empty($countFollwers)){
				$finalData['following'] = (string)$countFollwers;
		}
		else{
				$finalData['following'] = "0";
		}

		$message['success'] = '1';
		$message['message'] = 'Details found successfully';
		$message['details'] = $finalData;
		echo json_encode($message);
	}

	public function accountType(){
		$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		if($getUserDetails['privateAccount'] == 0){
			$data['privateAccount'] = 1;
			$status = true;
		}
		else{
			$data['privateAccount'] = 0;
			$status = false;
		}
		$update = $this->Common_Model->update('users',$data,'id',$this->input->post('userId'));
		$message['status'] = $status;
		echo json_encode($message);
	}

	public function likeVideoShow(){
		$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		if($getUserDetails['likeVideo'] == 0){
			$data['likeVideo'] = 1;
			$status = true;
		}
		else{
			$data['likeVideo'] = 0;
			$status = false;
		}
		$update = $this->Common_Model->update('users',$data,'id',$this->input->post('userId'));
		$message['status'] = $status;
		echo json_encode($message);
	}

	public function userCommentVideo(){
		if($this->input->post()){
			$data['userId'] = $this->input->post('userId');
			$data['videoId'] = $this->input->post('videoId');
			$data['comment'] = $this->input->post('comment');
			$data['created'] = date('Y-m-d H:i:s');
			$update = $this->db->insert('videoComments',$data);
			if($update){
				$id =$this->db->insert_id();


				$loginUserDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
				$UserDetails = $this->db->get_where('users',array('id' => $this->input->post('ownerId')))->row_array();
				$mess = $loginUserDetails['username']." commented on your video";
				$regId = $UserDetails['reg_id'];
           		if($this->input->post('userId') != $this->input->post('ownerId')){
					if($loginUserDetails['commentNotification'] == '1'){
						$this->notification($regId,$mess,'comment',$this->input->post('userId'),$this->input->post('ownerId'));
					}
                }
				$notiMess['loginId'] = $this->input->post('userId');
				$notiMess['userId'] = $this->input->post('ownerId');
				$notiMess['videoId'] = $this->input->post('videoId');
				$notiMess['commentId'] = $id;
				$notiMess['message'] = $mess;
				$notiMess['type'] = 'comment';
				$notiMess['notiDate'] = date('Y-m-d');
				$notiMess['created'] = date('Y-m-d H:i:s');
				$this->db->insert('userNotification',$notiMess);


				$checkCommentCount = $this->db->get_where('userVideos',array('id' =>$this->input->post('videoId')))->row_array();

				$upComment['commentCount'] = $checkCommentCount['commentCount'] + 1;
				$this->Common_Model->update('userVideos',$upComment,'id', $this->input->post('videoId'));
				$details = $this->db->query("select videoComments.*,users.username,users.image as userImage from videoComments left join users on users.id = videoComments.userId where videoComments.id=$id")->result_array();
				foreach($details as $detail){
					if(empty($detail['userImage'])){
						$detail['userImage'] =  base_url().'uploads/no_image_available.png';
					}
					$detail['likeStatus'] = false;
					$detail['likeCount'] = "0";
					$detail['subComment'] = [];
					$detail['created'] = 'just now';
					$dd[] = $detail;
				}

				$commentCountget = $upComment['commentCount'];

				$message['success'] = '1';
				$message['message'] = 'Comment added successfully';
				$message['commentCount'] = (string)$commentCountget;
				$message['details'] = $dd;
			}else{
				$message['success'] = '0';
	        	$message['message'] = 'Please try after sometime';
			}
		}else{
			$message['message'] = 'Please enter parameters';
		}
		echo json_encode($message);
	}

	public function likeAndDislikeComments(){
		$check_like =  $this->db->get_where('videoCommentsLikeOrUnlike', array('commentId' => $this->input->post('commentId'),'userId' => $this->input->post('userId')))->row_array();
		if(!empty($check_like)){
			if($check_like['status'] == '0'){
				$status = '1';
			}else{
				$status = '0';
			}
			$data = array(
				'userId' => $this->input->post('userId'),
				'commentId' => $this->input->post('commentId'),
				'status' => $status,
				'updated' => date('Y-m-d H:i:s')
			);
			$update = $this->Common_Model->update('videoCommentsLikeOrUnlike',$data,'id',$check_like['id']);
		}else{
			$status = '1';
			$data = array(
				'userId' => $this->input->post('userId'),
				'commentId' => $this->input->post('commentId'),
				'status' => $status,
				'created' => date('Y-m-d H:i:s')
			);
			$insert = $this->db->insert('videoCommentsLikeOrUnlike', $data);
		}
		$likeCount = $this->db->get_where('videoCommentsLikeOrUnlike', array('commentId' => $this->input->post('commentId'),'status'=> '1'))->num_rows();
		$successmessage = array(
			'success'=>'1',
			'likeStatus'=>$status,
			'likeCount'=>(string)$likeCount
		);
		echo json_encode($successmessage);
	}

	public function getVideoComments(){
		$getVideoIds = $this->Common_Model->getCommentsVideos($this->input->post('userId'),$this->input->post('videoId'));
		if(!empty($getVideoIds)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			foreach($getVideoIds as $lists){
				if(empty($lists['userImage'])){
					$lists['userImage'] = base_url().'uploads/no_image_available.png';
				}
				$lists['created'] = $this->getTime($lists['created']);
				$likeCount = $this->db->get_where('videoCommentsLikeOrUnlike', array('commentId' => $lists['id'],'status'=> '1'))->num_rows();
				$likeStatus = $this->db->get_where('videoCommentsLikeOrUnlike', array('commentId' => $lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
				if(!empty($likeCount)){
					$lists['likeCount'] = (string)$likeCount;
				}
				else{
					$lists['likeCount'] = '0';
				}
				if(!empty($likeStatus)){
					$lists['likeStatus'] = true;
				}
				else{
					$lists['likeStatus'] = false;
				}
				$getSubComment =  $this->Common_Model->getSubComment($lists['id']);
				if(!empty($getSubComment)){
					foreach($getSubComment as $getSubComments ){
						if(empty($getSubComments['userImage'])){
							$getSubComments['userImage'] = base_url().'uploads/no_image_available.png';
						}
						$getSubComments['created'] = $this->getTime($getSubComments['created']);
						$lists['subComment'][] = $getSubComments;
					}
				}
				else{
					$lists['subComment'] = [];
				}

				$message['details'][] = $lists;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'no details found';
		}
		echo json_encode($message);
	}

	public function getLikeVideo(){
		$getVideoIds = $this->Common_Model->getLikeVideoIds($this->input->post('userId'));
		if(!empty($getVideoIds)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			foreach($getVideoIds as $lists){
				if(!empty($lists['hashTag'])){
					$lists['hashtagTitle'] = $this->hashTagName($lists['hashTag']);
				}
				else{
					$lists['hashtagTitle'] = '';
				}
				$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' => $lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
				if(!empty($likeStatus)){
					$lists['likeStatus'] = true;
				}
				else{
					$lists['likeStatus'] = false;
				}
				$likeCount = $this->db->get_where('videoLikeOrUnlike', array('videoId' => $lists['id'],'status'=> '1'))->num_rows();
				$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' => $lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
				if(!empty($likeCount)){
					$lists['likeCount'] = (string)$likeCount;
				}
				else{
					$lists['likeCount'] = '0';
				}
				if(!empty($likeStatus)){
					$lists['likeStatus'] = true;
				}
				else{
					$lists['likeStatus'] = false;
				}

				$commentCoutList = $this->db->get_where('videoComments',array('videoId' => $lists['id']))->num_rows();
				if(!empty($commentCoutList)){
					$lists['commentCount'] = (string)$commentCoutList;
				}
				else{
					$lists['commentCount'] = '';
				}


				$lists['shareCount'] = '';
				$message['details'][] = $lists;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'no details found';
		}
		echo json_encode($message);
	}

	public function myVideoList(){
		if($this->input->post('loginId') == $this->input->post('userId')){
			$videoList =  $this->Common_Model->myVideoList($this->input->post('userId'));
		}
		else{
			$videoList =  $this->Common_Model->myVideoList1($this->input->post('userId'));
		}
		if(!empty($videoList)){
			foreach($videoList as $lists){
				$message['success'] = '1';
				$message['message'] = 'List found successfully';
				if(empty($lists['image'])){
					$lists['image'] = base_url().'uploads/no_image_available.png';
				}
				$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' => $lists['id'],'userId'=> $this->input->post('loginId'),'status'=> '1'))->row_array();
				if(!empty($likeStatus)){
					$lists['likeStatus'] = true;
				}
				else{
					$lists['likeStatus'] = false;
				}

				if(!empty($lists['hashtag'])){
					$lists['hashtagTitle'] = $this->hashTagName($lists['hashtag']);
				}
				else{
					$lists['hashtagTitle'] = '';
				}
				$message['details'][] = $lists;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'no details found';
		}
		echo json_encode($message);
	}

	public function likeAndDislikeVideo(){
		$check_like =  $this->db->get_where('videoLikeOrUnlike', array('videoId' => $this->input->post('videoId'),'userId' => $this->input->post('userId')))->row_array();
		if(!empty($check_like)){
			if($check_like['status'] == '0'){
				$status = '1';
			}
			else{
				$status = '0';
			}
			$data = array(
				'userId' => $this->input->post('userId'),
				'videoId' => $this->input->post('videoId'),
				'ownerId' => $this->input->post('ownerId'),
				'status' => $status,
				'updated' => date('y-m-d h:i:s')
			);
			$update = $this->Common_Model->update('videoLikeOrUnlike',$data,'id',$check_like['id']);
		}
		else{
			$status = '1';
			$data = array(
				'userId' => $this->input->post('userId'),
				'ownerId' => $this->input->post('ownerId'),
				'videoId' => $this->input->post('videoId'),
				'status' => $status,
				'created' => date('y-m-d h:i:s')
			);
			$insert = $this->db->insert('videoLikeOrUnlike', $data);
			$insert_id = $this->db->insert_id();
		}
		$likeInformation = $this->db->get_where('userProfileInformation',array('userId' => $this->input->post('ownerId')))->row_array();

		if(empty($check_like)){
			$userProfile['likes'] = 1 + $likeInformation['likes'];
			$message123 = 'Video like successfully';
		}
		else{
			if($status == '0'){
				$userProfile['likes'] = $likeInformation['likes'] - 1;
				$message123 = 'Video unlike successfully';
			}
			else{
				$userProfile['likes'] = 1 + $likeInformation['likes'];
				$message123 = 'Video like successfully';
			}
		}
		$videoDetails =$this->db->get_where('userVideos',array('id' => $this->input->post('videoId')))->row_array();
		if($status == '1'){
			$loginUserDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			$UserDetails = $this->db->get_where('users',array('id' => $this->input->post('ownerId')))->row_array();
			$mess = $loginUserDetails['username']." liked your video";
			$regId = $UserDetails['reg_id'];
        	if($this->input->post('userId') != $this->input->post('ownerId')){
				if($loginUserDetails['likeNotifaction'] == '1'){
					$this->notification($regId,$mess,'like',$this->input->post('userId'),$this->input->post('ownerId'));
				}

				$notiMess['loginId'] = $this->input->post('userId');
				$notiMess['userId'] = $this->input->post('ownerId');
				$notiMess['videoId'] = $this->input->post('videoId');
				$notiMess['message'] = $mess;
				$notiMess['type'] = 'like';
				$notiMess['notiDate'] = date('Y-m-d');
				$notiMess['created'] = date('Y-m-d H:i:s');
				$this->db->insert('userNotification',$notiMess);

            }


			$upLikeCount['likeCount'] = $videoDetails['likeCount'] + 1;
		}
		else{
			$upLikeCount['likeCount'] = $videoDetails['likeCount'] - 1;
		}
		$this->Common_Model->update('userVideos',$upLikeCount,'id',$this->input->post('videoId'));
		$update = $this->Common_Model->update('userProfileInformation',$userProfile,'id',$likeInformation['id']);
		$likeCount = $this->db->get_where('videoLikeOrUnlike', array('videoId' => $this->input->post('videoId'),'status'=> '1'))->num_rows();
		$successmessage = array(
				'success'=>'1',
				'message' => $message123,
				'like_status'=>$status,
				'like_count'=>(string)$upLikeCount['likeCount']
		);
		echo json_encode($successmessage);
	}

	public function hastTagIds($hastage,$userId){
		$hasTag = $hastage;
		$exp = explode(',',$hasTag);
		foreach($exp as $exps){
			$checkHashTag = $this->db->get_where('hashtag',array('hashtag' => $exps))->row_array();
			if(!empty($checkHashTag)){
				$updateCount['videoCount'] = $checkHashTag['videoCount'] + 1;
				$this->Common_Model->update('hashtag',$updateCount,'id',$checkHashTag['id']);
				$hastIds[] = $checkHashTag['id'];
			}
			else{
				$addHash['hashtag'] = $exps;
				$addHash['userId'] = $userId;
				$addHash['created'] = date('Y-m-d H:i:s');
				$addHash['videoCount'] = 1;
				$insertHash = $this->db->insert('hashtag',$addHash);
				$hastIds[] = $this->db->insert_id();
			}
		}
		$finalHashTag = implode(',',$hastIds);
		return $finalHashTag;
	}
	
	public function uploadVideos(){
		require APPPATH.'/libraries/vendor/autoload.php';
	
		
		$getUserDetails =  $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		$usernameWater = $getUserDetails['username'];
		$userPic = $getUserDetails['image'];
		$defaultUserPic = "uploads/defaultpic.png";
		if($userPic == null || $userPic == ""){
			$userPic = $defaultUserPic;
		}
		$regId = $getUserDetails['reg_id'];
		// $this->notification($regId,"Uploading started",'video',$this->input->post('userId'),$this->input->post('userId'));
		
		$isDuet= $this->input->post('isDuet');
		
		if(!empty($this->input->post('soundId'))){
			$sound =  $this->input->post('soundId');
		}
		else{
			$addSound['title'] = $this->input->post('soundTitle');
			$addSound['userId'] = $this->input->post('userId');
			$addSound['type'] = 'Original Sound';
			$addSound['created'] = date('Y-m-d H:i:s');
			if(!empty($_FILES['soundFile']['name'])){
				$name1= time().'_'.$_FILES["soundFile"]["name"];
				$name= str_replace(' ', '_', $name1);
				$tmp_name = $_FILES['soundFile']['tmp_name'];
				$path = 'uploads/sounds/'.$name;
				move_uploaded_file($tmp_name,$path);
				$addSound['sound'] = $path;
			}
			$this->db->insert('sounds',$addSound) ;
			$sound = $this->db->insert_id();
		}
		$data['userId'] = $this->input->post('userId');
		if(!empty($this->input->post('hashTag'))){
			$data['hashTag'] = $this->hastTagIds($this->input->post('hashTag'),$this->input->post('userId'));
		}
		else{
			$data['hashTag'] = '';

		}
		$dir_name = "/var/www/html/app/application/controllers/api/";
		$uploadPath = "uploads/";
		$duetUrl = "";
		
		
		$videoName =$_FILES['videoPath']['name'];
		$basename = basename($videoName,".mp4");
		$basenamepath = $uploadPath . $basename;
		$basenamepath = str_replace(" ", "",$basenamepath);

		$tmp_name_video = $_FILES['videoPath']['tmp_name'];
		$tmpName = $basenamepath . "_tmp.mp4"; 
		move_uploaded_file($_FILES['videoPath']['tmp_name'],$tmpName);


		$height_video= 720;
		$width_video= 480;
		if($isDuet == "true"){
			$duetUrl = $this->input->post('duetUrl');
			$height_video = $this->input->post('heightVideo');
			$width_video = $this->input->post('widthVideo');
		}
		$compressVideo = $basenamepath . "_tmp.mp4";
		$videoMainPath = $basenamepath . "_main.mp4";
		$command_compress  =  "ffmpeg -i " . $tmpName . " -vcodec libx264 -crf 28 ".$compressVideo;
	
		
		shell_exec($command_compress); 


		$downloadPath ="";
		$change_size =  "ffmpeg -i ".$compressVideo." -vf scale=".$width_video.":".$height_video. " ".$videoMainPath;
		shell_exec($change_size);
		if($isDuet == "true"){
			$dueticonpath = "uploads/duet_icon.png";
			$dueturlvideo = $basenamepath ."_main_merged.mp4";
			$dueturlwithicon = $basenamepath ."_main_duet.mp4";
			$merge_video_duet = "ffmpeg -i ".$duetUrl." -i ".$videoMainPath." -filter_complex hstack ".$dueturlvideo;
			$command_add_duet_icon = "ffmpeg -i " .$dueturlvideo ." -i ".$dueticonpath." -filter_complex ". "overlay=main_w-overlay_w-10:10 " . $dueturlwithicon;
			
			exec($merge_video_duet); 
			exec($command_add_duet_icon); 
			@unlink($videoMainPath);
			@unlink($dueturlvideo);

			$videoMainPath = $dueturlwithicon;
		$downloadPath = $this->addWaterMarkPrivate($videoMainPath,$watermarkPath,$usernameWater,$basenamepath,$userPic,$getUserDetails['bio']);

		}else{
			$downloadPath = $this->addWaterMarkPrivate($videoMainPath,$watermarkPath,$usernameWater,$basenamepath,$userPic,$getUserDetails['bio']);

		}

		$data['allowDownloads']  = $this->input->post('allowDownloads');
		$data['description'] = $this->input->post('description');
		$data['allowComment'] = $this->input->post('allowComment');
		$data['allowDuetReact'] = $this->input->post('allowDuetReact');
		$data['soundId']  = $sound;
		$data['viewVideo']  = $this->input->post('viewVideo');
		$data['created'] = date('Y-m-d H:i:s');

				//*******************//
		$s3 = new Aws\S3\S3Client([
				'version' => 'latest',
				'region'  => 'us-east-2',
				'credentials' => [
						'key'    => 'AKIASKU6EJBLLBL5FSOL',
						'secret' => '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'
				]
		]);
		$bucket = 'cancremedia';

        $upload = $s3->upload($bucket, $_FILES['videoPath']['name'], fopen($videoMainPath, 'rb'), 'public-read');
		$url = $upload->get('ObjectURL');
		if(!empty($url)){
			$data['videoPath'] = 'http://d2ufnc3urw5h1h.cloudfront.net/'.$_FILES['videoPath']['name'];
		}
		else{
			$data['videoPath'] = '';
		}

		$upload = $s3->upload($bucket, basename($downloadPath), fopen($downloadPath, 'rb'), 'public-read');
		$url = $upload->get('ObjectURL');
		if(!empty($url)){
			$data['downloadPath'] = 'http://d2ufnc3urw5h1h.cloudfront.net/'.basename($downloadPath);
		}
		else{
			$data['downloadPath'] = '';
		}
		
		//**********************//



		
		$insert = $this->db->insert('userVideos',$data);
		if(!empty($insert)){
			$vIDs = $this->db->insert_id();
			$checkData = $this->db->get_where('userProfileInformation',array('userId' => $this->input->post('userId')))->row_array();
			if(empty($checkData)){
				$userProfile['userId'] = $this->input->post('userId');
				$userProfile['videoCount'] = 1;
				$this->db->insert('userProfileInformation',$userProfile);
			}
			else{
				$userProfile['videoCount'] = 1 + $checkData['videoCount'];
				$update = $this->Common_Model->update('userProfileInformation',$userProfile,'id',$checkData['id']);
			}




		$videoId = $vIDs;
		$userId = $this->input->post('userId');
		$lists = $this->db->get_where('userFollow',array('followingUserId' => $userId))->result_array();
		if(!empty($lists)){
			foreach($lists as $list){
				$loginUserDetails = $this->db->get_where('users',array('id' => $userId))->row_array();
				$getUserId = $this->db->get_where('users',array('id' => $list['userId']))->row_array();
				$regId = $getUserId['reg_id'];
				$mess = $loginUserDetails['username'].' uploaded new video';
				if($loginUserDetails['videoNotification'] == '1'){
					$this->notification($regId,$mess,'video',$list['userId'],$userId);
				}
				$notiMess['loginId'] = $userId;
				$notiMess['userId'] = $list['userId'];
				$notiMess['videoId'] = $videoId;
				$notiMess['message'] = $mess;
				$notiMess['type'] = 'video';
				$notiMess['notiDate'] = date('Y-m-d');
				$notiMess['created'] = date('Y-m-d H:i:s');
				$this->db->insert('userNotification',$notiMess);
			}
		}




			$checkSoundData = $this->db->get_where('sounds',array('id' => $sound))->row_array();
			$updateSound['soundCount'] = 1 + $checkSoundData['soundCount'];
			$update = $this->Common_Model->update('sounds',$updateSound,'id',$sound);




			$message['success'] = '1';
			$message['message'] = 'Video Upload Successfully';
			// $this->notification($regId,$message['message'],'video',$this->input->post('userId'),$this->input->post('userId'));

			// if($isDuet == "true"){
			// $message['message'] = 'h = '.$height_video . "w = ".$width_video;
				

			// }
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
			$this->notification($regId,$message['message'],'video',$this->input->post('userId'),$this->input->post('userId'));

		}
		@unlink($tmpName);
		@unlink($compressVideo);
		@unlink($videoMainPath);
		@unlink($downloadPath);

		echo json_encode($message);
	}
	function createCircleImage($image,$mask,$usermainpic){
		$gmagick1 = new Gmagick($mask); 
    $gmagick2 = new Gmagick($image); 
    $gmagick2->resizeimage(150, 150, 0, 1);
    // Composite the images with no offset 
    $gmagick1->compositeimage($gmagick2, 
              Gmagick::COMPOSITE_ATOP, 0, 0); 
              

	$gmagick1->writeImage($usermainpic);
	return $usermainpic;
	}

	function addLastWatermark($main,$video_file,$username,$description,$userpic,$output1,$output2,$output3,$maindownloadfile,$rawfile1,$rawfile2,$circleimage){
		
		$circlepath = "";
		if($userpic != null || $userpic != "" ){
			if(strpos($userpic,"defaultpic")){
				$circlepath = $userpic;
			}else{
				$circlepath = $this->createCircleImage($userpic,"uploads/mask.png",$circleimage);

			}

		}
		$command_add_image = "ffmpeg -i ".$main." -i ".$circlepath." -filter_complex ".'"'."[1:v]scale=150:125 [ovrl], [0:v][ovrl]overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2:enable='between(t,1,3)'".'"'." ".$output1;
	
		exec($command_add_image);
		$command_add_text = "ffmpeg -i ".$output1." -vf drawtext=".'"'."text="."'".$username."'".": fontcolor=black: fontsize=28: x=(w-text_w)/2: y=(h-text_h)/2+95:enable='between(t,1,3)'".'"'." -codec:a copy ".$output2;
	
		exec($command_add_text);
		$command_add_text1 = "ffmpeg -i ".$output2." -vf drawtext=".'"'."text="."'".$description."'".": fontcolor=black: fontsize=18: x=(w-text_w)/2: y=(h-text_h)/2+125:enable='between(t,1,3)'".'"'." -codec:a copy ".$output3;
		exec($command_add_text1);

		$createraw1 = "ffmpeg -i ".$video_file." -c copy -bsf:v h264_mp4toannexb -f mpegts ".$rawfile1;
		$createraw2 = "ffmpeg -i ".$output3." -c copy -bsf:v h264_mp4toannexb -f mpegts ".$rawfile2;
		exec($createraw1);
		exec($createraw2);
	
		$combine_command = "ffmpeg -i ".'"'."concat:".$rawfile1."|".$rawfile2.'"'." -c copy -bsf:a aac_adtstoasc ".$maindownloadfile;
	
		exec($combine_command);
		@unlink($output1);
		@unlink($rawfile2);
		@unlink($rawfile1);
		@unlink($output2);
		@unlink($output3);
		@unlink($circlepath);
		@unlink($video_file);
		return $maindownloadfile;
	}
	function addWaterMarkPrivate($video_file,$watermark,$username,$basename_file,$userpic,$description){


		$end_watermark= "uploads/front.mp4";
		
		$watermark = "uploads/watermark.gif";
		$new_watermark = $basename_file . "2.gif";
		$text = $username;
		$first_sec = "9";
		$outputlast1 = $basename_file ."outputimage.mp4";
		$outputlast3 = $basename_file ."outputtext2.mp4";
		$outputlastts1 = $basename_file ."mainvideoraw.ts";
		$circle_image = $basename_file ."circleimage.png";
		$outputlast2 = $basename_file ."outputtext.mp4";
		$outputlastts2 = $basename_file ."outputtext.ts";
		$outputmainlast = $basename_file ."_main_downloadvideo.mp4";

		$video_split_1 = $basename_file ."split_1.mp4";
		$video_split_1_watermark =  $basename_file ."split_1_watermark.mp4";
		$video_split_2 =  $basename_file ."split_2.mp4";
		$video_split_2_watermark = $basename_file . "split_2_watermark.mp4";
		$video_merged = $basename_file . "_download.mp4";

		$frames_split_1 = "200";
		$frames_split_2 = "200";
		
		$commad_get_seconds = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ".$video_file;
		$text = $username;
		

		exec($commad_get_seconds,$seconds);
		$first_sec = $seconds[0]/2;

		$command_split_1 = "ffmpeg -i ".$video_file." -ss 0 -t ". $first_sec. " ".$video_split_1;
		$command_split_2 = "ffmpeg -i ".$video_file." -ss ". $first_sec." -t ". $first_sec ." ".$video_split_2;
		

		exec($command_split_1);
		exec($command_split_2);
		$get_frame_split1 = "ffprobe -v error -select_streams v:0 -show_entries stream=nb_frames -of default=nokey=1:noprint_wrappers=1 ".$video_split_1;
		$get_frame_split2 = "ffprobe -v error -select_streams v:0 -show_entries stream=nb_frames -of default=nokey=1:noprint_wrappers=1 ".$video_split_2;


		exec($get_frame_split1,$frames_1);
		exec($get_frame_split2,$frames_2);
		$frames_split_1 = $frames_1[0];
		$frames_split_2 = $frames_2[0];
		$new_watermark = $this->addtextImage($new_watermark,$watermark,$text);
		$watermark_to_split_1 = "ffmpeg -i ".$video_split_1." -ignore_loop 0 -i ".$new_watermark." -filter_complex overlay=0:0 -frames:v ". $frames_split_1 . " ".$video_split_1_watermark ;
		$watermark_to_split_2 = "ffmpeg -i ".$video_split_2." -ignore_loop 0 -i ".$new_watermark." -filter_complex overlay=W-w-5:H-h-5 -frames:v ". $frames_split_2 . " ".$video_split_2_watermark ;
		exec($watermark_to_split_1);
		exec($watermark_to_split_2);
		$combine_command = "MP4Box -add ".$video_split_1_watermark." -cat ".$video_split_2_watermark." ".$video_merged;
		exec($combine_command);
		$video_merged = $this->addLastWatermark($end_watermark,$video_merged,$username,$description,$userpic,$outputlast1,$outputlast2,$outputlast3,$outputmainlast,$outputlastts1,$outputlastts2,$circle_image);
		
		
		@unlink($video_split_1);
		@unlink($video_split_1_watermark);
		@unlink($video_split_2);
		@unlink($video_split_2_watermark);
		@unlink($new_watermark);
		
		return $video_merged;
	}
	// function addWaterMarkPrivate($video_file,$watermark,$username,$basename_file,$userpic,$description){


	// 	$end_watermark= "uploads/front.mp4";
		
	// 	$watermark = "uploads/watermark.gif";
	// 	$new_watermark = $basename_file . "2.gif";
	// 	$text = $username;
	// 	$first_sec = "9";
	// 	$outputlast1 = $basename_file ."outputimage.mp4";
	// 	$outputlast3 = $basename_file ."outputtext2.mp4";
	// 	$outputlastts1 = $basename_file ."mainvideoraw.ts";
	// 	$circle_image = $basename_file ."circleimage.png";
	// 	$outputlast2 = $basename_file ."outputtext.mp4";
	// 	$outputlastts2 = $basename_file ."outputtext.ts";
	// 	$outputmainlast = $basename_file ."_main_downloadvideo.mp4";

	// 	$video_split_1 = $basename_file ."split_1.mp4";
	// 	$video_split_1_watermark =  $basename_file ."split_1_watermark.mp4";
	// 	$video_split_2 =  $basename_file ."split_2.mp4";
	// 	$video_split_2_watermark = $basename_file . "split_2_watermark.mp4";
	// 	$video_split_2_watermark12 = $basename_file . "split_2_watermark1.mp4";
	// 	$video_split_1_watermark12 = $basename_file . "split_2_watermark2.mp4";
	// 	$video_merged = $basename_file . "_download.mp4";

	// 	$frames_split_1 = "200";
	// 	$frames_split_2 = "200";
		
	// 	$commad_get_seconds = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ".$video_file;
	// 	$text = $username;
		

	// 	exec($commad_get_seconds,$seconds);
	// 	$first_sec = $seconds[0]/2;

	// 	$command_split_1 = "ffmpeg -i ".$video_file." -ss 0 -t ". $first_sec. " ".$video_split_1;
	// 	$command_split_2 = "ffmpeg -i ".$video_file." -ss ". $first_sec." -t ". $first_sec ." ".$video_split_2;
		

	// 	exec($command_split_1);
	// 	exec($command_split_2);
	// 	$get_frame_split1 = "ffprobe -v error -select_streams v:0 -show_entries stream=nb_frames -of default=nokey=1:noprint_wrappers=1 ".$video_split_1;
	// 	$get_frame_split2 = "ffprobe -v error -select_streams v:0 -show_entries stream=nb_frames -of default=nokey=1:noprint_wrappers=1 ".$video_split_2;


	// 	exec($get_frame_split1,$frames_1);
	// 	exec($get_frame_split2,$frames_2);
	// 	$frames_split_1 = $frames_1[0];
	// 	$frames_split_2 = $frames_2[0];
	// 	// $new_watermark = $this->addtextImage($new_watermark,$watermark,$text);
	// 	$watermark_to_split_1 = "ffmpeg -i ".$video_split_1." -ignore_loop 0 -i ".$watermark." -filter_complex overlay=0:0 -frames:v ". $frames_split_1 . " ".$video_split_1_watermark ;
	// 	$watermark_to_split_2 = "ffmpeg -i ".$video_split_2." -ignore_loop 0 -i ".$watermark." -filter_complex overlay=W-w-5:H-h-5 -frames:v ". $frames_split_2 . " ".$video_split_2_watermark ;
		
		
	// 	exec($watermark_to_split_1);
	// 	exec($watermark_to_split_2);
	// 	$command_add_text11 = "ffmpeg -i ".$video_split_1_watermark." -vf drawtext=".'"'."text="."'".$username."'".": fontcolor=black: fontsize=22: x=0: y=40'".'"'." -codec:a copy ".$video_split_1_watermark12;
	// 	$command_add_text12 = "ffmpeg -i ".$video_split_2_watermark." -vf drawtext=".'"'."text="."'".$username."'".": fontcolor=black: fontsize=22: x=W-w-5: y=:H-h-5'".'"'." -codec:a copy ".$video_split_2_watermark12;
	// 	exec($command_add_text11);
	// 	exec($command_add_text12);
		
	// 	$combine_command = "MP4Box -add ".$video_split_1_watermark12." -cat ".$video_split_2_watermark12." ".$video_merged;
	// 	exec($combine_command);
	// 	$video_merged = $this->addLastWatermark($end_watermark,$video_merged,$username,$description,$userpic,$outputlast1,$outputlast2,$outputlast3,$outputmainlast,$outputlastts1,$outputlastts2,$circle_image);
		
		
	// 	@unlink($video_split_1);
	// 	@unlink($video_split_1_watermark);
	// 	@unlink($video_split_2);
	// 	@unlink($video_split_2_watermark);
	// 	@unlink($new_watermark);
		
	// 	return $video_merged;
	// }
	function addtextImage($gifpath,$watermarkpath,$textwrite){
		$gmagick = new Gmagick($watermarkpath);
		// echo "23";
		$gmagick = $gmagick->coalesceImages();
		// Create a GmagickDraw object
		$draw = new GmagickDraw();

		// Set the fill color
		$draw->setFillColor('white');

		// Set the font size
		$draw->setfontsize(22);
		// $draw->setfont('uploads/poppins.ttf');
		// $draw->setfont('times.ttf');
		// $draw->setfontstyle(\Gmagick::STYLE_NORMAL);

		// Annotate a text
		$gmagick->annotateImage($draw,15,75 ,0, $textwrite);

		// Use of drawimage function
		// $gmagick->drawImage($draw);

		// Display the output image
		header('Content-Type: image/gif');
		// echo $gmagick->getImageBlob();

		file_put_contents($gifpath,$gmagick->getImageBlob());

		return $gifpath;
	}

	public function getVideo(){
    	$startLimit = $this->input->post('startLimit');
        $endLimit = 5;
		$userId = $this->input->post('userId');
		$countNotification = $this->db->get_where('userNotification',array('userId' => $this->input->post('userId'),'status' => 0))->num_rows();
		if(!empty($countNotification)){
			$message['notificationCount'] = (string)$countNotification;
		}
		else{
			$message['notificationCount'] = '0';
		}

		if($this->input->post('videoType') == 'following'){
			$list =  $this->db->query("SELECT sounds.title as soundTitle,sounds.id as soundId,users.username,users.followerCount as followers,users.image,userVideos.id, userVideos.userId,userVideos.hashtag, userVideos.description, userVideos.videoPath,userVideos.viewCount, userVideos.allowComment, userVideos.allowDownloads, userVideos.allowDuetReact,userVideos.viewVideo,userVideos.likeCount,userVideos.commentCount,userVideos.downloadPath FROM `userVideos` left join userFollow on userFollow.followingUserId = userVideos.userId left join users on users.id = userVideos.userId left join sounds on sounds.id = userVideos.soundId where userFollow.userId = $userId and userVideos.viewVideo = 0 and userFollow.status = '1' and userVideos.userId NOT IN (select blockUserId from blockUser where userId = $userId  ) order by RAND() LIMIT $startLimit , 5")->result_array();
		}
		else{
			// $list =  $this->db->query("SELECT users.username,users.followerCount as followers,users.image,sounds.title as soundTitle,sounds.id as soundId, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath,userVideos.viewCount, userVideos.allowComment, userVideos.allowDuetReact,userVideos.allowDownloads,userVideos.viewVideo,userVideos.likeCount,userVideos.commentCount,userVideos.downloadPath FROM `userVideos` left join sounds on sounds.id = userVideos.soundId left join users on users.id = userVideos.userId where userVideos.viewVideo = 0 and userVideos.userId NOT IN (select blockUserId from blockUser where userId = '$userId' ) and userVideos.id NOT IN (select videoId from  viewVideo where userId = '$userId' ) ORDER BY userVideos.viewCount desc,userVideos.likeCount desc,userVideos.commentCount LIMIT $startLimit , 5")->result_array();
			$list =  $this->db->query("SELECT users.username,users.followerCount as followers,users.image,sounds.title as soundTitle,sounds.id as soundId, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath,userVideos.viewCount, userVideos.allowComment, userVideos.allowDuetReact,userVideos.allowDownloads,userVideos.viewVideo,userVideos.likeCount,userVideos.commentCount,userVideos.downloadPath FROM `userVideos` left join sounds on sounds.id = userVideos.soundId left join users on users.id = userVideos.userId where userVideos.viewVideo = 0 and userVideos.userId NOT IN (select blockUserId from blockUser where userId = '$userId' ) and userVideos.id NOT IN (select videoId from  viewVideo where userId = '$userId' ) ORDER BY RAND() LIMIT $startLimit , 5")->result_array();
		}

		if(!empty($list)){
			$message['success'] = '1';
			$message['message'] = 'List Found Successfully';
			foreach($list as $lists){
				if(!empty($lists['downloadPath'])){
					$lists['downloadPath'] = $lists['downloadPath'];
				}
				else{
					$lists['downloadPath'] =  '';
				}

				if(empty($lists['image'])){
					$lists['image'] = base_url().'uploads/no_image_available.png';
				}
				if(!empty($lists['hashtag'])){
					$lists['hashtagTitle'] = $this->hashTagName($lists['hashtag']);
					$finalTagIds = explode(',',$lists['hashtag']);
					foreach($finalTagIds as $finalTagId){
						$hashArray = $this->db->get_where('hashtag',array('id' => $finalTagId))->row_array();
						if(!empty($hashArray)){
							$lists['hastagLists'][] = $hashArray;
						}
					}
				}
				else{
					$lists['hashtagTitle'] = '';
					$lists['hastagLists'] = [];
				}
				$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' => $lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
				if(!empty($likeStatus)){
					$lists['likeStatus'] = true;
				}
				else{
					$lists['likeStatus'] = false;
				}

				$message['details'][] = $lists;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'NO List Found';
		}
		echo json_encode($message);
	}


	public function checkPhoneAndEmail(){
		$type = $this->input->post('type');
		$emailPhone = $this->input->post('emailPhone');
		$checkData = $this->db->get_where('users',array($type => $this->input->post('emailPhone')))->row_array();
		if(empty($checkData)){
			$otp = rand(1000,9999);
			if($type == 'phone'){
				$mess = 'Your Verification Code Is: '.$otp;
				$mobile = $this->input->post('emailPhone');
				$created = date('Y-m-d+H:s:i');
				$url = "https://api.msg91.com/api/sendhttp.php?authkey=339739Aqv4rSuggd5f44f645P1&mobiles=$mobile&country=91&message=$mess&sender=CANCRE&route=4";
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				$output=curl_exec($ch);
				curl_close($ch);
			}
			else{
				$url = "http://cancre.in/SendEmail/sendEmail.php?otp=$otp&email=$emailPhone";
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL,$url);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					$output=curl_exec($ch);
					curl_close($ch);
			}
			$message['success'] = '1';
			$message['message'] = 'Otp Send Successfully';
			$message['otp'] = (string)$otp;
		}
		else{
			$message['success'] = '0';
			$message['message'] = $type.' is alerdy exist';
		}
		echo json_encode($message);
	}

	public function updadtePhoneAndEmail(){
    	$type = $this->input->post('type');
		$data[$type] = $this->input->post('emailPhone');
    	$update = $this->Common_Model->update('users',$data,'id',$this->input->post('userId'));
		if(!empty($update)){
            $userDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			if(empty($userDetails['image'])){
				$userDetails['image'] =  base_url().'uploads/no_image_available.png';
			}
			$message['success'] = '1';
			$message['message'] = $type.' Update successfully';
			$message['details'] = $userDetails;
        }
		else{
        	$message['success'] = '0';
        	$message['message'] = 'Please try after some time';
        }
    	echo json_encode($message);
    }

	public function updateUserInformation(){
		if(!empty($this->input->post('name'))){
			$data['name'] = $this->input->post('name');
			$error = '';
		}
		if(!empty($this->input->post('username'))){
			$usernameGet = $this->input->post('username');
			$checkU = substr($usernameGet,0,1);
			$p = ($checkU == '@')?array('username' => $this->input->post('username')):array('username' => '@'.$this->input->post('username'));
			$checkUserName = $this->db->get_where('users',$p)->row_array();
			if(empty($checkUserName)){
					$data['username'] = ($checkU == '@')?$this->input->post('username'):'@'.$this->input->post('username');
			}
			else{
				$error = 'error';
			}
		}
		if(!empty($this->input->post('bio'))){
			$data['bio'] = $this->input->post('bio');
			$error = '';
		}
		if(empty($error)){
			$update = $this->Common_Model->update('users',$data,'id',$this->input->post('userId'));
			if(!empty($update)){
				$userDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
				if(empty($userDetails['image'])){
					$userDetails['image'] = base_url().'uploads/no_image_available.png';
				}
				$message['success'] = '1';
				$message['message'] = 'Information update successfully';
				$message['details'] = $userDetails;
			}
			else{
				$message['success'] = '0';
				$message['message'] = 'please try after some time';
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Username is already exist';
		}
		echo json_encode($message);
	}

	public function imageVideo(){
   		 require APPPATH.'/libraries/vendor/autoload.php';
			 $s3 = new Aws\S3\S3Client([
 					'version' => 'latest',
 					'region'  => 'us-east-2',
 					'credentials' => [
 							'key'    => 'AKIASKU6EJBLLBL5FSOL',
 							'secret' => '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'
 					]
 			]);
 			$bucket = 'cancremedia';

		if(!empty($_FILES['image']['name'])){
       		$upload = $s3->upload($bucket, $_FILES['image']['name'], fopen($_FILES['image']['tmp_name'], 'rb'), 'public-read');
        	$url = $upload->get('ObjectURL');
        	if(!empty($url)){
            	$details['image'] = 'http://d2ufnc3urw5h1h.cloudfront.net/'.$_FILES['image']['name'];;
        	}
        	else{
            	$details['image'] = '';
        	}
		}
		/*if(!empty($_FILES['video']['name'])){
       		$upload = $s3->upload($bucket, $_FILES['video']['name'], fopen($_FILES['video']['tmp_name'], 'rb'), 'public-read');
        	$url = $upload->get('ObjectURL');
        	if(!empty($url)){
            	$details['video'] = $url;
        	}
        	else{
            	$details['video'] = '';
        	}
		}*/

    	if(!empty($_FILES['video']['name'])){
        	$uploadMain = $s3->upload($bucket, $_FILES['video']['name'], fopen($_FILES['video']['tmp_name'], 'rb'), 'public-read');
        	$url123 = $uploadMain->get('ObjectURL');
        	if(!empty($url123)){
            	$details['video'] = $url123;
        	}
        	else{
            	$details['video'] = '';
        	}

            // $name1= time().'_'.$_FILES["video"]["name"];
            // $name= str_replace(' ', '_', $name1);
            // $tmp_name = $_FILES['video']['tmp_name'];
            // $path = 'uploads/users/'.$name;
            // move_uploaded_file($tmp_name,$path);
            // $details['video'] = base_url().$path;
		}

		$update = $this->Common_Model->update('users',$details,'id',$this->input->post('userId'));
		if(!empty($update)){
			$userDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			if(empty($userDetails['image'])){
				$userDetails['image'] =  base_url().'uploads/no_image_available.png';
			}
			$message['success'] = '1';
			$message['message'] = 'Information update successfully';
			$message['details'] = $userDetails;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'please try after some time';
		}
		echo json_encode($message);
	}

	public function imageVideoDelete(){
		$type = $this->input->post('type');
		$data[$type] = '';
		$update = $this->Common_Model->update('users',$data,'id',$this->input->post('userId'));
		if(!empty($update)){
			$userDetails = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
			if(empty($userDetails['image'])){
				$userDetails['image'] =  base_url().'uploads/no_image_available.png';
			}
			$message['success'] = '1';
			$message['message'] = 'Information update successfully';
			$message['details'] = $userDetails;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'please try after some time';
		}
		echo json_encode($message);

	}

	public function socialLogin(){
		$checkSocialId = $this->db->get_where('users',array('social_id' => $this->input->post('social_id')))->row_array();
    	if(!empty($this->input->post('email'))){
			$checkEmailId = $this->db->get_where('users',array('email' => $this->input->post('email')))->row_array();
        }
    	else{
        	$checkEmailId = '';
        }
		if(!empty($checkSocialId)){
			$datas = array('onlineStatus'=>1,'reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'));
			$update = $this->Common_Model->update('users',$datas,'id',$checkSocialId['id']);
			if(!empty($update)){
				$userDetails = $this->db->get_where('users',array('id' => $checkSocialId['id']))->row_array();
				if(empty($userDetails['image'])){
					$userDetails['image'] =  base_url().'uploads/no_image_available.png';
				}
				if(!empty($userDetails['video'])){
					$userDetails['video'] = base_url().$userDetails['video'];
				}
				else{
					$userDetails['video'] = '';
				}
				$message['success'] = '1';
				$message['message'] = 'user login successfully';
				$message['details'] = $userDetails;
			}
		}
		elseif(!empty($checkEmailId)){
			$datas1 = array('onlineStatus'=>1,'reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'),'social_id' => $this->input->post('social_id'));
			$update1 = $this->Common_Model->update('users',$datas1,'id',$checkEmailId['id']);
			if(!empty($update1)){
				$userDetails1 = $this->db->get_where('users',array('id' => $checkEmailId['id']))->row_array();
				if(empty($userDetails1['image'])){
					$userDetails1['image'] =  base_url().'uploads/no_image_available.png';
				}
				$message['success'] = '1';
				$message['message'] = 'user login successfully';
				$message['details'] = $userDetails1;
			}
		}
		else{
			$datass['username'] = '@user'.rand(100000,999999);
			$datass['name'] = $this->input->post('name');;
			$datass['social_id'] = $this->input->post('social_id');
			$datass['email'] = $this->input->post('email');
			$datass['phone'] = $this->input->post('phone');
			$datass['reg_id'] = $this->input->post('reg_id');
			$datass['image'] = $this->input->post('image');
			$datass['device_type'] = $this->input->post('device_type');
			$datass['login_type'] = 'normal';
			$datass['created'] = date('Y-m-d H:i:s');
			$insert = $this->db->insert('users',$datass);
			if(!empty($insert)){
				$insert_id = $this->db->insert_id();
				$userDetails = $this->db->get_where('users', array('id' => $insert_id))->row_array();

				$blockData['userId'] = $insert_id;
				$blockData['blockUserId'] = $insert_id;
				$blockData['created'] = date('Y-m-d H:i:s');
				$this->db->insert('blockUser',$blockData);

				$infoUserRegister['userId'] = $insert_id;
				$this->db->insert('userProfileInformation',$infoUserRegister);

				if(empty($userDetails['image'])){
					$userDetails['image'] =  base_url().'uploads/no_image_available.png';
				}
				$message = array('success' => '1', 'message' => 'User login successfully', 'details' => $userDetails);
			}else{
				$message = array('success' => '0', 'message' => 'Please Try after some time');
			}
		}
		echo json_encode($message);
	}

	public function logout(){
    	if($this->input->post()){
	        $userId=$this->input->post('userId');
					$data['reg_id']="";
          $data['onlineStatus']="0";

          $this->db->update('users',$data,array('id'=>$userId));

            $message['success'] = "1";
 			$message['message'] = "User logout successfully";
        }
       else{
         	$message = array(
				'message' => 'please enter parameters',
            );
        }
 		echo json_encode($message);
 	}
	public function register(){
		$type = $this->input->post('type');
		$data[$type] = $this->input->post('emailPhone');
		$data['password'] = md5($this->input->post('password'));
		$data['dob'] = $this->input->post('dob');
		$data['username'] = '@user'.rand(100000,999999);
		$data['reg_id'] = $this->input->post('reg_id');
		$data['device_type'] = $this->input->post('device_type');
		$data['login_type'] = 'normal';
		$data['onlineStatus'] = 1;
    	$data['status'] = 'Approved';
		$data['created'] = date('Y-m-d H:i:s');
		$insert = $this->db->insert('users',$data);
		if(!empty($insert)){
			$insert_id = $this->db->insert_id();
			$userDetails = $this->db->get_where('users', array('id' => $insert_id))->row_array();
			$blockData['userId'] = $insert_id;
			$blockData['blockUserId'] = $insert_id;
			$blockData['created'] = date('Y-m-d H:i:s');
			$this->db->insert('blockUser',$blockData);



			$infoUserRegister['userId'] = $insert_id;
			//$infoUserRegister['created'] = date('Y-m-d H:i:s');
			$this->db->insert('userProfileInformation',$infoUserRegister);




			$message = array('success' => '1', 'message' => 'User registered successfully', 'details' => $userDetails);
		}else{
			$message = array('success' => '0', 'message' => 'Please Try after some time');
		}
		echo json_encode($message);
	}

	public function login(){
		$emailPhone = $this->input->post('emailPhone');
		$password = md5($this->input->post('password'));
		$data = $this->db->query("select * from users where (email='$emailPhone' or phone='$emailPhone') and password = '$password'")->row_array();
		if(!empty($data)){
			$datas = array('onlineStatus'=>1,'reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'));
			$update = $this->Common_Model->update('users',$datas,'id',$data['id']);
			$userDetails = $this->db->get_where('users',array('id' => $data['id']))->row_array();
			if(empty($userDetails['image'])){
				$userDetails['image'] =  base_url().'uploads/no_image_available.png';
			}
			$message = array(
				'success'=>'1',
				'message' => 'user login successfully',
				'details' => $userDetails
			);
		}else{
			$message = array(
			  'success'=>'0',
			  'message' => 'Please enter valid login credentials!',
			);
		}
		echo json_encode($message);
	}

	public function accessToken(){
	    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.instamojo.com/oauth2/token/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=u5zMQS0PrSIhWm56tXtx8YcukqSUZped4UVPRkxK&client_secret=fnzNs8GZYMr0qxZQCWLF8ohAVrYu56WCBe4UwZ6fw5nYzNeEGcf8Wlj0f2dsvIeYJTQZRWchxPEVW3YPoqdXBCkEEro5y8RWJ0XxyCYUyFSxlo6vcAoNU0q0ULx7vI4N",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
	}

	public function hashCreate(){
		$response = $this->input->post();
		$data['reg_id'] = json_encode($response);
		// $this->Common_model->insert_data($data,'userDetails');
		$this->Common_Model->register('allPaymentDetails', $data);
		// It is very important to calculate the hash using the returned value and compare it against the hash that was sent while payment request, to make sure the response is legitimate /
		$salt = "64b46227472dc27a4ad162a0265481a95ad9494a"; // put your salt provided by traknpay here
		if(isset($salt) && !empty($salt)){
			$response['calculated_hash']=$this->hashCalculate($salt, $response);
			$response['valid_hash'] = ($response['hash']==$response['calculated_hash'])?'Yes':'No';
		} else {
			$response['valid_hash']='Set your salt in return_page.php to do a hash check on receiving response from Traknpay';
		}

	}

	public function hashCalculate($salt,$input){
		//  Remove hash key if it is present
		unset($input['hash']);
		/*Sort the array before hashing*/
		ksort($input);

		/*first value of hash data will be salt*/
		$hash_data = $salt;

		/*Create a | (pipe) separated string of all the $input values which are available in $hash_columns*/
		foreach ($input as $key=>$value) {
			if (strlen($value) > 0) {
				$hash_data .= '|' . $value;
			}
		}

		$hash = null;
		if (strlen($hash_data) > 0) {
			$hash = strtoupper(hash("sha512", $hash_data));
		}

		return $hash;
	}


	 public function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
		$earth_radius = 6371;
		$dLat = deg2rad($latitude2 - $latitude1);
		$dLon = deg2rad($longitude2 - $longitude1);
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
		$c = 2 * asin(sqrt($a));
		$d = $earth_radius * $c;
		return $d;
	}


	public function checkDestance(){
		$distance = $this->getDistance(30.7402543, 76.7738928, $this->input->post('lat'), $this->input->post('long'));
		if ($distance < 15) {
		  	$message['success'] = '1';
			$message['message'] = 'it is okay';
		}else{
			$details['latitude'] = $this->input->post('lat');
	      	$details['longitude'] = $this->input->post('long');
	      	$details['address'] = $this->input->post('address');
	      	$details['userId'] = $this->input->post('userId');;
	      	$details['created'] = date('Y-m-d H:i:s');
	      	$data = $this->Common_Model->register('userOutstationDetails', $details);
		 	if($data){
		 		$message['success'] = '0';
				$message['message'] = 'Sorry, we are not currently providing service in this area yet. Please, select between Chandigarh, Mohali and Panchkula.';
		 	}
		}
		echo json_encode($message);
	}

	public function checkUserEmail(){
		if (filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
			$data = $this->db->get_where('userDetails',array('email'=>$this->input->post('email')))->row_array();
	 		if(!empty($data)){
	 			$message['success'] = "0";
	 			$message['message'] = "Email is alerdy exists";
	 		}else{
	 			$message['success'] = "1";
	 			$message['message'] = "Email does not exists";
	 		}
		}else {
			$message['success'] = "0";
 			$message['message'] = "Please Enter valid Email";;
		}
 		echo json_encode($message);
 	}

	public function checkUserPhone(){
 		$data = $this->db->get_where('userDetails',array('phone'=>$this->input->post('phone')))->row_array();
 		if(!empty($data)){
 			$message['success'] = "0";
 			$message['message'] = "Email is already exists";
 		}else{
			$otp = rand(9999,1000);
 			$message['success'] = "1";
 			$message['message'] = "Phone does not exists";
			$message['otp'] = (string)$otp;
 		}
 		echo json_encode($message);
 	}

 	public function userLogin(){
		if($this->input->post()){
			$email = $this->input->post('email');
			//$data = $this->User_model->userLogin('userDetails',$email,md5($this->input->post('password')));
			$password = md5($this->input->post('password'));
			$data = $this->db->query("select * from userDetails where (email='$email' or phone='$email') and password = '$password'")->row_array();
			//$data = $this->db->get_where('userDetails',array('phone' =>$email,'password' =>  md5($this->input->post('password'))))->row_array();
			if(!empty($data)){
				if($data['phoneVerifyStatus'] == '0'){
					$message = array(
						'success'=>'2',
						'message' => 'Please Verify Your Phone Number',
						'details' => $data
					);
				}else{
					$datas = array('onlineStatus'=>'1','reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'));
					$update = $this->Common_Model->update('userDetails',$datas,'id',$data['id']);
					$userDetails = $this->db->get_where('userDetails',array('id' => $data['id']))->row_array();
					$message = array(
						'success'=>'1',
						'message' => 'user login successfully',
						'details' => $userDetails
					);
				}
		    }else{
		        $message = array(
		          'success'=>'0',
		          'message' => 'Please enter valid login credentials!',
		        );
	      }
	    }else{
	   		$message = array(
	        'message' => 'please enter parameters',
	        );
	    }
    	echo json_encode($message);
	}

 	public function matchVerificationToken(){
		if($this->input->post()){
	        $id = $this->input->post('id');
	        $token = $this->input->post('token');
	        $data = $this->User_model->match_verifaction_token($id,$token,'userDetails');
			if(!empty($data)){
				$datas = array('phoneVerifyStatus' => '1');
                $update = $this->Common_Model->update('userDetails',$datas,'id',$id);
				$message = array(
		            'success'=>'1',
		            'message' => 'verification token match successfully',
		        );
				echo json_encode($message);
	        }else{
	       	     $message = array(
		            'message'=>"sorry your verification token does not match!",
		            'success'=>'0'
		         );
				 echo json_encode($message);
	        }
        }else{
       		$message = array(
				'message' => 'please enter parameters',
            );
			echo json_encode($message);
        }
    }

	public function userRegister(){
	  	if ($this->input->post()){
	  		$phone = $this->input->post('phone');
	  		$email = $this->input->post('email');
	  		$data = $this->db->get_where('userDetails',array('email'=>$email))->row_array();
	  		if(!empty($phone)){
	  		    $dataa = $this->db->get_where('userDetails',array('phone'=>$phone))->row_array();
	  		}
			if(!empty($data)){
					$message = array('success' => '0', 'message' => 'Email already exists');
			}elseif(!empty($dataa)){
				$message = array('success' => '0', 'message' => 'Phone number already exists');
			}else{
		  		$details['name'] = $this->input->post('name');
		      	$details['email'] = $this->input->post('email');
		      	$details['phone'] = $this->input->post('phone');
		      	if(empty($this->input->post('phone'))){
    	  		    $details['phoneVerifyStatus'] = '1';
    	  		}
		      	$details['otp'] = mt_rand(1000,9999);
		      	$details['password'] = md5($this->input->post('password'));
		      	$details['device_type'] = $this->input->post('device_type');
		      	$details['reg_id'] = $this->input->post('reg_id');
		      	$details['login_type'] = $this->input->post('login_type');;
		      	$details['created'] = date('Y-m-d H:i:s');
		      	$data = $this->Common_Model->register('userDetails', $details);
		      	if($data){
	    	        $insert_id = $this->db->insert_id();
		            $userDetails = $this->db->get_where('userDetails', array('id' => $insert_id))->row_array();
		          	$message = array('success' => '1', 'message' => 'User registered successfully', 'details' => $userDetails);
		      	}else{
					$message = array('success' => '0', 'message' => 'Please Try after some time');
				}
			}
	    }else{
	      	$message = array('message' => 'Please enter parameters');
	    }
	    echo json_encode($message);
 	}

	public function userLogin2(){
		if($this->input->post()){
			$email = $this->input->post('email');
			$data = $this->User_model->userLogin('userDetails',$email,md5($this->input->post('password')));
			if(!empty($data)){
				$datas = array('reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'));
				$update = $this->Common_Model->update('userDetails',$datas,'id',$data['id']);
				$userDeatils = $this->db->get_where('userDetails',array('id' => $data['id']))->row_array();
				if($update){
					$message = array(
						'success'=>'1',
						'message' => 'User login successfully',
						'details' => $userDeatils
					);
				}else{
					$message = array(
						'success'=>'0',
						'message' => 'Please Try After Some Time',
					);
				}
		    }else{
		        $message = array(
		          	'success'=>'0',
		          	'message' => 'Please enter valid login credentials!',
				);
			}
	    }else{
	   		$message = array(
	        	'message' => 'Please enter parameters',
	        );
	    }
	    echo json_encode($message);
	}

	public function resendVerificationToken(){
   		if($this->input->post()){
	        $id = $this->input->post('id');
	        $otp = mt_rand(1000,9999);
			$user=$this->db->get_where('userDetails',array('id'=>$id))->row_array();
            if(!empty($user)){
	         	$datas = array('otp' => $otp);
                $update = $this->Common_Model->update('userDetails',$datas,'id',$id);

                $mess = "Hi ".$user['name']." your otp here ".$otp;
	            $number = $user['phone'];
	    $sendMessage = file_get_contents("https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode(".$mess.")."&language=english&route=p&numbers=".urlencode(''.$number.''));


		        $message = array(
		            'success'=>'1',
		            'message' => 'Otp send to your phone',
					'otp' =>(String)$datas['otp'],
		        );
	        }else{
	       	    $message = array(
					'success'=>'0',
		            'message'=>"Error",
		        );
	        }
        }else{
       		$message = array(
				'message' => 'Please enter parameters',
            );
        }
		echo json_encode($message);
    }

    public function userCheckAppleId(){
	   	if($this->input->post()){
			$check_social_id = $this->Common_Model->get_data_by_id('userDetails','social_id',$this->input->post('social_id'));
			if(!empty($check_social_id)){
				$message = array('success' =>'1','message'=>'User login successfully','details'=>$check_social_id);
			}
			else{
				$message = array('success' =>'0','message'=>'Please create your account');
			}
		}else{
			$message = array(
		  		'message' => 'Please enter parameters', // Automatically generated by the model
		  	 );
		}

		echo json_encode($message);
	}

		public function registerPhone(){
		if($this->input->post()){
			$userId = $this->input->post('userId');
			$check_user = $this->Common_Model->get_data_by_id('userDetails','id',$userId);
			if(!empty($check_user['phone'])){
		    	$message = array(
    				'success' => '1',
    				'message' => 'Phone number is registered' // Automatically generated by the model
				);
			}else{
			    $check_social_id = $this->Common_Model->get_data_by_id('userDetails','phone',$this->input->post('phone'));
			    if(empty($check_social_id)){
        			$data = array(
        				'phone' => $this->input->post('phone')
        			);
        			$insert = $this->db->update('userDetails',$data,array('id'=> $userId));
        			if($insert){
        				$userDetails = $this->db->get_where('userDetails',array('id' => $userId ))->row_array();
        				$message = array(
        					'success' => '1',
        					'message' => 'Profile Update Successfully', // Automatically generated by the model
        					'details' => $userDetails
        				);
        			}else{
        				$message = array(
            				'success' => '0',
            				'message' => 'Try after sometime' // Automatically generated by the model
        				);
        			}
			    }else{
			       $message = array(
        				'success' => '0',
        				'message' => 'Phone number already exist' // Automatically generated by the model
    				);
			    }
			}
		}else{
			$message = array(
			'message' => 'Please enter parameters' // Automatically generated by the model
			);
		}
		echo json_encode($message);
	}

		public function UserAppleLogin(){
		if($this->input->post()){
			$check_social_id = $this->db->get_where('userDetails',array('social_id'=>$this->input->post('social_id')))->row_array();
// 			if(!empty($this->input->post('email'))){
// 			    	$check_email = $this->db->get_where('userDetails',array('email'=>$this->input->post('email')))->row_array();
// 			}

			if(!empty($check_social_id)){
				$message = array('success' =>'1','message'=>'User login successfully','details'=>$check_social_id);
			}else{
				$data['name'] = $this->input->post('username');
				$data['email'] = $this->input->post('email');
				// $data['phone'] = $this->input->post('phone');
			    $data['social_id'] =$this->input->post('social_id');
				 $data['device_type'] ="ios";
				$data['reg_id'] =$this->input->post('reg_id');
				// $data['image'] =$this->input->post('image');
				// $data['login_type'] =$this->input->post('loginType');
				$data['created'] = date('y-m-d h:i:s');
				$details = $this->Common_Model->register('userDetails',$data);
				if($details){
					$insert_id = $this->db->insert_id();
				  	$datass['userId'] = $insert_id;
					$datass['productEmails'] ="1";
					$datass['marketingEmails'] = "1";
					$insert = $this->db->insert('userSettings', $datass);
					$userDetails = $this->db->get_where('userDetails', array('id' => $insert_id))->row_array();
					$message = array('success' => '1', 'message' => 'User register successfully', 'details' => $userDetails);
				}
			}
		}else{
			$message = array(
				'message' => 'Please enter parameters',
			);
		}
		echo json_encode($message);
	}


   public function userCheckSocialId(){
	   	if($this->input->post()){
			$check_social_id = $this->Common_Model->get_data_by_id('userDetails','social_id',$this->input->post('social_id'));
			if(!empty($check_social_id)){
              if($check_social_id['dob']=='' || $check_social_id['dob']==null){
              	$check_social_id['dob'] = "";
              }
              $datas = array('onlineStatus'=>'1','reg_id' => $this->input->post('reg_id'),'device_type' => $this->input->post('device_type'));
					$update = $this->Common_Model->update('userDetails',$datas,'id',$check_social_id['id']);
				    $message = array('success' =>'1','message'=>'User login successfully','details'=>$check_social_id);
			}
			else{
				$message = array('success' =>'0','message'=>'Please create your account');
			}
		}else{
			$message = array(
		  		'message' => 'Please enter parameters', // Automatically generated by the model
		  	 );
		}

		echo json_encode($message);
	}

	public function UserSocialLogin(){
		if($this->input->post()){
			$check_social_id = $this->db->get_where('userDetails',array('social_id'=>$this->input->post('social_id')))->row_array();
			if(!empty($check_social_id)){
               if($check_social_id['dob']=='' || $check_social_id['dob']==null){
              	$check_social_id['dob'] = "";
              }
				$message = array('success' =>'1','message'=>'User login successfully','details'=>$check_social_id);
			}else{
				$data['name'] = $this->input->post('username');
				$data['email'] = $this->input->post('email');
				$data['phone'] = $this->input->post('phone');
				$data['social_id'] =$this->input->post('social_id');
				$data['device_type'] =$this->input->post('device_type');
				$data['reg_id'] =$this->input->post('reg_id');
				$data['image'] =$this->input->post('image');
				$data['login_type'] =$this->input->post('loginType');
				$data['created'] = date('y-m-d h:i:s');
				$details = $this->Common_Model->register('userDetails',$data);
				if($details){
					$insert_id = $this->db->insert_id();
				  	$datass['userId'] = $insert_id;
					$datass['productEmails'] ="1";
					$datass['marketingEmails'] = "1";
					$insert = $this->db->insert('userSettings', $datass);
					$userDetails = $this->db->get_where('userDetails', array('id' => $insert_id))->row_array();
					$message = array('success' => '1', 'message' => 'User register successfully', 'details' => $userDetails);
				}
			}
		}else{
			$message = array(
				'message' => 'Please enter parameters',
			);
		}
		echo json_encode($message);
	}

	public function getProductList(){
		$data = $this->db->get_where('productList',array('status'=>'1'))->result_array();
 		if(!empty($data)){
 			$dd = $this->db->get_where('pages',array('id'=>5,'status'=>'1'))->row_array();
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			if(!empty($dd))
 				$message['homepageText']  = $dd['description'];
 			else
 				$message['homepageText']  = "";
 			$message['details'] = $data;

 		}
 		else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}

 	public function getProductListNew(){
		$data = $this->db->query('select * from productList where description <> "" ')->result_array();
 		if(!empty($data)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $data;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}

 	public function getFaqList(){
		$data = $this->db->get('faq')->result_array();
 		if(!empty($data)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $data;
 		}
 		else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}

 	public function getPartnerList(){
		$data = $this->db->get('partnerList')->result_array();
 		if(!empty($data)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $data;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}

 	public function aboutUs(){
		$data['datas'] = $this->db->get_where('pages',array('id'=>1))->row_array();
		$this->load->view('template/about_us',$data);
	}

	public function terms(){
		$data['datas'] = $this->db->get_where('pages',array('id'=>3))->row_array();
		$this->load->view('template/terms',$data);
	}

	public function privacyAndPolicy(){
		$data['datas'] = $this->db->get_where('pages',array('id'=>4))->row_array();
		$this->load->view('template/privacy_and_policy',$data);
	}

 	public function editUserProfile(){
		if($this->input->post()){
			$userId = $this->input->post('userId');
			$data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'address' => $this->input->post('address'),
                'dob' => $this->input->post('dob')
			);

			$insert = $this->db->update('userDetails',$data,array('id'=> $userId));
			if($insert){
				$userDetails = $this->db->get_where('userDetails',array('id' => $userId ))->row_array();
				$message = array(
					'success' => '1',
					'message' => 'Profile Update Successfully', // Automatically generated by the model
					'details' => $userDetails
				);
			}else{
				$message = array(
				'success' => '0',
				'message' => 'Try after sometime' // Automatically generated by the model
				);
			}
		}else{
			$message = array(
			'message' => 'Please enter parameters' // Automatically generated by the model
			);
		}
		echo json_encode($message);
	}

	public function userPlaceOrder(){
		if($this->input->post()){
			if($this->input->post('paymentMethod') == '1'){
				$details['paymentMethod'] = '1';
				$details['userId'] = $this->input->post('userId');
				$details['bookingId'] = mt_rand(100000,999999);
				$details['productId'] = $this->input->post('productId');
				$details['quantity'] = $this->input->post('quantity');
				$details['latitude'] = $this->input->post('latitude');
				$details['longitude'] = $this->input->post('longitude');
				$details['location'] = $this->input->post('location');
				$details['date'] = $this->input->post('date');
				if(empty($this->input->post('time'))){
					$details['time'] = date('23:59:00');
				}else{
					$details['time'] = $this->input->post('time');
				}
				$details['pricePerLitre'] = $this->input->post('pricePerLitre');
				$details['totalPrice'] = $this->input->post('totalPrice');
				$details['created'] = date('Y-m-d H:i:s');
				$data = $this->Common_Model->register('userBookingOrder',$details);
				if($data){
					$insertId = $this->db->insert_id();
					$det['bookingId'] = $insertId;
					$datadd = $this->Common_Model->register('notificationBooking',$det);
					$message['success'] = '1';
					$message['paymentMethod'] = 'Cash';
					$message['message'] = 'Order booked successfully';
				}
			}else{
			    $details['paymentMethod'] = '2';
			    $details['userId'] = $this->input->post('userId');
				$details['bookingId'] = mt_rand(100000,999999);
				$details['productId'] = $this->input->post('productId');
				$details['quantity'] = $this->input->post('quantity');
				$details['latitude'] = $this->input->post('latitude');
				$details['longitude'] = $this->input->post('longitude');
				$details['location'] = $this->input->post('location');
				$details['transactionId'] = $this->input->post('transactionId');
				$details['date'] = $this->input->post('date');
				if(empty($this->input->post('time'))){
					$details['time'] = date('23:59:00');
				}else{
					$details['time'] = $this->input->post('time');
				}
				$details['pricePerLitre'] = $this->input->post('pricePerLitre');
				$details['totalPrice'] = $this->input->post('totalPrice');
				$details['created'] = date('Y-m-d H:i:s');
				$data = $this->Common_Model->register('userBookingOrder',$details);
				if($data){
					$insertId = $this->db->insert_id();
					$det['bookingId'] = $insertId;
					$datadd = $this->Common_Model->register('notificationBooking',$det);
					$message['success'] = '1';
					$message['paymentMethod'] = 'TranknPay';
					$message['message'] = 'Order booked successfully';
				}
			}
			if($data){
				$user = $this->db->get_where('userDetails',array('id'=>$this->input->post('userId')))->row_array();
				$pro = $this->db->get_where('productList',array('id'=>$this->input->post('productId')))->row_array();
				$mess = "";
				if(!empty($user['name'])){
					$mess .= $user['name'].", ";
				}
				if(!empty($user['email'])){
					$mess .= $user['email']." ";
				}
				if(!empty($user['phone'])){
					$mess .= $user['phone'];
				}
				$mess .= " booked order for ".$this->input->post('quantity')." litre of ".$pro['title'].", "."address is ".$this->input->post('location');
           		$number = '9815493702,9817664164';
   				$sendMessage = file_get_contents("https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode(".$mess.")."&language=english&route=p&numbers=".urlencode(''.$number.''));
			}
		}else{
			$message = array('success'=>'0','message'=>'Please Enter Parameters.');
		}
		echo json_encode($message);
	}

	public function testingStripe(){
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

	public function getCurrentOrderList(){
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$providerId = $this->input->post('userId');
		$jobs = $this->db->query("select userBookingOrder.*,productList.title,productList.image from userBookingOrder left join productList on productList.id = userBookingOrder.productId where ((userBookingOrder.date ='$date' and userBookingOrder.time >='$time') or (userBookingOrder.date >'$date')) and userBookingOrder.orderStatus not in ('5') and userBookingOrder.orderStatus not in ('2') and userBookingOrder.userId='$providerId' order by userBookingOrder.id desc")->result_array();

		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function getPastOrderList(){
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$providerId = $this->input->post('userId');
		$jobs = $this->db->query("select userBookingOrder.*,productList.title,productList.image from userBookingOrder left join productList on productList.id = userBookingOrder.productId where ((userBookingOrder.date ='$date' and userBookingOrder.time <'$time') or (userBookingOrder.date <='$date')) and userBookingOrder.orderStatus='5' and userBookingOrder.userId='$providerId' order by userBookingOrder.id desc")->result_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function userReorderProduct(){
		$data = $this->db->get_where('userBookingOrder',array('id'=>$this->input->post('orderId')))->row_array();
 		if(!empty($data)){
 			if($this->input->post('paymentMethod') == '1'){
				$details['paymentMethod'] = '1';
				$details['userId'] = $this->input->post('userId');
				$details['productId'] = $data['productId'];
				$details['bookingId'] = mt_rand(100000,999999);
				$details['quantity'] = $data['quantity'];
				//$details['location'] = $data['location'];
				$details['latitude'] = $this->input->post('latitude');
				$details['longitude'] = $this->input->post('longitude');
				$details['location'] = $this->input->post('location');
				$details['date'] = $this->input->post('date');
				if(empty($this->input->post('time'))){
					$details['time'] = date('23:59:00');
				}else{
					$details['time'] = $this->input->post('time');
				}
				$details['pricePerLitre'] = $data['pricePerLitre'];
				$details['totalPrice'] = $data['totalPrice'];
				$details['created'] = date('Y-m-d H:i:s');
				$data1 = $this->Common_Model->register('userBookingOrder',$details);
				if($data1){
					$message['success'] = '1';
					$message['paymentMethod'] = 'Cash';
					$message['message'] = 'Order booked successfully';
				}
			}else{
			    $details['paymentMethod'] = '2';
			    $details['userId'] = $this->input->post('userId');
				$details['bookingId'] = mt_rand(100000,999999);
				$details['productId'] = $data['productId'];
				$details['quantity'] =  $data['quantity'];
				$details['latitude'] = $this->input->post('latitude');
				$details['longitude'] = $this->input->post('longitude');
				$details['location'] = $this->input->post('location');
				$details['transactionId'] = $this->input->post('transactionId');
				$details['date'] = $this->input->post('date');
				if(empty($this->input->post('time'))){
					$details['time'] = date('23:59:00');
				}else{
					$details['time'] = $this->input->post('time');
				}
				$details['pricePerLitre'] = $this->input->post('pricePerLitre');
				$details['totalPrice'] = $this->input->post('totalPrice');
				$details['created'] = date('Y-m-d H:i:s');
				$data1 = $this->Common_Model->register('userBookingOrder',$details);
				if($data1){
					$insertId = $this->db->insert_id();
					$det['bookingId'] = $insertId;
					$datadd = $this->Common_Model->register('notificationBooking',$det);
					$message['success'] = '1';
					$message['paymentMethod'] = 'TranknPay';
					$message['message'] = 'Order booked successfully';
				}
			}
			if($data1){
				$user = $this->db->get_where('userDetails',array('id'=>$this->input->post('userId')))->row_array();
				$pro = $this->db->get_where('productList',array('id'=>$data['productId']))->row_array();
				$mess = "";
				if(!empty($user['name'])){
					$mess .= $user['name'];
				}
				if(!empty($user['email'])){
					$mess .= ",".$user['email'];
				}
				if(!empty($user['phone'])){
					$mess .= ",".$user['phone'];
				}
				$mess .= " booked order for ".$data['quantity']." litre of ".$pro['title'].", "."address is ".$this->input->post('location');;
           		$number = '9815493702,9817664164';
   				$sendMessage = file_get_contents("https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode(".$mess.")."&language=english&route=p&numbers=".urlencode(''.$number.''));
			}
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}

	public function getTrackOrderList(){
		$orderId = $this->input->post('bookingOrderId');
		$jobs = $this->db->query("select userBookingOrder.*,productList.title,productList.image from userBookingOrder left join productList on productList.id = userBookingOrder.productId where userBookingOrder.id='$orderId'")->row_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function getLatestOrderList(){
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$providerId = $this->input->post('userId');
		$jobs = $this->db->query("select userBookingOrder.*,productList.title,productList.image from userBookingOrder left join productList on productList.id = userBookingOrder.productId where ((userBookingOrder.date ='$date' and userBookingOrder.time >='$time') or (userBookingOrder.date >'$date')) and userBookingOrder.orderStatus not in ('5','2') and userBookingOrder.userId='$providerId' order by date,time asc")->row_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function forgotPassword(){
    	if($this->input->post()){
	    	$email=$this->input->post('email');
	        $check_email = $this->db->get_where('userDetails',array('phone'=>$email))->row_array();
			if(empty($check_email)){
				$message = array(
					'message' => 'This phone number is not registered!',
					'success'=>'0'
				);
	        }else{
	         	$length = 8;
	         	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$password = '';
				for ($i = 0; $i < $length; $i++) {
			       $password .= $characters[rand(0, $charactersLength - 1)];
				}
		        $data = array(
		            'password' => md5($password),
		            'fStatus'=>'1'
 		        );
		        $update = $this->db->update('userDetails',$data,array('phone'=> $email));
		        if($update){
		   //       	$this->load->library('email');
					// $config['mailtype'] = 'html';
					// $this->email->initialize($config);
					// $this->email->from('info@petrowagon.com');
					// $this->email->to($check_email['email']);
					// $this->email->subject("PetroWagon - Regarding Forget password.");
					// $message = "Hi ".$check_email['name']." your new password here ".$password;
					// $this->email->message($message);
					// $send = $this->email->send();
					$mess = "Hi ".$check_email['name']." your password is here ".$password;
	           		$number = $check_email['phone'];
	   				$sendMessage = file_get_contents("https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode(".$mess.")."&language=english&route=p&numbers=".urlencode(''.$number.''));
		         	$message = array(
				            'message'=>'Your password send to your phone',
				            'success'=>'1'
				         );
		        }else{
		         	$message = array(
						'message' => 'some error occured',
					);
		        }
	        }
         }else{
         	$message = array(
				'message' => 'please enter parameters',
            );
        }
		echo json_encode($message);
    }

    public function changePassword(){
		if($this->input->post()){
			$id = $this->input->post('userId');
			$old_password=md5($this->input->post('old_password'));
			$new_password=md5($this->input->post('new_password'));
			$check_password=$this->Common_Model->check_password($old_password,$id);
			if(empty($check_password)){
				$message = array(
						'success'=>"0",
						'message' => "Old Password Doesn't Match"
				);
				echo json_encode($message);
			}else{
				$data = array(
					'password'=>$new_password,
				);
				$update_password=$this->db->update('users',$data,array('id'=> $id));
				if ($update_password) {
					$message = array(
						'success'=>"1",
						'message' => "Password Changed Successfully"
					);
					echo json_encode($message);
				}else {
					$message = array(
						'success'=>"0",
						'message' => "Please try again"
					);
					echo json_encode($message);
				}
			}
		}
	}

	public function notificationTesting(){
		if($this->input->post()){
			$bookingId = $this->input->post('bookingId');
			$order = $this->db->get_where('userBookingOrder',array('id'=>$bookingId))->row_array();
			if(!empty($order)){
				$datas1['orderStatus'] = $this->input->post('status');
				$up = $this->Common_Model->update('userBookingOrder',$datas1,'id',$bookingId);
				if($up){
					$datas = $this->db->get_where('userBookingOrder',array('id'=>$bookingId))->row_array();
					$reg_id = $this->db->get_where('userDetails',array('id'=>$order['userId'],'notificationStatus'=>'1'))->row_array();
					$regIDs = $reg_id['reg_id'];
					if($datas['orderStatus']=='1'){
						$message = $reg_id['name']." "."Your Order Accepted";
						$type = 'accept';
					}
					elseif($datas['orderStatus']=='2'){
						$message = $reg_id['name']." "."Your Order Is Rejected";
						$type = 'reject';
					}
					elseif($datas['orderStatus']=='3'){
						$message = $reg_id['name']." "."Your Order In Progress";
						$type = 'progress';
					}
					elseif($datas['orderStatus']=='4'){
						$message = $reg_id['name']." "."Your Order Is On The Way";
						$type = 'onTheWay';
					}
					elseif($datas['orderStatus']=='5'){
						$message = $reg_id['name']." "."Your Order Is Delivered Successfully";
						$type = 'delivered';
					}
					else{
						$message = $reg_id['name']." "."Your Order Accepted";
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
						'type'      => $type
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
					$message1['success'] = "1";
			 		$message1['message'] = "Status updated successfully";
			 		//$message['details'] = $jobs;
				}else{
					$message1 = array(
						'success'=>"0",
						'message' => "Try after some time"
					);
				}
			}
		}else{
			$message1 = array(
				'success'=>"0",
				'message' => "Please enter parameters"
			);
		}
		echo json_encode($message1);
	}

	public function pushNotificationsOnOff(){
		if($this->input->post()){
			$deta = $this->db->get_where('userDetails',array('id'=>$this->input->post('userId')))->row_array();
			if(!empty($deta)){
				$id = $this->input->post('userId');
				$datas = array(
					'notificationStatus'=>$this->input->post('status')
				);
    			$update = $this->Common_Model->update('userDetails',$datas,'id',$id);
    			if($update){
    				$deta1 = $this->db->get_where('userDetails',array('id'=>$this->input->post('userId')))->row_array();
    				if($deta1['notificationStatus']=='0'){
    					$message = array(
				      		'success'=>'1',
				      		'message' => 'Push notifications are off now'
					    );
    				}else{
    					$message = array(
				      		'success'=>'1',
				      		'message' => 'Push notifications are on now'
					    );
    				}
    			}
			}else{
				$message = array(
		      		'success'=>'0',
		      		'message' => 'No details found'
			    );
			}
	    }else{
	     	$message = array(
				'message' => 'please enter parameters',
	      	);
	    }
		echo json_encode($message);
 	}

 	public function getTransactionList(){
		$providerId = $this->input->post('userId');
		$jobs = $this->db->query("select userBookingOrder.*,productList.title,productList.image from userBookingOrder left join productList on productList.id = userBookingOrder.productId where userBookingOrder.orderStatus in ('5') and userBookingOrder.userId='$providerId' order by userBookingOrder.id desc")->result_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function cancelOrder(){
		if($this->input->post()){
			$details['orderStatus'] = '2';
			$details['cancelType'] = '1';
			$id = $this->input->post('orderId');
			$update = $this->Common_Model->update('userBookingOrder',$details,'id',$id);
			if($update){
				$message['success'] = "1";
 				$message['message'] = "Your order cancelled successfully";
			}else{
				$message['success'] = "0";
 				$message['message'] = "Try after sometime";
			}
		}else{
 			$message['message'] = "Please enter parameters";
		}
		echo json_encode($message);
	}

	public function userChangeAlert(){
		$checkData = $this->db->get_where('userSettings',array('userId' => $this->input->post('userId')))->row_array();
		if(empty($checkData)){
			$data['userId'] = $this->input->post('userId');
			$data['productEmails'] = $this->input->post('productEmails');
			$data['marketingEmails'] = $this->input->post('marketingEmails');
			$insert = $this->db->insert('userSettings', $data);
			if($insert){
				$message['success'] = '1';
				$message['message'] = 'Permission Assign Successfully';
			}
		}else{
			$data['userId'] = $this->input->post('userId');
			$data['productEmails'] = $this->input->post('productEmails');
			$data['marketingEmails'] = $this->input->post('marketingEmails');
			$update = $this->Common_Model->update('userSettings',$data,'id',$checkData['id']);
			if($update){
				$message['success'] = '1';
				$message['message'] = 'Permission Update Successfully';
			}
		}
		echo json_encode($message);
	}

	public function getUserSettings(){
		if($this->input->post()){
			$deta = $this->db->get_where('userSettings',array('userId'=>$this->input->post('userId')))->row_array();
			if(!empty($deta)){
				$deta1 = $this->db->get_where('userDetails',array('id'=>$this->input->post('userId')))->row_array();
				//print_r($deta1);die;
				$deta['nstatus'] = $deta1['notificationStatus'];
				$message['success'] = '1';
				$message['message'] = 'details found successfully';
				$message['details'] = $deta;
			}else{
				$message = array(
		      		'success'=>'0',
		      		'message' => 'No details found'
			    );
			}
	    }else{
	     	$message = array(
				'message' => 'please enter parameters',
	      	);
	    }
		echo json_encode($message);
 	}

 	public function getCharges(){
		$jobs = $this->db->get('charges')->result_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function getDeliveryTime(){
		$jobs = $this->db->get('deliveryTime')->result_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}
 		else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function getMinimumValues(){
		$jobs = $this->db->get('minimumValue')->result_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}
 		else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function checkFast2Sms(){
	    $otp = 3390;
	    $message = "hello your otp is $otp";
	    $number = '7087772970';
	   // $url = 'https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode("'.$message.'")."&language=english&route=p&numbers=".urlencode('.$number.')';
	   // echo $url;
	    $sendMessage = file_get_contents("https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode(".$message.")."&language=english&route=p&numbers=".urlencode(''.$number.''));

	    print_r($sendMessage);
	    //die;
	}

	public function getOrderRejectList(){
		$userId = $this->input->post('userId');
		$jobs = $this->db->query("select userBookingOrder.*,productList.title,productList.image from userBookingOrder left join productList on productList.id = userBookingOrder.productId where userBookingOrder.orderStatus='2' and userId='$userId'")->result_array();
		if(!empty($jobs)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $jobs;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
	}

	public function chkServer($host, $port){
		$hostip = @gethostbyname($host);
		if ($hostip == $host){
			echo "Server is down or does not exist";
		}else{
			if (!$x = @fsockopen($hostip, $port, $errno, $errstr, 5)){
				echo "Port $port is closed.";
			}else{
				echo "Port $port is open.";
				if ($x){
					@fclose($x);
				}
			}
		}
	}

	public function ser(){
		$x = $this->chkServer('gateway.sandbox.push.apple.com',2195);
		print_r($x);
		//chkServer('gateway.push.apple.com',2195);
	}

	public function pushIosNotification(){
		// (Android)API access key from Google API's Console.
		static $API_ACCESS_KEY = 'AIzaSyDG3fYAj1uW7VB-wejaMJyJXiO5JagAsYI';
		// (iOS) Private key's passphrase.
		static $passphrase = 'joashp';
		// (Windows Phone 8) The name of our push channel.
		static	$channelName = "joashp";
		// Change the above three vriables as per your app.
		// Sends Push notification for iOS users
		$text = "hello boy";
		$msg_payload = array (
			'mtitle' => 'Hello PetroWagon',
			'mdesc' => $text,
		);
		$data = $msg_payload;
		$registrationIds = '6683de0c388ebf6efd039ab14cd7a6342fc181db38dca646e157ef61c96fdb86';
		$deviceToken = $registrationIds;

		$ctx = stream_context_create();
		// ck.pem is your certificate file
		// $t = $this->load->view('pemFile/pushcert.pem');
		$t =APPPATH . 'third_party/CertPetroWgn.pem';
		stream_context_set_option($ctx, 'ssl', 'local_cert', $t);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);

		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
				'title' => "fdgh",
				'body' => "fghd",
			 ),
			'sound' => 'default'

		);
		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		print_r($result);
		// Close the connection to the server
		fclose($fp);
		if (!$result)
			return "0";
		else
			return "1";
	}

	public function checkVersion(){
		$data['versionStatus'] = '3';
		$message['success'] = '1';
		$message['message'] = 'App is in Maintenance';
		$message['details'] = $data;
		echo json_encode($message);
	}

	public function checkVersionIos(){
		$data['versionStatus'] = '1.0.3';
		$message['success'] = '1';
		$message['message'] = 'App is in Maintenance';
		$message['details'] = $data;
		echo json_encode($message);
	}

		public function forgotPassword1(){
    	if($this->input->post()){
	    	$email=$this->input->post('email');
	        $check_email = $this->db->query("select * from userDetails where email='$email' or phone='$email'")->row_array();
			if(empty($check_email)){
			    if($this->input->post('status')==1){
    				$message = array(
    					'message' => 'This phone number is not registered!',
    					'success'=>'0'
    				);
			    }else{
			       	$message = array(
    					'message' => 'This email is not registered!',
    					'success'=>'0'
    				);
			    }
	        }else{
	         	$length = 8;
	         	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$password = '';
				for ($i = 0; $i < $length; $i++) {
			       $password .= $characters[rand(0, $charactersLength - 1)];
				}
		        $data = array(
		            'password' => md5($password),
		            'fStatus'=>'1'
 		        );
		        $update = $this->db->update('userDetails',$data,array('phone'=> $email));
		        if($update){
		   //
					 if($this->input->post('status')==1){
					     	$mess = "Hi ".$check_email['name']." your password is here ".$password;
        	           		$number = $check_email['phone'];
        	   				$sendMessage = file_get_contents("https://www.fast2sms.com/dev/bulk?authorization=OCpJriqzRs9yoexQYvINak8SfhcW7PjtAgdL0BEMXFHmZD4GunnMfLtmDHBu4EAG6Y7idvNFqKbWjSJe&sender_id=Omnino&message=".urlencode(".$mess.")."&language=english&route=p&numbers=".urlencode(''.$number.''));
        		         	$message = array(
    				            'message'=>'Your password send to your phone',
    				            'success'=>'1'
    				         );
					 }else{
					     $this->load->library('email');
    					$config['mailtype'] = 'html';
    					$this->email->initialize($config);
    					$this->email->from('info@petrowagon.com');
    					$this->email->to($check_email['email']);
    					$this->email->subject("PetroWagon - Regarding Forget password.");
    					$message = "Hi ".$check_email['name']." your new password here ".$password;
    					$this->email->message($message);
    					$send = $this->email->send();
    					if($send){
    					     $message = array(
                    				'message'=>'Your password send to your email',
    				                'success'=>'1'
                            );
    					}
					 }

		        }else{
		         	$message = array(
						'message' => 'some error occured',
					);
		        }
	        }
         }else{
         	$message = array(
				'message' => 'please enter parameters',
            );
        }
		echo json_encode($message);
    }

  public function getCountryCode(){
		$data = $this->db->get('country')->result_array();
    $i=0;
    while($i<count($data)){
      $img= base_url()."assets/country/flags-medium/".strtolower($data[$i]['code']).".png";
     $data[$i]['image']=$img;
      $i++;
      }
 		if(!empty($data)){
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $data;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}




  public function enquiry(){
    	if($this->input->post()){
	       $data['username']=$this->input->post('username');
           $data['email']=$this->input->post('email');
           $data['dob']=$this->input->post('dob');
           $data['mobile']=$this->input->post('mobile');
           $data['address']=$this->input->post('address');
           $data['city']=$this->input->post('city');
           $data['time']=$this->input->post('time');
           $test=$this->db->insert('enquiryDetails',$data);
          if($test){
            $message['success'] = "1";
 			$message['message'] = "data submitted successfully";
            $message['data']=$data;
          }


        }
       else{
         	$message = array(
				'message' => 'please enter parameters',
            );
        }
 		echo json_encode($message);
 	}

	 public function getSearchUsersList(){
       	if(!empty($this->input->post('search'))){
           	$search = strtolower($this->input->post('search'));
        	$userId  = $this->input->post('userId');
			$data = $this->db->query("select users.id,users.name,users.username,users.image from users where id NOT IN (select blockUserId from blockUser where userId = $userId ) and (username like '@$search%' || name like '$search%') ")->result_array();
          //echo $this->db->last_query();die;
        }else{
         	$data = $this->db->query("select users.id,users.name,users.username,users.image from users where id NOT IN (select blockUserId from blockUser where userId = $userId ) ")->result_array();
        }
 		if(!empty($data)){
          	foreach($data as $list){
               $checkStataus = $this->db->get_where('userFollow',array('userId' => $list['id'],'followingUserId' => $this->input->post('userId'),'status' => '1'))->num_rows();
              //print_r($checkStataus);die;
              // echo $this->db->last_query();
              $checkStataus1 = $this->db->get_where('userFollow',array('userId' => $this->input->post('userId'),'followingUserId' => $list['id'],'status' => '1'))->num_rows();

                if(!empty($checkStataus) && !empty($checkStataus1)){
                  $list['status'] = '3';
                }elseif(!empty($checkStataus) && empty($checkStataus1)){
                    $list['status'] = '2';
                }elseif(empty($checkStataus) && !empty($checkStataus1)){
                    $list['status'] = '1';
                }else{
                	$list['status'] = '0';
                }
                if(empty($list['image'])){
                    $list['image'] =  base_url().'uploads/no_image_available.png';
                }
              $dd[] = $list;
            }
 			$message['success'] = "1";
 			$message['message'] = "Details found successfully";
 			$message['details'] = $dd;
 		}else{
 			$message['success'] = "0";
 			$message['message'] = "Details not found";
 		}
 		echo json_encode($message);
 	}

	public function getSoundsList(){
    	if(!empty($this->input->post('search'))){
        	$search = strtolower($this->input->post('search'));

        	$checkData = $this->db->query("select id,title,soundImg,concat('".base_url()."',sound) as sound from sounds where lower(title) like '%$search%'")->result_array();
        //echo $this->db->last_query();die;
        }else{
			$checkData = $this->db->select("id,title,soundImg,concat('".base_url()."',sound) as sound")->get('sounds')->result_array();
        }
		if(!empty($checkData)){
        	foreach($checkData as $deta){
            	$deta['favStatus'] = empty($this->db->get_where("favouriteSoundList",array('userId'=>$this->input->post('userId'),'soundId'=>$deta['id'],'status'=>'1'))->row_array())?'0':'1';
            	if(!empty($deta['soundImg'])){
                	$deta['soundImg'] = base_url().$deta['soundImg'];
                }
            	else{
           			$deta['soundImg'] = base_url().'uploads/logo/logo3.png';
                }
            	$dd[] = $deta;
            }
			$message['success'] = '1';
			$message['message'] = 'Details found successfully';
			$message['details'] = $dd;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Details not found';
		}
		echo json_encode($message);
	}

	public function getUserSoundFavoriteList(){
		// $checkData = $this->db->select("favouriteSoundList.*,sounds.title,concat('".base_url()."',sounds.sound) as sound")->join("sounds","sounds.id=favouriteSoundList.soundId","left")->get_where("favouriteSoundList",array('userId'=>$this->input->post('userId'),'status'=>'1'))->result_array();
		// $path = base_url();
		$userId = $this->input->post('userId');
		$query = $this->db->query("SELECT favouriteSoundList.*,soundImg,sounds.title,concat('".base_url()."',sounds.sound) as sound FROM `favouriteSoundList` left join sounds on sounds.id = favouriteSoundList.soundId WHERE favouriteSoundList.userId = $userId and favouriteSoundList.status = '1'");
		$checkData = $query->result_array();
		if(!empty($checkData)){
			$message['success'] = '1';
			$message['message'] = 'Details found successfully';
        	foreach($checkData as $list){
            	if(!empty($list['soundImg'])){
                	$list['soundImg'] = base_url().$list['soundImg'];
                }
            	else{
           			$list['soundImg'] = base_url().'uploads/logo/logo3.png';
                }
				$message['details'][] = $list;
            }
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Details not found';
		}
		echo json_encode($message);
	}

	public function userBlock(){
		$checkBlock = $this->db->get_where('blockUser',array('userId' => $this->input->post('userId'),'blockUserId' => $this->input->post('blockUserId')))->row_array();
		if(!empty($checkBlock)){
			$this->db->delete('blockUser',array('id' => $checkBlock['id']));
			$message['success'] = '1';
			$message['message'] = 'user unblock successfully';
		}
		else{
			$data['userId'] = $this->input->post('userId');
			$data['blockUserId'] = $this->input->post('blockUserId');
			$data['created'] = date('Y-m-d H:i:s');
			$insert = $this->db->insert('blockUser',$data);
			if(!empty($insert)){
				$this->db->delete('userFollow',array('userId' => $this->input->post('userId'),'followingUserId' => $this->input->post('blockUserId')));
				$this->db->delete('userFollow',array('userId' => $this->input->post('blockUserId'),'followingUserId' => $this->input->post('userId')));
				$message['success'] = '1';
				$message['message'] = 'user block successfully';
			}
			else{
				$message['success'] = '0';
				$message['message'] = 'Please try after some time';
			}
		}
		echo json_encode($message);
	}

	public function commentReply(){
		$data['userId'] = $this->input->post('userId');
		$data['videoId'] = $this->input->post('videoId');
		$data['commentId'] = $this->input->post('commentId');
		$data['comment'] = $this->input->post('comment');
		$data['created'] = date('Y-m-d H:i:s');
		$insert = $this->db->insert('videoSubComment',$data);
		if(!empty($insert)){
			$lastId = $this->db->insert_id();
			$checkCommentCount = $this->db->get_where('userVideos',array('id' =>$this->input->post('videoId')))->row_array();
			$upComment['commentCount'] = $checkCommentCount['commentCount'] + 1;
			$this->Common_Model->update('userVideos',$upComment,'id', $this->input->post('videoId'));
			$userDetails = $this->db->get_where('users',array('id' =>$this->input->post('userId')))->row_array();
			if(empty($userDetails['userImage'])){
				$data1['userImage'] =  base_url().'uploads/no_image_available.png';
			}





        $lists = $this->Common_Model->getMainCommentsVideos($this->input->post('userId'),$this->input->post('videoId'),$this->input->post('commentId'));
		if(empty($lists['userImage'])){
			$lists['userImage'] = base_url().'uploads/no_image_available.png';
		}
		$lists['created'] = $this->getTime($lists['created']);
		$likeCount = $this->db->get_where('videoCommentsLikeOrUnlike', array('commentId' => $lists['id'],'status'=> '1'))->num_rows();
		$likeStatus = $this->db->get_where('videoCommentsLikeOrUnlike', array('commentId' => $lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
		if(!empty($likeCount)){
			$lists['likeCount'] = (string)$likeCount;
		}
		else{
			$lists['likeCount'] = '0';
		}
		if(!empty($likeStatus)){
			$lists['likeStatus'] = true;
		}
		else{
			$lists['likeStatus'] = false;
		}
		$getSubComment =  $this->Common_Model->getSubComment($lists['id']);
		if(!empty($getSubComment)){
			foreach($getSubComment as $getSubComments ){
				if(empty($getSubComments['userImage'])){
					$getSubComments['userImage'] = base_url().'uploads/no_image_available.png';
				}
				$getSubComments['created'] = $this->getTime($getSubComments['created']);
				$lists['subComment'][] = $getSubComments;
			}
		}
		else{
			$lists['subComment'] = [];
		}




		$commentCountget  = $upComment['commentCount'] ;
			$message['success'] = '1';
			$message['message'] = 'comment send successfully';
			$message['commentCount'] = (string)$commentCountget;
			$message['details'][] = $lists;
		}else{
			$message['success'] = '0';
			$message['message'] = 'please try after some time';
		}
		echo json_encode($message);
	}

	public function userSearchApi($search,$userId){
		$userLists =  $this->Common_Model->userSearch($search,$userId);
		if(!empty($userLists)){
			foreach($userLists as $userList){
				if(empty($userList['image'])){
					$userList['image'] = base_url().'uploads/no_image_available.png';
				}
				$checkStataus = $this->db->get_where('userFollow',array('userId' => $userList['id'],'followingUserId' => $this->input->post('userId'),'status' => '1'))->num_rows();
				$checkStataus1 = $this->db->get_where('userFollow',array('userId' => $this->input->post('userId'),'followingUserId' => $userList['id'],'status' => '1'))->num_rows();
				if(!empty($checkStataus) && !empty($checkStataus1)){
				  $userList['followStatus'] = '3';
				}elseif(!empty($checkStataus) && empty($checkStataus1)){
					$userList['followStatus'] = '2';
				}elseif(empty($checkStataus) && !empty($checkStataus1)){
					$userList['followStatus'] = '1';
				}else{
					$userList['followStatus'] = '0';
				}
				$finalUserList[] = $userList;
			}
		}
		else{
			$finalUserList = [];
		}
		return $finalUserList;
	}

	public function videoSearchApi($search,$userId){
		$userLists =  $this->Common_Model->videoSearch($search,$userId);
		if(!empty($userLists)){
			foreach($userLists as $userList){
				if(!empty($userList['downloadPath'])){
					$userList['downloadPath'] = $userList['downloadPath'];
				}
				else{
					$userList['downloadPath'] = $userList['videoPath'];
				}
				if(!empty($userList['hashTag'])){
					$userList['hashtagTitle'] = $this->hashTagName($userList['hashTag']);
				}
				else{
					$userList['hashtagTitle'] = '';
				}
				if(empty($userList['image'])){
					$userList['image'] = base_url().'uploads/no_image_available.png';
				}
				$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' =>$userList['id'],'userId'=> $userId,'status'=> '1'))->row_array();
				if(!empty($likeStatus)){
					$userList['likeStatus'] = true;
				}
				else{
					$userList['likeStatus'] = false;
				}
				$finalVideoList[] = $userList;
			}
		}
		else{
			$finalVideoList = [];
		}
		return $finalVideoList;
	}

	public function userHashTagApi($search,$userId){
		$getHashtag =  $this->Common_Model->gethashTag($search);
		if(!empty($getHashtag)){
			$finalHashTag = $getHashtag;
		}
		else{
			$finalHashTag = [];
		}
		return $finalHashTag;
	}

	public function userSoundApi($search,$userId){
		$getSoundList =  $this->Common_Model->getSoundListApi($search);
		if(!empty($getSoundList)){
			foreach($getSoundList as $getSoundLists){
				$favorites = $this->db->get_where('favouriteSoundList',array('userId' => $userId,'soundId' => $getSoundLists['id'],'status' => '1'))->row_array();
				if(!empty($favorites['status'])){
					$getSoundLists['favoritesStatus'] = $favorites['status'];
				}
				else{
					$getSoundLists['favoritesStatus'] = '0';
				}
				$finalSoundList[] = $getSoundLists;
			}
		}
		else{
			$finalSoundList = [];
		}
		return $finalSoundList;
	}

	public function search(){
		$type = $this->input->post('type');
		$search = $this->input->post('search');
		$userId = $this->input->post('userId');
		if($type == '1'){
			$finalUserList = $this->userSearchApi($search,$userId);
			$finalVideoList = [];
			$finalHashTag = [];
			$finalSoundList = [];
		}
		elseif($type == '2'){
			$finalUserList = [];
			$finalVideoList = $this->videoSearchApi($search,$userId);
			$finalHashTag = [];
			$finalSoundList = [];
		}
		elseif($type == '3'){
			$finalUserList = [];
			$finalVideoList = [];
			$finalHashTag = $this->userHashTagApi($search,$userId);
			$finalSoundList = [];
		}
		elseif($type == '4'){
			$finalUserList = [];
			$finalVideoList = [];
			$finalHashTag = [];
			$finalSoundList = $this->userSoundApi($search,$userId);
		}
		else{
			$finalUserList = $this->userSearchApi($search,$userId);
			$finalVideoList = $this->videoSearchApi($search,$userId);
			$finalHashTag = $this->userHashTagApi($search,$userId);
			$finalSoundList = $this->userSoundApi($search,$userId);
		}
		if(!empty($finalUserList) || !empty($finalVideoList) || !empty($finalHashTag) || !empty($finalSoundList)){
			$list['peopleList'] = $finalUserList;
			$list['videoList'] = $finalVideoList;
			$list['hasTagList'] = $finalHashTag;
			$list['soundList'] = $finalSoundList;
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $list;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'List not found';
		}
		echo json_encode($message);
	}

	public function blockList(){
		$userLists =  $this->Common_Model->blockListUser($this->input->post('userId'));
		if(!empty($userLists)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			foreach($userLists as $userList){
				if(empty($userList['image'])){
					$userList['image'] = base_url().'uploads/no_image_available.png';
				}
				$message['details'][] = $userList;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'List not found';
		}
		echo json_encode($message);
	}

	public function showFollowingUserStatus(){
		$checkStatus = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		if($checkStatus['followingUser'] == 1){
			$upStatus['followingUser'] = 0;
			$status = true;
		}
		else{
			$upStatus['followingUser'] = 1;
			$status = false;
		}
		$update = $this->Common_Model->update('users',$upStatus,'id',$this->input->post('userId'));
		if(!empty($update)){
			$message['success'] = '1';
			$message['message'] = 'follow Status update successfully';
			$message['status'] = $status;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function showProfilePhotoStatus(){
		$checkStatus = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		if($checkStatus['profilePhotoStatus'] == 1){
			$upStatus['profilePhotoStatus'] = 0;
			$status = false;
		}
		else{
			$upStatus['profilePhotoStatus'] = 1;
			$status = true;
		}
		$update = $this->Common_Model->update('users',$upStatus,'id',$this->input->post('userId'));
		if(!empty($update)){
			$message['success'] = '1';
			$message['message'] = 'Profile Photo Status update successfully';
			$message['status'] = $status;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function hashtag(){
		$getHashtag =  $this->Common_Model->gethashTag($this->input->post('search'));
		if(!empty($getHashtag)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $getHashtag;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No details found';
		}
		echo json_encode($message);
	}


	public function soundVideos(){
		$getSound = $this->db->get_where('sounds',array('id' => $this->input->post('soundId')))->row_array();
		$getVideo = $this->db->order_by('id','asc')->get_where('userVideos',array('soundId' => $this->input->post('soundId')))->row_array();
		if(!empty($getVideo)){
			$favorites = $this->db->get_where('favouriteSoundList',array('userId' => $this->input->post('userId'),'soundId' => $this->input->post('soundId'),'status' => '1'))->row_array();
			if(!empty($getSound['userId'])){
				$userDetails = $this->db->get_where('users',array('id' => $getSound['userId']))->row_array();
				$userInfo['username'] = $userDetails['username'];
				$userInfo['name'] = $userDetails['name'];
				$userInfo['followers'] = $userDetails['followerCount'];
				if(empty($userDetails['image'])){
					$userInfo['image'] = base_url().'uploads/no_image_available.png';
				}
				else{
					$userInfo['image'] = $userDetails['image'];
				}
			}
			else{
				$userInfo['username'] = '';
				$userInfo['name'] = '';
				$userInfo['image'] = '';
				$userInfo['followers'] = '';
			}
			$userInfo['soundTitle'] = $getSound['title'];
			$userInfo['soundPath'] = base_url().$getSound['sound'];
			$userInfo['videoCount'] = $getSound['soundCount'];
			$userInfo['description'] = $getVideo['description'];
			$userInfo['allowComment'] = $getVideo['allowComment'];
			$userInfo['allowDuetReact'] = $getVideo['allowDuetReact'];
			$userInfo['allowDownloads'] = $getVideo['allowDownloads'];
			$userInfo['viewVideo'] = $getVideo['viewVideo'];
			$userInfo['soundId'] = $getVideo['soundId'];
			$userInfo['commentCount'] = $getVideo['commentCount'];
			$userInfo['viewCount'] = $getVideo['viewCount'];
			$userInfo['likeCount'] = $getVideo['likeCount'];
			$userInfo['id'] = $getVideo['id'];
			$userInfo['userId'] = $getVideo['userId'];
			$userInfo['videoPath'] =$getVideo['videoPath'];
			if(!empty($favorites['status'])){
				$userInfo['favoritesStatus'] = $favorites['status'];
			}
			else{
				$userInfo['favoritesStatus'] = '0';
			}

			$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' =>$getVideo['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
			if(!empty($likeStatus)){
				$userInfo['likeStatus'] = true;
			}
			else{
				$userInfo['likeStatus'] = false;
			}
			$userInfo['hashTag'] = $getVideo['hashTag'];
			if(!empty($getVideo['hashTag'])){
				$userInfo['hashtagTitle'] = $this->hashTagName($getVideo['hashTag']);
			}
			else{
				$userInfo['hashtagTitle'] = '';
			}



			$videoId = $getVideo['id'];
			$soundId = $this->input->post('soundId');
			$userId = $this->input->post('userId');

			$list =  $this->db->query("SELECT sounds.title as soundTitle,sounds.id as soundId,sounds.soundCount as videoCount,sounds.sound as soundPath,sounds.type as soundType, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath, userVideos.allowComment, userVideos.allowDownloads, userVideos.viewVideo, userVideos.viewCount, userVideos.likeCount, userVideos.commentCount,userVideos.viewCount,userVideos.allowDuetReact FROM `userVideos` left join sounds on sounds.id = userVideos.soundId where userVideos.id !=  $videoId and userVideos.soundId = $soundId and userVideos.userId NOT IN (select blockUserId from blockUser where userId = $userId  ) ORDER BY userVideos.id ASC LIMIT  0 , 10")->result_array();
			if(!empty($list)){
				foreach($list as $lists){
					$userDetails = $this->db->get_where('users',array('id' => $lists['userId']))->row_array();
					$lists['name'] = $userDetails['name'];
					$lists['username'] = $userDetails['username'];
					$lists['followers'] = $userDetails['followerCount'];
					if(empty($userDetails['image'])){
						$lists['image'] = base_url().'uploads/no_image_available.png';
					}
					else{
						$lists['image'] = $userDetails['image'];
					}

					$lists['hashtag'] = $lists['hashtag'];
					if(!empty($lists['hashtag'])){
						$lists['hashtagTitle'] = $this->hashTagName($lists['hashtag']);
					}
					else{
						$lists['hashtagTitle'] = '';
					}

					if(!empty($favorites['status'])){
						$lists['favoritesStatus'] = $favorites['status'];
					}
					else{
						$lists['favoritesStatus'] = '0';
					}


					$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' =>$lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
					if(!empty($likeStatus)){
						$lists['likeStatus'] = true;
					}
					else{
						$lists['likeStatus'] = false;
					}

					$lists['soundPath'] = base_url().$lists['soundPath'];
					$finalSoundList[] = $lists;
				}
			}
			else{
				$finalSoundList = [];
			}
			$finalUserINfo[] = $userInfo;
			$finalListSound = array_merge($finalUserINfo,$finalSoundList);

			$array['soundInfo'] = $userInfo;
			$array['soundVideo'] = $finalListSound;
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $array;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No list found';
		}
		echo json_encode($message);

	}




	public function hashTagVideos(){
		$getHashTag = $this->db->get_where('hashtag',array('id' => $this->input->post('hashtagId')))->row_array();
		$getVideo = $this->Common_Model->getHashTagVideos($this->input->post('hashtagId'));
		if(!empty($getVideo)){
			$favorites = $this->db->get_where('favouriteHashTagList',array('userId' => $this->input->post('userId'),'hashtagId' => $this->input->post('hashtagId'),'status' => '1'))->row_array();
			$getSound = $this->db->get_where('sounds',array('id' => $getVideo['soundId']))->row_array();
			if(!empty($getHashTag['userId'])){
				$userDetails = $this->db->get_where('users',array('id' => $getHashTag['userId']))->row_array();
				$userInfo['username'] = $userDetails['username'];
				$userInfo['name'] = $userDetails['name'];
				$userInfo['followers'] = $userDetails['followerCount'];
				if(empty($userDetails['image'])){
					$userInfo['image'] = base_url().'uploads/no_image_available.png';
				}
				else{
					$userInfo['image'] = $userDetails['image'];
				}
			}
			else{
				$userInfo['username'] = '';
				$userInfo['name'] = '';
				$userInfo['image'] = '';
				$userInfo['followers'] = '';
			}
			$userInfo['soundTitle'] = $getSound['title'];
			$userInfo['soundPath'] = base_url().$getSound['sound'];
			$userInfo['videoCount'] = $getSound['soundCount'];
			$userInfo['description'] = $getVideo['description'];
			$userInfo['allowComment'] = $getVideo['allowComment'];
			$userInfo['allowDuetReact'] = $getVideo['allowDuetReact'];
			$userInfo['allowDownloads'] = $getVideo['allowDownloads'];
			$userInfo['viewVideo'] = $getVideo['viewVideo'];
			$userInfo['soundId'] = $getVideo['soundId'];
			$userInfo['commentCount'] = $getVideo['commentCount'];
			if(!empty($getVideo['downloadPath'])){
				$userInfo['downloadPath'] = $getVideo['downloadPath'];
			}
			else{
				$userInfo['downloadPath'] = $getVideo['videoPath'];
			}
			$userInfo['viewCount'] = $getVideo['viewCount'];
			$userInfo['likeCount'] = $getVideo['likeCount'];
			$userInfo['id'] = $getVideo['id'];
			$userInfo['userId'] = $getVideo['userId'];
			$userInfo['videoPath'] =$getVideo['videoPath'];
			if(!empty($favorites['status'])){
				$userInfo['favoritesStatus'] = $favorites['status'];
			}
			else{
				$userInfo['favoritesStatus'] = '0';
			}

			$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' =>$getVideo['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
			if(!empty($likeStatus)){
				$userInfo['likeStatus'] = true;
			}
			else{
				$userInfo['likeStatus'] = false;
			}
			$userInfo['hashTag'] = $getHashTag['id'];
			if(!empty($getVideo['hashTag'])){
				$userInfo['hashtagMainTitle'] = $getHashTag['hashtag'];
				$userInfo['hashtagTitle'] = $this->hashTagName($getVideo['hashTag']);
				$finalTagIds = explode(',',$getVideo['hashTag']);
				foreach($finalTagIds as $finalTagId){
					$hashArray = $this->db->get_where('hashtag',array('id' => $finalTagId))->row_array();
					if(!empty($hashArray)){
						$userInfo['hastagLists'][] = $hashArray;
					}
				}
			}
			else{
				$userInfo['hashtagMainTitle'] = '';
				$userInfo['hashtagTitle'] = '';
				$userInfo['hastagLists'] = [];
			}

			$videoId = $getVideo['id'];
			$hashtagId = $this->input->post('hashtagId');
			$userId = $this->input->post('userId');

			$list =  $this->db->query("SELECT sounds.title as soundTitle,sounds.id as soundId,sounds.soundCount as videoCount,sounds.sound as soundPath, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath, userVideos.allowComment, userVideos.allowDownloads,userVideos.viewVideo,userVideos.viewCount,userVideos.likeCount, userVideos.commentCount,userVideos.viewCount,userVideos.allowDuetReact,userVideos.downloadPath FROM `userVideos` left join sounds on sounds.id = userVideos.soundId where userVideos.id !=  $videoId and userVideos.hashTag Like '%$hashtagId%' and userVideos.userId NOT IN (select blockUserId from blockUser where userId = $userId  ) ORDER BY userVideos.id ASC LIMIT  0 , 10")->result_array();
			if(!empty($list)){
				foreach($list as $lists){
					if(!empty($lists['downloadPath'])){
						$lists['downloadPath'] = $lists['downloadPath'];
					}
					else{
						$lists['downloadPath'] = $lists['videoPath'];
					}
					$userDetails = $this->db->get_where('users',array('id' => $lists['userId']))->row_array();
					$lists['name'] = $userDetails['name'];
					$lists['username'] = $userDetails['username'];
					$lists['followers'] = $userDetails['followerCount'];
					if(empty($userDetails['image'])){
						$lists['image'] = base_url().'uploads/no_image_available.png';
					}
					else{
						$lists['image'] = $userDetails['image'];
					}

					$lists['hashtag'] = $lists['hashtag'];
					if(!empty($lists['hashtag'])){
						$lists['hashtagMainTitle'] = '';
						$lists['hashtagTitle'] = $this->hashTagName($lists['hashtag']);
						$finalTagIds = explode(',',$lists['hashtag']);
						foreach($finalTagIds as $finalTagId){
							$hashArray = $this->db->get_where('hashtag',array('id' => $finalTagId))->row_array();
							if(!empty($hashArray)){
								$lists['hastagLists'][] = $hashArray;
							}
						}
					}
					else{
						$lists['hashtagMainTitle'] = '';
						$lists['hashtagTitle'] = '';
						$lists['hastagLists'] = [];
					}

					if(!empty($favorites['status'])){
						$lists['favoritesStatus'] = $favorites['status'];
					}
					else{
						$lists['favoritesStatus'] = '0';
					}


					$likeStatus = $this->db->get_where('videoLikeOrUnlike', array('videoId' =>$lists['id'],'userId'=> $this->input->post('userId'),'status'=> '1'))->row_array();
					if(!empty($likeStatus)){
						$lists['likeStatus'] = true;
					}
					else{
						$lists['likeStatus'] = false;
					}

					$lists['soundPath'] = base_url().$lists['soundPath'];
					$finalSoundList[] = $lists;
				}
			}
			else{
				$finalSoundList = [];
			}
			$finalUserINfo[] = $userInfo;
			$finalListSound = array_merge($finalUserINfo,$finalSoundList);

			$array['hashtagInfo'] = $userInfo;
			$array['hashtagVideo'] = $finalListSound;
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $array;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No list found';
		}
		echo json_encode($message);

	}


	public function userNotificationSetting(){
		$list = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		$data['likeNotifaction'] =  $list['likeNotifaction'];
		$data['commentNotification'] =  $list['commentNotification'];
		$data['followersNotification'] =  $list['followersNotification'];
		$data['messageNotification'] =  $list['messageNotification'];
		$data['videoNotification'] =  $list['videoNotification'];
		$message['success'] = '1';
		$message['message'] = 'List found Successfully';
		$message['details'] = $data;
		echo json_encode($message);
	}

	public function updateNotificationSetting(){
		$data['likeNotifaction'] =  $this->input->post('likeNotifaction');
		$data['commentNotification'] =  $this->input->post('commentNotification');
		$data['followersNotification'] =  $this->input->post('followersNotification');
		$data['messageNotification'] =  $this->input->post('messageNotification');
		$data['videoNotification'] =  $this->input->post('videoNotification');
		$update = $this->Common_Model->update('users',$data,'id',$this->input->post('userId'));
		if($update){
			$message['success'] = '1';
			$message['message'] = 'Notification Status updated successfully';
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function hashTagName($ids){
		$exp = explode(',',$ids);
		foreach($exp as $exps){
			$hashTitile = $this->db->get_where('hashtag',array('id' => $exps))->row_array();
			$hashTati[] = $hashTitile['hashtag'];
		}
		return $finalTitle = implode(',',$hashTati);
	}

	public function contactList(){
		$myArr = $this->input->post('phoneNumber');
		//$myArr = '[{"phone_no":"01765509238"},{"phone_no":"7087772970"},{"phone_no":"9898989898"}]';
		$list = [];
		//$myArr = '{"7087772970","78987987","9876543"}';
		$contacts1 = str_replace('"', '', $myArr);
		$contacts = explode(',',$contacts1);
		foreach($contacts as $contact){
			$checkPhone = $this->db->get_where('users',array('phone' => $contact,'id !=' => $this->input->post('userId')))->row_array();
			if(!empty($checkPhone)){
				$list[] = $checkPhone;
			}
		}
		if(!empty($list)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			foreach($list as $lists){
				$finalData['userId'] = $lists['id'];
				$finalData['username'] = $lists['username'];
				$finalData['name'] = $lists['name'];
				$finalData['phone'] = $lists['phone'];
				if(empty($lists['image'])){
					$finalData['image'] = base_url().'uploads/no_image_available.png';
				}
				$checkStataus = $this->db->get_where('userFollow',array('userId' => $lists['id'],'followingUserId' => $this->input->post('userId'),'status' => '1'))->num_rows();
				$checkStataus1 = $this->db->get_where('userFollow',array('userId' => $this->input->post('userId'),'followingUserId' => $lists['id'],'status' => '1'))->num_rows();
				if(!empty($checkStataus) && !empty($checkStataus1)){
				  $finalData['followStatus'] = '3';
				}elseif(!empty($checkStataus) && empty($checkStataus1)){
					$finalData['followStatus'] = '2';
				}elseif(empty($checkStataus) && !empty($checkStataus1)){
					$finalData['followStatus'] = '1';
				}else{
					$finalData['followStatus'] = '0';
				}
				$message['details'][] = $finalData;
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'No List found';
		}
		echo json_encode($message);
	}

	public function addFavoriteHahtag(){
		if($this->input->post()){
        	$deta = $this->db->get_where("favouriteHashTagList",array('hashtagId'=>$this->input->post('hashtagId'),'userId'=>$this->input->post('userId')))->row_array();
        	$data['userId'] = $this->input->post('userId');
        	$data['hashtagId'] = $this->input->post('hashtagId');
			if(empty($deta)){
            	$data['status'] = '1';
            	$data['created'] = date("Y-m-d H:i:s");
            	$in = $this->db->insert("favouriteHashTagList",$data);
			}else{
				$data['status'] = ($deta['status']=='0')?'1':'0';
            	$data['updated'] = date("Y-m-d H:i:s");
            	$in = $this->db->update("favouriteHashTagList",$data,array('hashtagId'=>$this->input->post('hashtagId'),'userId'=>$this->input->post('userId')));
			}
        	if($in){
            	$message['success'] = '1';
				$message['message'] = 'Added to favorites';
            }else{
            	$message['success'] = '0';
				$message['message'] = 'Please try again';
            }
		}else{
			$message['message'] = 'Please enter parameters';
        }
		echo json_encode($message);
	}


	public function getFavoriteHahtag(){
		$userId = $this->input->post('userId');
		$query = $this->db->query("SELECT favouriteHashTagList.*,hashtag.hashtag FROM `favouriteHashTagList` left join hashtag on hashtag.id = favouriteHashTagList.hashtagId WHERE favouriteHashTagList.userId = $userId and favouriteHashTagList.status = '1'");
		$checkData = $query->result_array();
		if(!empty($checkData)){
			$message['success'] = '1';
			$message['message'] = 'Details found successfully';
			$message['details'] = $checkData;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Details not found';
		}
		echo json_encode($message);
	}

	public function report(){
		$list = $this->db->get_where('report')->result_array();
		if(!empty($list)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $list;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function reportUser(){
		$data['userId'] = $this->input->post('userId');
		$data['reportUserId'] = $this->input->post('reportUserId');
		$data['created'] = date('Y-m-d H:i:s');
		$data['report'] = $this->input->post('report');
		$this->db->insert('reportUser',$data);
		$message['success'] = '1';
		$message['message'] = 'user reporting successfully';
		echo json_encode($message);
	}

	public function muteNotification(){
		$checkData = $this->db->get_where('muteUserNotification',array('userId' => $this->input->post('userId'),'muteId' => $this->input->post('muteId')))->row_array();
		if(!empty($checkData)){
			if($checkData['status'] == '1'){
				$data['status'] = '0';
				$mess = 'unmute notifiation successfully';
			}
			else{
				$data['status'] = '1';
				$mess = 'mute notifiation successfully';
			}
			$data['created'] = date('Y-m-d H:i:s');
			$update = $this->Common_Model->update('muteUserNotification',$data,'id',$checkData['id']);
		}
		else{
			$data['status'] = '1';
			$data['userId'] = $this->input->post('userId');
			$data['muteId'] = $this->input->post('muteId');
			$data['created'] = date('Y-m-d H:i:s');
			$insert = $this->db->insert('muteUserNotification',$data);
			$mess = 'mute notifiation successfully';
		}
		$message['success'] = '1';
		$message['message'] = $mess;
		$message['status'] = (string)$data['status'];
		echo json_encode($message);
	}


	public function viewVideo(){
		$data['userId'] = $this->input->post('userId');
		$data['videoId'] = $this->input->post('videoId');
		$this->db->insert('viewVideo',$data);
		$getCount = $this->db->get_where('userVideos',array('id' => $this->input->post('videoId')))->row_array();
		$data1['viewCount'] = $getCount['viewCount'] +  1;
		$update = $this->Common_Model->update('userVideos',$data1,'id',$this->input->post('videoId'));
		$message['success'] = '1';
		$message['message'] = 'Count update successfully';
		echo json_encode($message);
	}

	public function problemReport(){
		$list = $this->db->get_where('problemReport')->result_array();
		if(!empty($list)){
			$message['success'] = '1';
			$message['message'] = 'List found successfully';
			$message['details'] = $list;
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function userProblemReport(){
		$data['userId'] = $this->input->post('userId');
		$data['report'] = $this->input->post('report');
		$data['created'] = date('Y-m-d H:i:s');
		$this->db->insert('problemReportUser',$data);
		$message['success'] = '1';
		$message['message'] = 'user reporting successfully';
		echo json_encode($message);
	}


	public function sendMessage(){
		require APPPATH.'/libraries/vendor/autoload.php';
		$sender_id =$this->input->post('sender_id');
		$reciver_id = $this->input->post('reciver_id');
		$message123 =  $this->input->post('message');
		$get_sender_data=$this->Common_Model->get_inbox_same_data($sender_id,$reciver_id);
		if(empty($get_sender_data)){
			$data['sender_id ']= $sender_id;
			$data['reciver_id'] = $reciver_id;
			$data['messageType'] = $this->input->post('messageType');
			if($this->input->post('messageType')=='1'){
				$data['message'] = $this->input->post('message');
				$sendResponse['image'] = '';
			}else{
				$s3 = new Aws\S3\S3Client([
						'version' => 'latest',
						'region'  => 'us-east-2',
						'credentials' => [
								'key'    => 'AKIASKU6EJBLLBL5FSOL',
								'secret' => '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'
						]
				]);
				$bucket = 'cancremedia';
				$upload = $s3->upload($bucket, $_FILES['image']['name'], fopen($_FILES['image']['tmp_name'], 'rb'), 'public-read');
				$url = $upload->get('ObjectURL');
				if(!empty($url)){
					$data['image'] = 'http://d2ufnc3urw5h1h.cloudfront.net/'.$_FILES['image']['name'];;
				}
				else{
					$data['image'] = '';
				}
				$sendResponse['image'] = $url;
			}
			$data['created'] = date('y-m-d H:i:s');
			$insert = $this->db->insert('inbox', $data);
			if(!empty($insert)){
				$insert_conversation = $this->db->insert('conversation', $data);
				$lastId = $this->db->insert_id();
				$sendResponse['id'] = $lastId;
				$sendResponse['sender_id'] = $sender_id;
				$sendResponse['reciver_id'] = $reciver_id;
				$sendResponse['message'] = $message123;
				$sendResponse['messageType'] =$this->input->post('messageType');
				$sendResponse['created'] = $data['created'];
				$sendResponse['time'] = 'just now';
				$message['success'] = '1';
				$message['message'] = 'Message send succssfully';
				$message['details'][] = $sendResponse;
			}
		}
		else{
			$data['sender_id ']= $sender_id;
			$data['reciver_id'] = $reciver_id;
			$data['messageType'] = $this->input->post('messageType');
			$data['deleteChat'] = '';
			if($this->input->post('messageType')=='1'){
				$data['message'] = $this->input->post('message');
				$sendResponse['image'] ='';
			}else{
				$s3 = new Aws\S3\S3Client([
						'version' => 'latest',
						'region'  => 'us-east-2',
						'credentials' => [
								'key'    => 'AKIASKU6EJBLLBL5FSOL',
								'secret' => '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'
						]
				]);
				$bucket = 'cancremedia';
				$upload = $s3->upload($bucket, $_FILES['image']['name'], fopen($_FILES['image']['tmp_name'], 'rb'), 'public-read');
				$url = $upload->get('ObjectURL');
				if(!empty($url)){
					$data['image'] = 'http://d2ufnc3urw5h1h.cloudfront.net/'.$_FILES['image']['name'];;
				}
				else{
					$data['image'] = '';
				}
				$sendResponse['image'] = $url;
			}
			$data['created'] = date('y-m-d H:i:s');
			$update = $this->Common_Model->update('inbox',$data,'id',$get_sender_data['id']);
			if(!empty($update)){
				$data['created'] = date('y-m-d H:i:s');
				$insert_conversation = $this->db->insert('conversation', $data);
				$lastId = $this->db->insert_id();
				$sendResponse['id'] = $lastId;
				$sendResponse['sender_id'] = $sender_id;
				$sendResponse['reciver_id'] = $reciver_id;
				$sendResponse['message'] = $message123;
				$sendResponse['messageType'] =$this->input->post('messageType');
				$sendResponse['created'] = $data['created'];
				$sendResponse['time'] = 'just now';
				$message['success'] = '1';
				$message['message'] = 'Message send succssfully';
				$message['details'][] = $sendResponse;
			}
		}
		echo json_encode($message);
 	}


	public function conversationMessage(){
		$sender_id =$this->input->post('sender_id');
		$reciver_id = $this->input->post('reciver_id');

		$changeStatus['readStatus'] = 1;
		$this->db->update('conversation',$changeStatus,array('reciver_id' => $sender_id,'sender_id' => $reciver_id));

		$datas = $this->Common_Model->get_conversation_data($sender_id,$reciver_id);
		if(!empty($datas)){
			$user = $this->db->get_where('users',array('id' => $reciver_id))->row_array();
			if(empty($user['image'])){
				$message['image'] = base_url().'uploads/no_image_available.png';
			}
			$message['username'] = $user['username'];

			foreach($datas as $data){
				$HiddenProducts = explode(',',$data['deleteChat']);
				if(!in_array($sender_id, $HiddenProducts)){
					$data['time'] = $this->getTime($data['created']);
					$conver[] = $data;
				}
			}
			if(!empty($conver)){
				$message['success'] = '1';
				$message['message'] = 'conversation message get succssfully';
				$message['details'] = $conver;
			}
			else{
				$message['success'] = '0';
				$message['message'] = 'no message data found';
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'no message data found';
		}
		echo json_encode($message);
	}


	public function deleteChat(){
		$userId = $this->input->post('userId');
		$inboxId = $this->input->post('inboxId');
		//$senderId = $this->input->post('senderId');
  	//	$receiverId = $this->input->post('receiverId');
		$getInboxList = $this->db->get_where('inbox',array('id' => $inboxId))->row_array();
		if($getInboxList['sender_id'] == $userId){
			$senderId = $userId;
	  	$receiverId = $getInboxList['reciver_id'];
		}
		else{
			$senderId = $getInboxList['reciver_id'];
	  	$receiverId = $getInboxList['sender_id'];
		}

		$list =  $this->db->query("SELECT * FROM `inbox` where sender_id = $senderId and reciver_id = $receiverId || sender_id = $receiverId and reciver_id = $senderId ")->row_array();
		if(!empty($list['deleteChat'])){
			$data['deleteChat'] = $senderId.','.$receiverId;
		}
		else{
			$data['deleteChat'] = $senderId;
		}
		$update = $this->Common_Model->update('inbox',$data,'id',$list['id']);

		$getConver = $this->Common_Model->get_conversation_data($senderId,$receiverId);
		foreach($getConver as $getConve){
			if(!empty($getConve['deleteChat'] && $getConve['deleteChat'] != $senderId)){
				$up['deleteChat'] = $senderId.','.$receiverId;
			}
			else{
				$up['deleteChat'] = $senderId;
			}
			$this->Common_Model->update('conversation',$up,'id',$getConve['id']);
		}
		$message['success'] = '1';
		$message['message'] = 'chat delete successfully';
		echo json_encode($message);
	}

	public function singleMessageDelete(){
		$userId = $this->input->post('userId');
		$conversationId = $this->input->post('conversationId');
		$getConverList = $this->db->get_where('conversation',array('id' => $conversationId))->row_array();
		if($getConverList['sender_id'] == $userId){
			$senderId = $userId;
	  	$receiverId = $getConverList['reciver_id'];
		}
		else{
			$senderId = $getConverList['reciver_id'];
	  	$receiverId = $getConverList['sender_id'];
		}
		if(!empty($getConverList['deleteChat'] && $getConverList['deleteChat'] != $senderId)){
			$up['deleteChat'] = $senderId.','.$receiverId;
		}
		else{
			$up['deleteChat'] = $senderId;
		}
		$this->Common_Model->update('conversation',$up,'id',$getConverList['id']);
		$message['success'] = '1';
		$message['message'] = 'Message delete successfully';
		echo json_encode($message);
	}

	public function inbox(){
		$sender_id= $this->input->post('sender_id');
		$datas = $this->Common_Model->get_inbox_data($sender_id);
		if(!empty($datas)){
			foreach($datas as $data){
				$HiddenProducts = explode(',',$data['deleteChat']);
				if(!in_array($sender_id, $HiddenProducts)){
					$data['time'] = $this->getTime($data['created']);
					if($data['sender_id'] == $sender_id){
						$mainId = $data['reciver_id'];
						$user=$this->Common_Model->get_data_by_id('users','id',$data['reciver_id']);
					}
					else{
						$mainId = $data['sender_id'];
						$user=$this->Common_Model->get_data_by_id('users','id',$data['sender_id']);
					}
					if(empty($user['image'])){
						$data['image'] = base_url().'uploads/no_image_available.png';
					}
					else{
						$data['image'] = $user['image'];
					}
					$countMessage = $this->db->get_where('conversation',array('reciver_id' => $this->input->post('sender_id'),'sender_id' => $mainId,'readStatus' => 0))->num_rows();
					if(!empty($countMessage)){
						$data['messageCount'] = $countMessage;
					}
					else{
						$data['messageCount'] = '0';
					}

					$data['username'] = $user['username'];
					$data['name'] = $user['name'];
					$inboxDetails[] = $data;
				}
			}

			if(!empty($inboxDetails)){
				$message['success'] = '1';
				$message['message'] = 'inbox message get succssfully';
				$message['details'] = $inboxDetails;
			}
			else{
				$message['success'] = '0';
				$message['message'] = 'no message data found';
			}
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'no message data found';
		}
		echo json_encode($message);
	}


	public function sawanNotifactionTesting(){
			$registrationIds =  array('cV7gLlU6WvU:APA91bH1LCMMPI0qTyHUCX-pR1hkhY_OXT9GpyZqKUAz3vTE6xnBhQ5nEMSMGsggh0RA_Nl9ZUMOB4YWVcXvISXlXKy-o04SS-kedGaxc_XcQDHKRHSqhObeX7guTlNBZWk7Ethqfh-4');
			define('API_ACCESS_KEY', 'AAAAgSo3kQs:APA91bG0vlTjQ2JAJ8xuVXVrSnAcHYrHV31-xwIT3X6b7Gvo6oCoNaf0LkG6koZrzTUr2QpSQ8ZZUT4sbfk71wSNDNr4gkINuudR0humcpkivp-l6U1HG_bpNYKJirdpr8Miw6UctU-z');
			 $msg = array(
				 'message' 	=> "Welcome to Rockstarss",
				 'title'		=> 'Rockstarss',
				 'type'		=> "video",
				 'subtitle'	=> "video",
				 'loginId' => '1',
				 'userId' => '2',
				 'vibrate'	=> 1,
				 'sound'		=> 1,
				 'largeIcon'	=> 'large_icon',
				 'smallIcon'	=> 'small_icon',
			 );
			 $fields = array(
				 'registration_ids' 	=> $registrationIds,
				 'data'			=> $msg
			 );
			 $headers = array(
				 'Authorization: key=' . API_ACCESS_KEY,
				 'Content-Type: application/json'
			 );
			 $curl = curl_init();

			 curl_setopt_array($curl, array(
				 CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
				 CURLOPT_RETURNTRANSFER => true,
				 CURLOPT_CUSTOMREQUEST => "POST",
				 CURLOPT_POSTFIELDS => json_encode($fields),
				 CURLOPT_HTTPHEADER => $headers

			 ));

			 $response = curl_exec($curl);
			 print_r($response);
			 die;
			 $err = curl_error($curl);
			 curl_close($curl);

	}

	public function uploadPanAndAadhar(){
		require APPPATH.'/libraries/vendor/autoload.php';
		$data['userId'] = $this->input->post('userId');
		$data['panAadharNumber'] = $this->input->post('panAadharNumber');
		$data['type'] = $this->input->post('type');
		$s3 = new Aws\S3\S3Client([
				'version' => 'latest',
				'region'  => 'us-east-2',
				'credentials' => [
						'key'    => 'AKIASKU6EJBLLBL5FSOL',
						'secret' => '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'
				]
		]);
		$bucket = 'cancremedia';

		$upload = $s3->upload($bucket, $_FILES['image']['name'], fopen($videoMainPath, 'rb'), 'public-read');
		$url = $upload->get('ObjectURL');
		if(!empty($url)){
			$data['image'] = 'http://d2ufnc3urw5h1h.cloudfront.net/'.$_FILES['image']['name'];
		}
		else{
			$data['image'] = '';
		}
		$insert = $this->db->insert('userPanAndAadharCard',$data);
		if(!empty($insert)){
			$message['success'] = '1';
			$message['message'] = 'Infomation upload Successfully';
		}
		else{
			$message['success'] = '0';
			$message['message'] = 'Please try after some time';
		}
		echo json_encode($message);
	}

	public function getVerifyStatus(){
		// 1 accept
		// 2 pending
		// 3 reject
		$checkStatus = $this->db->get_where('users',array('id' => $this->input->post('userId')))->row_array();
		$message['success'] = '1';
		$message['message'] = 'infomation get Successfully';
		$message['status'] = '1';
		echo json_encode($message);
	}





}
