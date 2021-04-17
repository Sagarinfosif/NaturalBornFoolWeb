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
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
			<li class="active">	<?= $title;?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
		   <!-- /.box -->
				<div class="box">
					<div class="box-header">
						<!-- <h3 class="box-title"><a href="<?= site_url();?>/Faq/addFaq" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add FaQ's</a></h3> -->
					</div>
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
								<th>Sr.No.</th>
								<th>Title</th>
								<th>Description</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							<?php $i = 1; foreach($details as $data){ ?>
								<tr>
									<td><?= $i; ?></td>
									<td><?= $data['name']?></td>
									<td><?= substr($data['description'], 0,50)."..."?></td>
									<td><label class="switch">
										<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                                                        <input <?php if($data['status'] == '1'){ echo 'checked'; } ?> id="online_status_<?php echo $data['id']; ?>" value="<?php echo $data['status']; ?>" type="checkbox">
                                                          <span class="slider round"></span>
                                                        <script>
                                                            $("#online_status_<?php echo $data['id']; ?>").click(function(){
                                                            	//alert("hello");
                                                                $.ajax({
                                                                type: "POST",
                                                                url: "<?php echo base_url(); ?>index.php/pages/page_set_status",
                                                                data:'id=<?php echo $data['id']; ?>',              
                                                                success: function(data){
                                                                    if(data==1){
                                                                        alert('Page is active');
                                                                    }else{
                                                                        alert('Page is inactive');
                                                                    }
                                                                }
                                                                });
                                                              });
                                                        </script>
                                                        </label>
                                                    </td>
									<td>
										<a href="<?php echo site_url('/pages/editPages/').$data['id']?>">
				                			<i data-toggle="tooltip" title="Edit" class="fa fa-edit editIcon" style="color:#3c8dbc;font-size: 15px;"></i>
				              			</a>
									</td>
								</tr>
							<?php $i++; } ?>
							</tbody>
						</table>
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
