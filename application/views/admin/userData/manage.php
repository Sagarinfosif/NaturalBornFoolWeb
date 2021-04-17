<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
<style>
	button.multiselect.dropdown-toggle.btn.btn-default {
	    width: 100%;
	}
	.btn-group.open {
	    width: 100%;
	}
	ul.multiselect-container.dropdown-menu {
    width: 100% !important;
	}
	.btn-group {
    width: 100%;
	}
	/*.multiselect-container>li>a>label>input[type=checkbox] {
    margin-left: -20px;
		width: 10% !important;
	}
	.radio input[type="radio"], .checkbox input[type="checkbox"] {
    margin-left: 0px !important;
		width: 10% !important;
  }*/
	div#ccity ::placeholder {
    color: red;
    font-weight: 400;
	}

div#example1_filter {
    float: right;
}

.paging_simple_numbers{
    float: right;
}
.dataTables_empty{
  text-align: center;
}
tr.ap {
    height: 21px;
}
.userCount {
    background-color: #b6dede !important;
}
.dataCount {
    background-color: antiquewhite !important;
}
.selectOne {
    padding: 10px;
}
.form-group {
    width: 50%;
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
						<!--h3 class="box-title"><a href="<?= site_url();?>/Category/addCategory" style="font-size: 14px;" class="btn btn-block btn-success btn-xs">Add Category</a></h3-->
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php if($this->session->flashdata('success')){ ?>
							<div class="success-message">
								<script> swal("Good job!", "<?= $this->session->flashdata('success')?>!", "success") </script>
							</div>
						<?php }?>
						<table id="example1" class="table table-bordered table-striped">
							<tbody>
							<tr>
								<th rowspan = "3"><b>A</b></th>
								<th>Data</th>
								<th>0</th>
								<th>1</th>
								<th>2</th>
								<th>3</th>
								<th>4</th>
								<th>5</th>
								<th>6</th>
								<th>7</th>
								<th>8</th>
								<th>9</th>
							</tr>
							<tr class="dataCount">
								<td><?= 'Data Count';?></td>
								<td><?= $details['A0'];?></td>
								<td><?= $details['A1'];?></td>
								<td><?= $details['A2'];?></td>
								<td><?= $details['A3'];?></td>
								<td><?= $details['A4'];?></td>
								<td><?= $details['A5'];?></td>
								<td><?= $details['A6'];?></td>
								<td><?= $details['A7'];?></td>
								<td><?= $details['A8'];?></td>
								<td><?= $details['A9'];?></td>
							</tr>
							<tr class="userCount">
								<td><?= "User Count";?></td>
								<td><?= $userCount['A0'];?></td>
								<td><?= $userCount['A1'];?></td>
								<td><?= $userCount['A2'];?></td>
								<td><?= $userCount['A3'];?></td>
								<td><?= $userCount['A4'];?></td>
								<td><?= $userCount['A5'];?></td>
								<td><?= $userCount['A6'];?></td>
								<td><?= $userCount['A7'];?></td>
								<td><?= $userCount['A8'];?></td>
								<td><?= $userCount['A9'];?></td>
							</tr>
							<tr class="ap"></tr>
							<tr class="ap"></tr>
							<tr>
								<th rowspan = "3"><b>B</b></th>
								<th>Data</th>
								<th>0</th>
								<th>1</th>
								<th>2</th>
								<th>3</th>
								<th>4</th>
								<th>5</th>
								<th>6</th>
								<th>7</th>
								<th>8</th>
								<th>9</th>
							</tr>
							<tr class="dataCount">
								<td><?= "Data Count";?></td>
								<td><?= $details['B0'];?></td>
								<td><?= $details['B1'];?></td>
								<td><?= $details['B2'];?></td>
								<td><?= $details['B3'];?></td>
								<td><?= $details['B4'];?></td>
								<td><?= $details['B5'];?></td>
								<td><?= $details['B6'];?></td>
								<td><?= $details['B7'];?></td>
								<td><?= $details['B8'];?></td>
								<td><?= $details['B9'];?></td>
							</tr>
							<tr class="userCount">
								<td><?= "User Count";?></td>
								<td><?= $userCount['B0'];?></td>
								<td><?= $userCount['B1'];?></td>
								<td><?= $userCount['B2'];?></td>
								<td><?= $userCount['B3'];?></td>
								<td><?= $userCount['B4'];?></td>
								<td><?= $userCount['B5'];?></td>
								<td><?= $userCount['B6'];?></td>
								<td><?= $userCount['B7'];?></td>
								<td><?= $userCount['B8'];?></td>
								<td><?= $userCount['B9'];?></td>
							</tr>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->

          <div class="selectOne">
            <h3>Result Out</h3>
            <div style="display: flex;">
              <div class="form-group">
                <label for="sel1">A List:</label>
                <select class="form-control" id="aLish">
				          <option value=""><?= "Select A Result"; ?></option>
                  <?php for($i = 0; $i <= 9; $i++){?>
                    <option value="<?= $i; ?>" <?php if($resultOut['resultA'] == $i){echo "selected";}?>><?= $i; ?></option>
                  <?php }?>
                </select>
              </div>


              <div class="form-group" style="margin-left: 15px;">
                <label for="sel1">B List:</label>
                <select class="form-control" id="blisy">
				          <option value=""><?= "Select B Result"; ?></option>
                  <?php for($i = 0; $i <= 9; $i++){?>
                    <option value="<?= $i; ?>" <?php if($resultOut['resultB'] == $i){echo "selected";}?>><?= $i; ?></option>
                  <?php }?>
                </select>
              </div>

            </div>

            <h3>Result Out</h3>
            <div>
              <div class="form-group">
				<label for="sel1">A List:</label>
					<?php for($i = 0; $i <= 9; $i++){?>
						<input type="checkbox" class="Alist" id = "A".<?= $i?>>   <?= $i;?>&nbsp&nbsp
					<?php } ?>
			</div>
              <div class="form-group" style="margin-left: 15px;">
                <label for="sel1">B List:</label>
					<?php for($i = 0; $i <= 9; $i++){?>
						<input type="checkbox" class="Blist" id = "B".<?= $i?>>   <?= $i;?>&nbsp&nbsp
					<?php } ?>
              </div>

            </div>

          <div>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script>
	$(document).ready(function(){
		$('input[type="checkbox"]').click(function(event) {
		if (this.checked && $('.Alist:checked').length > 9) {
			event.preventDefault();
			alert('You\'re not allowed to choose more than 9 boxes');
		}
		if (this.checked && $('.Blist:checked').length > 9) {
			event.preventDefault();
			alert('You\'re not allowed to choose more than 9 boxes');
		}
	});
	});
</script>
<script>
	$(document).ready(function(){
	$('#aLish').change(function(){
	  var valA = $('#aLish').val();
	  $.ajax({
		type : 'post',
		url : '<?= site_url();?>/UserData/resultA',
		data : {valA:valA},
		success : function(result){
		  // alert(result);
		}
	  });
	});
	});
</script>

<script>
	$(document).ready(function(){
	$('#blisy').change(function(){
	 var valB = $('#blisy').val();
	 $.ajax({
	   type : 'post',
	   url : '<?= site_url();?>/UserData/resultB',
	   data : {valB:valB},
	   success : function(result){
		 // alert(result);
	   }
	 });
	});
	});
</script>
