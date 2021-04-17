<div class="content-wrapper">

	<section class="content-header">

		<h1>

			<?= $title;?>

		</h1>

		<ol class="breadcrumb">

			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="<?php echo site_url();?>/User/viewMessage">Manage Push Messages</a></li>

			<li class="active"><?= $title;?></li>

		</ol>

	</section>
	<?php if($this->session->flashdata('error')){ ?>
		<div class="success-message">
			<script> swal("Opps!", "<?= $this->session->flashdata('error')?>!", "error") </script>
		</div>
	<?php }?>
	<section class="content">

		<div class="row">

			<div class="col-md-12">

				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">

					<div class="box box-warning">

						<div class="box-header with-border">

							<h3 class="box-title"><?= $title;?></h3>

						</div>

						<div class="box-body">

							<div class="form-group">
								 <label for="sel1">Select User:</label>
								    <select class="form-control" id="user" name="usertype">
								        <option value="">select user</option>
								        <option value="1">All Users</option>
								        <option value="2"> Single User</option>
								    </select>
								    <span class="form-error1"><?= form_error('usertype');?></span>
							</div>
   
                           
                            <div class="form-group" id="replace" style="display: none">
								<label for="sel1">User Name:</label>
								 <input type="text"  class="form-control"  value="<?= set_value('username');?>" name="username" >
							</div>

							<div class="form-group">

								<label>Message</label>

								<!-- <input type="textarea"  value="" name="message" > -->
								<textarea name="message" class="form-control"><?= set_value('message');?></textarea>
								<div class="form-error1"><?= form_error('message');?></div>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	$("#user").change(function(){
	var	user = $("#user").val();
	if(user == 1){
		$("#replace").hide(1000);
	}
	else{
		$("#replace").show(1000);
	}
});
</script>