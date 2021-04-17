<div class="content-wrapper">

	<section class="content-header">

		<h1>

			<?= $title;?>

		</h1>

		<ol class="breadcrumb">

			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="<?php echo site_url();?>/Sounds/Sound">Manage Sound</a></li>

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

								<label>Name*</label>

								<input type="text" class="form-control" value="<?php echo $details['title'];?>" name="title" placeholder="Name">

								<div class="form-error1"><?= form_error('title');?></div>

							</div>

							<!-- <div class="form-group">

								<label>Type*</label>
								<input type="text" class="form-control" value="<?php echo $details['type'];?>" name="type" placeholder="Type">
								<div class="form-error1"><?= form_error('type');?></div>

							</div> -->

							<div class="form-group">

								<label>Sound*</label>

								<input type="file" class="form-control" name="sound">

								<div class="form-error1"><?= form_error('sound');?></div>

							</div>

							<div class="form-group">

								<label>Sound Image*</label>

								<input type="file" class="form-control" name="image">

								<div class="form-error1"><?= form_error('image');?></div>

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