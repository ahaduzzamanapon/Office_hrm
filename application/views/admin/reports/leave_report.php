<?php 

$stype="Monthly Leave report";
?>
<?php
if(count($xin_employees)<1){
echo "<p style='text-align: center;font-weight: bold;color: red;font-size: xx-large;'>There is no data</p>";
exit();
}
?>

<link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_hrsale_custom.css">

<style type="text/css">
	.box-tools {
	    margin-right: -5px !important;
	}
	table{
		margin-top: 15px !important;
	}

	table thead tr th {
		font-size:12px;
		padding: 3px !important;
	}

	table tbody tr td {
		font-size: 12px;
		padding: 3px !important;
	}

	@media print {
		.box-tools {
	    margin-right: -5px !important;
		}

		table thead tr th {
			font-size: 12px;
			padding: 3px !important;
		}

		table tbody tr td {
			font-size: 14px;
			padding: 3px !important;
		}
		body {
			zoom: 80% !important;
		}
	}

</style>
<?php


?>
<div class="box" id="print_area">
  <div style="text-align: center;">
   <?php  $this->load->view('admin/head_bangla'); ?>
   <h4><?= $stype ?></h4>
  </div>
  <div class="container">
  	<div class="box-body">
	    <div class="box-datatable table-responsive">
	      <table class="table table-striped table-bordered">
	        <thead>
                <td>Sl. No.</td>
                <td>Employee Name</td>
                <td>From</td>
                <td>to</td>
                <td>Type</td>
                <td>Qty</td>
                <td>Reason</td>
                <td>Total Leave Balance</td>
                <td>Status</td>
	        </thead>
			

            	<?php 
						
					$total_el_leave=0;
					$total_si_leave=0;
					$total_day=0;
					$totalemp=[];

			foreach($xin_employees as $key=>$row){
				if(!in_array($row->employee_id,$totalemp)){
					array_push($totalemp,$row->employee_id);
				};

                $user_info = $this->Xin_model->read_employee_info($row->employee_id);
                
                ?>
                <td><?php echo $key+1;?></td>
                <td><?php echo $user_info[0]->first_name.' '.$user_info[0]->last_name?></td>
				  
				<?php
				  $toDateString = $row->from_date;
				  $dayName = date('l', strtotime($toDateString));
				  
				 
			     ?>
                <td><?php echo $row->from_date. ' ('.$dayName. ')'?></td>
				
				  <?php
				  $toDateString = $row->to_date;
				  $dayName = date('l', strtotime($toDateString));
				  
				 
			     ?>
				
                <td><?php echo $row->to_date . ' ('.$dayName.')'?></td>
				<?php
                if ($row->leave_type=='el') {
					$total_el_leave +=$row->qty;

                    echo "<td class='text' >Earn Leave</td>";
                }else{
					$total_si_leave +=$row->qty;

					echo "<td class='text' >Sick Leave</td>";
				}
                ?>

                <td><?php

				$total_day += $row->qty;

				echo $row->qty?>
				</td>
                <td>
						<?= $row->reason?>

			    </td>
				
				<td>
					<?php 
						$data = leave_cal($row->employee_id);
						// dd($data);
						echo "Earn Leave = ".$data['leaves'][0]['qty'] . ", Sick Leave = ".$data['leaves'][1]['qty'];
					?>
				</td>
                <?php
                if ($row->status==1) {
                    echo "<td class='text text-info' >Pending</td>";
                }elseif ($row->status==2) {
                    echo "<td class='text text-success' >Approved</td>";
                }elseif ($row->status==3) {
                    echo "<td class='text text-danger' >Rejected</td>";
                }
                ?>
            </tbody>
            <?php } ?>

	      </table>
		  <table class="table">
			<thead>
				<tr>
					<th>Total Employee</th>
					<th>Total leave</th>
					<th>Total Earn Leave</th>
					<th>Total Sick Leave</th>
					
				</tr>
			</thead>
			<tbody>
				<tr>
				   <td><?=count($totalemp)?></td>
				   <td><?=$total_day?></td>
				   <td><?=$total_el_leave?></td>
				   <td><?=$total_si_leave?></td>
				  
					
				</tr>
			</tbody>
		  </table>
	    </div>
	  </div>
  </div>
</div>
<?php
			


?>
<!-- jQuery 3 -->
<script type="text/javascript" src="<?php echo base_url();?>skin/hrsale_assets/vendor/jquery/jquery-3.2.1.min.js"></script> 
<script src="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/jquery/dist/jquery.min.js"></script>

<script src="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->