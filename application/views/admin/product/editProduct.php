<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/Product">Manage Product</a></li>
			<li class="active"><?= $title;?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">
					<div class="box box-warning">
						<div class="box-header with-border">
							<h3 class="box-title"><?= $title;?></h3>
							<span style="float: right;">
                <button type="submit" data-toggle="tooltip" title="Save" class="saveButton"><i class="fa fa-save saveIcon"></i> </button>
              </span>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label>Product Title</label>
								<input type="text" class="form-control" value="<?= $details['title'];?>" name="title" placeholder="Enter...">
								<div class="form-error1"><?= form_error('title');?></div>
							</div>
							<div class="form-group">
								<label>Price</label>
								<input type="text" class="form-control" value="<?= $details['price'];?>" name="price" placeholder="Enter..">
								<div class="form-error1"><?= form_error('price');?></div>
							</div>
							 <div class="form-group">
				                  <label for="exampleInputFile">Image</label><br>
				                  <img src="<?php echo base_url().$details['image'];?>" style="width:60px;"><br>
				                  <input type="file" class="form-control" name="image">
			                </div>
			                <div class="form-group">
				                  <label for="exampleInputFile">Description</label><br>
				                  <textarea class="form-control" name="description" rows="4">
				                  	<?= $details['description'];?>
				                  </textarea>
				                  
			                </div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
