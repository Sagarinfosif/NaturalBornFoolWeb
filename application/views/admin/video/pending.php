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
						<!--h3 class="box-title"><a href="<?= site_url();?>/User/addUser" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add User</a></h3-->
					</div>
					</form>
          <div class="main-data">
            <div class="col-md-12 filter_form" style="margin-left:60px;margin-top:10px;margin-bottom:20px">
              <form method="post" action="<?= site_url()."/videos/getShortVideoApprovedResult";?>" id="getPdf">
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
                          <span><input type="text" id="videoId" name="videoId" style="border-radius: 4px;border-style: groove;width: 111px;"></span>
                      </div>
                  </div>

                      <div class="col-md-2">
                        <div class="main-data-single-field">
                            <!-- <label style="font-weight: bold;">Sort By</label> -->
                            <div class="form-group">
                              <label for="sel1" style="font-weight: bold;">Sort By</label>
                              <select class="form-control" id="sel1" name="sort">
                                  <option value="">Sort By</option>
                                  <option value="likeCount desc">Sort By Like Descending</option>
                                  <option value="likeCount asc">Sort By Like Ascending</option>
                                  <option value="viewCount desc">Sort By View Descending</option>
                                  <option value="viewCount asc">Sort By View Ascending</option>
                                  <option value="created desc">Sort By Created Descending</option>
                                  <option value="created asc">Sort By Created Ascending</option>
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
					<!-- /.box-header -->
					<div class="box-body">
						<?php if($this->session->flashdata('success')){ ?>
							<div class="success-message">
								<script> swal("Good job!", "<?= $this->session->flashdata('success')?>!", "success") </script>
							</div>

						<?php }?>
            <button type="button" class="btn btn-danger" name="rejectBtn" id="rejectVideo">Reject</button>
            <button type="button" class="btn btn-warning" name="trendingBtn" id="trendVideo" >Trending</button>
            <button type="button" class="btn btn-info" name="nonViewedBtn" id="nonViewedVideo" >Non Viewed</button>
						<table id="example1" class="table table-bordered table-striped">
							<thead>
							<tr>
                <th><input type="checkbox" name="reject[]" value="" id="checkAll"></th>
								<th>Sr.</th>
                <th>Id</th>
								<th>User Image</th>
								<th>Username</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Hastag</th>
                <th>Video View</th>
                <th>Date/Time</th>
                <th>Video</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody id = "ts">

							<?php $i = $segmentCount; foreach($details as $data){ ?>
								<tr>
                  <td><input type="checkbox" name="reject" class="checkbox" value="<?php  echo $data['reg_id'];?>" id="<?php echo $data['id'] ?>"></td>
									<td><?= $i;?></td>
                  <th><?php echo $data['id']?></th>
									<td>
										<?php if(!empty($data['image'])){
											if (filter_var($data['image'], FILTER_VALIDATE_URL)) { ?>
											  <img src="<?php echo $data['image'] ?>" style="width:80px;height:80px;">
											<?php } else { ?>
											   	<img src="<?php echo base_url().$data['image']?>" style="width:80px;height:80px;">
											<?php }
											?>
										<?php } else{?>
											<img src="<?php echo base_url()?>uploads/demo.jpeg" style="width:80px;height:80px;">
										<?php }?>
      									</td>
      									<td><?php  echo $data['username'];?></td>
      									<td><?php  echo $data['email'];?></td>
      									<td><?php  echo $data['phone'];?></td>
      									<td><?php     $a = explode(',',$data['hashTag']);
                             $count = count($a);
                          if(!empty($data['hashtags'])) { ?>
                           <a role="menuitem" tabindex="-1" href="<?= site_url()?>/Videos/pendingView/<?php echo  $data['id'];?>">
                            <span class="label label-warning"><?= $count; ?></span>
                          </a>
                           <?php }else{ ?>
                            <a role="menuitem" tabindex="-1" href="<?= site_url()?>/Videos/pendingView/<?php echo  $data['id'];?>">
                              <span class="label label-info">0</span>
                            </a>
                            <?php }
                  ?></td>
                  <td><?php  echo $data['viewCount'];?></td>
                  <td>
                    <?php
                      $videoDate=date_create($data['created']);
                      echo date_format($videoDate,"d M Y H:i:s");
                    ?>
                  </td>
                  <td>
                    <video  controls  loop style="float:right;width: 120px;height: 120px;">
                      <source src="<?php echo $data['videoPath'];?>" type="video/mp4">
                    </video>
                  </td>
									<td>
										<a role="menuitem" tabindex="-1" href="<?= site_url()?>/Videos/pendingView/<?php echo  $data['id'];?>">
											<i class="fa fa-eye" style="font-size: 22px;"></i>
										</a>
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
// 	 $(document).ready(function(){
// 	 $("#search").click(function(){
// 		var start = $("#sdate").val();
// 		var end = $("#edate").val();
// 		var name = $("#pname").val();
//     	$.ajax({
//     		type:"post",
//    			url:"<?= site_url()?>/videos/getpendResult",
//     		data:{s:start,e:end,p:name},
//     		dataType: 'json',                //data format
//             success: function(data){
//             if(data ==''){
//             	$('#ts').html('<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr>');
//             }
//             else{
//             	var html, comment;
//                 for(var i = 0; i < data.length; i++) {
//                 	comment = data[i];
//                 	var img = '<img src="<?php echo $data['image']?>" style="width:80px;height:80px;">';
//            			html += '<tr><td>' + (i+1) + '</td><td>' + img + '</td><td>' + comment.username + '</td><td>' + comment.email + '</td><td>' + comment.phone +
//                     '</td><td>' + comment.hash + '</td><td>' + comment.description + '</td><td><a role="menuitem" tabindex="-1" href="<?= site_url()?>/Videos/pendingView/<?php echo  $data['id'];?>"><i class="fa fa-eye" style="font-size: 22px;">' + "" + '</i></a></td></tr>';
//             	}

//            		$('#ts').html(html);
//            		}
//        		}
//     	});
// 	});
// });
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
  $("#checkAll").click(function(){
           if (this.checked) {
               $('.checkbox').each(function() {
                   this.checked = true;
               });
           } else {
               $('.checkbox').each(function() {
                   this.checked = false;
               });
           }

  });


  $('.checkbox').on('click', function() {

           if ($('.checkbox:checked').length == $('.checkbox').length) {
               $('#checkall').prop('checked', true);
           } else {
               $('#checkall').prop('checked', false);
           }
       });

      $("#rejectVideo").click(function(){
        var m = [];
        //var id = $('.checkbox').attr("id");
        $('.checkbox:checked').each(function() {
                m.push(this.id);
           });

        var id = m.join(',');
      // alert(id);
        $.ajax({
          url:"<?php echo site_url() ?>/Videos/rejectStatus",
          type:"post",
          data:{id:id},
          success: function (data) {
            swal("succes",data,"success").then(function(){
                location.reload();
            })
          }
        });
});

$("#trendVideo").click(function(){
  var m = [];
  //var id = $('.checkbox').attr("id");
  $('.checkbox:checked').each(function() {
          m.push(this.id);
     });

  var id = m.join(',');
   //alert(id);
  $.ajax({
    url:"<?php echo site_url() ?>/Videos/trendingStatus",
    type:"post",
    data:{id:id},
    success: function (data) {
      // alert(data);
      swal("succes",data,"success").then(function(){
          location.reload();
      })
    }
  });
});


$("#nonViewedVideo").click(function(){
  var m = [];
  //var id = $('.checkbox').attr("id");
  $('.checkbox:checked').each(function() {
          m.push(this.id);
     });

  var id = m.join(',');
   //alert(id);
  $.ajax({
    url:"<?php echo site_url() ?>/Videos/nonViewedStatus",
    type:"post",
    data:{id:id},
    success: function (data) {
      // alert(data);
      swal("succes",data,"success").then(function(){
          location.reload();
      })
    }
  });
});



</script>
