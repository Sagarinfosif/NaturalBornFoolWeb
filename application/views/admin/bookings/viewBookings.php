<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<?php if($details['orderStatus']=='0'){ ?>
				<li><a href="<?php echo site_url();?>/Bookings">Manage New Orders</a></li>
			<?php }elseif($details['orderStatus']=='1'){ ?>
				<li><a href="<?php echo site_url();?>/Bookings/accept">Manage Accepted Orders</a></li>
			<?php }elseif($details['orderStatus']=='2'){ ?>
				<li><a href="<?php echo site_url();?>/Bookings/rejected">Manage Rejected Orders</a></li>
			<?php }elseif($details['orderStatus']=='3'){ ?>
				<li><a href="<?php echo site_url();?>/Bookings/fuel">Manage fueling in progress Orders</a></li>
			<?php }elseif($details['orderStatus']=='4'){ ?>
				<li><a href="<?php echo site_url();?>/Bookings/deliver">Manage Delivery in progress Orders</a></li>
			<?php }elseif($details['orderStatus']=='5'){ ?>
				<li><a href="<?php echo site_url();?>/Bookings/delivered">Manage Delivered Orders</a></li>
			<?php }?>
			<li class="active">	<?= $title;?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-lg-6 col-md-12 col-sm-12">
		   <!-- /.box -->
				<div class="">
					<div class="box-header">
						<h3 class="box-title">Order Details</h3>
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
								<th>Order No.: </th>
								<td><?= $details['bookingId']?></td>
							</tr>
							<tr>
								<th>Poduct Name: </th>
								<td><?= $details['productTitle']?></td>
							</tr>
							<tr>
								<th>Quantity: </th>
								<td><?= $details['quantity']?></td>
							</tr>
							<tr>
								<th>Price: </th>
								<td><?= $details['pricePerLitre']?></td>
							</tr>
							<tr>
								<th>Total Price: </th>
								<td><?= $details['totalPrice']?></td>
							</tr>
							<tr>
								<th>Order Time: </th>
								<td><?= $details['time']?></td>
							</tr>
							<tr>
								<th>Order Date: </th>
								<td><?= $details['date']?></td>
							</tr>
							<tr>
								<th>Order Status: </th>
								<td><?php if($details['orderStatus']=='0'){echo "<b>New</b>";
							}elseif($details['orderStatus']=='1'){
								echo "<b>Accepted</b>";
							}elseif($details['orderStatus']=='2'){
								echo "<b>Rejected</b>";
							}elseif($details['orderStatus']=='3'){
								echo "<b>Fueling in progress</b>";
							}elseif($details['orderStatus']=='4'){
								echo "<b>Delivery in progress</b>";
							}elseif($details['orderStatus']=='5'){
								echo "<b>Delivered</b>";
							}


								?></td>
							</tr>
							
							</thead>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<div class="col-lg-6 col-md-12 col-sm-12">
		   <!-- /.box -->
				<div class="">
					<div class="box-header">
						<h3 class="box-title">Customer Details</h3>
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
								<th>Customer Name: </th>
								<td><?= ucfirst($details['userName'])?></td>
							</tr>
							<tr>
								<th>Customer Phone: </th>
								<td><?php if(!empty($details['userPhone'])){echo $details['userPhone']; }else{echo "N.A";}?></td>
							</tr>
							<tr>
								<th>Location: </th>
								<td><?= $details['location']?></td>
							</tr>
							<?php if($details['orderStatus']!='2' && $details['orderStatus']!='5'){?>
							<tr>
								<th>Status Update: </th>
								<td><button class="btn btn-success" data-toggle="modal" data-target="#modal-default"><i class="fa fa-edit"></i>Order update</button>
								
								</td>
							</tr>
							<?php }?>
							<!-- <tr>
								<th>Price</th>
								<td><?= $details['pricePerLitre']?></td>
							</tr>
							<tr>
								<th>Total Price</th>
								<td><?= $details['totalPrice']?></td>
							</tr> -->
							</thead>
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

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Order Status Update</h4>
              </div>
              <div class="modal-body">
               <section class="content">
		<div class="row">
			<div class="col-md-12">
				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">
					<div class="box box-warning">
						<div class="box-body">
							<div class="form-group">
								<input type="hidden" id="orderId" value="<?= $details['id']?>">
								<label>Order Status</label>
								<select class="form-control" name="status" id="status">
									<option value="">Select Status</option>
									<?php if($details['orderStatus']=='0'){?>
										<option value="1">Accept</option>
									<?php }?>
									
									<?php if($details['orderStatus']!='3' && $details['orderStatus']!='4'){?>
									<option value="3">Fueling in Progress</option>
									<?php }?>
									<?php if($details['orderStatus']!='4'){?>
									<option value="4">Delivery in Progress</option>
									<?php }?>
									<?php if($details['orderStatus']!='5'){?>
									<option value="5">Delivered</option>
									<?php }?>
									<option value="2">Reject</option>
								</select>
								<div class="form-error1"><?= form_error('status');?></div>
							</div>
						</div>
					</div>
				
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="send">Save changes</button>
              </div>
              </form>
			</div>
		</div>
	</section>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

    <script type="text/javascript">
        $(document).ready(function(){
            $('#send').click(function(){
            	//alert("jhgj");
                var status = $('#status').val();
                var orderId = $('#orderId').val();
                //alert(orderId);
                if(status!=''){
                    $.ajax({
                        type:"post",
                        url:"<?= site_url('/Bookings/updateStatus')?>",
                        data:{status:status,orderId:orderId},
                        success:function(data){
                            if(data){
                            	window.location.href="<?= site_url('/Bookings/updateStatus')?>/"+data;
                        	}
                        }
                    });
                }else{
                   swal("Oops!", "Please Select the Status", "error");
                }
            });
        });


    </script>

