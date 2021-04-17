<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/subAdmin/manage">Manage Sub Admin</a></li>
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
											<a href="#">Status</a> 
											<?php if($details['status'] == 'Approved'){?>
												<span class="label label-success"><?php  echo $details['status'];?></span>
											<?php } else{?>
												<span class="label label-warning"><?php  echo $details['status'];?></span>
											<?php }?>
										</li>
										<li>
											<a href="#">Description</a> 
											<span></span>
										</li>
										<li>
											<?php  echo $details['description'];?>
										</li>
									</div>
									<div class="col-md-6">
										<li>
											<a href="#">Created at</a>    
											<span><?php  echo $details['created'];?></span>
										</li>
										<li>
											<a href="#">Updated at</a>    
											<span><?php  echo $details['updated'];?></span>
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