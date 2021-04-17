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
#fade {
  display: none;
  position: fixed;
  top: 0%;
  left: 0%;
  width: 100%;
  height: 100%;
  background-color: black;
  z-index: 1001;
  -moz-opacity: 0.8;
  opacity: .80;
  filter: alpha(opacity=80);
}

#light {
  display: none;
  position: absolute;
  top: 25%;
  left: 50%;
  max-width: 600px;
  max-height: 360px;
  margin-left: -300px;
  margin-top: -180px;
  border: 2px solid #FFF;
  background: #FFF;
  z-index: 1002;
  overflow: visible;
}

#boxclose {
  float: right;
  cursor: pointer;
  color: #fff;
  border: 1px solid #AEAEAE;
  border-radius: 3px;
  background: #222222;
  font-size: 31px;
  font-weight: bold;
  display: inline-block;
  line-height: 0px;
  padding: 11px 3px;
  position: absolute;
  right: 2px;
  top: 2px;
  z-index: 1002;
  opacity: 0.9;
}

.boxclose:before {
  content: "×";
}

#fade:hover ~ #boxclose {
  display:none;
}

.test:hover ~ .test2 {
  display: none;
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
						<h3 class="box-title"><a href="<?php echo  site_url();?>/Videos/addAdminVideo" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add Video</a></h3>
					</div>
					</form>
<div class="box-header" style="margin-left:800px;margin-top:0px">
          <form id="getform" >

          <input type="text" value="<?php echo $adminVideoLimmit['setLimit']?>" name="limit" id="adminLimitValue" >
          <h3 class="box-title"><a onclick="setLimit()" href="javascript:void" style="font-size: 14px; " class="btn btn-warning  btn-xs" >Set Limit</a></h3>
      </form>
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
								<th>Title</th>
								<th>Sponsored</th>
								<th>Button</th>
								<th>Url</th>
                <th>Status</th>
								<th>Date/Time</th>
                <th>View Count</th>
                <th>Hit Count</th>
                <th style="WIDTH: 150px;">Video</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody id = "ts">

							<?php $i = 1; foreach($details as $data){ ?>
								<tr>
									<td><?= $i;?></td>
									<td><?php  echo $data['videoTitle'];?></td>
									<td><?php  echo $data['sponsored'];?></td>
									<td><?php  echo $data['buttonName'];?></td>
									<td><?php  echo $data['videoUrl'];?></td>
                  <td>
                    <?php if($data['status'] == 'Approved'){?>
											<span class="label label-success"><?php  echo $data['status'];?></span>
										<?php } else{?>
											<span class="label label-warning"><?php  echo $data['status'];?></span>
										<?php }?>
                  </td>
                  <td>
                    <?php
                      $videoDate=date_create($data['created']);
                      echo date_format($videoDate,"d M Y H:i:s");
                    ?>
                  </td>
                  <td>
                    <!-- <?php //if($data['viewCount'] != 0){?> -->
                    <?php  
                      $videoId = $data['id'];
                      $viewCount = $this->db->query("SELECT COUNT(videoId) as viewCount FROM `adminVideoView` where videoId=$videoId ")->row_array();
                      if(!empty($viewCount)){
                      ?>
										  <a href="<?php echo site_url()?>/Videos/adminVideoCount/<?php echo  $data['id'];?>">	<span class="label label-success"><?php  echo $viewCount['viewCount'];?></span></a>
										<?php } else{?>
											<span class="label label-warning"><?php  echo '0';?></span>
										<?php }?>
                  </td>
                  <td>
                    <!-- <?php //if($data['hitCount'] != 0){?> -->
                    <?php  
                      $videoId = $data['id'];
                      $hitCount = $this->db->query("SELECT COUNT(videoId) as hitCount FROM `adminVideoHitCount` where videoId=$videoId ")->row_array();
                      if(!empty($hitCount)){
                      ?>
											<a href="<?php echo site_url()?>/Videos/adminVideoHitCount/<?php echo  $data['id'];?>">	<span class="label label-success"><?php  echo $hitCount['hitCount'];?></span></a>
										<?php } else{?>
											<span class="label label-warning"><?php  echo '0';?></span>
										<?php } ?>
                  </td>
                  <td>
                    <video  controls  loop muted style="float:right;width: 120px;height: 120px;">
                      <source src="<?php echo $data['videoPath'];?>" type="video/mp4">
                    </video>
                  </td>
									<td>
                    <ul class="admin-action btn btn-default" style="background-color: #f4f4f4;color: #fff !important;">
											<li class="dropdown">
											    <a class="dropdown-toggle" style="color: black;" data-toggle="dropdown" href="#" aria-expanded="false">
												  Action <span class="caret"></span>
												</a>
												<ul class="dropdown-menu dropdown-menu-right">
													<li role="presentation">
														<a role="menuitem" tabindex="-1" href="<?= site_url()?>/videos/editAdminVideo/<?= $data['id'];?>">Edit</a>
													</li>
													<li>
														<a role="menuitem" tabindex="-1" onclick="return confirm(&quot;Once you've deleted 	this account, the user (<?php  echo $data['videoTitle'];?>) will no longer be able to log in to the site or apps. This action cannot be undone.&quot;);" href="<?= site_url()?>/videos/deleteAdminVideo/<?= $data['id'];?>">Delete
											            </a>
													</li>
													<li role="presentation" class="divider"></li>
											        <li role="presentation">
														<a role="menuitem" onclick="return confirm(<?php  echo $data['videoTitle'];?> - Do you want <?php if($data['status'] == 'Approved'){ echo "Decline"; } else{ echo "Approve";}?> this user&quot;);" tabindex="-1" href="<?= site_url()?>/videos/updateAdminVideoStatus/<?= $data['id'];?>">
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

function setLimit(){
  var limiValue = $("#adminLimitValue").val();
  $.ajax({
		type :  'POST',
		url  : '<?php echo site_url()?>/Videos/adminLimitSet',
		data :  {limiValue : limiValue},
		success: function(result){
			if(result == 1){
        swal("Good job!", "Videos limit updated successfully", "success");
      }
      else{
        swal("OOPS!", "Videos limit updated successfully", "error");
      }
		}
  });
}

window.document.onkeydown = function(e) {
  //alert('hoge')
  if (!e) {
    e = event;
  }
  if (e.keyCode == 27) {
    lightbox_close();
  }
}

function lightbox_open() {
  var lightBoxVideo = document.getElementById("VisaChipCardVideo");
  window.scrollTo(0, 0);
  document.getElementById('light').style.display = 'block';
  document.getElementById('fade').style.display = 'block';
  lightBoxVideo.play();
}

function lightbox_close() {
  var lightBoxVideo = document.getElementById("VisaChipCardVideo");
  document.getElementById('light').style.display = 'none';
  document.getElementById('fade').style.display = 'none';
  lightBoxVideo.pause();
}

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
   			url:"<?= site_url()?>/videos/getpendResult",
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
                	var img = '<img src="<?php echo $data['image']?>" style="width:80px;height:80px;">';
           			html += '<tr><td>' + (i+1) + '</td><td>' + img + '</td><td>' + comment.username + '</td><td>' + comment.email + '</td><td>' + comment.phone +
                    '</td><td>' + comment.hash + '</td><td>' + comment.description + '</td><td><a role="menuitem" tabindex="-1" href="<?= site_url()?>/Videos/pendingView/<?php echo  $data['id'];?>"><i class="fa fa-eye" style="font-size: 22px;">' + "" + '</i></a></td></tr>';
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
<script>
   functin setLimit(){
     alert("hrllo");
   }

</script>
