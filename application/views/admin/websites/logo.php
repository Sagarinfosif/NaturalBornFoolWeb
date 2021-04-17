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
						<div class="box-body col-md-6">
							<div class="form-group" style="margin-top: 10%;">
								<label>Logo*</label><br>
								<input type="hidden" name="id" value="4567890"> 
								<input type="file" class="form-control" name="image">
								<div class="form-error1"><?= form_error('image');?></div>
							</div>
							
							<div class="form-group">
								<button type="reset" class="btn btn-danger">Cancel</button>
								<input type="submit" class="btn btn-success pull-right" name="submit" value="Submit">
							</div>
						</div>
						<div class="col-md-6 text-right">
							<img style="height: 200px; width: 200px;" src="<?php echo base_url().$logo['image']; ?>">
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>