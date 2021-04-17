<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cinema Flix | Enter New Password</title>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>

.login-page, .register-page {
    height: auto;
    background: #ffffff !important;
}

		.login-box, .register-box {
		  width: 400px;
		}
    .form-error {
      color: #a76161;
      text-align: center;
      margin-bottom: 10px;
    }
    .form-error1 {
      color: #a76161;
    }
    .login-box-body {
      border: 2px solid #fd0100;
      box-shadow: 0 5px 13px #fd0100;
  }
	.login-page, .register-page {
    height: auto;
    background: #f7f7f7;
}
	</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
   <a href=""><img style="width: 50%;" src="<?php echo base_url();?>uploads/logo/logo3.png"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Enter New Password</p>
    <?php if($this->session->flashdata('error')){ ?><div class="form-error"><?php echo $this->session->flashdata('error'); ?></div><?php }?>
    <form action="<?php echo site_url();?>/admin/enterNewPassword" method="post">

      <div class="form-group has-feedback">
        <input type="text" name="password" value="<?php echo set_value('password')?>" class="form-control" placeholder="  Enter Password">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <div class="form-error1"><?php echo form_error('password') ?></div>
      </div>
      <div class="form-group has-feedback">
        <input type="password" value="<?php echo set_value('newpassword')?>" name="newpassword" class="form-control" placeholder="New Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div class="form-error1"><?php echo form_error('newpassword') ?></div>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" style="background: #fd0100;border: 1px solid #000000;color: white;" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


    <!-- /.social-auth-links -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>