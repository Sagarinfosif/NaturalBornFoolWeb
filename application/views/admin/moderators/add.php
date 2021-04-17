<div class="content-wrapper">

	<section class="content-header">

		<h1>

			<?= $title;?>

		</h1>

		<ol class="breadcrumb">

			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="<?php echo site_url();?>/Moderators/manage">Manage Moderators</a></li>

			<li class="active"><?= $title;?></li>

		</ol>

	</section>

	<section class="content">

		<div class="row">

			<div class="col-md-10">

				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">

					<div class="box box-warning">

						<div class="box-header with-border">

							<h3 class="box-title"><?= $title;?></h3>

						</div>

						<div class="box-body">

							<div class="form-group">

								<label>Username*</label>

								<input type="text" class="form-control" value="<?= set_value('username');?>" name="username" placeholder="Name">

								<div class="form-error1"><?= form_error('username');?></div>

							</div>

							<div class="form-group">

								<label>Email*</label>

								<input type="text" class="form-control" value="<?= set_value('email');?>" name="email" placeholder="Email">

								<div class="form-error1"><?= form_error('email');?></div>

							</div>

							<div class="form-group">

								<label>Mobile*</label>

								<input type="number" class="form-control" value="<?= set_value('phone');?>" name="phone" placeholder="Mobile">

								<div class="form-error1"><?= form_error('phone');?></div>

							</div>

							<div class="form-group">

								<label>Password*</label>

								<input type="password" class="form-control" value="<?= set_value('password');?>" name="password" placeholder="password">

								<div class="form-error1"><?= form_error('password');?></div>

							</div>

							<div class="form-group">

								<label>Confirm Password*</label>

								<input type="password" class="form-control" value="<?= set_value('cpassword');?>" name="cpassword" placeholder="password">

								<div class="form-error1"><?= form_error('cpassword');?></div>

							</div>

							<div class="form-group">

								<button type="reset" class="btn btn-danger">Cancel</button>

								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">

							</div>

						</div>

					</div>

				</form>

			</div>

		</div>

	</section>

</div>