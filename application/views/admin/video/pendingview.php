<style>
select#status {
    border-top: none;
    border-left: none;
    border-right: none;
}
.label-success {
  animation: blinker 3s linear infinite;
}

@keyframes blinker {
  30% { opacity: 0; }
}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/Videos/<?php echo $backFunction?>"><?php echo $title ?></a></li>
			<li class="active"><?= $title;?></li>
		</ol>
	</section>
	<section class="content">
			<?php if($this->session->flashdata('success')){ ?>
							<div class="success-message">
								<script> swal("Good job!", "<?= $this->session->flashdata('success')?>!", "success") </script>
							</div>

						<?php }?>
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
					<div class="box box-warning">
						<div class="box-header with-border" style="display: flex;">
							<?php if(!empty($details['image'])){
								if (filter_var($details['image'], FILTER_VALIDATE_URL)) { ?>
									<img src="<?php echo $details['image'] ?>" style="width:60px;height:60px;border-radius: 50%;">
								<?php } else { ?>
										<img src="<?php echo base_url().$details['image']?>" style="width:60px;height:60px;border-radius: 50%;">
								<?php }
								?>
							<?php } else{?>
								<img src="<?php echo base_url()?>uploads/demo.jpeg" style="width:60px;height:60px;border-radius: 50%;">
							<?php }?>
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
											<a href="#">hashTag</a>
											<span><?php  echo $details['hashtags'];?></span>
										</li>
										<li>
											<a href="#">Description</a>
											<span><?php  echo $details['description'];?> </span>
										</li>

										<li>
											<a href="<?php echo site_url()?>/Videos/Likes/<?php echo $details['id']?>">Likes</a>

											<span class="label label-success"><a href="" data-toggle="modal" onclick="likeVideo(this.id)" id="<?php echo $details['id'];?>" data-target="#exampleModaldd" style="color: inherit;"><?php echo $details['likeCount'];?></a></span>
										</li>
										<!-- <li>
											<a href="<?php echo site_url()?>/Videos/Likes/<?php echo $details['id']?>">Likes</a>
											<span class="label label-success"><a href="<?php echo site_url()?>/Videos/Likes/<?php echo $details['id']?>" style="color: inherit;"><?php echo $details['likeCount'];?> </a></span>
										</li> -->
										<li>
											<a href="<?php echo site_url()?>/Videos/Views/<?php echo $details['id']?>">Views</a>
											<span class="label label-success"><a href="" data-toggle="modal" onclick="viewCount(this.id)" id="<?php echo $details['id'];?>" data-target="#exampleModaldd123" style="color: inherit;"><?php echo $details['viewCount'];?></a></span>

										</li>
										<!-- <li>
											<a href="<?php echo site_url()?>/Videos/Views/<?php echo $details['id']?>">Views</a>
											<span class="label label-success"><a href="<?php echo site_url()?>/Videos/Views/<?php echo $details['id']?>" style="color: inherit;"><?php  echo $details['viewCount'];?> </a></span>
										</li> -->
										<li>
											<a href="<?php echo site_url()?>/Videos/Comments/<?php echo $details['id']?>">Comments</a>
											<span class="label label-success"><a href="<?php echo site_url()?>/Videos/Comments/<?php echo $details['id']?>" style="color: inherit;"><?php  echo $details['commentCount'];?></a></span>
										</li>
										<li>
											<a href="#">Sound File</a><br><br>
											<audio controls src="<?php echo base_url().$details['sound'];?>"></audio>
										</li>
									    <li style="border-bottom: none;padding: 0px;">
				                      <select class="form-control" id="status" name="status">
				                          <option value="0">Select  Status</option>
											<?php if($details['status'] == 1){?>

						                            <option value="2">Rejected</option>
						                    	    <option value="0">Viewed</option>
						                            <option value="3">Non Viewed</option>

											<?php } elseif($details['status'] == 2){?>

													<option value="1">Trending</option>
													<option value="0">Viewed</option>
													<option value="4">delete</option>

											<?php } elseif($details['status'] == 3){?>

													<option value="1">Trending</option>
													<option value="0">Viewed</option>
						                            <option value="2">Rejected</option>

												<?php }
												else{ ?>

													<option value="1">Trending</option>
													<option value="3">Non Viewed</option>
						                            <option value="2">Rejected</option>

												<?php }

												?>

			                          </select>

										</li>

						              <li id ="comment" style="display:none">
  						               <form method="post" action="<?php echo site_url() ?>/Videos/rejVideos/<?php echo $details['id']; ?>">
    						                 <div class="form-group" >
              											<label>Comment</label>
              											<textarea name="comment" class="form-control"><?= set_value('comment');?></textarea>
              											<div class="form-error1"><?= form_error('comment');?></div>
      							             </div>
      							            <button type="submit" class="btn btn-danger">Reject</button>
							            	 </form>
						              </li>

									</div>

									<div class="col-md-6">

									    <video height="50%" width="50%" controls autoplay loop style="float:right">
                                          <source src="<?=$details['videoPath'];?>" type="video/mp4">
                                        </video>
                                        <!-- <iframe src="<?php echo base_url().$details['videoPath'];?>" height="20%" width="50%" style="float:right"></iframe> -->
									</div>
								</ul>

							</div>
						</div>
						<br>


					</div>
			</div>
			<div class="col-md-1">
			</div>
		</div>
	</section>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="modal fade" id="exampleModaldd" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Video Like Count </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="email_address1">Like Count</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="message" class="form-control" placeholder="Enter......... ">
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
    <div class="modal fade" id="exampleModaldd123" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Video View Count </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="email_address1">View Count</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="viewCount" class="form-control" placeholder="Enter......... ">
                        </div>
                    </div>
                </div>
                <input type="hidden" value="" id="videoID">
                <div class="modal-footer">
                    <button type="button" id="viewCountSubmit" class="btn btn-info waves-effect">Save</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
	// $("document").ready(function(){
	// 	$("#status").change(function(){
	// 		var status = $("#status").val();
	// 		var id = "<?php echo $details['id']?>";

	// 		if(status != '0'){
	// 			$.ajax({
	// 				type :  'POST',
	// 				url  : '<?php echo site_url()?>/Videos/videoStatus',
	// 				data :  {status : status,id:id},
	// 				success: function(result){
	// 					swal(result);
	// 				}
	//       });
	// 		}
	// 	});
	// });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	$("document").ready(function(){
		$("#status").change(function(){
			var status = $("#status").val();
			//alert(status);
			var id = "<?php echo $details['id']?>";
			var userId = "<?php echo $details['userId']; ?>";
			//alert(status);
			if(status==2){
          	  $("#comment").show(1000);
            }

	    else{

	    	$.ajax({
					type :  'POST',
					url  : '<?php echo site_url()?>/Videos/videoStatus',
					data :  {status : status,id:id,userId:userId},
					success: function(result){
						//alert(result);
					 swal("success",result,"success").then(function(){
					 	location.reload();
					 })
					}
	        });
	    }

	});
});
</script>

<script>
  function likeVideo(id){
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
        url: "<?php echo site_url()?>/videos/updateLikeCount",
        data:{id:id,message:message},
        success: function(result){
          // $('#exampleModaldd').modal('toggle');
          if(result == 1){
            alert('Video Like Count updated successfully');

             window.open("<?php echo site_url()?>/Videos/pendingView/"+id,'_self');
          }
          else{
            alert('Video Like Count not updated');
             window.open("<?php echo site_url()?>/Videos/pendingView/"+id,'_self');
          }
       }
      });
    });
  });
</script>
<script>
  function viewCount(id){
    var id = id;
    document.getElementById("videoID").value = id;
  }

  $(document).ready(function(){
    $("#viewCountSubmit").click(function(){
      var id = $("#videoID").val();
      var viewCount = $("#viewCount").val();
      // alert(message);

      $.ajax({
        type: 'post',
        url: "<?php echo site_url()?>/videos/updateViewCount",
        data:{id:id,viewCount:viewCount},
        success: function(result){
          // $('#exampleModaldd').modal('toggle');
          if(result == 1){
            alert('Video view count updated successfully');
            window.open("<?php echo site_url()?>/Videos/pendingView/"+id,'_self');
          }
          else{
            alert('View Count not updated');
             window.open("<?php echo site_url()?>/Videos/pendingView"+id,'_self');
          }
       }
      });
    });
  });
</script>
