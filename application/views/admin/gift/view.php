<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/User/manage">Manage User</a></li>
			<li class="active"><?= $title;?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
				<form role="form" method="post" id="specialitiesForm" enctype="multipart/form-data">
					<div class="box box-warning">
						<div class="box-header with-border" style="display: flex;">
							<img src="<?php echo base_url().$details['image'];?>" style="width: 60px;height: 60px;border-radius: 50%;">
							<div style="margin-left: 15px;font-size: 20px;"><?php echo $details['username']?></div>
						</div>
						<div class="box-body">
						
							<div role="tabpanel" style="background-color: #fff;" class="tab-pane active" id="track">
								 <ul style="padding: 0;margin: 0;">
									<div class="col-md-6">
										<li>
											<a href="#">Username</a>    
											<span><?php echo $details['username']?></span>
										</li>
										<li>
											<a href="#">Email</a> 
											<span><?php echo $details['email']?></span>
										</li>
										<li>
											<a href="#">Mobile</a> 
											<span><?php echo $details['phone']?></span>
										</li>
										<li>
											<a href="#">Is Moderator</a> 
											<span class="label label-warning">No</span>
										</li>
										<li>
											<a href="#">Status</a> 
											<?php if($details['status'] == 'Approved'){?>
												<span class="label label-success"><?php  echo $details['status'];?></span>
											<?php } else{?>
												<span class="label label-warning"><?php  echo $details['status'];?></span>
											<?php }?>
										</li>
										<li>
											<a href="#">Is Email Verified</a> 
											<span class="label label-success">Yes</span>
										</li>
										<li>
											<a href="#">User Type</a> 
											<span class="label label-warning">Normal User</span>
										</li>
										<li>
											<a href="#">Amount Paid</a> 
											<span>0.00</span>
										</li>
									</div>
									<div class="col-md-6">
										<li>
											<a href="#">No of account</a>    
											<span>1 </span>
										</li>
										<li>
											<a href="#">Device Type</a>    
											<span>Android </span>
										</li>
										<li>
											<a href="#">Login by</a>    
											<span>Manual </span>
										</li>
										<li>
											<a href="#">Social Unique Id</a>    
											<span>- </span>
										</li>
										<li>
											<a href="#">Created at</a>    
											<span><?php  echo $details['created'];?></span>
										</li>
									</div>
								</ul>
							</div>
									
						
							
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-1">
			</div>
		</div>
	</section>
</div>