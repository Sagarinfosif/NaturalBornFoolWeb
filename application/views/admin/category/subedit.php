<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/Category/subCategory">Manage Sub Category</a></li>
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
								<input type="text" class="form-control" value="<?= $details['title'];?>" name="title" placeholder="Name">
								<div class="form-error1"><?= form_error('title');?></div>
							</div>
							<div class="form-group">
								<label>Category*</label>
								<select class="form-control" name="categoryId" id="sel1">
									<?php foreach($category as $categorys){?>
										<option <?php if($categorys['id'] == $details['categoryId']){ echo "selected"; }?>value="<?php echo $categorys['id']?>"><?php echo $categorys['title']?></option>
									<?php }?>
								 </select>
							</div>
							
							<div class="form-group">
								<label>Description*</label>
								<textarea class="form-control" rows="5" name="description" id="comment"><?= $details['description'];?></textarea>
								<div class="form-error1"><?= form_error('description');?></div>
							</div>
							<div class="form-group">
								<label>Picture*</label>
								<input type="file" class="form-control" name="image">
								<div class="form-error1"><?= form_error('image');?></div>
							</div>
							<input type="hidden" value="<?php echo $details['id']?>" name="id">
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