<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class COMMON_MODEL extends CI_Model {
    public function insert_data($data,$tbl_name){
		$sql = $this->db->insert($tbl_name,$data);
		return ( $this->db->insert_id() );
	}
   public function get_data($tbl,$field,$value,$limit=0,$limit_start=0){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		if(!empty($limit)){
			$this->db->limit($limit, $limit_start);
		}

		$this->db->order_by('id','DESC');
		return $this->db->get($tbl)->result_array();
	}
	public function get_all_data($tbl){

		$this->db->order_by('id','DESC');
		return $this->db->get($tbl)->result_array();
	}


	public function update($tbl,$data,$field,$value){
		$this->db->where($field,$value);
		return $this->db->update($tbl,$data);
	}
	public function get_rows($tbl,$field=0,$value=0)	{
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		return $this->db->get($tbl)->num_rows();
	}
	public function get_data_by_id($tbl,$field=0,$value=0){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		return $this->db->get($tbl)->row_array();
	}

	public function delete($tbl,$field=0,$value=0){
		$this->db->where($field,$value);
		return $this->db->delete($tbl);
	}
	public function count_data_with_id($tbl,$field=0,$value=0){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		return $this->db->count_all_results($tbl);
	}
  public function change_status($table, $column, $value, $uniqueNameCol, $uniqueColValue)
	{
		$query = $this->db->query("UPDATE ".$table." SET `".$column."` = '".$value."' WHERE `".$uniqueNameCol."` = '".$uniqueColValue."' ");
		//echo $this->db->last_query();
	}

	public function num_data($id,$tbl)
	{
		$this->db->select('*');
		$this->db->order_by($id);
		$result = $this->db->get($tbl);
		return $result->num_rows();
	}

	public function get_b_commnt($limit , $start,$b_id ){
		$this->db->select('tbl_blog_comments.*');
		$this->db->join('tbl_blog','tbl_blog.b_id = tbl_blog_comments.b_id','left');
		$this->db->limit($limit, $start);
		$this->db->where("tbl_blog.b_id",$b_id);
		$qry = $this->db->get('tbl_blog_comments');
		// echo $this->db->last_query();
		return $qry->result_array();
	}

	public function num_comnt_data($b_id)
	{
		$this->db->select('tbl_blog_comments.*');
		$this->db->join('tbl_blog','tbl_blog.b_id = tbl_blog_comments.b_id','left');
		$this->db->where("tbl_blog.b_id",$b_id);
		$qry = $this->db->get('tbl_blog_comments');
		return $qry->num_rows();
	}

	public function get_faqs() {
		$this->db->select('*');
		$this->db->order_by('id','DESC');
		$result = $this->db->get('tbl_faqs');
		return $result->result();
	}
	public function save_faq($faq_data){
		$sql =$this->db->insert('tbl_faqs' ,$faq_data);
		return true;
	}
	public function edit_faq($faq_id){
		$this->db->select('*');
		$this->db->where('id',$faq_id);
		$result = $this->db->get('tbl_faqs');
		return $result->row();
	}
	public function update_faq($faq_id ,$news_data){
		$this->db->where("id", $faq_id);
		$sql =$this->db->update('tbl_faqs' ,$news_data);
		return true;
	}
	public function delete_faq($faq_id){
		$query = $this->db->where('id', $faq_id);
		$query = $this->db->delete('tbl_faqs');
		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}

	public function get_images($_id)
	{
		$this->db->select('*');
		$this->db->where("gallery_id",$_id);
		$result = $this->db->get('tbl_images');
		// echo $this->db->last_query();
		return $result->result();
	}
	 public function get_datas_search($tbl,$field,$value,$limit=0,$limit_start=0,$searh_query){
		if(!empty($field)){
			$this->db->where($field,$value);
		}
		if(!empty($searh_query))
		{
			$this->db->like('user_group', $searh_query);
		}
		if(!empty($limit)){
			$this->db->limit($limit, $limit_start);
		}
		return $this->db->get($tbl)->result_array();
	}
		 public function get_datas_searches($tbl,$limit=0,$limit_start=0,$searh_query){

		if(!empty($searh_query))
		{
			$this->db->like('user_group', $searh_query);
		}
		if(!empty($limit)){
			$this->db->limit($limit, $limit_start);
		}
		return $this->db->get($tbl)->result_array();
	}

	public function getDriverDocument($ids){
		$sql = $this->db->query("SELECT p.title,d.* FROM partnershipDocument as p left join driverDocuments as d on d.patnershipDocumentID = p.id where d.driverId = $ids and p.id NOT IN('19') ");
		return $sql->result_array();
	}

	public function partnershipDocumentDetails($finalIDs){
		$sql = $this->db->query("SELECT * FROM partnershipDocument where id not in ($finalIDs) ");
		return $sql->result_array();
	}

  public function getTop10User(){
    $sql = $this->db->query("select * from userDetails ORDER BY id DESC LIMIT 8;");
    return $sql->result_array();
  }

  public function getTop10Order(){
    $sql = $this->db->query("select * from userBookingServices ORDER BY id DESC LIMIT 7;");
    return $sql->result_array();
  }
  public function deleteSubcategory($categoryId){
    $sql = $this->db->query("delete from subCategory where categoryId = $categoryId ");
    return $sql;
  }
  public function getSubCategories(){
    $sql = $this->db->query("select subCategory.*,category.name as categoryName from subCategory left join category on category.id = subCategory.categoryId order by subCategory.id asc");
    return $sql->result_array();
  }
   public function getCategorydata(){
    $sql = $this->db->query("select storyCategory.*,maincategory.title ,maincategory.image from storyCategory left join maincategory on maincategory.id = storyCategory.titleId ");
    return $sql->result_array();
  }

  public function getCoralzTitle($postion){
    $sql = $this->db->query("select * from coralz where id = $postion ");
    return $sql->result_array();
  }

  public function getUniversity(){
	  $sql = $this->db->query("SELECT countrylist.title as countryTitle,university.* FROM university left JOIN countrylist on countrylist.id = university.countryId where university.status = '0' order by university.id DESC ");
    return $sql->result_array();
  }

  public function getSchool(){
	  $sql = $this->db->query("SELECT countrylist.title as countryTitle,school.* FROM school left JOIN countrylist on countrylist.id = school.countryId where school.status = '0' order by school.id DESC ");
    return $sql->result_array();
  }

  public function getStudies(){
	  $sql = $this->db->query("SELECT countrylist.title as countryTitle,studies.* FROM studies left JOIN countrylist on countrylist.id = studies.countryId where studies.status = '0' order by studies.id DESC ");
    return $sql->result_array();
  }
  public function subCategory(){
	  $sql = $this->db->query("SELECT category.title as categoryTitle, subcategory.* FROM `subcategory` left join category on category.id = subcategory.categoryId order by subcategory.id DESC ");
    return $sql->result_array();
  }

  public function getVideos($status){
    $sql = $this->db->query("SELECT users.username,users.name,users.email,users.phone,users.image,userVideos.* FROM `userVideos` left join users on users.id = userVideos.userId where userVideos.status = 0 order by userVideos.id desc");
    return $sql->result_array();

  }

  public function serchList($startdate,$enddate,$status,$search,$todatDate){
    $data = array();
    $whre ='';
    if(!empty($status)){
      $data[] = "onlineStatus = '$status'";
    }
    if(!empty($search)){
      $data[] = "username like '%$search%'";
    }
    if(!empty($startdate) && !empty($enddate)){
      $data[] = "date(created) >= '$startdate' and date(created) <= '$enddate'";
    }
    elseif(!empty($enddate)){
      $data[] = "date(created) = '$enddate'";
    }
    elseif(!empty($startdate)){
      $data[] = "date(created) = '$startdate'";
    }

    if(!empty($data)){
      $whre = 'and '.implode(' and ', $data);
    }
    $sql = $this->db->query("SELECT * FROM `users`  where id != 0 $whre");
    return $sql->result_array();

  }

  public function countVideoComLike($useid){
    $sql = $this->db->query("SELECT count(id) as totalVideos,sum(likeCount) as totalLikeCount, sum(commentCount) as totalCommentCount, sum(viewCount) as totalViewCount FROM `userVideos` where userId = $useid group by userId");
    return $sql->row_array();
  }



	public function searchResult($end, $pname, $start, $Urifunction, $offset,$sort,$videoId){
		if($Urifunction == 'getShortVideoApprovedResult'){
			$status = '0'; $videoType = '0';
		}
		else if($Urifunction == 'getnonViewedVideoApprovedResult'){
			$status = '3'; $videoType = '0';
		}
		else if($Urifunction == 'getLongnonViewedVideoApprovedResult'){
			$status = '3'; $videoType = '1';
		}
		else if($Urifunction == 'getShortVideoRejectResult'){
			$status = '2'; $videoType = '0';
		}
		else if($Urifunction == 'getlongPendingVideoResult'){
			$status = '0'; $videoType = '1';
		}
		else if($Urifunction == 'getShortTrendingVideoApprovedResult'){
			$status = '1'; $videoType = '0';
		}
		else if($Urifunction == 'getLongTrendingVideoApprovedResult'){
			$status = '1'; $videoType = '1';
		}
		else if($Urifunction == 'getlongRejectVideoResult'){
			$status = '2'; $videoType = '1';
		}
	  	if(!empty($end) && !empty($pname) && !empty($start)){
	  		$where = "userVideos.created between '$start' and '$end' and users.username like '%$pname%' or users.email like '%$pname%' or users.phone like '%$pname%' and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
	  	elseif(!empty($start) && !empty($end)){
	  		$where = "userVideos.created between '$start' and '$end' and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
	  	elseif(!empty($start) && !empty($pname)){
	  		$where = "userVideos.created = '$start' and (users.username like '%$pname%' or users.email like '%$pname%' or users.phone like '%$pname%') and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
	  	elseif(!empty($end) && !empty($pname)){
	  		$where = "userVideos.created = '$end' and (users.username like '%$pname%' or users.email like '%$pname%' or users.phone like '%$pname%') and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
	  	elseif(!empty($start)){
	  		$where = "userVideos.created = '$start' and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
	  	elseif(!empty($end)){
	  		$where = "userVideos.created = '$end' and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}

	  	elseif(!empty($pname)){
	  		$where = "users.username like '%$pname%' and userVideos.status = '$status' and userVideos.videoType = '$videoType' or users.email like '%$pname%' and userVideos.status = '$status' and userVideos.videoType = '$videoType' or users.phone like '%$pname%' and userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
      elseif (!empty($videoId)) {
          $where = "userVideos.id = $videoId";
      }
	  	else{
	  		$where = "userVideos.status = '$status' and userVideos.videoType = '$videoType'";
	  	}
      if(!empty($sort)){
	    	$sql = $this->db->query("select userVideos.*, users.username, users.email, users.phone ,users.image,hashtag.id as di,hashtag.hashtag as hash from userVideos left join users on users.id=userVideos.userId left join hashtag on hashtag.id = userVideos.hashTag where $where order by $sort limit $offset,10 ")->result_array();
          $count = $this->db->query("select userVideos.*, users.username, users.email, users.phone ,users.image,hashtag.id as di,hashtag.hashtag as hash from userVideos left join users on users.id=userVideos.userId left join hashtag on hashtag.id = userVideos.hashTag where $where order by $sort ")->num_rows();
     }
     elseif (!empty($videoId)) {
         $sql = $this->db->query("select userVideos.*, users.username, users.email, users.phone ,users.image,hashtag.id as di,hashtag.hashtag as hash from userVideos left join users on users.id=userVideos.userId left join hashtag on hashtag.id = userVideos.hashTag WHERE $where  limit $offset,10 ")->result_array();
           $count = $this->db->query("select userVideos.*, users.username, users.email, users.phone ,users.image,hashtag.id as di,hashtag.hashtag as hash from userVideos left join users on users.id=userVideos.userId left join hashtag on hashtag.id = userVideos.hashTag where $where order by users.id ")->num_rows();
     }
     else{
       $sql = $this->db->query("select userVideos.*, users.username, users.email, users.phone ,users.image,hashtag.id as di,hashtag.hashtag as hash from userVideos left join users on users.id=userVideos.userId left join hashtag on hashtag.id = userVideos.hashTag where $where  limit $offset,10 ")->result_array();
         $count = $this->db->query("select userVideos.*, users.username, users.email, users.phone ,users.image,hashtag.id as di,hashtag.hashtag as hash from userVideos left join users on users.id=userVideos.userId left join hashtag on hashtag.id = userVideos.hashTag where $where order by users.id ")->num_rows();
     }
 // echo $this->db->last_query();
 // die;
	    return  $data = [$sql,$count];
	}


  	public function searchUserResult($end, $pname, $start, $offset,$sort,$userId){
  		if(!empty($end) && !empty($pname) && !empty($start)){
            $where = "where created between '$start' and '$end' and username like '%$pname%' or name like '%$pname%' or email like '%$pname%' or phone like '%$pname%' ";
        }elseif(!empty($start) && !empty($end)){
           $where = "where created between '$start' and '$end' ";
        }elseif(!empty($start) && !empty($pname)){
            $where = "where created = '$start' and username like '%$pname%' or name like '%$pname%' or email like '%$pname%' or phone like '%$pname%' ";
        }elseif(!empty($end) && !empty($pname)){
            $where = "where created = '$end' and username like '%$pname%' or name like '%$pname%' or email like '%$pname%' or phone like '%$pname%' ";
        }
        elseif(!empty($start)){
            $where = "where created = '$start' ";
        }elseif(!empty($end)){
            $where = "where created = '$end' ";
        }
        elseif(!empty($pname)){
            $where = "where username like'%$pname%' or name like '%$pname%' or email like '%$pname%' or phone like '%$pname%' ";
        }elseif(!empty($userId)){
            $where = "where users.id = $userId ";
        }
        else{
            $where = "";
        }
        if(!empty($sort)){
         $sql = $this->db->query("SELECT users.*, count(userVideos.id) as totalVideos,sum(userVideos.likeCount) as totalLikeCount, sum(userVideos.commentCount) as totalCommentCount, sum(userVideos.viewCount) as totalViewCount FROM `users` LEFT JOIN userVideos ON userVideos.userId = users.id $where group by userVideos.userId ORDER BY $sort")->result_array();
       }
       elseif (!empty($userId)) {

          $sql = $this->db->query("SELECT users.*, count(userVideos.id) as totalVideos,sum(userVideos.likeCount) as totalLikeCount, sum(userVideos.commentCount) as totalCommentCount, sum(userVideos.viewCount) as totalViewCount FROM `users` LEFT JOIN userVideos ON userVideos.userId = users.id  $where group by userVideos.userId")->result_array();
       }
       else{
         $sql = $this->db->query("SELECT users.*, count(userVideos.id) as totalVideos,sum(userVideos.likeCount) as totalLikeCount, sum(userVideos.commentCount) as totalCommentCount, sum(userVideos.viewCount) as totalViewCount FROM `users` LEFT JOIN userVideos ON userVideos.userId = users.id $where group by userVideos.userId ORDER BY users.id")->result_array();

       }

        $count = $this->db->query("SELECT * from users $where")->num_rows();
        return  $data = [$sql,$count];



  	}

}
