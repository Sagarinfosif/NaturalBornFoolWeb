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
					<div class="box-header">
						<!--h3 class="box-title"><a href="<?= site_url();?>/Report/add" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add Report</a></h3-->
					</div>

                	<div class="main-data">
						<div class="row">
                        <form method="post" id="getPdf">
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
                                        <span><button type="button" id="search"style="background-color: #d9a944;color: #FFFFFF;border-radius:6px;border:1px solid #33cccc;box-shadow: 0 4px 5px 0 rgba(156, 39, 176, 0.14), 0 1px 10px 0 rgba(156, 39, 176, 0.12), 0 2px 4px -1px rgba(156, 39, 176, 0.2);">Search</button></span>
                                    </div>
                                </div>
                            </div>
							</form>
						</div>
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
								<th>Sr.</th>
								<th>User Name</th>
								<th>Report User Name</th>
								<th>Report</th>
                <th>Date/Time</th>
                <th>video</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody id = "ts">
							<?php $i = 1; foreach($details as $data){
                $id=$data['reportUserId'];
                $list = $this->db->query("SELECT users.username as rname from users WHERE id= $id")->row_array();

                ?>
								<tr>
									<td><?= $i;?></td>
									<td><?php  if(!empty($data['username'])){ echo $data['username']; }else{ echo "N/A"; }?></td>
									<td><?php  echo $list['rname'];?></td>


									<td><?php  echo $data['report'];?></td>
									<td><?php
                      $videoDate=date_create($data['created']);
                      echo date_format($videoDate,"d M Y H:i:s");
                    ?>
                  </td>
                  <td>
                    <video controls height="120px" width="120px">
                      <source src ="<?php echo $data['reportVideo']; ?>">
                    </video>
                  </td>
									<td>
										<ul class="admin-action btn btn-default" style="background-color: #f4f4f4;color: #fff !important;">
											<li class="dropdown">
											    <a class="dropdown-toggle" style="color: black;" data-toggle="dropdown" href="#" aria-expanded="false">
												  Action <span class="caret"></span>
												</a>
												<ul class="dropdown-menu dropdown-menu-right">
													<li>
														<!-- <a type="button" onclick="delete(<?= $data['id'];?>)"  data-toggle="modal" >Delete</a> -->
                            <a href="" data-toggle="modal" onclick="deletevideo(this.id)" id="<?php echo $data['id'];?>" data-target="#exampleModaldd">Delete </a>

                            <a href="" data-toggle="modal" onclick="approveVideo(this.id)" id="<?php echo $data['id'];?>" data-target="#exampleModalApproved">Approved </a>
                            <!-- <a type="button"   data-toggle="modal" data-target="#Approve">Approve</a> -->
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


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="modal fade" id="exampleModaldd" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Delete Message </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="email_address1">Message</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea type="text" id="message" class="form-control" placeholder="Type message "></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="" id="userReportId">
                <div class="modal-footer">
                    <button type="button" id="deleteMessage" class="btn btn-info waves-effect">Save</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="modal fade" id="exampleModalApproved" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Approved Message </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="email_address1">Message</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea type="text" id="approverdMessage" class="form-control" placeholder="Type message "></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="" id="approveVideoId">
                <div class="modal-footer">
                    <button type="button" id="submitMessage12" class="btn btn-info waves-effect">Save</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
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
   			url:"<?= site_url()?>/report/getstreamResult",
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
           			html += '<tr><td>' + (i+1) + '</td><td>' + comment.uname + '</td><td>' + comment.uid + '</td><td>' + comment.rname +
                    '</td><td>' + comment.rid + '</td><td>' + comment.report +
                    '</td><td><ul class="admin-action btn btn-default" style="background-color: #f4f4f4;color: #fff !important;"><li class="dropdown"><a class="dropdown-toggle" style="color: black;" data-toggle="dropdown" href="#" aria-expanded="false">' + "Action " + '<span class="caret"></span></a></li></ul></td></tr>';
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  function deletevideo(id){
    var id = id;
    document.getElementById("userReportId").value = id;
  }

  $(document).ready(function(){
    $("#deleteMessage").click(function(){
      var id = $("#userReportId").val();
      var message = $("#message").val();
      // alert(message);

      $.ajax({
        type: 'post',
        url: "<?php echo site_url()?>/Report/deleteReportVideo",
        data:{id:id,message:message},
        success: function(result){
          // $('#exampleModaldd').modal('toggle');
          if(result == 1){
            alert('Video Deleted successfully');
            window.open("<?php echo site_url()?>/Report/userReportVideo",'_self');
          }
          else{
            alert('Video not Deleted');
             window.open("<?php echo site_url()?>/Report/userReportVideo",'_self');
          }
       }
      });
    });
  });
</script>


<script>
  function approveVideo(id){
    var id = id;
    document.getElementById("approveVideoId").value = id;
  }

  $(document).ready(function(){
    $("#submitMessage12").click(function(){

      var id = $("#approveVideoId").val();
      var message = $("#approverdMessage").val();
      // alert(message);

      $.ajax({
        type: 'post',
        url: "<?php echo site_url()?>/Report/approvedReportVideo",
        data:{id:id,message:message},
        success: function(result){

          // $('#exampleModalApproved').modal('toggle');
          if(result == 1){
            alert('Video Approved successfully');
            window.open("<?php echo site_url()?>/Report/userReportVideo",'_self');
          }
          else{
            alert('Video not Approved');
            window.open("<?php echo site_url()?>/Report/userReportVideo",'_self');
          }
        }
      });
    });
  });
</script>

