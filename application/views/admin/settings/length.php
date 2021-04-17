<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/pages">Manage Length</a></li>
			<li class="active"><?= $title;?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">
					<div class="box box-warning">
						<?php if($this->session->flashdata('success')){ ?>
							<div class="success-message">
								<script> swal("Good job!", "<?= $this->session->flashdata('success')?>!", "success") </script>
							</div>

						<?php }?>
						<div class="box-header with-border">
							<h3 class="box-title"><?= $title;?></h3>
							
							<span style="float: right;">
							    	<input type="hidden" name="id" value=""> 
				           <button type="submit" data-toggle="tooltip" name="submit" title="Save" class="saveButton">     
				           	<a href="<?php echo site_url();?>/settings/length/<?php echo $details['id'];?>"><i class="fa fa-save saveIcon"></i></a> </button>
				            </span>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label>Description Length</label>
								<input type="number" class="form-control" name="image" value="<?=$details['img']?>">
								<div class="form-error1"><?php echo  form_error('image');?></div>
							</div>
						 <!-- <input type="hidden" name="id" value="<?php echo $details['id'];?>"> -->
						</div>
					</div>
				</form>
			</div>
			
			<!-- <div class="col-md-4">
				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">
					<div class="box box-warning">
						<div class="box-body">
							<div class="form-group">
								<label>Logo</label>
									<span style="float: right;">
							<img src="<?php echo base_url().$details['img'];?>" style="height: 120px;" >
                                      
                                     
                            </span>
							</div>
						
						</div>
					</div>
				</form>
			</div> -->
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


