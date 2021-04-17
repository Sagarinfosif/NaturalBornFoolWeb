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
</style><!-- Content Wrapper. Contains page content -->
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
						<h3 class="box-title">Delivery in progress</h3>
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
								<th>Booking ID</th>
								<th>Poduct Title</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Total Price</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							<?php $i = 1; foreach($details as $data){ ?>
								<tr>
									<td><?= $i; ?></td>
									<td><?= $data['bookingId']?></td>
									<td><?= $data['productTitle']?></td>
									<td><?= $data['quantity']?></td>
									<td><?= $data['pricePerLitre']?></td>
									<td><?= $data['totalPrice']?></td>
									<td><span class="new badge green" data-badge-caption="red" style="background-color:purple;">Delivery In Progress</span></td>
									<td>
										<a href="<?= site_url()?>/Bookings/viewBooking/<?= $data['id'];?>">
				                			<i data-toggle="tooltip" title="View" style="font-size:20px;" class="fa fa-eye" aria-hidden="true"></i>

				              			</a>
											<!-- <a onclick="return confirm('Are you sure want to delete it? ');" href="<?= site_url()?>/vendorAdmin/Product/deleteProduct/<?= $data['id'];?>"> -->
				                			<!-- <i class="fa fa-fw fa-remove deleteIcon"></i> -->
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
