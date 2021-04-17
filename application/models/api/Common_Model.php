<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model {

	public function register($table,$details){
		$sql = $this->db->insert($table , $details);
		return true;
	}

	public function check_user($table,$coloum,$value){
		$this->db->where($coloum,$value);
		$query = $this->db->get($table);
		return $query->row_array();
	}

	public function update_user($details){
		$this->db->where('username',$details['username']);
		$this->db->where('password',$details['password']);
		if($this->db->update('users', $details)) {
			return true;
		} else {
			return false;
		}
	}

	public function login($details){
		$this -> db -> select('*');
	    $this -> db -> from('users');
	    $this -> db -> where('username', $details['username']);
	    $this -> db -> where('password', $details['password']);
	    $this -> db -> limit(1);
	    $query = $this -> db -> get();
	    if($query -> num_rows() == 1){
			return $query->row_array();
	    }
	    else{
			return false;
	    }
	}

	public function get_data($tbl,$column,$limit=0,$limit_start=0){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		if(!empty($limit)){
			$this->db->limit($limit, $limit_start);
		}

		$this->db->order_by($column,'DESC');
		return $this->db->get($tbl)->result_array();
	}

	public function get_data_by_id($tbl,$field=0,$value=0){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		return $this->db->get($tbl)->row_array();
	}

	public function get_services_by_id($tbl,$field=0,$value=0){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		return $this->db->get($tbl)->result_array();
	}


	public function insert_data($data,$tbl_name){
		$sql = $this->db->insert($tbl_name,$data);
		return ( $this->db->insert_id() );
	}

	public function check_email($email){
		$sql =  $this->db->query ("SELECT * from userDetails where email = '$email'");
		return $sql->row_array();
	}

	public function getCategories(){
		$sql =  $this->db->query ("SELECT `title` FROM `categories` ");
		return $sql->result_array();
	}

	public function update($tbl,$data,$field,$value){
		$this->db->where($field,$value);
		return $this->db->update($tbl,$data);
	}

	public function check_password($oldpass,$user_id){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('password', $oldpass );
		$this->db->where('id', $user_id );
		$query = $this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$row = $query->row_array();
			return $row;
		}
	}

	public function change_password($new_password,$id){
		$data=array('password'=>$new_password);
		$this->db->where('id',$id);
		$update = $this->db->update('userDetails',$data);
		if($update){
			return true;
	    }
	    else{
			return false;
	    }
	}

	public function like_data_get($details){
		$sql = $this->db->query ("SELECT * from `like` Where id IN (".$details.")");
		return $sql->result_array();
	}

	public function instruments_data_get($details){
		$sql = $this->db->query ("SELECT * from `instruments` Where id IN (".$details.")");
		return $sql->result_array();
	}

	public function get_user_data($search){
		$this->db->select('*');
		$this->db->from('users');
		if(!empty($search))
		{
			$query = $this->db->where("name LIKE '%$search%'")->get();
		}
		else
		{
			$query = $this->db->get();
		}
		if($query->num_rows() > 0){
			return $query->result_array();
	    }
	    else{
			return false;
	    }
	}

	public function get_providers($id){
		$sql = $this->db->query('select p.*,pr.business_name as business_name,pr.business_phone as business_phone from provider_business_details as p left join provider as pr on p.provider_id=pr.id where p.id = "'.$id.'"');
		return $sql->row_array();
	}

	public function provider_business_data($latitude,$longitude){
		$sql = $this->db->query("select *,(((acos(sin((".$latitude."*pi()/180)) * sin((business_latitude*pi()/180))+cos((".$latitude."*pi()/180)) * cos((business_latitude*pi()/180)) * cos(((".$longitude."- business_longitude)* pi()/180))))*180/pi())*60*1.1515) as distance from provider_business_details");
		return $sql->result_array();
	}
	public function distance_provider_user($id,$latitude,$longitude){
		$sql = $this->db->query("select *,(((acos(sin((".$latitude."*pi()/180)) * sin((business_latitude*pi()/180))+cos((".$latitude."*pi()/180)) * cos((business_latitude*pi()/180)) * cos(((".$longitude."- business_longitude)* pi()/180))))*180/pi())*60*1.1515) as distance from provider_business_details where provider_id ='".$id."'");
		return $sql->row_array();
	}

	public function provider_business_and_services_data($provider_business_id){
		$this->db->select('*');
		$this->db->from('provider_services');
		$this->db->where('provider_id',$provider_business_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function user_favourite_salon($user_id,$latitude,$longitude){
		$sql =  $this->db->query ("SELECT p.*,(((acos(sin((".$latitude."*pi()/180)) * sin((p.business_latitude*pi()/180))+cos((".$latitude."*pi()/180)) * cos((p.business_latitude*pi()/180)) * cos(((".$longitude."- p.business_longitude)* pi()/180))))*180/pi())*60*1.1515) as distance, f.user_id,f.salon_id,f.status from provider_business_details as p left join user_favourite_salon as f on  f.salon_id = p.id where f.user_id = $user_id and f.status = '1'");
		return $sql->result_array();
	}

	public function get_provider_request_list(){
		$sql = $this->db->query('select * from user_book_services where status = 1 order by created asc');
		// $sql = $this->db->query('select s.*,u.name as user_name,u.image as user_image,p.service_name from user_book_services as s left join users as u on u.id = s.user_id left join provider_services as p on p.id = s.services_id where s.status = 1 ');
		return $sql->result_array();
	}
	public function update_booking_services($data,$bid,$uid){
		$this->db->where('user_id',$uid);
		$this->db->where('business_id',$bid);
		return $this->db->update('user_book_services',$data);

	}
	public function getHotOfferSalonServices(){
		$this->db->select("*");
		$this->db->from("provider_services");
		$this->db->order_by("discount", "desc");
		$this->db->limit(8);
		$this->db->group_by('provider_id');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getOtherImages($userId){
		$sql = $this->db->query("select userId from userStory where userId != '$userId' group by userId");
		return $sql->result_array();
	}
	public function getUserImages($userId){
		$sql = $this->db->query("select userId from userStory where userId != '$userId' group by userId");
		return $sql->result_array();
	}

	public function getLoginUserStory($userId){
		$sql = $this->db->query("select image from userStory where userId = '$userId' and image != '' ");
		return $sql->result_array();
	}

	public function otherUserDetails($userId){
		$sql = $this->db->query("select id,firstName,image from userDetails where id != '$userId'");
		return $sql->result_array();
	}
	public function getAllPosts($userId){
		$sql = $this->db->query("select userPosts.*,userDetails.firstName,userDetails.lastName,userDetails.image as userImage,userLikePost.likeStatus from userPosts left join userDetails on userDetails.id = userPosts.userId left join userLikePost on (userLikePost.postId = userPosts.id AND userLikePost.userId = $userId) where userPosts.status = '1'");
		return $sql->result_array();
	}

	public function getNumberList(){
		$sql = $this->db->query("select phone,about,image from userDetails");
		return $sql->result_array();
	}
	public function getAllPostsComment($postId){
		$sql = $this->db->query("select userCommentPost.*,userDetails.firstName,userDetails.lastName,userDetails.image as userImage from userCommentPost left join userDetails on userDetails.id = userCommentPost.userId where userCommentPost.postId = '".$postId."'");
		return $sql->result_array();
	}

	public function getAuctionsSubCategory($userId,$categoryId){
		$sql = $this->db->query("select auctionsSubCategory.*,auctionsCategory.title as categoryTitle from auctionsSubCategory left join auctionsCategory on auctionsCategory.id = auctionsSubCategory.auctionsCategoryId where auctionsSubCategory.auctionsCategoryId = '".$categoryId."' AND auctionsSubCategory.userId NOT IN ($userId) order by auctionsSubCategory.id desc ");
		return $sql->result_array();
	}

	public function getFishSubCategory($userId,$categoryId){
		$sql = $this->db->query("select fishSubCategory.*,fishCategory.title as categoryTitle from fishSubCategory left join fishCategory on fishCategory.id = fishSubCategory.fishCategoryId where fishSubCategory.fishCategoryId = '".$categoryId."' AND fishSubCategory.userId NOT IN ($userId) order by fishSubCategory.id desc ");
		return $sql->result_array();
	}

	public function getBuyItNowCategory($userId,$categoryId){
		$sql = $this->db->query("select buyItNowSubCategory.*,buyItNowCategory.title as categoryTitle from buyItNowSubCategory left join buyItNowCategory on buyItNowCategory.id = buyItNowSubCategory.buyItNowCategoryId where buyItNowSubCategory.buyItNowCategoryId = '".$categoryId."' AND buyItNowSubCategory.userId NOT IN ($userId) order by buyItNowSubCategory.id desc ");
		return $sql->result_array();
	}

	public function getAllSubCategory($userId){
		$sql = $this->db->query("select fishSubCategory.*,fishCategory.title as categoryTitle from fishSubCategory left join fishCategory on fishCategory.id = fishSubCategory.fishCategoryId where fishSubCategory.userId ='$userId' order by fishSubCategory.id desc ")->result_array();

		$sql1 = $this->db->query("select auctionsSubCategory.*,auctionsCategory.title as categoryTitle from auctionsSubCategory left join auctionsCategory on auctionsCategory.id = auctionsSubCategory.auctionsCategoryId where auctionsSubCategory.userId ='$userId' order by auctionsSubCategory.id desc ")->result_array();

			$sql3 = $this->db->query("select buyItNowSubCategory.*,buyItNowCategory.title as categoryTitle from buyItNowSubCategory left join buyItNowCategory on buyItNowCategory.id = buyItNowSubCategory.buyItNowCategoryId where buyItNowSubCategory.userId ='$userId' order by buyItNowSubCategory.id desc ")->result_array();
		$ss = array_merge($sql,$sql1,$sql3);
		return $ss;
	}

	public function aquariumsProduct($userId,$categoryId){
		$sql = $this->db->query("select aquariumsProduct.*,aquariumsSubCategory.title as categoryTitle from aquariumsProduct left join aquariumsSubCategory on aquariumsSubCategory.id = aquariumsProduct.aquariumsSubCategoryId where aquariumsProduct.aquariumsSubCategoryId = '".$categoryId."' AND aquariumsProduct.userId NOT IN ($userId) order by aquariumsProduct.id desc ");
		return $sql->result_array();
	}

	public function getEventDates($userId,$curentDate){
		$sql = $this->db->query("select startDate from event where startDate >= '".$curentDate."'  group by startDate ");
		return $sql->result_array();
	}

	public function getEventDatesWise($userId,$eventDate){
		$sql = $this->db->query("select * from event where startDate = '".$eventDate."'  ");
		return $sql->result_array();
	}

	public function bidSum($Id){
		$sql = $this->db->query("select max(bidPrice) as totalBidPrice from auctionsBid where categoryId =  $Id ");
		return $sql->row_array();
	}

	public function buyItNowbidSum($Id){
		$sql = $this->db->query("SELECT max(bidPrice) as topbidprice FROM `buyItNowBid` where categoryId =  $Id ");
		return $sql->row_array();
	}

	public function fishbidSum($Id){
		$sql = $this->db->query("SELECT max(bidPrice) as topbidprice FROM `fishBid` where categoryId =  $Id ");
		return $sql->row_array();
	}

	public function aquariumsbidSum($Id){
		$sql = $this->db->query("SELECT max(bidPrice) as topbidprice FROM `aquariumsBid` where categoryId =  $Id ");
		return $sql->row_array();
	}

	public function getLikeVideoIds($Id){
		$path = base_url();
		$sql = $this->db->query("select videoId,ownerId,users.username,users.followerCount as followers, users.name,users.image as userImage,users.video as userVideo, userVideos.*,sounds.title as soundTitle,sounds.id as soundId from videoLikeOrUnlike left join users on users.id = videoLikeOrUnlike.ownerId left join userVideos on userVideos.id = videoLikeOrUnlike.videoId left join sounds on sounds.id = userVideos.soundId  where videoLikeOrUnlike.userId = $Id and videoLikeOrUnlike.status = '1' and userVideos.status != '2' ");
		return $sql->result_array();
	}

	public function getCommentsVideos($Id,$vid){
		$path = base_url();
		$sql = $this->db->query("select videoComments.*,users.username,users.image as userImage from videoComments left join users on users.id = videoComments.userId where videoComments.videoId=$vid order by id asc");
		return $sql->result_array();
	}

	public function myVideoList($Id){
		//$sql = $this->db->query("select userVideos.*, userVideos.videoPath from  userVideos where userId = $Id");
		//$sql = $this->db->query("SELECT users.username,users.followerCount as followers,users.image,sounds.title as soundTitle,sounds.id as soundId, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath, userVideos.allowComment, userVideos.allowDownloads, userVideos.viewVideo, userVideos.viewCount, userVideos.likeCount, userVideos.commentCount FROM `userVideos` left join sounds on sounds.id = userVideos.soundId left join users on users.id = userVideos.userId where userVideos.userId = $Id ORDER BY userVideos.viewCount desc,userVideos.likeCount desc,userVideos.commentCount desc");
		//return $sql->result_array();
    	$sql = $this->db->query("SELECT users.username,users.followerCount as followers,users.image,sounds.title as soundTitle,sounds.id as soundId, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath, userVideos.allowComment, userVideos.allowDownloads, userVideos.viewVideo, userVideos.imageThumb,userVideos.status as rejectStatus,userVideos.videoType,userVideos.viewCount,  userVideos.commentCount,userVideos.downloadPath,userVideos.downloadCount, userVideos.categoryId,userVideos.allowDuetReact FROM `userVideos` left join sounds on sounds.id = userVideos.soundId left join users on users.id = userVideos.userId where userVideos.userId = $Id ORDER BY userVideos.id desc");
		return $sql->result_array();
	}

	public function myVideoList1($Id){
		$sql = $this->db->query("SELECT users.username,users.followerCount as followers,users.image,sounds.title as soundTitle,sounds.id as soundId, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath,userVideos.videoType, userVideos.allowComment, userVideos.allowDownloads, userVideos.imageThumb, userVideos.viewVideo,userVideos.status as rejectStatus, userVideos.viewCount,  userVideos.commentCount,userVideos.downloadPath,userVideos.downloadCount, userVideos.categoryId,userVideos.allowDuetReact FROM `userVideos` left join sounds on sounds.id = userVideos.soundId left join users on users.id = userVideos.userId where userVideos.userId = $Id and userVideos.viewVideo = 0 and userVideos.status != '2' ORDER BY userVideos.id desc");
		return $sql->result_array();
	}

	public function userNotification($userId,$startLimit){
		$sql = $this->db->query("select notiDate,created FROM userNotification where userId = $userId group by notiDate  order by created desc  ");
		return $sql->result_array();
	}

  	public function followerUser($userId,$search){
    	if(!empty($search)){
			$sql = $this->db->query("SELECT users.name,users.username,users.image,userFollow.* from userFollow left join users on users.id = userFollow.userId where userFollow.followingUserId = $userId and userFollow.status = '1' and (username like '@$search%' || name like '$search%') order by userFollow.id DESC");
			return $sql->result_array();
		}else{
			$sql = $this->db->query("SELECT users.name,users.username,users.image,userFollow.* from userFollow left join users on users.id = userFollow.userId where userFollow.followingUserId = $userId and userFollow.status = '1' order by userFollow.id DESC");
			return $sql->result_array();
		}
    }

  	public function followingUser($userId,$search){
		  if(!empty($search)){
			$sql = $this->db->query("SELECT users.name,users.username,users.image,userFollow.* from userFollow left join users on users.id = userFollow.followingUserId where userFollow.userId = $userId and userFollow.status = '1' and (username like '@$search%' || name like '$search%') order by userFollow.id DESC");
			return $sql->result_array();
		  }else{
			$sql = $this->db->query("SELECT users.name,users.username,users.image,userFollow.* from userFollow left join users on users.id = userFollow.followingUserId where userFollow.userId = $userId and userFollow.status = '1' order by userFollow.id DESC");
			return $sql->result_array();
		  }

    }

	public function getSubComment($commentId){
		$path = base_url();
		$sql = $this->db->query("select users.name,users.username,users.image,videoSubComment.* from videoSubComment left join users on users.id = videoSubComment.userId where commentId = $commentId ");
		return $sql->result_array();
	}

	public function userSearch($search,$userId){
		if(!empty($search)){
			$sql = $this->db->query("select id,username,name,image,followerCount from users where id NOT IN (select blockUserId from blockUser where userId = $userId ) and (username LIKE '%$search%' || name LIKE '%$search%' || email LIKE '%$search%') order by followerCount desc LIMIT 0,10");
			return $sql->result_array();
		}
		else{
			$sql = $this->db->query("select id,username,name,image,followerCount from users where id NOT IN (select blockUserId from blockUser where userId = $userId ) order by followerCount desc LIMIT 0,10");
			return $sql->result_array();
		}
	}

	public function videoSearch($search,$userId){



		$path = base_url();
		if(!empty($search)){
			$sql = $this->db->query("SELECT users.name,users.followerCount as followers, users.username, users.image, users.video, userVideos.*,sounds.title as soundTitle,sounds.id as soundId FROM `userVideos` left join users on users.id = userVideos.userId left join sounds on sounds.id = userVideos.soundId where userVideos.viewVideo = 0 and userVideos.status != '2' and userVideos.userId NOT IN (select blockUserId from blockUser where userId = $userId ) and userVideos.description LIKE '%$search%' ORDER BY userVideos.viewCount desc,userVideos.likeCount desc,userVideos.commentCount DESC LIMIT 0,10");
			return $sql->result_array();
		}
		else{
			$sql = $this->db->query("SELECT users.name,users.followerCount as followers, users.username, users.image, users.video, userVideos.*,sounds.title as soundTitle,sounds.id as soundId FROM `userVideos` left join users on users.id = userVideos.userId left join sounds on sounds.id = userVideos.soundId where userVideos.viewVideo = 0 and userVideos.status != '2' and userVideos.userId NOT IN (select blockUserId from blockUser where userId = $userId ) ORDER BY userVideos.viewCount desc,userVideos.likeCount desc,userVideos.commentCount DESC LIMIT 0,10");
			return $sql->result_array();
		}
	}

	public function blockListUser($userId){
		$sql = $this->db->query("SELECT id,name,username,image,video FROM `users` where id IN (select blockUserId from blockUser where userId = $userId) and id != $userId");
		return $sql->result_array();
	}

	public function gethashTag($search){
		if(!empty($search)){
			$sql = $this->db->query("SELECT * FROM `hashtag` where hashtag LIKE '%$search%' order by videoCount DESC LIMIT 0,20");
			return $sql->result_array();
		}
		else{
			$sql = $this->db->query("SELECT * FROM `hashtag` order by videoCount DESC  LIMIT 0,20");
			return $sql->result_array();
		}
	}

	public function getSoundVideo($soundId,$userId){
		$sql = $this->db->query("SELECT sounds.title as soundTitle,sounds.id as soundId,sounds.sound as soundFile,sounds.type as soundType,sounds.soundCount, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath, userVideos.allowComment, userVideos.allowDownloads,userVideos.viewVideo,userVideos.likeCount,userVideos.commentCount,userVideos.downloadPath FROM `userVideos` left join sounds on sounds.id = userVideos.soundId where sounds.id = 8 and userVideos.userId NOT IN (select blockUserId from blockUser where userId = $userId ) ORDER BY sounds.userId DESC");
		return $sql->result_array();
		//SELECT sounds.title as soundTitle,sounds.id as soundId,sounds.sound as soundFile,sounds.type as soundType,sounds.soundCount, userVideos.id, userVideos.userId, userVideos.hashtag, userVideos.description, userVideos.videoPath, userVideos.allowComment, userVideos.allowDownloads,userVideos.viewVideo,userVideos.likeCount,userVideos.commentCount FROM `userVideos` left join sounds on sounds.id = userVideos.soundId where sounds.id = 8 and userVideos.userId NOT IN (select blockUserId from blockUser where userId = 9 ) ORDER BY sounds.soundCount DESC,sounds.userId DESC
	}


	public function getSoundListApi($search){
		$path = base_url();
		if(!empty($search)){
			$sql = $this->db->query("SELECT sounds.*,concat('$path','',sounds.sound) as sound,concat('$path','',sounds.soundImg) as soundImg  FROM `sounds` where title LIKE '%$search%' order by soundCount DESC LIMIT 0,15");
			return $sql->result_array();
		}
		else{
			$sql = $this->db->query("SELECT sounds.*,concat('$path','',sounds.sound) as sound,concat('$path','',sounds.soundImg) as soundImg FROM `sounds` order by soundCount DESC LIMIT 0,15");
			return $sql->result_array();
		}
	}

	public function getHashTagVideos($hashtagIds){
		$sql = $this->db->query("SELECT * FROM `userVideos` where hashTag LIKE '%$hashtagIds%' and status != '2' order by id asc LIMIT 1");
		return $sql->row_array();

	}


	public function get_inbox_same_data($sender_id,$reciver_id){
		$sql =  $this->db->query ("SELECT * from inbox where `sender_id` = $sender_id and `reciver_id` = $reciver_id or `sender_id` = $reciver_id and `reciver_id` = $sender_id ");
		return $sql->row_array();
	}

	public function getMainCommentsVideos($Id,$vid,$ids){
		$path = base_url();
		$sql = $this->db->query("select videoComments.*,users.username,users.image as userImage from videoComments left join users on users.id = videoComments.userId where videoComments.videoId=$vid and videoComments.id=$ids order by id asc");
		return $sql->row_array();
	}

	public function get_conversation_data($sender_id,$reciver_id){
		$sql =  $this->db->query ("SELECT * from conversation where `sender_id` = $sender_id and `reciver_id` = $reciver_id or `sender_id` = $reciver_id and `reciver_id` = $sender_id order by id ASC");
		return $sql->result_array();
	}

	public function get_inbox_data($sender_id){
		//$sql =  $this->db->query ("SELECT * from inbox where `sender_id` = $sender_id and `reciver_id` = $reciver_id order by created desc");
		//return $sql->result_array();

		$this->db->select('*');
		$this->db->from('inbox');
		$this->db->where("sender_id",$sender_id);
		$this->db->or_where("reciver_id",$sender_id);
		$this->db->order_by('created','desc');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
	    }
	    else{
			return false;
	    }
	}

	public function getCategory($search){
		if(!empty($search)){
			$sql = $this->db->query("SELECT * FROM `category` where title LIKE '%$search%' and status = 'Approved' order by videoCount DESC");
			return $sql->result_array();
		}
		else{
			$sql = $this->db->query("SELECT * FROM `category` where status = 'Approved' order by videoCount DESC");
			return $sql->result_array();
		}
	}


}
