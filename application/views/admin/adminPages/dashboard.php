<style>
  .dummyImage {
    width: 128px !important;
    height: 128px !important;
}
  </style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url()?>/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
          <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #06690d;color: #fff;">
            <span class="info-box-icon"><i class="fa fa-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Total Users</b></span>
              <span class="info-box-number"><?php echo $user; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #c72a16;color: #fff;">
            <span class="info-box-icon"><i class="fa fa-users" aria-hidden="true"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Total Moderators</b></span>
              <span class="info-box-number"><?php echo $mode; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #058d9e;color: #fff;">
            <span class="info-box-icon "><i class="fa fa-video-camera"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Total Videos</b></span>
               <span class="info-box-number"><?php echo $videos; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
		 <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #054c75;color: #fff;">
            <span class="info-box-icon "><i class="ion ion-bag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Total Revenue</b></span>
               <span class="info-box-number">$100</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      <!--   <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Likes</span>
              <span class="info-box-number">41,410</span>
            </div> -->
            <!-- /.info-box-content -->
          <!-- </div> -->
          <!-- /.info-box -->
        <!-- </div> -->
        <!-- /.col -->

        <!-- fix for small devices only -->

        <!-- /.col -->

      </div>
      <!-- /.row -->












                          <!--graphs-->

      <div class="row">
        <!-- Left col -->
    <section class="col-lg-12" style="padding-bottom: 10px">
        <div style="padding-right: 2em">

            <canvas id="myChart"  height="120"></canvas>

        </div>

        <div style="display: flex;flex-wrap: wrap">
            <div style="width: 50%">

                <div>
                    <canvas id="follow_chart"  height="150"></canvas>
                </div>


            </div>
            <div style="width: 50%">

                <div>
                    <canvas id="block_chart"  height="150"></canvas>
                </div>


            </div>
            <div style="width: 50%;margin-top: 2em;">

                <div>
                    <canvas id="view_video"  height="150"></canvas>
                </div>


            </div>
            <div style="width: 50%;margin-top: 2em;">

                <div>
                    <canvas id="like_video"  height="150"></canvas>
                </div>


            </div>
            <div style="width: 50%;margin-top: 2em;">

                <div>
                    <canvas id="comment_video"  height="150"></canvas>
                </div>


            </div>
            <div style="width: 50%;margin-top: 2em;">

                <div>
                    <canvas id="upload_video"  height="150"></canvas>
                </div>


            </div>
        </div>

    </section>


      </div>
      <!-- /.row (main row) -->



      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
          <!-- MAP & BOX PANE -->

          <div class="row">

            <div class="col-md-12">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Members</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">8 New Members</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                    <?php foreach($topEagUser as $topEagUsers){?>
                      <li>
                        <?php if(empty($topEagUsers['image'])){?>
                        	<img class="dummyImage" src="<?php echo base_url()?>/uploads/dummy.png" alt="User Image">
                        <?php } else{
                          $url = $topEagUsers['image'];

                          if (filter_var($url, FILTER_VALIDATE_URL)) { ?>
                              <img class="dummyImage" src="<?php echo $topEagUsers['image'];?>" alt="User Image">
                          <?php } else { ?>
                              <img class="dummyImage" src="<?php echo base_url().$topEagUsers['image'];?>" alt="User Image">
                         <?php } }

                        ?>

                        <a class="users-list-name" href="<?php echo site_url()?>/User/view/<?php echo $topEagUsers['id']?>"><?php echo $topEagUsers['username']?></a>
                        <span class="users-list-date">
                        	<?php
                          		$originalDate = $topEagUsers['created'];
								echo date("d M", strtotime($originalDate));
                          	?>
                        </span>
                      </li>
                    <?php }?>
                  </ul>
                </div>
                <div class="box-footer text-center">
                  <a href="<?php echo site_url()?>/User/manage" class="uppercase">View All Users</a>
                </div>
              </div>
            </div>
          </div>

          <!-- TABLE: LATEST ORDERS -->
          <!--div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Popularity</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                    <td>Call of Duty IV</td>
                    <td><span class="label label-success">Shipped</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>iPhone 6 Plus</td>
                    <td><span class="label label-danger">Delivered</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="label label-info">Processing</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>iPhone 6 Plus</td>
                    <td><span class="label label-danger">Delivered</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                    <td>Call of Duty IV</td>
                    <td><span class="label label-success">Shipped</span></td>
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
            <!--div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <!-- Info Boxes Style 2 -->
          <!-- PRODUCT LIST -->
          <!--div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Added Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo base_url();?>assets/dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Samsung TV
                      <span class="label label-warning pull-right">$1800</span></a>
                    <span class="product-description">
                          Samsung 32" 1080p 60Hz LED Smart HDTV.
                        </span>
                  </div>
                </li>
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo base_url();?>assets/dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Bicycle
                      <span class="label label-info pull-right">$700</span></a>
                    <span class="product-description">
                          26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                        </span>
                  </div>
                </li>
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo base_url();?>assets/dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Xbox One <span
                        class="label label-danger pull-right">$350</span></a>
                    <span class="product-description">
                          Xbox One Console Bundle with Halo Master Chief Collection.
                        </span>
                  </div>
                </li>
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo base_url();?>assets/dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">PlayStation 4
                      <span class="label label-success pull-right">$399</span></a>
                    <span class="product-description">
                          PlayStation 4 500GB Console (PS4)
                        </span>
                  </div>
                </li>
              </ul>
            </div>
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
          </div-->
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>

    $(function () {


        var dates = [];
        for (var i = 29; i >= 0; i--) {
            var current = new Date();
            current.setDate(current.getDate() - i);
            dates.push(current.toDateString());
        }





        var ctx = document.getElementById('myChart').getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    fill:'origin',
                    label: 'User\'s joined in last 30 days',
                    data: [<?php echo $chartUserCount;?>],
                    backgroundColor:'#69cd3c',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $("#myChart").click(function (evt) {

            var firstPoint = myChart.getElementAtEvent(evt)[0];

            if (firstPoint) {
                var label = new Date(myChart.data.labels[firstPoint._index]);
                // var value = myChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
                var date = label.getFullYear()+"-"+(parseInt(label.getMonth())+1)+"-"+label.getDate();



                window.location.href = "<?php echo site_url()."/user/graphResult/"?>"+date;
            }

        });


        // follow chart


        var follow = document.getElementById('follow_chart').getContext('2d');

        var follow_chart = new Chart(follow, {
            type: 'bar',
            data: {
                labels: ['<?php echo $labels; ?>'],
                datasets: [{
                    label: 'user\'s following',
                    data: [<?php echo $datasets; ?>],
                    backgroundColor:'#4633FF',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $("#follow_chart").click(function (evt) {

            var follow_firstPoint = follow_chart.getElementAtEvent(evt)[0];

            if (follow_firstPoint) {
                let follow_label = follow_chart.data.labels[follow_firstPoint._index];
                let sliced = follow_label.slice(1);

                window.location.href = "<?php echo site_url()."/user/followGraph/"; ?>"+sliced;
            }

        });

         // blockeduser chart


        var block = document.getElementById('block_chart').getContext('2d');

        var block_chart = new Chart(block, {
            type: 'bar',
            data: {
                labels: ['<?php echo $block_labels; ?>'],
                datasets: [{
                    label: 'user\'s blocked',
                    data: [<?php echo $block_datasets; ?>],
                    backgroundColor:'#4633FF',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $("#block_chart").click(function (evt) {

            var block_firstPoint = block_chart.getElementAtEvent(evt)[0];

            if (block_firstPoint) {
                let block_label = block_chart.data.labels[block_firstPoint._index];
                let sliced = block_label.slice(1);

                window.location.href = "<?php echo site_url()."/user/blockGraph/"; ?>"+sliced;
            }

        });



        // most viewed video graph



        var view = document.getElementById('view_video').getContext('2d');

        var viewVideo = new Chart(view, {
            type: 'bar',
            data: {
                labels: ['<?php echo $views_video_labels; ?>'],
                datasets: [{
                    label: 'Video views',
                    data: [<?php echo $views_video_datasets; ?>],
                    backgroundColor:'#1F91F0',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $('#view_video').click(function (evt) {
            var view_first = viewVideo.getElementAtEvent(evt)[0];

            if (view_first) {
                let view_label = viewVideo.data.labels[view_first._index];

                window.location.href = "<?php echo site_url()."/videos/video_views_graph/"; ?>"+view_label;
            }
        });




        //most liked video graph


        var liked = document.getElementById("like_video").getContext('2d');

        var likeVideo = new Chart(liked,{
            type:'bar',
            data:{
                labels:['<?php echo $like_labels; ?>'],
                datasets:[{
                    label:'Video likes',
                    data:[<?php echo $like_datasets; ?>],
                    backgroundColor:'#1F91F0',
                    borderWidth: 1
                }]
            },
            options:{
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $("#like_video").click(function (evt) {
            var like_first = likeVideo.getElementAtEvent(evt)[0];
            if (like_first) {
                let like_label = likeVideo.data.labels[like_first._index];
                window.location.href = "<?php echo site_url()."/videos/video_likes_graph/"; ?>"+like_label;
            }
        });


        // graph for most commented video


        var comment = document.getElementById("comment_video").getContext('2d');

        var commentVideo = new Chart(comment,{
            type:'bar',
            data:{
                labels:['<?php echo $comment_labels; ?>'],
                datasets:[{
                    label:'Video Comments',
                    data:[<?php echo $comment_datasets; ?>],
                    backgroundColor:'#6a5acd',
                    borderWidth: 1
                }]
            },
            options:{
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $("#comment_video").click(function (evt) {
            var comment_first = commentVideo.getElementAtEvent(evt)[0];
            if (comment_first) {
                let comment_label = commentVideo.data.labels[comment_first._index];
                window.location.href = "<?php echo site_url()."/videos/video_comment_graph/"; ?>"+comment_label;
            }
        });



        //   most uploaded videos


        var upload = document.getElementById("upload_video").getContext('2d');

        var uploadVideo = new Chart(upload,{
            type:'bar',
            data:{
                labels:['<?php echo $upload_labels; ?>'],
                datasets:[{
                    label:'Video\'s uploaded',
                    data:[<?php echo $upload_datasets; ?>],
                    backgroundColor:'#6a5acd',
                    borderWidth: 1
                }]
            },
            options:{
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        $("#upload_video").click(function (evt) {
            var upload_first = uploadVideo.getElementAtEvent(evt)[0];
            if (upload_first) {
                let upload_label = uploadVideo.data.labels[upload_first._index];
                let upload_sliced = upload_label.slice(1);
                window.location.href = "<?php echo site_url()."/videos/video_upload_graph/"; ?>"+upload_sliced;
            }
        });

    });
</script>