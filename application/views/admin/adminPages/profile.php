
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Profile
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">User profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">

              <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url(). $admin['image'];?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $admin['name']?></h3>

              <p class="text-muted text-center"><?php echo $admin['designation']?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-phone margin-r-5"></i> Phone Number</strong>

              <p class="text-muted">
                <?php echo $admin['phone']?>
              </p>

              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>

              <p class="text-muted">
                <?php echo $admin['email']?>
              </p>

              <hr>

              <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

              <p class="text-muted">
                <?php echo $admin['education']?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

              <p class="text-muted"><?php echo $admin['address']?></p>

              <hr>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="<?php if($activeTab == 'profile'){ echo "active"; }?>"><a href="#settings" data-toggle="tab">Settings</a></li>
							<li class="<?php if($activeTab == 'changePass'){ echo "active"; }?>"><a href="#changePassword" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
							<?php if($this->session->flashdata('success')){ ?><div class="form-error">
								<script>
									swal("Good job!", "Profile Update Successfully!", "success")
								</script>
							</div><?php }?>
              <div class="<?php if($activeTab == 'profile'){ echo "active"; }?> tab-pane" id="settings">
                <form class="form-horizontal" method="post" enctype='multipart/form-data'>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" name="name" value="<?php echo $admin['name']?>" class="form-control" id="inputName" placeholder="Name">
											<div class="form-error1"><?php echo form_error('name') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="text" name="email" value="<?php echo $admin['email']?>" class="form-control" id="inputEmail" placeholder="Email">
											<div class="form-error1"><?php echo form_error('email') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Phone Number</label>

                    <div class="col-sm-10">
                      <input type="number" name="phone" value="<?php echo $admin['phone']?>" class="form-control" id="inputName" placeholder="Phone Number">
											<div class="form-error1"><?php echo form_error('phone') ?></div>
                    </div>
                  </div>
									<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Designation</label>

                    <div class="col-sm-10">
                      <input type="text" name="designation" value="<?php echo $admin['designation'] ?>" class="form-control" id="inputName" placeholder="Designation">
											<div class="form-error1"><?php echo form_error('designation') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Education</label>

                    <div class="col-sm-10">
											<input type="text" class="form-control" value="<?php echo $admin['education']?>" name="education" id="inputSkills" placeholder="Education">
											<div class="form-error1"><?php echo form_error('education') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Location</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="<?php echo $admin['address']?>" name="location" id="inputSkills" placeholder="Location">
											<div class="form-error1"><?php echo form_error('location') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Image</label>

                    <div class="col-sm-10">
                      <input type="file" class="form-control" name="image" >
                    </div>
                  </div>
									<input type="hidden" value="profile" name="type">
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <input type="submit" class="btn btn-success" name="submit" value="Edit Profile">
                    </div>
                  </div>

                </form>
              </div>

							<div class="<?php if($activeTab == 'changePass'){ echo "active"; }?> tab-pane" id="changePassword">
								<?php if($this->session->flashdata('oldPass')){ ?><div class="form-error">
									<script>
										swal("OOPS!", "Old Password Does't Match!", "error")
									</script>
								</div><?php }?>
								<?php if($this->session->flashdata('passSuccess')){ ?><div class="form-error">
									<script>
										swal("Good job!", "Password Update Successfully!", "success")
									</script>
								</div><?php }?>
                <form class="form-horizontal" method="post">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Old Password</label>

                    <div class="col-sm-10">
                      <input type="password" name="oldPassword" value="<?php echo set_value('oldPassword') ?>" class="form-control" id="inputName" placeholder="Old Password">
											<div class="form-error1"><?php echo form_error('oldPassword') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">New Password</label>

                    <div class="col-sm-10">
                      <input type="password" name="newPassword" value="<?php echo set_value('newPassword') ?>" class="form-control" id="inputEmail" placeholder="New Password">
											<div class="form-error1"><?php echo form_error('newPassword') ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Confirm Password</label>

                    <div class="col-sm-10">
                      <input type="password" name="confirmPassword" value="<?php echo set_value('confirmPassword') ?>" class="form-control" id="inputName" placeholder="Confirm Password">
											<div class="form-error1"><?php echo form_error('confirmPassword') ?></div>
                    </div>
                  </div>
									<input type="hidden" value="pass" name="type">
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <input type="submit" class="btn btn-success" value="Update Password">
                    </div>
                  </div>
                </form>
              </div>

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
