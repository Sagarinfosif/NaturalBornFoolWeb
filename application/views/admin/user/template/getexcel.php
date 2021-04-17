

	<table id="example1" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>Sr.</th>
								<th>Username</th>
								<th>Email</th>
								<th>Phone</th>

							</tr>
							</thead>
							<tbody>
							<?php $i = 1; foreach($details as $data){ ?>
								<tr>
									<td><?= $i;?></td>
									<td><?php  echo $data['username'];?></td>
									<td><?php  echo $data['email'];?></td>
									<td><?php  echo $data['phone'];?></td>


								</tr>
							<?php $i++; } ?>
							<?php
    					header('Content-Type: application/xls');
             header('Content-Disposition: attachment; filename=download.xls');
						             ?>
							</tbody>
						</table>
