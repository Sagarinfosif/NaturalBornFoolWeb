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

.filter_form label {
    display:block;
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
					<div class="box-header">
						<h3 class="box-title"><a href="<?= site_url();?>/User/addUser" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add User</a></h3>
						<div style="float:right; font-size:30px;">   <a href="javascript:void(0)" id="getpdf"> <i class="fa fa-file-pdf-o" style="color:red; "aria-hidden="true"></i></a>
                             <a href="javascript:void(0)" id="getexcel">   <i class="fa fa-file-excel-o" style=" color:green;" aria-hidden="true"></i></a>
                             </div>
					</div>
					</form>


          <div class="main-data">
            <div class="col-md-12 filter_form" style="margin-left:60px;margin-top:10px;margin-bottom:20px">
              <form method="post" action="<?= site_url()."/User/getResult";?>" id="getPdf">
                <div class = "row">
                  <div class="col-md-2">
                      <div class="main-data-single-field">
                        <label style="font-weight: bold;">Search</label>
                          <span><input type="text" id="pname" name="pname" style="border-radius: 4px;border-style: groove;width: 111px;"></span>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="main-data-single-field">
                        <label style="font-weight: bold;">Search By Id</label>
                          <span><input type="text" id="userId" name="userId" style="border-radius: 4px;border-style: groove;width: 111px;"></span>
                      </div>
                  </div>

                      <div class="col-md-2">
                        <div class="main-data-single-field">

                            <div class="form-group">
                              <label for="sel1" style="font-weight: bold;">Sort By</label>
                              <select class="form-control" id="sel1" name="sort">
                                  <option value="">Sort By</option>
                                  <option value="totalLikeCount desc">Sort By Like Descending</option>
                                  <option value="totalLikeCount asc">Sort By Like Ascending</option>
                                  <option value="totalViewCount desc">Sort By View Descending</option>
                                  <option value="totalViewCount asc">Sort By View Ascending</option>

                                  <!-- <option value="created desc">Sort By Created Descending</option>
                                  <option value="created asc">Sort By Created Ascending</option> -->
                              </select>
                            </div>

                          </div>
                      </div>

                      <div class="col-md-2">
                        <div class="main-data-single-field">
                            <label style="font-weight: bold;">Start Date</label>
                              <span><input type="date" id="sdate" name="sdate" style="border-radius: 4px;border-style: groove;"></span>
                          </div>
                      </div>
                      <div class="col-md-4">
                        <div class="main-data-single-field">
                            <label style="font-weight: bold;">End Date</label>
                              <span><input type="date" id="edate" name="edate" style="border-radius: 4px;border-style: groove;"></span>
                              <span><button type="submit" id="search" name="submit" style="background-color: #d9a944;color: #FFFFFF;border-radius:6px;border:1px solid #33cccc;box-shadow: 0 4px 5px 0 rgba(156, 39, 176, 0.14), 0 1px 10px 0 rgba(156, 39, 176, 0.12), 0 2px 4px -1px rgba(156, 39, 176, 0.2);">Search</button></span>
                          </div>
                      </div>
                  </div>
              </form>
            </div>
          </div>

					<!-- <div class="main-data">
						<div class="row">
                        <form method="post" action="<?= site_url();?>/User/getResult" >
                        	<div class = "col-md-12" style="margin-left:60px;margin-top:10px;margin-bottom:20px">
								<div class="col-md-3">
                                	<div class="main-data-single-field">
                                    	<span style="font-weight: bold;">Search</span>
                                        <span><input type="text" id="pname" name="pname" style="border-radius: 4px;border-style: groove;width: 111px;"></span>
                                    </div>
                                </div>
								<div class="col-md-4">
                                	<div class="main-data-single-field">
                                    	<span style="font-weight: bold;">Start Date</span>
                                        <span><input type="date" id="sdate" name="sdate" style="border-radius: 4px;border-style: groove;"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                	<div class="main-data-single-field">
                                    	<span style="font-weight: bold;">End Date</span>
                                        <span><input type="date" id="edate" name="edate" style="border-radius: 4px;border-style: groove;"></span>
                                        <span><button type="submit" id="search"style="background-color: #d9a944;color: #FFFFFF;border-radius:6px;border:1px solid #33cccc;box-shadow: 0 4px 5px 0 rgba(156, 39, 176, 0.14), 0 1px 10px 0 rgba(156, 39, 176, 0.12), 0 2px 4px -1px rgba(156, 39, 176, 0.2);">Search</button></span>
                                    </div>
                                </div>
                            </div>
							</form>
						</div>
					</div> -->
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
                <th>UserId</th>
								<th>Username</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Video Count</th>
								<th>Like Count</th>
								<th>Comment Count</th>
								<th>View Count</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody id = "ts">
							<?php $i = $segmentCount; foreach($details as $data){ ?>
								<tr>
                  <td><?= $i;?></td>
									<td><?php echo $data['id']?></td>
									<td><?php  echo $data['username'];?></td>
									<td><?php if(!empty($data['name'])){ echo $data['name']; } else{ echo "N/A";}?></td>
									<td><?php  echo $data['email'];?></td>
									<td><?php  echo $data['phone'];?></td>
									<td>
										<?php if(!empty($data['totalVideos'])){ ?>
										<a href="<?php echo site_url()?>/User/videoList/<?php echo $data['id']?>">
											<span class="label label-success"><?php  echo $data['totalVideos'];?></span>
										</a>
										<?php } else{?>
											<span class="label label-warning">0</span>
										<?php } ?>
									</td>

									<td>
										<?php if(!empty($data['totalLikeCount'])){ ?>
											<span class="label label-success"><?php  echo $data['totalLikeCount'];?></span>
										<?php } else{?>
											<span class="label label-warning">0</span>
										<?php } ?>
									</td>

									<td>
										<?php if(!empty($data['totalCommentCount'])){ ?>
											<span class="label label-success"><?php  echo $data['totalCommentCount'];?></span>
										<?php } else{?>
											<span class="label label-warning">0</span>
										<?php } ?>
									</td>

									<td>
										<?php if(!empty($data['totalViewCount'])){ ?>
											<span class="label label-success"><?php  echo $data['totalViewCount'];?></span>
										<?php } else{?>
											<span class="label label-warning">0</span>
										<?php } ?>
									</td>

									<td>
										<?php if($data['status'] == 'Approved'){?>
											<span class="label label-success"><?php  echo $data['status'];?></span>
										<?php } else{?>
											<span class="label label-warning"><?php  echo $data['status'];?></span>
										<?php }?>
									</td>
									<td>
										<ul class="admin-action btn btn-default" style="background-color: #f4f4f4;color: #fff !important;">
											<li class="dropdown">
											    <a class="dropdown-toggle" style="color: black;" data-toggle="dropdown" href="#" aria-expanded="false">
												  Action <span class="caret"></span>
												</a>
												<ul class="dropdown-menu dropdown-menu-right">
													<li role="presentation">
														<a role="menuitem" tabindex="-1" href="<?= site_url()?>/user/view/<?= $data['id'];?>">View</a>
													</li>
													<li role="presentation">
														<a role="menuitem" tabindex="-1" href="<?= site_url()?>/user/edit/<?= $data['id'];?>">Edit</a>
													</li>
													<li>
														<a role="menuitem" tabindex="-1" onclick="return confirm(&quot;Once you've deleted 	this account, the user (<?php  echo $data['username'];?>) will no longer be able to log in to the site or apps. This action cannot be undone.&quot;);" href="<?= site_url()?>/user/delete/<?= $data['id'];?>">Delete
											            </a>
													</li>
													<li role="presentation" class="divider"></li>
											        <li role="presentation">
														<a role="menuitem" onclick="return confirm(<?php  echo $data['username'];?> - Do you want <?php if($data['status'] == 'Approved'){ echo "Decline"; } else{ echo "Approve";}?> this user&quot;);" tabindex="-1" href="<?= site_url()?>/user/status/<?= $data['id'];?>">
														<?php if($data['status'] == 'Approved'){ echo "Decline"; } else{ echo "Approve";}?>

														</a>
													</li>
												</ul>
											</li>
										</ul>
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


<script>
     $("#pname").keyup(function(event) {
          event.preventDefault();
          if (event.keyCode === 13) {
              $("#search").click();
           }
     });
	 $(document).ready(function(){
	 $("#search").click(function(){
		var start = $("#sdate").val();
		var end = $("#edate").val();
		var name = $("#pname").val();
    	$.ajax({
    		type:"post",
   			url:"<?= site_url()?>/user/getResult",
    		data:{s:start,e:end,p:name},
    		dataType: 'json',                //data format
            success: function(data){
            if(data ==''){
            	$('#ts').html('<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr>');
            }
            else{
            	var html, comment;
                for(var i = 0; i < data.length; i++) {
                	comment = data[i];
           			html += '<tr><td>' + (i+1) + '</td><td>' + comment.username + '</td><td>' + comment.email + '</td><td>' + comment.phone +
                    '</td><td><span class="label label-warning">' + "Upgrade" + '</span></td><td>' + "--" + '</td><td><span class="label label-warning">' + "Clear" +
                    '</span></td><td><span class="label label-success">' + comment.status +
                    '</span></td><td><ul class="admin-action btn btn-default" style="background-color: #f4f4f4;color: #fff !important;"><li class="dropdown"><a class="dropdown-toggle" style="color: black;" data-toggle="dropdown" href="#" aria-expanded="false">' + "Action " + '<span class="caret"></span></a></li></ul></td></tr>';
            	}

           		$('#ts').html(html);
           		}
       		}
    	});
	});
});
		$(function(){
			var dtToday = new Date();
			var month = dtToday.getMonth() + 1;
			var day = dtToday.getDate();
			var year = dtToday.getFullYear()+1;
			if(month < 10)
				month = '0' + month.toString();
			if(day < 10)
				day = '0' + day.toString();
				var maxDate = year + '-' + month + '-' + day;
			$('#sdate').attr('max', maxDate);
			$('#edate').attr('max', maxDate);
		});
</script>
