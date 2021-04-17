<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?= $title;?></li>
		</ol>
	</section>
	<?php if($this->session->flashdata('success')){ ?>
		<div class="success-message">
			<script> swal("Good job!", "<?= $this->session->flashdata('success')?>!", "success") </script>
		</div>
	<?php }?>
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
								<label>videoUrl 1 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl1'] ?>" name="videoUrl1">
								<div class="form-error1"><?= form_error('videoUrl1');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 2 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl2'] ?>" name="videoUrl2">
								<div class="form-error1"><?= form_error('videoUrl2');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 3 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl3'] ?>" name="videoUrl3">
								<div class="form-error1"><?= form_error('videoUrl3');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 4 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl4'] ?>" name="videoUrl4">
								<div class="form-error1"><?= form_error('videoUrl4');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 5 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl5'] ?>" name="videoUrl5">
								<div class="form-error1"><?= form_error('videoUrl5');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 6 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl6'] ?>" name="videoUrl6">
								<div class="form-error1"><?= form_error('videoUrl6');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 7 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl7'] ?>" name="videoUrl7">
								<div class="form-error1"><?= form_error('videoUrl7');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 8 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl8'] ?>" name="videoUrl8">
								<div class="form-error1"><?= form_error('videoUrl8');?></div>
							</div>
							<div class="form-group">
								<label>videoUrl 9 *</label><br>
								<input type="text" class="form-control" value="<?php echo $websiteVideo['videoUrl9'] ?>" name="videoUrl9">
								<div class="form-error1"><?= form_error('videoUrl9');?></div>
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