<?php
$totalRows = count($alldata);
$in_out=0;
if ($totalRows>0) {
$in_out=$alldata[0]->in_out;
$outtime=$alldata[0]->out_time;

$payable_amount=0;
foreach ($alldata as $data) {
    $payable_amount+= $data->payable_amount;
}
// Specify the target date and time
$targetDate = new DateTime($outtime);

// Get the current date and time
$currentDate = new DateTime();

// Calculate the time difference
$timeDiff = $currentDate->diff($targetDate);

// Output the time difference
$timeDifferenceFormatted=$timeDiff->format('%d day, %H:%i:%s');

}
?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


<style>
.movemodal {
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5);
}


.modal-content {
    margin: 5% 28%;
    overflow: auto;
    width: 52%;
    height: 407px;
    position: relative;
}

.header {
    width: 100%;
    padding: 2px;
    background: #0177bc;
    height: 28px;
    display: flex;
    justify-content: space-between;
}

.contentss {
    padding: 30px;
    display: flex;
    gap: 28px;
    flex-direction: column;
}

.form-field {
    padding: 6px;
    border: 1px solid #938b8b;
    border-radius: 5px;
}

.actions {
    text-align: right;
    padding: 0;
}

textarea {
    height: 122px;
    width: 100%;
    border: none;
}


.close-icon {
    position: absolute;
    top: -4px;
    right: 10px;
    font-size: 30px;
    cursor: pointer;

}

.cboton {
    color: #000;
    font-family: Roboto;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    height: 42px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 2px;
    border: 1px solid #9d9797;

}

.cactive {
    color: #FFF;
    background: #2DCA8C;
}

.heading {
    font-size: 12px !important;
}

.btn {
    width: 146px;
    height: 32px;
    border-radius: 2px;
    border: 1px solid var(--b, #599AE7);
    background: var(--b, #599AE7);
    color: white;
    font-weight: bold;
}

.list-group>li:nth-child(5n+1) {
    border-top: 1px solid rgba(0, 0, 0, .125);
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
}

.list-group>li:nth-child(5n+0) {
    border-bottom-left-radius: .25rem;
    border-bottom-right-radius: .25rem;
}

.pagination-container {
    justify-content: right !important;
    display: flex !important;
}
</style>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&amp;display=swap">
<link rel="stylesheet" href="<?= base_url('skin/hrsale_assets/css/lunch_emp_bill.css') ?>">
<link rel="stylesheet" href="<?= base_url('skin/hrsale_assets/css/emp_attandenc.css') ?>">


<div id="movemodal" class="movemodal" style="display: none;">
    <form action="<?= base_url('admin/attendance/add_move_register') ?>" method="post">
        <div class="modal-content">
            <div class="header">
                <div></div>
                <div class="close-icon" onclick="closem()">&times;</div>
            </div>
<?php if($in_out!=1){?>
            <div class="col-md-12 contentss">
                <input type="hidden" name="location_status" value="<?=($location_status==1)? 1: 2?> ">

                <div class="form-field">
                    <label for="reason">Select Reason of move**</label>
                    <select id="reason" name="reason" class="col-md-12" style="border: none;" required>
                        <option> Select Reason of move</option>
                        <option value="option1">Option 1</option>
                        <option value="option2">Option 2</option>
                        <option value="option3">Option 3</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="message">Write Place Adress</label>
                    <textarea id="message" name="place_adress" required></textarea>
                </div>
                <div class="actions">
                    <input type="submit" class="btn" style="background: #39a3ff;" value="Move Outside">
                    <a class="btn" onclick="closem()">Cancel</a>
                </div>
            </div>
            <?php }else{  ?>






                <?php } ?>

        </div>
    </form>
</div>
<div class="divrow col-md-12" style="margin-bottom: 27px;margin-top: -15px!important;">
    <div class="divstats-info col-md-3" style="background-color: #d1ecf1;">
        <div class="heading">Outside <?=($location_status==1)? 'Office': 'Dhaka'?> Move</div>
        <div class="heading2"><?= $totalRows ?></div>
    </div>

    <div class="divstats-info col-md-3" style="background-color: #F1CFEE;">
        <div class="heading">Total TA Bill (BDT)</div>
        <div class="heading2"><?= (isset($payable_amount))? $payable_amount:'0' ?></div>
    </div>
    <?php if($in_out==0){ ?>
    <div class="divstats-info col-md-6" style="background-color:#FFF;">
        <div class="heading">Are you want to go Outside <?=($location_status==1)? 'Office': 'Dhaka'?> ? Please Make Sure your Checking & entry Purpose</div>
        <div class="heading2">
            <a class="btn" onclick="move_modal()"> <?=($location_status==1)? 'Check In': 'Request'?>Check In</a>
        </div>
    </div>
    <?php }else{ ?>

    <div class="divstats-info col-md-6" style="background-color:#FFF;">
        <div class="heading" style="font-size: 12px!important;color: red;">Your Check In time  <span
                style="color: #599AE7;"><?= $timeDifferenceFormatted ?></span>. Make Sure when You Come back to office & Check out
        </div>
        <div class="heading2"><a class="btn" href="<?= base_url('admin/attendance/checkout/')?><?=($location_status==1)? 1: 2 ?> "
                style="width: 146px;height: 32px;border-radius: 2px;border: 1px solid var(--b, #599AE7);background: var(--b, #599AE7);color: white;font-weight: bold;">Check
                Out</a></div>
    </div>
    <?php } ?>
</div>
<div class="col-md-12 medelbar" style="gap: 4px;margin: 2px;align-items: end;">
    <div class="col-md-2 divform-group " style="padding: 0;">
        <a href="<?= base_url('admin/attendance/employee_movement/0') ?>" class="cboton ">Floor wise Movement</a>
    </div>
    <div class="col-md-2 divform-group" style="padding: 0;">
        <a href="<?= base_url('admin/attendance/employee_movement/1') ?>" class="cboton <?=($location_status==1)? 'cactive': ''?>">Outside office </a>
    </div>
    <div class="col-md-2 divform-group" style="padding: 0;">
        <a href="" class="cboton <?=($location_status==1)? '': 'cactive'?>">Outside Dhaka</a>
    </div>
    <div class="col-md-2 divform-group">
    </div>
    <div class="col-md-3 divform-group" style="padding: 0;">
        <div class="input">
            <div class="level">Select Date</div>
            <div class="pseudo6">
                <input onchange=getdata(this) style="width: 98%;border: none;cursor: pointer;" type="date" name=""
                    value="<?= date('Y-m-d') ?>" id="datef">
            </div>
        </div>
    </div>

</div>
<div id="msg"><?php echo (isset($msg)) ? $msg : ''; ?></div>
<div id="datatable">
    <?php echo $tablebody;?>
</div>
<script>
function getdata(status) {

    // console.log(status.id);
    if (status.id == 'datef') {
        var firstdate = document.getElementById('datef').value
        var seconddate = document.getElementById('datef').value
    } else if (status.id == 'month') {
        var date = new Date(document.getElementById('month').value);


        var firstDate = new Date(date.getFullYear(), date.getMonth(), 2);

        // Get the last date of the month by setting the day to 0 (which gives the last day of the previous month) and adding 1 day
        var lastDate = new Date(date.getFullYear(), date.getMonth() + 1, 1);

        // Format the dates as strings in the format 'YYYY-MM-DD'
        var firstdate = firstDate.toISOString().slice(0, 10);
        var seconddate = lastDate.toISOString().slice(0, 10);

    } else if (status.id == 'year') {
        var date = document.getElementById('year').value;
        var firstDate = new Date(date, 0, 1); // Month is zero-based, so 0 represents January
        var lastDate = new Date(date, 11, 31); // Month is zero-based, so 11 represents December

        // Format the dates as strings
        var firstdate = firstDate.toDateString();
        var seconddate = lastDate.toDateString();
    }
    $.ajax({
        url: '<?php echo base_url('admin/attendance/employee_movement_outside_')?><?=($location_status==1)? 'Office': 'dhaka'?> ',
        method: 'POST',
        data: {
            firstdate: firstdate,
            seconddate: seconddate
        },
        success: function(resp) {
            $('#datatable').empty();
            $('#datatable').html(resp);
        }
    });
}
</script>
<script>
function move_modal() {
    var modal = document.getElementById("movemodal");
    modal.style.display = "block";
}

function closem() {
    var modal = document.getElementById("movemodal");
    modal.style.display = "none";
}
</script>'
<script>
$('#movementform1').on('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the form data
    var formData = $(this).serialize();


    // Make an AJAX post request to the controller
    $.ajax({
        url: '<?= base_url('admin/floor_movement/outformsub') ?>', // Replace 'controller/method' with your actual controller and method
        method: 'POST',
        data: formData,
        success: function(response) {
            // Handle the success response

            alert(response);
            location.reload();
            // Process the response data returned from the controller
        },
        error: function(xhr, status, error) {
            // Handle any errors that occur during the request

            alert(error);
            location.reload();
        }
    });
});
</script>