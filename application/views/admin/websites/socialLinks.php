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
								<label>Facebook *</label><br>
								<input type="text" class="form-control" name="facebook" value="<?php echo $links['facebook'] ?>"> 
								<div class="form-error1"><?= form_error('facebook');?></div>
							</div>
							<div class="form-group">
								<label>Twitter *</label><br>
								<input type="text" class="form-control" name="twitter" value="<?php echo $links['twitter'] ?>"> 
								<div class="form-error1"><?= form_error('twitter');?></div>
							</div>
							<div class="form-group">
								<label>Instagram *</label><br>
								<input type="text" class="form-control" name="instagram" value="<?php echo $links['instagram'] ?>"> 
								<div class="form-error1"><?= form_error('instagram');?></div>
							</div>

							<div class="form-group">
								<label>Skype *</label><br>
								<input type="text" class="form-control" name="skype" value="<?php echo $links['skype'] ?>"> 
								<div class="form-error1"><?= form_error('skype');?></div>
							</div>
							<div class="form-group">
								<label>Google Plus *</label><br>
								<input type="text" class="form-control" name="googlePlus" value="<?php echo $links['googlePlus'] ?>"> 
								<div class="form-error1"><?= form_error('googlePlus');?></div>
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