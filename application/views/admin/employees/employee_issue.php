<div class="container">
    <!-- Button to trigger add modal -->
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPurposeModal">
        <i class="fas fa-plus-circle"></i> Add Issue
    </button>

    <!-- Table to display payment purposes -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Sl</th>
                    <th>Employee name</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purposes as $key=>$purpose): ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td>
                      <?php
                       $data=$this->Employees_model->fetch_user_info($purpose->emp_id);
                    //    dd($data);
                       echo $data[0]->first_name.' '.$data[0]->last_name
                      ?>
                    </td>
                    <td><?= $purpose->comment ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info edit-purpose" data-id="<?= $purpose->id ?>"
                            data-toggle="modal" data-target="#editPurposeModal">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>
                        <a href="#" class="btn btn-sm btn-danger delete-purpose" data-id="<?= $purpose->id ?>">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Purpose Modal -->
    <div class="modal fade" style="z-index: 111111111111111111 !important;" id="addPurposeModal" tabindex="-1"
        role="dialog" aria-labelledby="addPurposeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="add-purpose-form" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addPurposeModalLabel">Add Issue</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Employee</label>
                            <select name="emp_id" id="emp_id">
                                <option>Select employee</option>
                                <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee->user_id ?>"><?= $employee->first_name.' '.$employee->last_name ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Comment</label>
                            <textarea name="comment" id="comment" cols="30" rows="10">

                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Add Issue">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Purpose Modal -->
    <div class="modal fade" style="z-index: 111111111111111111 !important;" id="editPurposeModal" tabindex="-1"
        role="dialog" aria-labelledby="editPurposeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="edit-purpose-form" method="post">
                    <input type="hidden" name="edit_id" id="edit-id">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editPurposeModalLabel">Edit Issue</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Employee</label>
                            <select name="emp_id" id="emp_id">
                                <option>Select employee</option>
                                <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee->user_id ?>"><?= $employee->first_name.' '.$employee->last_name ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Comment</label>
                            <textarea name="comment" id="comment" cols="30" rows="10">

                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save Changes">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// AJAX to add a new purpose
$('#add-purpose-form').submit(function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: '<?= base_url('admin/employees/employee_issue/add') ?>',
        data: $(this).serialize(),
        success: function(response) {
            if (response === 'success') {
                $('#addPurposeModal').modal('hide');
                showSuccessAlert(response);
            } else {
                // Handle validation errors or empty fields
                console.log(response);
            }
        }
    });
});

// AJAX to edit a purpose (fill data in the edit modal)
$('.edit-purpose').click(function() {
    var id = $(this).data('id');
    $.ajax({
        type: 'GET',
        url: '<?= base_url('admin/employees/employee_issue/edit/') ?>' + id,
        dataType: 'json',
        success: function(response) {
            $('#emp_id').val(response.emp_id);
            $('#edit-id').val(response.id);
            $('#comment').val(response.comment);
        }
    });
});

// AJAX to update a purpose
$('#edit-purpose-form').submit(function(e) {
    e.preventDefault();
    var id = $('#edit-id').val();
    $.ajax({
        type: 'POST',
        url: '<?= base_url('admin/employees/employee_issue/edit/') ?>' + id,
        data: $(this).serialize(),
        success: function(response) {
            if (response === 'success') {
                $('#editPurposeModal').modal('hide');
                showSuccessAlert(response);
            } else {
                // Handle validation errors or empty fields
                console.log(response);
            }
        }
    });
});

// AJAX to delete a purpose
$('.delete-purpose').click(function() {
    if (confirm('Are you sure you want to delete this purpose?')) {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('admin/employees/employee_issue/delete/') ?>' + id,
            success: function(response) {
                if (response === 'success') {
                    showSuccessAlert(response);
                } else {
                    // Handle any errors
                    console.log(response);
                }
            }
        });
    }
});
</script>