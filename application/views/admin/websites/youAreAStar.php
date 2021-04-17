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
								<label>Title *</label><br>
								<input type="text" value="<?php echo $youAreAStar['title']; ?>" class="form-control" name="title">
								<div class="form-error1"><?= form_error('title');?></div>
							</div>
							<div class="form-group">
								<label>Centent *</label><br>
								<textarea type="text" class="form-control" name="content" style="height:250px;"><?php echo $youAreAStar['content']; ?></textarea>
								<div class="form-error1"><?= form_error('content');?></div>
							</div>
							<div class="form-group">
								<label>Image *</label><br>
								<img style="height: 200px; width: 200px;" src="<?php echo base_url().$youAreAStar['image']; ?>">
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