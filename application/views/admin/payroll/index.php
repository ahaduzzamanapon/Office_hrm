<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="col-lg-8">
<div class="box mb-4 <?php echo $get_animate;?>">
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-7">
            <div class="form-group">
              <br>
              <label>Select Month and Year :</label>
              <select id='sal_month'>
                <?php 
                  $year = date('Y');
                  for($i=1; $i<=12;$i++)
                  {
                    $month = date( 'F', mktime(0, 0, 0, $i, 1, $year) );
                    $month_numeric =  date( 'm', mktime(0, 0, 0, $i, 1, $year) );
                    $current_month = date('m');
                    if($current_month == $month_numeric){
                    ?>
                      <option value="<?php echo $month_numeric;?>" selected="selected"><?php echo $month;?></option>
                    <?php
                    }else{
                    ?>
                      <option value="<?php echo $month_numeric;?>" ><?php echo $month;?></option>
                    <?php
                    }
                  }
                ?>
              </select>
              <select id='sal_year'>
                <?php
                  $current_year = date('Y');
                  for($i = $current_year-10; $i <= $current_year + 10; $i++)
                  {
                    if($current_year == $i){
                    ?>
                      <option value="<?php echo $i;?>" selected="selected"><?php echo $i;?></option>
                    <?php
                    }else{
                    ?>
                      <option value="<?php echo $i;?>" ><?php echo $i;?></option>
                    <?php
                    }
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <br/>
              <span ><b>Status:</b></span>
              <select   name="status" id="status">
                <option value="">Select one</option>
                <option value="1">regular</option>
                <option value="2">left</option>
                <option value="3">resign</option>
              </select>
            </div>
          </div>

          <div class="col-md-1">
            <div class="form-group"> 
              <button style="margin-top:15px;margin-left: -70px;" class="btn btn-success" onclick="salary_process()">Process</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modify Employee Salary</h4>
      </div>
      <?php
         $sql= 'SELECT user_id,first_name,last_name FROM xin_employees';
         $employees = $this->db->query($sql);
         $emps=$employees->result();
      ?>
      <div class="modal-body">
      <form>
         
          <div class="col-md-6">
            <label>Employee Name</label>
            <select name="emp_id" class="form-control" id="emp_name">
              <option value="">Select Employee Name</option>
              <?php foreach($emps as $emp){?>
              <option value="<?php echo $emp->user_id?>"><?php echo $emp->first_name.' '.$emp->last_name?></option>
              <?php }?>
            </select>
          </div>
          <div class="col-md-2">
            <label >Gross Salary</label>
            <input type="text" readonly class="form-control" id="gross_salary" placeholder="00.00">
          </div>
          <div class="col-md-2">
            <label >Deduct Salary</label>
            <input type="number" readonly class="form-control" id="deduct_salary" placeholder="00.00">
          </div>

          <div class="col-md-2">
            <label >Modify Salary</label>
            <input type="number"  class="form-control" id="modify_salary" placeholder="00.00">
          </div>
          
          <!-- <input type="text" id="gross_salary" name="gross_salary" value="test" readonly="readonly"> -->
          <div class="modal-footer" >
            <button type="button" name="btn" onclick="save_modify_salary()" class="btn btn-sm btn-success" style="margin-top:10px !important">Save</button>
            <button type="button" class="btn btn-sm btn-danger" style="margin-top:10px !important" data-dismiss="modal">Close</button>
          </div>
          </form>
      </div>

    </div>
    
  </div>
</div>
<!-- modal close -->


<div id="loader"  align="center" style="margin:0 auto; width:600px; overflow:hidden; display:none; margin-top:10px;"><img src="<?php echo base_url();?>/uploads/ajax-loader.gif" /></div>

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border" id="report_title">
    <h3 class="box-title" id="report"> Salary Report
      <!-- < ?php echo $this->lang->line('xin_daily_attendance_report');?> -->
   </h3>
     <button id="modify_salary" class="btn btn-sm btn-primary pull-right" style="padding: 6px 10px !important;" data-toggle="modal" data-target=".bd-example-modal-lg">Modify Salary</button>
  </div>

  <div class="box-body" id="emp_report">
    <ul class="nav nav-tabs " id="myTab" role="tablist">
        <li class="nav-item active">
          <a class="nav-link " id="daily-tab" data-toggle="tab" href="#daily" role="tab" aria-controls="daily" aria-selected="true">Reort</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="false">Excel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="continuously-tab" data-toggle="tab" href="#continuously" role="tab" aria-controls="continuously" aria-selected="false">Continuously</a>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
      

      <div class="tab-pane fade active in" id="daily" role="tabpanel" aria-labelledby="daily-tab" style="margin-top: 30px;">
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="daily_report('Present')">Present</button>
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="daily_report('Absent')">Absent</button>
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="daily_report('Present',1)">Late</button>
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="lunch_report('Lunch in/out')">Lunch In/Out</button>
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="lunch_report('Lunch Late',1)">Lunch Late</button>
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="early_out_report('Early Out')">Early Out</button>
          <button class="btn btn-sm mr-5" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="movement_report('Movement')">Movement</button>
      </div>

      <div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="monthly-tab" style="margin-top: 30px;">
        <button class="btn btn-sm mr-5 rounded" style="background: #2393e3eb; color: white;margin-right: 10px;padding:6px 10px !important;" onclick="salary_sheet_excel()">Salary Sheet</button>

      </div>

      <div class="tab-pane fade" id="continuously" role="tabpanel" aria-labelledby="continuously-tab" style="margin-top: 30px;">
        <button class="btn btn-sm btn-success rounded" style="padding:6px 10px !important;" onclick="jobCard()">Job Card</button>

      </div>

    </div>

  </div>


  <div  class="box-body" id="entry_form">

  </div>



</div>
</div>

<div class="col-lg-4">
<div class="box" style="height: 74vh;overflow-y: scroll;">
<table class="table table-striped table-hover" id="fileDiv">
  <tr style="position: sticky;top: 0;z-index:1">
      <th class="active" style="width:10%"><input type="checkbox" id="select_all" class="select-all checkbox" name="select-all" /></th>
      <th class="" style="width:10%;background:#0177bcc2;color:white">Id</th>
      <th class=" text-center" style="background:#0177bc;color:white">Name</th>
  </tr>
</table>
</div>
</div>



<!-- <script type="text/javascript" src="<?php echo base_url() ?>skin/hrsale_assets/js/hrm.js"></script> -->
<script type="text/javascript" src="<?php echo base_url() ?>skin/hrsale_assets/js/salary.js"></script>
<script>
  $(document).ready(function(){

    // select all item or deselect all item
    $("#select_all").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
    });

    // on load employee
    $("#status").change(function(){
      status = document.getElementById('status').value;
      var url = "<?php echo base_url('admin/attendance/get_employee_ajax_request'); ?>";
      $.ajax({
        url: url,
        type: 'GET',
        data: {"status":status},
        contentType: "application/json",
        dataType: "json",


        success: function(response){
          arr = response.employees;
          if (arr.length != 0) {
            var items = '';
            $.each(arr, function(index,value) {
              items += '<tr id="removeTr">';
              items += '<td><input type="checkbox" class="checkbox" id="select_emp_id" name="select_emp_id[]" value="'+value.emp_id+'" ></td>';
              items += '<td class="success">'+value.emp_id+'</td>';
              items += '<td class="warning ">'+value.first_name +' '+ value.last_name +'</td>';
              items += '</tr>';
            });
            // console.log(items);
            $('#fileDiv tr:last').after(items);
          } else {
            $('#fileDiv #removeTr').remove(); 
          }
        }
      });
    });
  });

  $("#emp_name option").filter(function() {
        return $id= $(this).val() == $("#gross_salary").val();
    }).attr('selected', true);

    $("#emp_name").on("change", function() {
      
        id= $(this).find("option:selected").attr("value");

        if($('option value') == ''){
          $("#gross_salary")[0].reset();
          $("#reduct_salary")[0].reset();
          return false;
        }
        
        var url = "<?php echo base_url('admin/payroll/modify_salary/'); ?>"+id;
        $.ajax({
        url: url,
        type: 'GET',
        data: {"id":id},
        contentType: "application/json",
        dataType: "json",
        success: function(response){

          $("#gross_salary").val(response[0].basic_salary);
          $("#deduct_salary").val(response[0].late_deduct);

          $('.modal').on('hidden.bs.modal', function(){
              $(this).find('form')[0].reset();
          });
         
        }
      });
   

 });

function save_modify_salary(){
  let basic_salary= $("#gross_salary").val();
  let deduct_salary= $("#deduct_salary").val();
  let modify_salary= $("#modify_salary").val();
  let id= $("#emp_name").val();  
  var url = "<?php echo base_url('admin/payroll/save_modify_salary'); ?>";
        $.ajax({
        url: url,
        type: 'POST',
        data: {
                id:id,
                gross_salary:basic_salary,
                deduct_salary:deduct_salary,
                modify_salary:modify_salary
              },
        success: function(response){
          // alert("Success: " +response);
        }
      });
  
 } 


//  function save_modify_salary()
//   {
//     // alert(csrf_token); return;
//     var ajaxRequest;  // The variable that makes Ajax possible!
//     ajaxRequest = new XMLHttpRequest();


//     basic_salary = document.getElementById('gross_salary').value;
//     deduct_salary = document.getElementById('deduct_salary').value;
//     modify_salary = document.getElementById('modify_salary').value;
//     id = document.getElementById('emp_name').value;

//     var data = "basic_salary="+basic_salary+"&deduct_salary="+deduct_salary+'&modify_salary='+modify_salary+"&id="+id;

//     // console.log(data); return;
//     url = "< ?php echo base_url('admin/payroll/save_modify_salary'); ?>";
//     ajaxRequest.open("POST", url, true);
//     ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf-8");
//     ajaxRequest.send(data);
//     return;

//     ajaxRequest.onreadystatechange = function(){
//       if(ajaxRequest.readyState == 4){
//         // console.log(ajaxRequest);
//         var resp = ajaxRequest.responseText;
//         a = window.open('', '_blank', 'menubar=1,resizable=1,scrollbars=1,width=1200,height=800');
//         a.document.write(resp);
//       }
//     }
//   }





</script>

