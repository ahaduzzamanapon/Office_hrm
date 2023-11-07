<link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_hrsale_custom.css">
<body style="background:white">
<?php  $this->load->view('admin/head_bangla'); ?>

<h4 class="text-center">Report of Employee List</h4>
<table class="table table-striped table-bordered table-responsive">
    <thead style="font-size:12px;" >
        <tr>
            <th class="text-center">S.N</th>
            <th class="text-center">Name</th>
            <th class="text-center">Designation</th>
            <th class="text-center">Department</th>
            <th class="text-center">Team Leader</th>
            <th class="text-center">Email</th>
            <th class="text-center">Phone</th>
            <th class="text-center">Address</th>

            <?php if($session['role_id'] ==1){?>
            <th class="text-center">Gross Salary</th>
            <?php }?>
            <th class="text-center">Joining Date</th>
            <th class="text-center">Job Duration</th>
            <th class="text-center">Last Increment Date</th>
            <th class="text-center">Next Increment Date</th>
            <th class="text-center">PC Password</th>
            <th class="text-center">Using Device</th>
        </tr>
    </thead>
    <tbody style="font-size:12px;" >
        <?php  $i=1; foreach ($emp_list as $key => $value) {?>
        <tr>
            <td><?= $i++?></td>
            <td><?= $value->first_name.' '.$value->last_name?></td>
            <td><?= $value->department_name?></td>
            <td><?= $value->designation_name?></td>
            <td><?= $value->lead_first_name.' '.$value->lead_last_name?></td>
            <td><?= $value->email?></td>
            <td><?= $value->contact_no?></td>
            <td><?= $value->address?></td>
            <?php 
                $this->db->select('pam.model_name,pac.cat_name,pac.cat_short_name,pa.device_name_id');
                $this->db->from('product_accessories as pa');
                $this->db->join('product_accessory_categories as pac','pa.cat_id = pac.id','left');	
                $this->db->join('product_accessories_model as pam','pa.device_model = pam.id','left');
                $this->db->where_in('pa.user_id',$value->user_id);
                $query = $this->db->get()->result();
                // dd($query);
            ?>

            <?php if($session['role_id'] ==1){?>
            <td><?= $value->basic_salary?></td>
            <?php }?>
            <td><?= $value->date_of_joining?></td>
            <?php 
                $years = floor($value->duration / 365); 
                $remainingDays = $value->duration % 365;
                $months = floor($remainingDays / 30); 
                $remainingDays = $remainingDays % 30;
            ?>
            <td><?= ($years == 0 ? '': $years.' years ').$months.' months '. $remainingDays.' days'?></td>
            <td><?= date('Y-m-d',strtotime('-1 year'.$value->next_incre_date))?></td>
            <td><?= $value->next_incre_date?></td>
            <td><?= $value->user_password?></td>
            <td>
                <?php $i=1; foreach ($query as $key => $value) {?>
                    <?= $i++.'. '.$value->model_name.' '.$value->cat_name.' '.$value->cat_short_name.'-'.$value->device_name_id.'<br>'?>
               <?php }?>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

</body>