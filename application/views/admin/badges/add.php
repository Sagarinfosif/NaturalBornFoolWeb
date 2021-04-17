<div class="content-wrapper">

	<section class="content-header">

		<h1>

			<?= $title;?>

		</h1>

		<ol class="breadcrumb">

			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="<?php echo site_url();?>/Badges">Manage Crowns</a></li>

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

								<label>Title*</label>

								<input type="text" class="form-control" value="<?= set_value('title');?>" name="title" placeholder="Name">

								<div class="form-error1"><?= form_error('title');?></div>

							</div>

							<div class="form-group">

								<label>likes*</label>

								<input type="text" class="form-control allownumericwithoutdecimal" value="<?= set_value('likes');?>" id="likes" name="likes">

								<div class="form-error1"><?= form_error('likes');?></div>

							</div>

							<div class="form-group">

								<label>Followers*</label>

								<input type="text" class="form-control allownumericwithoutdecimal" value="<?= set_value('followers');?>" id="followers" maxlength="2" name="followers">

								<div class="form-error1"><?= form_error('followers');?></div>

							</div>

							<div class="form-group">

								<label>Followers Count*</label>

								<input type="text" class="form-control allownumericwithoutdecimal" id="totalFollowers" name="totalFollowers" readonly>

							</div>

							<div class="form-group">

								<label>Color*</label>

								<input type="color" class="form-control" value="<?= set_value('color');?>" name="color">

								<div class="form-error1"><?= form_error('color');?></div>

							</div>

							<div class="form-group">

								<label>Image*</label>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	$(document).ready(function() {
 		$(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
       $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
	});

	$("#likes").on('keyup paste',function(){
    var likesVal = $(this).val();
		if(likesVal == ''){
			$("#followers").val('');
			$("#totalFollowers").val('');
		}
		else{
			var followersVal = $("#followers").val();
			if(followersVal != ''){
				var mainval = likesVal * followersVal;
				var deviVal = parseInt(mainval / 100);
				$("#totalFollowers").val(deviVal);
			}
		}
  });


	//$("#followers").keyup(function(){
	$("#followers").on('keyup paste',function(){
		var likesVal = $('#likes').val();
		if(likesVal == ''){
			$("#totalFollowers").val('');
		}
		else{
			var followersVal = $("#followers").val();
			if(followersVal != ''){
				var mainval = likesVal * followersVal;
				var deviVal = parseInt(mainval / 100);
				$("#totalFollowers").val(deviVal);
			}
			else{
				$("#totalFollowers").val('');
			}
		}
  });

</script>
