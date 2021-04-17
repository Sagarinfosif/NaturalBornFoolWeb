<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/MinimumValue">Manage Minimum Values</a></li>
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
								<label>Minimum Price</label>
								<input type="text" class="form-control" value="<?= $details['minimumPrice'];?>" name="title" placeholder="Enter...">
								<div class="form-error1"><?= form_error('title');?></div>
							</div>
							<div class="form-group">
								<label>Minimum Quantity</label>
								<input type="text" class="form-control" value="<?= $details['minimumQuantity'];?>" name="ans" placeholder="Enter..">
								<div class="form-error1"><?= form_error('ans');?></div>
							</div>
							<div class="form-group">
								<label>Text for Price</label>
								<!-- <textarea class="form-control" value="" name="text" placeholder="Enter.."> -->
								<!-- <?= $details['priceText']?></textarea> -->
								<textarea class="textarea" name="text" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                					<?= $details['priceText'];?>
                				</textarea>
								<div class="form-error1"><?= form_error('text');?></div>
							</div>
							<div class="form-group">
								<label>Text for Quantity</label>
								<!-- <textarea class="form-control" value="" name="text1" placeholder="Enter.."> -->
								<!-- <?= $details['quantityText'];?></textarea> -->
								<textarea class="textarea" name="text1" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                					<?= $details['quantityText'];?>
                				</textarea>
								<div class="form-error1"><?= form_error('text1');?></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
</script>