<div class="content-wrapper">

	<section class="content-header">

		<h1>

			<?= $title;?>

		</h1>

		<ol class="breadcrumb">

			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="<?php echo site_url();?>/Videos/adminVideo">Manage Admin Video</a></li>

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

								<label>Video Title*</label>

								<input type="text" class="form-control" value="<?php echo  $list['videoTitle'];?>" name="videoTitle" placeholder="Video title">

								<div class="form-error1"><?= form_error('videoTitle');?></div>

							</div>

							<div class="form-group">

								<label>Sponsored*</label>

								<input type="text" class="form-control" value="<?= $list['sponsored'];?>" name="sponsored" placeholder="Sponsored">

								<div class="form-error1"><?= form_error('sponsored');?></div>

							</div>

							<div class="form-group">

								<label>Button Name*</label>

								<input type="text" class="form-control" value="<?= $list['buttonName'];?>" name="buttonName" placeholder="Button Name">

								<div class="form-error1"><?= form_error('buttonName');?></div>

							</div>

							<div class="form-group">

								<label>Video Url*</label>

								<input type="text" class="form-control" value="<?= $list['videoUrl'];?>" name="videoUrl" placeholder="Video Url">

								<div class="form-error1"><?= form_error('videoUrl');?></div>

							</div>

							<div class="form-group">

								<label>Upload Video*</label>

								<br>
									<video  controls  loop muted style="width: 250px;height: 250px;margin-top: 50px;margin-bottom: 50px;">
										<source src="<?php echo $list['videoPath'];?>" type="video/mp4">
									</video>
								<br>

								<input type="hidden" name="id" value="<?php echo $list['id']?>">
								<input type="file" class="form-control" name="videoPath">

								<div class="form-error1"><?= form_error('videoPath');?></div>

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
