<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/Charges">Manage Charges</a></li>
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
								<label>Start Limit</label>
								<input type="number" class="form-control" value="<?= $details['slimit'];?>" name="slimit" placeholder="Enter...">
								<div class="form-error1"><?= form_error('slimit');?></div>
							</div>
							<div class="form-group">
								<label>End Limit</label>
								<input type="number" class="form-control" value="<?= $details['elimit'];?>" name="elimit" placeholder="Enter..">
								<!-- <div class="form-error1"><?= form_error('elimit');?></div> -->
							</div>
							<div class="form-group">
								<label>Charges Type</label>
								<select  class="form-control"name="type">
									<option value="">Select Charges Type</option>
									<option value="1" <?php if($details['type']=='1'){echo "selected";}?>>Flat</option>
									<option value="2"<?php if($details['type']=='2'){echo "selected";}?>>Per Litre</option>
								</select>
								<div class="form-error1"><?= form_error('type');?></div>
							</div>
							<div class="form-group">
								<label>Charges</label>
								<input type="text" class="form-control" value="<?= $details['charges'];?>" name="charges" placeholder="Enter..">
								<div class="form-error1"><?= form_error('charges');?></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
