<style>
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
    border: 1px solid #a6812d !important;
    background: #a6812d !important;
    font-weight: normal !important;
    color: #ffffff !important;
}

    .ui-widget-header {
    border: 1px solid #a6812d !important;
    background: #a6812d !important;
    color: #eaf5f7 !important;
    font-weight: bold !important;
}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?= $title;?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url();?>/User/userReport">Manage Report</a></li>
			<li class="active"><?= $title;?></li>
		</ol>
	</section>
	            <section class="content">

					<div class="box box-warning">
						<div class="box-header with-border">
							<h3 class="box-title"><?= $title;?></h3>
						</div>
						<div class="box-body">
						    <div class="col-md-2" >
							<div class="form-group">
								<label>Start Date*</label>
								<input type="text" class="form-control" id="startdate" placeholder="startdate">
							</div>
							</div>
							<div class="col-md-2" >
							<div class="form-group">
								<label>End Date*</label>
								<input type="text" class="form-control"  id="enddate" placeholder="enddate">
							</div>
							</div>
							<div class="col-md-2" >
							 <div class="form-group">
                              <label for="sel1">Select Status:</label>
                              <select class="form-control" id="status">
                                  <option value="">Select your status</option>
                                <option value="1">Online</option>
                                <option value="0">Offline</option>
                              </select>
                            </div>
                            </div>
                            <div class="col-md-4" >
							<div class="form-group">
								<label>Search :</label><br>

							    <input type="text" placeholder="username" name="search" id="search">
                                <button type="submit" class="find"><i class="fa fa-search"></i></button>
							</div>
							</div>
                            <div class="col-md-2" >
          <form id="getform" >
							<div class="form-group">
							    	<label>  </label><br>
								<i class="fa fa-file-excel-o" aria-hidden="true" id="getexcel" style="color:green; font-size:40px;"></i>

								<i class="fa fa-file-pdf-o" aria-hidden="true" id="getpdf" style="color:red; font-size:40px;"></i>

							</div>
							</form>
							</div>
						</div>
					</div>
		        	<div class="row">
            			<div class="col-xs-12">
            		   <!-- /.box -->
            				<div class="box">

            					<div class="box-body" id="report">
            						<table id="example1" class="table table-bordered table-striped" id="table_search">
            							<thead>
            							<tr>
            								<th>Sr.</th>
            								<th>Username</th>
            								<th>Email</th>
            								<th>Phone</th>
            								<th>Bio</th>
            								<th>Date of birth</th></th>
            								<th>Status</th>
            							</tr>
            							</thead>
            							<tbody>
            							<?php $i = 1; foreach($details as $data){ ?>
            								<tr>
            									<td><?= $i;?></td>
            									<td><?php  echo $data['username'];?></td>
            									<td><?php  echo $data['email'];?></td>
            									<td><?php  echo $data['phone'];?></td>
            									<td><?php  echo $data['bio'];?></td>
            									<td><?php  echo $data['dob'];?></td>
            									<td>
            										<?php if($data['status'] == 'Approved'){?>
            											<span class="label label-success"><?php  echo $data['status'];?></span>
            										<?php } else{?>
            											<span class="label label-warning"><?php  echo $data['status'];?></span>
            										<?php }?>
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

	</section>





</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"
				type="text/javascript"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css"
			rel="Stylesheet" type="text/css" />
<script type="text/javascript">
		$(function () {
				$("#startdate").datepicker({
				    dateFormat: "yy/mm/dd" ,
						numberOfMonths: 1,
						maxDate:0,
						onSelect: function (selected) {
								var dt = new Date(selected);
								dt.setDate(dt.getDate() + 1);
								$("#enddate").datepicker("option", "minDate", dt);
						}
				});
				$("#enddate").datepicker({
				    dateFormat: "yy/mm/dd" ,
						numberOfMonths: 1,
					maxDate:0,
						onSelect: function (selected) {
								var dt = new Date(selected);
								dt.setDate(dt.getDate() - 1);
								$("#startdate").datepicker("option", "maxDate", dt);
						}
				});
		});
</script>
<!--search -->

<script>
    $(document).ready(function() {
             $(".find").click(function(){
				var startdate = $("#startdate").val();
				var enddate = $("#enddate").val();
				var status = $("#status").val();
				var search = $("#search").val();
                $.ajax({
                  type: "post",
                  url: "<?php echo site_url();?>/User/search",
                  data: {startdate:startdate,enddate:enddate,status:status,search:search },
                  success: function(data) {
                           $("#report").replaceWith(data);
          }
        });
      });

    });
</script>

<!--excel script    -->
<script>
    $(document).ready(function() {
        $('#getexcel').on('click', function() {
            var action = '<?php echo site_url();?>/User/getReportexcel';
            $('#getform').attr('action', action);
            $('#getform').submit();

        });
    });

</script>

<!--pdf script  -->
<script>
     $("#getpdf").click(function() {
        var action = '<?php echo site_url();?>/User/getReportpdf';
        $('#getform').attr('action', action);
        $('#getform').attr('target', "_blank");
        $('#getform').submit();
    });


</script>
