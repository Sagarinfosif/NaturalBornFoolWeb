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
							<img style="width: 33%;height: 400px;" src="<?php echo base_url().$websiteImages['image1']; ?>">
							<img style="width: 33%;height: 400px;" src="<?php echo base_url().$websiteImages['image2']; ?>">
							<img style="width: 33%;height: 400px;" src="<?php echo base_url().$websiteImages['image3']; ?>">
						</div>
						<div class="box-body">
							<div class="form-group">
								<label>Image1 *</label><br>
								<input type="file" class="form-control" name="image1">
								<div class="form-error1"><?= form_error('image1');?></div>
							</div>
							<div class="form-group">
								<label>Image2 *</label><br>
								<input type="file" class="form-control" name="image2">
								<div class="form-error1"><?= form_error('image2');?></div>
							</div>
							<div class="form-group">
								<label>Image3 *</label><br>
								<input type="file" class="form-control" name="image3">
								<div class="form-error1"><?= form_error('image3');?></div>
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