<style>
div#example1_filter {
    float: right;
}

.paging_simple_numbers{
    float: right;
}
.dataTables_empty{
  text-align: center;
}
span.link {
    padding: 5px;
    border: 1px solid gray;
    margin: 2px;
    border-radius: 10px;
}
.label-success {
  animation: blinker 3s linear infinite;
}

@keyframes blinker {
  30% { opacity: 0; }
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/User/view/<?php echo $mainId;?>"> View User</a></li>
			<li class="active"><?= $title?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
		   <!-- /.box -->
				<div class="box">
				    <form id="getform" >
					<!--div class="box-header">
						<h3 class="box-title"><a href="<?= site_url();?>/User/addUser" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add User</a></h3>
						<div style="float:right; font-size:30px;">   <a href="javascript:void(0)" id="getpdf"> <i class="fa fa-file-pdf-o" style="color:red; "aria-hidden="true"></i></a>
                             <a href="javascript:void(0)" id="getexcel">   <i class="fa fa-file-excel-o" style=" color:green;" aria-hidden="true"></i></a>
                             </div>

					</div-->
					</form>

					<!-- /.box-header -->
					<div class="box-body">
						<?php if($this->session->flashdata('success')){ ?>
							<div class="success-message">
								<script> swal("Good job!", "<?= $this->session->flashdata('success')?>!", "success") </script>
							</div>

						<?php }?>
						<table id="example1" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>Sr.</th>
								<th>Sound</th>
								<th>Hashtag</th>
								<th>Allow Com.</th>
								<th>Allow Down.</th>
								<th>Views</th>
								<th>Likes</th>
								<th>Comment</th>
								<th>Status</th>
								<th>Vidoe Type</th>
								<th style="width: 15%;">Video</th>
							</tr>
							</thead>
							<tbody>
							<?php $i = 1; foreach($list as $data){ ?>
								<tr>
									<td><?= $i;?></td>
									<td><?php  echo $data['soundTitle'];?></td>
									<td><?php  echo $data['hashtagTitle'];?></td>
									<td><?php if($data['allowComment'] == 1){ echo "ON"; } else{ echo "OFF"; }?></td>
									<td><?php if($data['allowDownloads'] == 1){ echo "ON"; } else{ echo "OFF"; }?></td>
									<td><?php  echo $data['viewCount'];?></td>
									<td>
										<?php if(!empty($data['likeCount'])){?>
											<a href="<?php echo site_url()?>/User/likeList/<?php echo $mainId?>/<?php echo $data['id']?>">
												<span class="label label-success"><?php  echo $data['likeCount'];?> </span>
											</a>
										<?php } else{?>
											<a href="#">
												<span class="label label-warning">0</span>
											</a>
										<?php } ?>
									</td>
									<td>
					                    <?php if(!empty($data['commentCount'])){?>
											<a href="<?php echo site_url()?>/User/commentList/<?php echo $mainId?>/<?php echo $data['id']?>">
												<span class="label label-success"><?php  echo $data['commentCount'];?> </span>
											</a>
										<?php } else{?>
											<a href="#">
												<span class="label label-warning">0</span>
											</a>
										<?php } ?>
				                  	</td>
				                  	<td><?php if($data['status']=='0'){ ?> <span class="label label" style="color: white; background-color: green" >Viewed</span> <?php }elseif($data['status']=='1'){?> <span class="label label-warning" >Trending</span>  <?php }elseif($data['status']=='2'){ ?> <span class="label label-danger" >Rejected</span> <?php   }else{  ?> <span class="label label-primary"  >Non Viewed</span> <?php } ?></td>
				                  	<td><?php if($data['videoType']=='0'){echo 'Short'; }else{ echo 'Long'; }?></td>
									<td>
										<video style="width: 100%;height: 170px;" controls>
										  <source src="<?php echo $data['videoPath']; ?>" type="video/mp4">
										  <source src="<?php echo $data['videoPath']; ?>" type="video/ogg">
										  Your browser does not support the video tag.
										</video>
									</td>
								</tr>
							<?php $i++; } ?>
							</tbody>
						</table>
						<?php echo $links;?>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
<script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />
	<script>
	$(document).ready(function() {
	  $(".popup_image").on('click', function() {
		w2popup.open({
		  title: 'Payment Image',
		  height : 1000,
		  width : 1000,
		  showMax : true,
		  body: '<div class="w2ui-centered"><img src="' + $(this).attr('src') + '"></img></div>'
		});
	  });

	});
</script>

<!--excel script    -->
<script>
    $(document).ready(function() {
        $('#getexcel').on('click', function() {
            var action = '<?php echo site_url();?>/user/getexcel';
            $('#getform').attr('action', action);
            $('#getform').submit();

        });
    });

</script>


<!--pdf script  -->



<script>
     $("#getpdf").click(function() {
        var action = '<?php echo site_url();?>/user/getpdf';
        $('#getform').attr('action', action);
        $('#getform').attr('target', "_blank");
        $('#getform').submit();
    });


</script>
