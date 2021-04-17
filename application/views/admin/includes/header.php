<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= $title; ?></title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->

  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <link rel="icon" href="<?php echo base_url()?>uploads/logo/cinema flex.png">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .form-error1 {
      color: #a76161;
    }
   .skin-blue .main-header .navbar {
    background-color: #7f0780;
}
.skin-blue .main-header .logo {
    background-color: #7f0780;
    color: #fff;
    border-bottom: 0 solid transparent;
}
.skin-blue .main-header .logo:hover {
    background-color: #37FDFC;
}
.skin-blue .main-header .navbar .sidebar-toggle:hover {
    background-color: #37FDFC;
}
.skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side {
    background-color: #201804;
}
.skin-blue .sidebar-menu>li.header {
    color: #4b646f;
    background: black;
}
.skin-blue .sidebar-menu>li.active>a {
    border-left-color: #e08601;
}
.skin-blue .main-header li.user-header {
    background-color: #1dc2f1;
}
.box.box-warning {
    border-top-color:#7f0780;
}
.btn-default:hover, .btn-default:active, .btn-default.hover {
    background-color: #7f0780;
    color: #fff !important;
}
.nav-tabs-custom>.nav-tabs>li.active {
    border-top-color: #e08601;
}
.box.box-primary {
    border-top-color: #e08601;
}
.btn-success {
    background-color: #7f0780;
    border-color: #7f0780;
}
.box.box-danger {
    border-top-color: #7f0780;
}
.box-header.with-border {
    background: #7f0780;
    color: #fff;
}
.main-footer {
    background: #7f0780;
    padding: 15px;
    color: #fff;
    border-top: 1px solid #d2d6de;
}
.admin-action {
    list-style: none;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333 !important;
}
div#track ul li {
    list-style-type: none;
    border-bottom: 2px solid #eee;
    padding: 8px;
    padding-left: 20px;
    color: #666;
    font-size: 14px;
    margin-top: 20px;
}
div#track ul li a {
    text-decoration: none;
    color: #666;
}
div#track ul li span {
    display: inline-block;
    float: right;
    padding-right: 10px;
}
.reposrtImage img {
    width: 50px;
    / top: 17px; /
    position: absolute;
    / right: -68px; /
    left: 0px;
}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    z-index: 3;
    color: #fff;
    cursor: default;
    background-color: #7f0780;
    border-color: #7f0780;
}

.skin-blue .main-header li.user-header {
    background-color: #7f0780;
}
i.fa.fa-edit.editIcon {
    font-size: 18px;
}

i.fa.fa-fw.fa-remove.deleteIcon {
    color: #b52929;
    font-size: 20px;
}
.box-body {
    overflow: auto;
}
	</style>


</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?= site_url();?>/admin/dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>NBF</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Natural Born Fool</b></span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
           <!--li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>

          <span class="label label-warning"><?= $newOrder;?></span>
            </a>
            <ul class="dropdown-menu">

              <li class="header"><a href="<?php echo site_url()?>/Bookings">You have total <?= $newOrders?> Orders.</a></li>
            </ul>
          </li-->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php if(!empty($admin['image'])){?>
              	<img src="<?php echo base_url(). $admin['image'];?>" class="user-image" alt="User Image">
							<?php } else{ ?>
								<img src="<?php echo base_url();?>assets/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
							<?php }?>
              <span class="hidden-xs"><?php echo $admin['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url(). $admin['image'];?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $admin['designation'];?>
                  <small><?php echo $admin['education'];?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo site_url()?>/admin/edit_profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url();?>/admin/logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
					<?php if(!empty($admin['image'])){?>
						<img src="<?php echo base_url(). $admin['image'];?>" class="img-circle" alt="User Image">
					<?php } else{ ?>
						<img src="<?php echo base_url();?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
					<?php }?>
        </div>
        <div class="pull-left info">
          <p><?php echo $admin['name']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
	  <li class="<?php if($active == 'dashboard'){ echo "active"; }?>"><a href="<?php echo site_url()?>/admin/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>


		<li class="header">ACCOUNTS MANAGEMENT</li>
        <li class="<?php if($active == 'addUser' || $active == 'user' || $active == 'userreport' || $active == 'live'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-user"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addUser'){ echo "active"; }?>"><a href="<?php echo site_url()?>/User/addUser"><i class="fa fa-circle-o"></i>Add User</a></li>
			      <li class="<?php if($active == 'user'){ echo "active"; }?>"><a href="<?php echo site_url()?>/User/manage"><i class="fa fa-circle-o"></i>View Users</a></li>
            <li class="<?php if($active == 'userreport'){ echo "active"; }?>"><a href="<?php echo site_url()?>/User/userReport"><i class="fa fa-circle-o"></i>User Report</a></li>
				    <li class="<?php if($active == 'live'){ echo "active"; }?>"><a href="<?php echo site_url()?>/User/live"><i class="fa fa-circle-o"></i>Live Streaming</a></li>
          </ul>
        </li>

       <li class="<?php if($active == 'sendmessage' || $active == 'viewmessage'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-envelope"></i> <span>Push Message</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'sendmessage'){ echo "active"; }?>"><a href="<?php echo site_url()?>/User/sendMessage"><i class="fa fa-circle-o"></i>Send Message</a></li>
            <li class="<?php if($active == 'viewmessage'){ echo "active"; }?>"><a href="<?php echo site_url()?>/User/viewMessage"><i class="fa fa-circle-o"></i>View Messages</a></li>

          </ul>
        </li>


		<li class="<?php if($active == 'addModerators' || $active == 'moderators'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Moderators</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addModerators'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Moderators/addModerators"><i class="fa fa-circle-o"></i>Add Moderators</a></li>
			<li class="<?php if($active == 'moderators'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Moderators/manage"><i class="fa fa-circle-o"></i>View Moderators</a></li>
          </ul>
        </li>
		<li class="<?php if($active == 'addSubAdmin' || $active == 'subAdmin'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-support"></i> <span>Sub Admin</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addSubAdmin'){ echo "active"; }?>"><a href="<?php echo site_url()?>/SubAdmin/addSubAdmin"><i class="fa fa-circle-o"></i>Add Sub Admin</a></li>
    			<li class="<?php if($active == 'subAdmin'){ echo "active"; }?>"><a href="<?php echo site_url()?>/SubAdmin/manage"><i class="fa fa-circle-o"></i>View Sub Admin</a></li>
         </ul>
        </li>

        <li class="header">CROWNS & GIFT</li>
    	<li class="<?php if($active == 'badges'){ echo "active"; }?> treeview">
      		<a href="#">
       			 <i class="fa fa-star"></i> <span>Crown</span>
       			 <span class="pull-right-container">
          			<i class="fa fa-angle-left pull-right"></i>
       			 </span>
      		</a>
      		<ul class="treeview-menu">
        		<li class="<?php if($active == 'badges'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Badges"><i class="fa fa-circle-o"></i>Crown</a></li>
      		</ul>
    	</li>

      <li class="<?php if($active == 'addGift' || $active == 'gift'){ echo "active"; }?> treeview">
        <a href="#">
          <i class="fa fa-gift"></i> <span>Gift</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?php if($active == 'addGift'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Gift/add"><i class="fa fa-circle-o"></i>Add Gift</a></li>
          <li class="<?php if($active == 'gift'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Gift/manage"><i class="fa fa-circle-o"></i>View Gift</a></li>
        </ul>
      </li>


      <li class="<?php if($active == 'addCoin' || $active == 'coin'){ echo "active"; }?> treeview">
        <a href="#">
          <i class="fa fa-gift"></i> <span>Coins</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?php if($active == 'addCoin'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Coins/add"><i class="fa fa-circle-o"></i>Add Coins</a></li>
          <li class="<?php if($active == 'coin'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Coins/manage"><i class="fa fa-circle-o"></i>View Coins</a></li>
        </ul>
      </li>

		<li class="header">VIDEO MANAGEMENT</li>
    <li class="<?php if($active == 'pendingVideo' || $active == 'apporveVideo' || $active == 'rejectVideo' || $active == 'trendingVideo' || $active == 'nonViewedVideo' ){ echo "active"; }?> treeview">
      <a href="#">
        <?php $countShortVIdeo = $this->db->get_where('userVideos', array('videoType' => 0))->num_rows();?>
        <i class="fa fa-video-camera"></i> <span>Short Videos  (<?php echo $countShortVIdeo; ?>)</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <?php $countShortNonViewdVIdeo = $this->db->get_where('userVideos', array('videoType' => 0,'status' => '3'))->num_rows();
          $countShortViewdVIdeo = $this->db->get_where('userVideos', array('videoType' => 0,'status' => '0'))->num_rows();
          $countShortTrendingVIdeo = $this->db->get_where('userVideos', array('videoType' => 0,'status' => '1'))->num_rows();
          $countShortRejectVIdeo = $this->db->get_where('userVideos', array('videoType' => 0,'status' => '2'))->num_rows();
        ?>
        <li class="<?php if($active == 'nonViewedVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/nonViewed"><i class="fa fa-circle-o"></i>Non Viewed Videos (<?php echo $countShortNonViewdVIdeo; ?>)</a></li>
        <li class="<?php if($active == 'pendingVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/pending"><i class="fa fa-circle-o"></i>Viewed Videos (<?php echo $countShortViewdVIdeo; ?>)</a></li>
        <li class="<?php if($active == 'trendingVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/trending"><i class="fa fa-circle-o"></i>Trending Videos (<?php echo $countShortTrendingVIdeo; ?>)</a></li>
        <li class="<?php if($active == 'rejectVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/rejected"><i class="fa fa-circle-o"></i>Rejected Videos (<?php echo $countShortRejectVIdeo; ?>)</a></li>
      </ul>
    </li>
    <li class="<?php if($active == 'longPendingVideo' || $active == 'TrandingVidoe' || $active == 'longApporveVideo' || $active == 'longRejectVideo' || $active == 'longnonViewed' ){ echo "active"; }?> treeview">
      <a href="#">
        <?php $countLongVIdeo = $this->db->get_where('userVideos', array('videoType' => 1))->num_rows();?>
        <i class="fa fa-video-camera"></i> <span>Long Videos (<?php echo $countLongVIdeo; ?>)</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <?php $countLongNonViewdVIdeo = $this->db->get_where('userVideos', array('videoType' => 1,'status' => '3'))->num_rows();
          $countLongViewdVIdeo = $this->db->get_where('userVideos', array('videoType' => 1,'status' => '0'))->num_rows();
          $countLongTrendingVIdeo = $this->db->get_where('userVideos', array('videoType' => 1,'status' => '1'))->num_rows();
          $countLongRejectVIdeo = $this->db->get_where('userVideos', array('videoType' => 1,'status' => '2'))->num_rows();
        ?>
        <li class="<?php if($active == 'longnonViewed'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/nonViewedLong"><i class="fa fa-circle-o"></i>Non Viewed Videos (<?php echo $countLongNonViewdVIdeo; ?>)</a></li>
        <li class="<?php if($active == 'longPendingVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/longPendingVideo"><i class="fa fa-circle-o"></i>Viewed Videos (<?php echo $countLongViewdVIdeo; ?>)</a></li>
        <li class="<?php if($active == 'TrandingVidoe'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/longtrending"><i class="fa fa-circle-o"></i>Trending Videos (<?php echo $countLongTrendingVIdeo; ?>)</a></li>
        <li class="<?php if($active == 'longRejectVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/longRejectVideo"><i class="fa fa-circle-o"></i>Rejected Videos (<?php echo $countLongRejectVIdeo; ?>)</a></li>
      </ul>
    </li>

    <li class="<?php if($active == 'addAdminVideo' || $active == 'adminVideo' ){ echo "active"; }?> treeview">
      <a href="#">
        <i class="fa fa-video-camera"></i> <span>Admin Video</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="<?php if($active == 'addAdminVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/addAdminVideo"><i class="fa fa-circle-o"></i>Add Videos</a></li>
        <li class="<?php if($active == 'adminVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Videos/adminVideo"><i class="fa fa-circle-o"></i>View Videos</a></li>
      </ul>
    </li>

		<li class="<?php if($active == 'addCategory' || $active == 'category'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span>Category</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addCategory'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Category/addCategory"><i class="fa fa-support"></i>Add Category</a></li>
			<li class="<?php if($active == 'category'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Category/manage"><i class="fa fa-circle-o"></i>View Category</a></li>
          </ul>
      </li>
		<li class="<?php if($active == 'addSubCategory' || $active == 'subCategory'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span>Sub Category</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addSubCategory'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Category/addSubCategory"><i class="fa fa-circle-o"></i>Add Sub Category</a></li>
			<li class="<?php if($active == 'subCategory'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Category/subCategory"><i class="fa fa-circle-o"></i>View Sub Category</a></li>
          </ul>
        </li>
        <li class="<?php if($active == 'addSound' || $active == 'manage'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span>Sounds</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addSound'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Sounds/addSound"><i class="fa fa-circle-o"></i>Add Sound</a></li>
      <li class="<?php if($active == 'manage'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Sounds/Sound"><i class="fa fa-circle-o"></i>View Sound</a></li>
          </ul>
        </li>
         <li class="<?php if($active == 'addhash' || $active == 'managehash'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span>Hashtags</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addhash'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Hashtags/addHash"><i class="fa fa-circle-o"></i>Add Hashtags</a></li>
            <li class="<?php if($active == 'managehash'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Hashtags/manage"><i class="fa fa-circle-o"></i>View Hashtags</a></li>
          </ul>
        </li>

		<!--li class="<?php if($active == 'slider' || $active == 'addSlider'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-diamond"></i> <span>Sliders</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addSlider'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Slider/add"><i class="fa fa-circle-o"></i>Slider</a></li>
			<li class="<?php if($active == 'slider'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Slider/manage"><i class="fa fa-circle-o"></i>View Slider</a></li>
          </ul>
        </li>

		<li class="<?php if($active == 'addGems' || $active == 'Gems'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-diamond"></i> <span>Gems</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'addGems'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Gems/add"><i class="fa fa-circle-o"></i>Add Gems</a></li>
			<li class="<?php if($active == 'Gems'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Gems/manage"><i class="fa fa-circle-o"></i>View Gems</a></li>
          </ul>
        </li-->
    <li class="header">REPORTS MANAGEMENT</li>
    <li class="<?php if($active == 'report' || $active == 'streamReport' || $active == 'problemReport' || $active == 'userReport'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'report'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Report/manage"><i class="fa fa-circle-o"></i>Reports</a></li>
      <li class="<?php if($active == 'streamReport'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Report/streamReport"><i class="fa fa-circle-o"></i>User Report</a></li>
      <li class="<?php if($active == 'problemReport'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Report/problemReport"><i class="fa fa-circle-o"></i>Problem Report</a></li>
       <li class="<?php if($active == 'userReport'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Report/userProblem"><i class="fa fa-circle-o"></i>UserProblem Report</a></li>
          </ul>
        </li>

        <li class="<?php if($active == 'videoreport' || $active == 'userReportVideo' ){ echo "active"; }?> treeview">
              <a href="#">
                <i class="fa fa-book"></i> <span>video Reports</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($active == 'videoreport'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Report/videoReports"><i class="fa fa-circle-o"></i>Reports</a></li>
          <li class="<?php if($active == 'userReportVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Report/userReportVideo"><i class="fa fa-circle-o"></i>Video Reports</a></li>

              </ul>
        </li>

    <li class="header">WEBSITE MANAGEMENT</li>

    <!-- <li class="<?php //if($active == 'logo'){ echo "active"; }?>"><a href="<?php //echo site_url()?>/Website/logo"><i class="fa fa-dashboard"></i>Logo</a></li> -->

    <li class="<?php if($active == 'socialLinks'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/socialLinks"><i class="fa fa-dashboard"></i>Social Links</a></li>

    <li class="<?php if($active == 'languages'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/languages"><i class="fa fa-dashboard"></i>Languages</a></li>

    <li class="<?php if($active == 'bannerImage'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/bannerImage"><i class="fa fa-dashboard"></i>Banner Image</a></li>

    <li class="<?php if($active == 'youAreAStar'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/youAreAStar"><i class="fa fa-dashboard"></i>You Are A Star</a></li>

    <li class="<?php if($active == 'websiteImages'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/websiteImages"><i class="fa fa-dashboard"></i>Image Section</a></li>

    <li class="<?php if($active == 'websiteVideo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/websiteVideo"><i class="fa fa-dashboard"></i>Video Section</a></li>

    <li class="<?php if($active == 'theWordIsWatching'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/theWordIsWatching"><i class="fa fa-dashboard"></i>THE world is watching</a></li>

    <li class="<?php if($active == 'Content' ||$active == 'email' || $active == 'applinks'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span>Footer</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'email'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/email"><i class="fa fa-dashboard"></i>Email & Address</a></li>
            <li class="<?php if($active == 'applinks'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/applinks"><i class="fa fa-dashboard"></i>App links</a></li>
            <li class="<?php if($active == 'Content'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/footerContent"><i class="fa fa-dashboard"></i>Contents</a></li>
          </ul>
    </li>
    <li class="<?php if($active == 'privacyPolicy' ||$active == 'termsAndConditions'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span>Pages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'privacyPolicy'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/privacyPolicy"><i class="fa fa-dashboard"></i>Privacy Policy</a></li>
            <li class="<?php if($active == 'termsAndConditions'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Website/termsAndConditions"><i class="fa fa-dashboard"></i>Terms And Conditions</a></li>
          </ul>
    </li>


		<li class="header">PAYMENT MANAGEMENT</li>

		<li class="<?php if($active == 'payment' || $active == 'revenue'  || $active == 'ppvpayment' ){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-credit-card"></i> <span>Payments</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!--li class="<?php if($active == 'revenue'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Payment/revenue"><i class="fa fa-circle-o"></i>Revenue System</a></li-->
			<li class="<?php if($active == 'payment'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Payment/manage"><i class="fa fa-circle-o"></i>Subscription Payments</a></li>
			<li class="<?php if($active == 'ppvpayment'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Payment/ppvpayment"><i class="fa fa-circle-o"></i>PPV Payments</a></li>
          </ul>
        </li>



		<li class="header">Admin Account</li>
    <li class="<?php if($active == 'adminNumber'){ echo "active"; }?>"><a href="<?php echo site_url()?>/admin/adminNumber"><i class="fa fa-diamond"></i>Admin Number</a></li>
		 <li class="<?php if($active == 'edit_profile'){ echo "active"; }?>"><a href="<?php echo site_url()?>/admin/edit_profile"><i class="fa fa-diamond"></i>Account</a></li>

		 <li class="<?php if($active == 'settings' || $active == 'logo' || $active == 'length'){ echo "active"; }?> treeview">
          <a href="#">
            <i class="fa fa-cog" aria-hidden="true"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($active == 'logo'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Settings/logo"><i class="fa fa-circle-o"></i>Logo</a></li>
			      <li class="<?php if($active == 'length'){ echo "active"; }?>"><a href="<?php echo site_url()?>/Settings/length"><i class="fa fa-circle-o"></i>Description Length</a></li>
          </ul>
        </li>

		 <li class=""><a href="<?php echo site_url()?>/admin/logout"><i class="fa fa-sign-out"></i>Sign Out</a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
