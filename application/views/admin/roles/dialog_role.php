<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['role_id']) && $_GET['data']=='role'){
$role_resources_ids = explode(',',$role_resources);
?>
<div class="modal-header">
  <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_role_editrole');?></h4>
</div>
<?php $attributes = array('name' => 'edit_role', 'id' => 'edit_role', 'autocomplete' => 'off','class' => '"m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', 'ext_name' => $role_name, '_token' => $role_id);?>
<?php echo form_open('admin/roles/update/'.$role_id, $attributes, $hidden);?>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="role_name"><?php echo $this->lang->line('xin_role_name');?><i class="hrsale-asterisk">*</i></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_role_name');?>" name="role_name" type="text" value="<?php echo $role_name;?>">
            </div>
          </div>
        </div>
        <div class="row">
        	<input type="checkbox" name="role_resources[]" value="0" checked style="display:none;"/>
          <div class="col-md-12">
            <div class="form-group">
              <label for="role_access"><?php echo $this->lang->line('xin_role_access');?><i class="hrsale-asterisk">*</i></label>
              <select class="form-control custom-select" id="role_access_modal" name="role_access" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_role_access');?>">
                <option value="">&nbsp;</option>
                <option value="1" <?php if($role_access==1):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_role_all_menu');?></option>
                <option value="2" <?php if($role_access==2):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_role_cmenu');?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <p><strong><?php echo $this->lang->line('xin_role_note_title');?></strong></p>
            <p><?php echo $this->lang->line('xin_role_note1');?></p>
            <p><?php echo $this->lang->line('xin_role_note2');?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="resources"><?php echo $this->lang->line('xin_role_resource');?></label>
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m1"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> 
  </div>
<?php echo form_close(); ?>
<script type="text/javascript">
 $(document).ready(function(){
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		 $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass   : 'iradio_minimal-blue'
		});

		/* Edit data */
		$("#edit_role").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=role&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					} else {
						// On page load: datatable
						var xin_table = $('#xin_table').dataTable({
							"bDestroy": true,
							"ajax": {
								url : "<?php echo site_url("admin/roles/role_list") ?>",
								type : 'GET'
							},
							dom: 'lBfrtip',
							"buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
							"fnDrawCallback": function(settings){
							$('[data-toggle="tooltip"]').tooltip();          
							}
						});
						xin_table.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
  <script>

jQuery("#treeview_m1").kendoTreeView({
checkboxes: {
checkChildren: true,
//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"*/
template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"
},
check: onCheck,
dataSource: [

	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('let_staff');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('103',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "103",  items: [
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('dashboard_employees');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "13", check: "<?php if(isset($_GET['role_id'])) { if(in_array('13',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "13", check: "<?php if(isset($_GET['role_id'])) { if(in_array('13',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "201", check: "<?php if(isset($_GET['role_id'])) { if(in_array('201',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

			{ id: "", class: "role-checkbox-modal", text: "Employee List",  add_info: "Employee List", value: "377", check: "<?php if(isset($_GET['role_id'])) { if(in_array('377',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 

			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_edit');?>", value: "202", check: "<?php if(isset($_GET['role_id'])) { if(in_array('202',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_delete');?>", value: "203", check: "<?php if(isset($_GET['role_id'])) { if(in_array('203',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_view_company_emp_title');?>",  add_info: "<?php echo $this->lang->line('xin_view_company_emp_title');?>", value: "372", check: "<?php if(isset($_GET['role_id'])) { if(in_array('372',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_view_location_emp_title');?>",  add_info: "<?php echo $this->lang->line('xin_view_location_emp_title');?>", value: "373", check: "<?php if(isset($_GET['role_id'])) { if(in_array('373',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
			]},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hrsale_custom_fields');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "393",  items: [
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "393",check: "<?php if(isset($_GET['role_id'])) { if(in_array('393',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "394",check: "<?php if(isset($_GET['role_id'])) { if(in_array('394',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_edit');?>", value: "395",check: "<?php if(isset($_GET['role_id'])) { if(in_array('395',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_delete');?>", value: "396",check: "<?php if(isset($_GET['role_id'])) { if(in_array('396',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
			]},		
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_set_employees_salary');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "351", check: "<?php if(isset($_GET['role_id'])) { if(in_array('351',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_import_employees');?>",  add_info: "<?php echo $this->lang->line('xin_import_employees');?>", value: "92", check: "<?php if(isset($_GET['role_id'])) { if(in_array('92',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_employees_directory');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "88", check: "<?php if(isset($_GET['role_id'])) { if(in_array('88',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_employees_exit');?>",  add_info: "<?php echo $this->lang->line('xin_view_update');?>", value: "23", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "23", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "204", check: "<?php if(isset($_GET['role_id'])) { if(in_array('204',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_edit');?>", value: "205", check: "<?php if(isset($_GET['role_id'])) { if(in_array('205',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_delete');?>", value: "206", check: "<?php if(isset($_GET['role_id'])) { if(in_array('206',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_employees_exit').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "231", check: "<?php if(isset($_GET['role_id'])) { if(in_array('231',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
			]},
			{ id: "", class: "role-checkbox-modal", text: "Set Team Lead",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "400",check: "<?php if(isset($_GET['role_id'])) { if(in_array('400',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_employees_last_login');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "22", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
			{ id: "", class: "role-checkbox-modal", text: "Employee List",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "377", check: "<?php if(isset($_GET['role_id'])) { if(in_array('377',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
			{ id: "", class: "role-checkbox-modal", text: "Employee Issue",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "3770", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3770',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"} 
		]},

	//core
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('12',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "12",  items: [
	
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_awards');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "14", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "14", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "207", check: "<?php if(isset($_GET['role_id'])) { if(in_array('207',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "208", check: "<?php if(isset($_GET['role_id'])) { if(in_array('208',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "209", check: "<?php if(isset($_GET['role_id'])) { if(in_array('209',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_awards').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view').' '.$this->lang->line('left_awards');?>", value: "232", check: "<?php if(isset($_GET['role_id'])) { if(in_array('232',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_transfers');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "15", check: "<?php if(isset($_GET['role_id'])) { if(in_array('15',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "15", check: "<?php if(isset($_GET['role_id'])) { if(in_array('15',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "210", check: "<?php if(isset($_GET['role_id'])) { if(in_array('210',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "211", check: "<?php if(isset($_GET['role_id'])) { if(in_array('211',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "212", check: "<?php if(isset($_GET['role_id'])) { if(in_array('212',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_transfers').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "233", check: "<?php if(isset($_GET['role_id'])) { if(in_array('233',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_resignations');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "16", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "16", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "213", check: "<?php if(isset($_GET['role_id'])) { if(in_array('213',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "214", check: "<?php if(isset($_GET['role_id'])) { if(in_array('214',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "215", check: "<?php if(isset($_GET['role_id'])) { if(in_array('215',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_resignations').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "234", check: "<?php if(isset($_GET['role_id'])) { if(in_array('234',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_manager_level_title').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_manager_level_title');?>", value: "406", check: "<?php if(isset($_GET['role_id'])) { if(in_array('406',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_hrd_level_title').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_hrd_level_title');?>", value: "407", check: "<?php if(isset($_GET['role_id'])) { if(in_array('407',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_gm_om_level_title').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_gm_om_level_title');?>", value: "408", check: "<?php if(isset($_GET['role_id'])) { if(in_array('408',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_travels');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "17", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "17", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "216", check: "<?php if(isset($_GET['role_id'])) { if(in_array('216',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "217", check: "<?php if(isset($_GET['role_id'])) { if(in_array('217',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "218", check: "<?php if(isset($_GET['role_id'])) { if(in_array('218',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_travels').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "235", check: "<?php if(isset($_GET['role_id'])) { if(in_array('235',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_promotions');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "18", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "18", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "219", check: "<?php if(isset($_GET['role_id'])) { if(in_array('219',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "220", check: "<?php if(isset($_GET['role_id'])) { if(in_array('220',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "221", check: "<?php if(isset($_GET['role_id'])) { if(in_array('221',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_promotions').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "236", check: "<?php if(isset($_GET['role_id'])) { if(in_array('236',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_complaints');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "19", check: "<?php if(isset($_GET['role_id'])) { if(in_array('19',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "19", check: "<?php if(isset($_GET['role_id'])) { if(in_array('19',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "222", check: "<?php if(isset($_GET['role_id'])) { if(in_array('222',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "223", check: "<?php if(isset($_GET['role_id'])) { if(in_array('223',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "224", check: "<?php if(isset($_GET['role_id'])) { if(in_array('224',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_complaints').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "237", check: "<?php if(isset($_GET['role_id'])) { if(in_array('237',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_warnings');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "20", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "20", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "225", check: "<?php if(isset($_GET['role_id'])) { if(in_array('225',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "226", check: "<?php if(isset($_GET['role_id'])) { if(in_array('226',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "227", check: "<?php if(isset($_GET['role_id'])) { if(in_array('227',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_warnings').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "238", check: "<?php if(isset($_GET['role_id'])) { if(in_array('238',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_terminations');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "21", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "21", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "228", check: "<?php if(isset($_GET['role_id'])) { if(in_array('228',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "229", check: "<?php if(isset($_GET['role_id'])) { if(in_array('229',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "230", check: "<?php if(isset($_GET['role_id'])) { if(in_array('230',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_terminations').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "239", check: "<?php if(isset($_GET['role_id'])) { if(in_array('239',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]}
	]},
	//core

	{ id: "", class: "role-checkbox-modal", text: "Account",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2030',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "2030",  items: [
		{ id: "", class: "role-checkbox-modal", text: "Payment In",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "2001", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2001',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "Payment Out",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "2002", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2002',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "Settings",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "2003", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2003',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "Petty cash",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "2004", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2004',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "Report",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "2005", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2005',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	]},

	// Organization
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_organization');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value:"2", items: [
		// sub 1
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_department');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "3", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "3", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "240", check: "<?php if(isset($_GET['role_id'])) { if(in_array('240',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "241", check: "<?php if(isset($_GET['role_id'])) { if(in_array('241',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "242", check: "<?php if(isset($_GET['role_id'])) { if(in_array('242',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_designation');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "4", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "4", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "243", check: "<?php if(isset($_GET['role_id'])) { if(in_array('243',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "244", check: "<?php if(isset($_GET['role_id'])) { if(in_array('244',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "245", check: "<?php if(isset($_GET['role_id'])) { if(in_array('245',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_designation').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "249",check: "<?php if(isset($_GET['role_id'])) { if(in_array('249',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_company');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "5", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "5", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "246", check: "<?php if(isset($_GET['role_id'])) { if(in_array('246',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "247", check: "<?php if(isset($_GET['role_id'])) { if(in_array('247',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "248", check: "<?php if(isset($_GET['role_id'])) { if(in_array('248',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_location');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "6", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "6", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "250", check: "<?php if(isset($_GET['role_id'])) { if(in_array('250',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "251", check: "<?php if(isset($_GET['role_id'])) { if(in_array('251',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "252", check: "<?php if(isset($_GET['role_id'])) { if(in_array('252',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_announcements');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "11", check: "<?php if(isset($_GET['role_id'])) { if(in_array('11',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "11", check: "<?php if(isset($_GET['role_id'])) { if(in_array('11',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "254", check: "<?php if(isset($_GET['role_id'])) { if(in_array('254',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "255", check: "<?php if(isset($_GET['role_id'])) { if(in_array('255',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "256", check: "<?php if(isset($_GET['role_id'])) { if(in_array('256',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_announcements').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "257", check: "<?php if(isset($_GET['role_id'])) { if(in_array('257',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_policies');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "9", check: "<?php if(isset($_GET['role_id'])) { if(in_array('9',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "9", check: "<?php if(isset($_GET['role_id'])) { if(in_array('9',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "258", check: "<?php if(isset($_GET['role_id'])) { if(in_array('258',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "259", check: "<?php if(isset($_GET['role_id'])) { if(in_array('259',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "260", check: "<?php if(isset($_GET['role_id'])) { if(in_array('260',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_org_chart_title');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "96", check: "<?php if(isset($_GET['role_id'])) { if(in_array('96',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},	
	]}, // sub 1 end
	// Organization

	// Asset
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_assets');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('24',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "24",  items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_assets');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "25", check: "<?php if(isset($_GET['role_id'])) { if(in_array('25',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "25", check: "<?php if(isset($_GET['role_id'])) { if(in_array('25',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "262", check: "<?php if(isset($_GET['role_id'])) { if(in_array('262',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "263", check: "<?php if(isset($_GET['role_id'])) { if(in_array('263',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "264", check: "<?php if(isset($_GET['role_id'])) { if(in_array('264',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_assets').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "265", check: "<?php if(isset($_GET['role_id'])) { if(in_array('265',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_category');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "26", check: "<?php if(isset($_GET['role_id'])) { if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "26", check: "<?php if(isset($_GET['role_id'])) { if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "266", check: "<?php if(isset($_GET['role_id'])) { if(in_array('266',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "267", check: "<?php if(isset($_GET['role_id'])) { if(in_array('267',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "268", check: "<?php if(isset($_GET['role_id'])) { if(in_array('268',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
	]},
	// Asset

	// Events & Meetings
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_events_meetings');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('97',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "97",  items: [

		{ id: "", class: "role-checkbox", text: "Employee Events",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "131",check: "<?php if(isset($_GET['role_id'])) { if(in_array('131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_events');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "98", check: "<?php if(isset($_GET['role_id'])) { if(in_array('98',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "98", check: "<?php if(isset($_GET['role_id'])) { if(in_array('98',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "269", check: "<?php if(isset($_GET['role_id'])) { if(in_array('269',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "270", check: "<?php if(isset($_GET['role_id'])) { if(in_array('270',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "271", check: "<?php if(isset($_GET['role_id'])) { if(in_array('271',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_hr_events').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "272", check: "<?php if(isset($_GET['role_id'])) { if(in_array('272',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},

		{ id: "", class: "role-checkbox", text: "Notice",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "127",check: "<?php if(isset($_GET['role_id'])) { if(in_array('127',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_meetings');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "99", check: "<?php if(isset($_GET['role_id'])) { if(in_array('99',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "99", check: "<?php if(isset($_GET['role_id'])) { if(in_array('99',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "273", check: "<?php if(isset($_GET['role_id'])) { if(in_array('273',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "274", check: "<?php if(isset($_GET['role_id'])) { if(in_array('274',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "275", check: "<?php if(isset($_GET['role_id'])) { if(in_array('275',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_hr_meetings').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "276", check: "<?php if(isset($_GET['role_id'])) { if(in_array('276',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
	]},
	// Events & Meetings

	// Timesheet HR
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_timesheet');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "27",  items: [

		{ id: "", class: "role-checkbox", text: "Employee Attendance",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "389",check: "<?php if(isset($_GET['role_id'])) { if(in_array('389',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox", text: "Employee Movement",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "123",check: "<?php if(isset($_GET['role_id'])) { if(in_array('123',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox", text: "Employee Leave",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "124",check: "<?php if(isset($_GET['role_id'])) { if(in_array('124',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox", text: "Employee Holiday",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "130",check: "<?php if(isset($_GET['role_id'])) { if(in_array('130',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox", text: "attn file upload",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1001",check: "<?php if(isset($_GET['role_id'])) { if(in_array('1001',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox", text: "Attendance process",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1002",check: "<?php if(isset($_GET['role_id'])) { if(in_array('1002',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox", text: " Movement register",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1003",check: "<?php if(isset($_GET['role_id'])) { if(in_array('1003',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_attendance');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "28", check: "<?php if(isset($_GET['role_id'])) { if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "28", check: "<?php if(isset($_GET['role_id'])) { if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_timesheet').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "397", check: "<?php if(isset($_GET['role_id'])) { if(in_array('397',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_month_timesheet_title');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "10", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "10", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_month_timesheet_title').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "253",check: "<?php if(isset($_GET['role_id'])) { if(in_array('253',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox", text: "<?php echo $this->lang->line('xin_attendance_timecalendar');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "261",check: "<?php if(isset($_GET['role_id'])) { if(in_array('261',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_date_wise_attendance');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "29", check: "<?php if(isset($_GET['role_id'])) { if(in_array('29',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "29", check: "<?php if(isset($_GET['role_id'])) { if(in_array('29',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_date_wise_attendance').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "381", check: "<?php if(isset($_GET['role_id'])) { if(in_array('381',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_update_attendance');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "30", check: "<?php if(isset($_GET['role_id'])) { if(in_array('30',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "30", check: "<?php if(isset($_GET['role_id'])) { if(in_array('30',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "277", check: "<?php if(isset($_GET['role_id'])) { if(in_array('277',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "278", check: "<?php if(isset($_GET['role_id'])) { if(in_array('278',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "279", check: "<?php if(isset($_GET['role_id'])) { if(in_array('279',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_upd_company_attendance').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "310", check: "<?php if(isset($_GET['role_id'])) { if(in_array('310',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_overtime_request');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "401", check: "<?php if(isset($_GET['role_id'])) { if(in_array('401',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "401", check: "<?php if(isset($_GET['role_id'])) { if(in_array('401',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "402", check: "<?php if(isset($_GET['role_id'])) { if(in_array('402',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "403", check: "<?php if(isset($_GET['role_id'])) { if(in_array('403',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_import_attendance');?>",  add_info: "<?php echo $this->lang->line('xin_attendance_import');?>", value: "31", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_office_shifts');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "7", check: "<?php if(isset($_GET['role_id'])) { if(in_array('7',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "7", check: "<?php if(isset($_GET['role_id'])) { if(in_array('7',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "280", check: "<?php if(isset($_GET['role_id'])) { if(in_array('280',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "281", check: "<?php if(isset($_GET['role_id'])) { if(in_array('281',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "282", check: "<?php if(isset($_GET['role_id'])) { if(in_array('282',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_change_default');?>",  add_info: "<?php echo $this->lang->line('xin_role_change_default');?>", value: "2822", check: "<?php if(isset($_GET['role_id'])) { if(in_array('2822',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_office_shifts').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "311", check: "<?php if(isset($_GET['role_id'])) { if(in_array('311',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_holidays');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "8", check: "<?php if(isset($_GET['role_id'])) { if(in_array('8',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "8", check: "<?php if(isset($_GET['role_id'])) { if(in_array('8',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "283", check: "<?php if(isset($_GET['role_id'])) { if(in_array('283',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "284", check: "<?php if(isset($_GET['role_id'])) { if(in_array('284',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "285", check: "<?php if(isset($_GET['role_id'])) { if(in_array('285',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_leaves');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "46", check: "<?php if(isset($_GET['role_id'])) { if(in_array('46',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "46", check: "<?php if(isset($_GET['role_id'])) { if(in_array('46',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "287", check: "<?php if(isset($_GET['role_id'])) { if(in_array('287',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "288", check: "<?php if(isset($_GET['role_id'])) { if(in_array('288',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "289", check: "<?php if(isset($_GET['role_id'])) { if(in_array('289',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_leaves').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "290", check: "<?php if(isset($_GET['role_id'])) { if(in_array('290',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_1st_level_approval').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "286", check: "<?php if(isset($_GET['role_id'])) { if(in_array('286',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_2nd_level_approval').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "312", check: "<?php if(isset($_GET['role_id'])) { if(in_array('312',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
	]},
	// Timesheet HR

	// Recruitment
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_recruitment');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('48',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "48",  items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_job_posts');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "49", check: "<?php if(isset($_GET['role_id'])) { if(in_array('49',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "49", check: "<?php if(isset($_GET['role_id'])) { if(in_array('49',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "291", check: "<?php if(isset($_GET['role_id'])) { if(in_array('291',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "292", check: "<?php if(isset($_GET['role_id'])) { if(in_array('292',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "293", check: "<?php if(isset($_GET['role_id'])) { if(in_array('293',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_jobs_listing');?> <small><?php echo $this->lang->line('left_frontend');?></small>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "50", check: "<?php if(isset($_GET['role_id'])) { if(in_array('50',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_job_candidates');?>",  add_info: "<?php echo $this->lang->line('xin_update_status_delete');?>", value: "51", check: "<?php if(isset($_GET['role_id'])) { if(in_array('51',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "51", check: "<?php if(isset($_GET['role_id'])) { if(in_array('51',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_dwn_resume');?>",  add_info: "<?php echo $this->lang->line('xin_role_dwn_resume');?>", value: "294", check: "<?php if(isset($_GET['role_id'])) { if(in_array('294',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_delete');?>", value: "295", check: "<?php if(isset($_GET['role_id'])) { if(in_array('295',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_view_own');?>",  add_info: "<?php echo $this->lang->line('xin_role_view_own');?>", value: "387", check: "<?php if(isset($_GET['role_id'])) { if(in_array('387',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_job_interviews');?>",  add_info: "<?php echo $this->lang->line('xin_add_delete');?>", value: "52", check: "<?php if(isset($_GET['role_id'])) { if(in_array('52',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "52", check: "<?php if(isset($_GET['role_id'])) { if(in_array('52',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "296", check: "<?php if(isset($_GET['role_id'])) { if(in_array('296',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "297", check: "<?php if(isset($_GET['role_id'])) { if(in_array('297',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_view_own');?>",  add_info: "<?php echo $this->lang->line('xin_role_view_own');?>", value: "388", check: "<?php if(isset($_GET['role_id'])) { if(in_array('388',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
		]},
	]},
	// Recruitment

	// Lunch part //
	{ id: "", class: "role-checkbox-modal", text: "Lunch",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1050',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "1050",  items: [

		{ id: "", class: "role-checkbox-modal", text: "Today Lunch",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1052", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1052',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  

		{ id: "", class: "role-checkbox-modal", text: "lunch list",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1051", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1051',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 

		{ id: "", class: "role-checkbox-modal", text: "Employee List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1059", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1059',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  

		{ id: "", class: "role-checkbox-modal", text: "Employee Pay",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1053", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1053',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  

		{ id: "", class: "role-checkbox-modal", text: "Vendor Pay",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1054", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1054',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
		{ id: "", class: "role-checkbox-modal", text: "Vendor Lunch",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1060", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1060',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
			
		{ id: "", class: "role-checkbox-modal", text: "Report",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1055", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1055',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 

		{ id: "", class: "role-checkbox-modal", text: "Settings",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1056", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1056',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [

				{ id: "", class: "role-checkbox-modal", text: "Lunch Packag",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1057", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1057',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
				{ id: "", class: "role-checkbox-modal", text: "Lunch Menu",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1058", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1058',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
				{ id: "", class: "role-checkbox-modal", text: "Active Lunch",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1059", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1059',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 

			]},

	]},
	// Lunch part //
 
	// Payroll part //
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_payroll');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "32",  items: [

		{ id: "", class: "role-checkbox", text: "Employee Salary",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "125",check: "<?php if(isset($_GET['role_id'])) { if(in_array('125',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		
		{ id: "", class: "role-checkbox", text: "Employee Bonus",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "126",check: "<?php if(isset($_GET['role_id'])) { if(in_array('126',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		
		{ id: "", class: "role-checkbox", text: "Advanced Salary",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "127",check: "<?php if(isset($_GET['role_id'])) { if(in_array('127',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		
		{ id: "", class: "role-checkbox", text: "Festival Bonus",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "128",check: "<?php if(isset($_GET['role_id'])) { if(in_array('127',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		
		{ id: "", class: "role-checkbox", text: "Employee Lunch Bill",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "390",check: "<?php if(isset($_GET['role_id'])) { if(in_array('390',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "Generate Salary",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1021", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1021',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		]},

		{ id: "", class: "role-checkbox-modal", text: "Requisition Form",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1022", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1022',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
		]},

		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_generate_payslip');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "36", check: "<?php if(isset($_GET['role_id'])) { if(in_array('36',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "36",check: "<?php if(isset($_GET['role_id'])) { if(in_array('36',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "313",check: "<?php if(isset($_GET['role_id'])) { if(in_array('313',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_generate_company_payslips').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "314",check: "<?php if(isset($_GET['role_id'])) { if(in_array('314',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
			]
		},

		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_payment_history');?>",  add_info: "<?php echo $this->lang->line('xin_view_payslip');?>", value: "37", check: "<?php if(isset($_GET['role_id'])) { if(in_array('37',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "37", check: "<?php if(isset($_GET['role_id'])) { if(in_array('37',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_payment_history').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "391", check: "<?php if(isset($_GET['role_id'])) { if(in_array('391',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_payroll_verifier_title');?>",  add_info: "<?php echo $this->lang->line('xin_payroll_verifier_title');?>", value: "404", check: "<?php if(isset($_GET['role_id'])) { if(in_array('404',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_payroll_approver_title');?>",  add_info: "<?php echo $this->lang->line('xin_payroll_approver_title');?>", value: "405", check: "<?php if(isset($_GET['role_id'])) { if(in_array('405',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	]},
	// Payroll part //

	// Store part //
	{ id: "", class: "role-checkbox-modal", text: "Store",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1030',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "1030",  items: [
		{ id: "", class: "role-checkbox-modal", text: "My Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1031", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1031',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "Requisition",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1070", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1070',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
				{ id: "", class: "role-checkbox-modal", text: "Create Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1071", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1071',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
				{ id: "", class: "role-checkbox-modal", text: "Requisition List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1072", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1072',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
				{ id: "", class: "role-checkbox-modal", text: "Pending Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1073", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1073',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
				{ id: "", class: "role-checkbox-modal", text: "Approved Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1074", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1074',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
				{ id: "", class: "role-checkbox-modal", text: "Delivered Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1075", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1075',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
				{ id: "", class: "role-checkbox-modal", text: "Rejected Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1076", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1076',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},

		{ id: "", class: "role-checkbox-modal", text: "Purchase",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1080", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1080',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
					{ id: "", class: "role-checkbox-modal", text: "Requisition",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1081", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1081',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
					{ id: "", class: "role-checkbox-modal", text: "Pending List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1082", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1082',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
					{ id: "", class: "role-checkbox-modal", text: "Approved List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1083", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1083',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					{ id: "", class: "role-checkbox-modal", text: "Order Received List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1084", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1084',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					{ id: "", class: "role-checkbox-modal", text: "Rejected List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1085", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1085',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
		]},


		{ id: "", class: "role-checkbox-modal", text: "Report",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1033", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1033',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "Settings",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1041", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1041',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
				{ id: "", class: "role-checkbox-modal", text: "Product",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1042", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1042',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
				{ id: "", class: "role-checkbox-modal", text: "Movement",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1048", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1048',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
				{ id: "", class: "role-checkbox-modal", text: "Low Product",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1047", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1047',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 
				{ id: "", class: "role-checkbox-modal", text: "Supplier",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1046", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1046',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
				{ id: "", class: "role-checkbox-modal", text: "Unit",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1043", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1043',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
				{ id: "", class: "role-checkbox-modal", text: "Category",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1044", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1044',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
				
				{ id: "", class: "role-checkbox-modal", text: "Sub Category",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1045", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1045',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
			]},

	]},
	// Store part //

	// Inventory / Accessories part //
	{ id: "", class: "role-checkbox-modal", text: "Inventory",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "1100",  items: [

		{ id: "", class: "role-checkbox-modal", text: "Item List",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1101", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1101',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "Item Add",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1102", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1102',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

		{ id: "", class: "role-checkbox-modal", text: "Report",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1103", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1103',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 

		{ id: "", class: "role-checkbox-modal", text: "Settings",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "1110", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1110',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
				{ id: "", class: "role-checkbox-modal", text: "Category",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1111", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}, 

				{ id: "", class: "role-checkbox-modal", text: "Device Model",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1112", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1112',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
				
				{ id: "", class: "role-checkbox-modal", text: "Add Phone Number",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1113", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1113',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
				
				{ id: "", class: "role-checkbox-modal", text: "Add Desk",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "1114", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1114',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},  
			]},

	]},
	// Inventory / Accessories part /




	
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_performance');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('40',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "40",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_performance_indicator');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "41", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "41", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "298", check: "<?php if(isset($_GET['role_id'])) { if(in_array('298',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "299", check: "<?php if(isset($_GET['role_id'])) { if(in_array('299',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "300", check: "<?php if(isset($_GET['role_id'])) { if(in_array('300',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').'<br>'.$this->lang->line('left_performance_indicator').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "301", check: "<?php if(isset($_GET['role_id'])) { if(in_array('301',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_performance_appraisal');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "42",check: "<?php if(isset($_GET['role_id'])) { if(in_array('42',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "42", check: "<?php if(isset($_GET['role_id'])) { if(in_array('42',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "302", check: "<?php if(isset($_GET['role_id'])) { if(in_array('302',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "303", check: "<?php if(isset($_GET['role_id'])) { if(in_array('303',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "304", check: "<?php if(isset($_GET['role_id'])) { if(in_array('304',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').'<br>'.$this->lang->line('left_performance_appraisal').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "305", check: "<?php if(isset($_GET['role_id'])) { if(in_array('305',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	]},
	]},
	
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_tickets');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('43',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", value: "43",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "43", check: "<?php if(isset($_GET['role_id'])) { if(in_array('43',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "306", check: "<?php if(isset($_GET['role_id'])) { if(in_array('306',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "307", check: "<?php if(isset($_GET['role_id'])) { if(in_array('307',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "308", check: "<?php if(isset($_GET['role_id'])) { if(in_array('308',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_tickets').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "309", check: "<?php if(isset($_GET['role_id'])) { if(in_array('309',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_projects');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('104',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", value: "104",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_projects');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "44", check: "<?php if(isset($_GET['role_id'])) { if(in_array('44',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "44", check: "<?php if(isset($_GET['role_id'])) { if(in_array('44',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "315", check: "<?php if(isset($_GET['role_id'])) { if(in_array('315',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "316", check: "<?php if(isset($_GET['role_id'])) { if(in_array('316',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "317", check: "<?php if(isset($_GET['role_id'])) { if(in_array('317',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_projects').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "318", check: "<?php if(isset($_GET['role_id'])) { if(in_array('318',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_tasks');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "45", check: "<?php if(isset($_GET['role_id'])) { if(in_array('45',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
	// { id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "45", check: "<?php if(isset($_GET['role_id'])) { if(in_array('45',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	// { id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "319", check: "<?php if(isset($_GET['role_id'])) { if(in_array('319',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	// { id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "320", check: "<?php if(isset($_GET['role_id'])) { if(in_array('320',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	// { id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "321", check: "<?php if(isset($_GET['role_id'])) { if(in_array('321',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	// { id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_tasks').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "322", check: "<?php if(isset($_GET['role_id'])) { if(in_array('322',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_invoice_tax_type');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "122", check: "<?php if(isset($_GET['role_id'])) { if(in_array('122',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "122", check: "<?php if(isset($_GET['role_id'])) { if(in_array('122',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "331", check: "<?php if(isset($_GET['role_id'])) { if(in_array('331',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "332", check: "<?php if(isset($_GET['role_id'])) { if(in_array('332',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "333", check: "<?php if(isset($_GET['role_id'])) { if(in_array('333',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_goal_tracking');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('106',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "106",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_goal_tracking');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "107", check: "<?php if(isset($_GET['role_id'])) { if(in_array('107',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "107", check: "<?php if(isset($_GET['role_id'])) { if(in_array('107',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "334", check: "<?php if(isset($_GET['role_id'])) { if(in_array('334',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "335", check: "<?php if(isset($_GET['role_id'])) { if(in_array('335',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "336", check: "<?php if(isset($_GET['role_id'])) { if(in_array('336',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_goal_tracking_type');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "108", check: "<?php if(isset($_GET['role_id'])) { if(in_array('108',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "108", check: "<?php if(isset($_GET['role_id'])) { if(in_array('108',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "338", check: "<?php if(isset($_GET['role_id'])) { if(in_array('338',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "339", check: "<?php if(isset($_GET['role_id'])) { if(in_array('339',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "340", check: "<?php if(isset($_GET['role_id'])) { if(in_array('340',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	]},
	
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_files_manager');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "47", check: "<?php if(isset($_GET['role_id'])) { if(in_array('47',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	
]
});

jQuery("#treeview_m2").kendoTreeView({
checkboxes: {
checkChildren: true,
//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"*/
template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"
},
check: onCheck,
dataSource: [
//
{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_training');?>",  add_info: "", value: "53", check: "<?php if(isset($_GET['role_id'])) { if(in_array('53',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_training_list');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "54",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "54", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "341", check: "<?php if(isset($_GET['role_id'])) { if(in_array('341',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "342", check: "<?php if(isset($_GET['role_id'])) { if(in_array('342',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "343", check: "<?php if(isset($_GET['role_id'])) { if(in_array('343',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_training').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>", value: "344", check: "<?php if(isset($_GET['role_id'])) { if(in_array('344',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	]},
{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_training_type');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('55',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "55",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "55", check: "<?php if(isset($_GET['role_id'])) { if(in_array('55',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "345", check: "<?php if(isset($_GET['role_id'])) { if(in_array('345',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "346", check: "<?php if(isset($_GET['role_id'])) { if(in_array('346',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "347", check: "<?php if(isset($_GET['role_id'])) { if(in_array('347',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_trainers_list');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('56',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "56",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "56", check: "<?php if(isset($_GET['role_id'])) { if(in_array('56',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "348", check: "<?php if(isset($_GET['role_id'])) { if(in_array('348',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "349", check: "<?php if(isset($_GET['role_id'])) { if(in_array('349',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "350", check: "<?php if(isset($_GET['role_id'])) { if(in_array('350',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
]},
{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_system');?>",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('57',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",value: "57",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_settings');?>",  add_info: "<?php echo $this->lang->line('xin_view_update');?>", value: "60", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_constants');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "61", check: "<?php if(isset($_GET['role_id'])) { if(in_array('61',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox", text: "<?php echo $this->lang->line('xin_acc_payment_gateway');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "118", check: "<?php if(isset($_GET['role_id'])) { if(in_array('118',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_db_backup');?>",  add_info: "<?php echo $this->lang->line('xin_create_delete_download');?>", value: "62", check: "<?php if(isset($_GET['role_id'])) { if(in_array('62',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_email_templates');?>",  add_info: "<?php echo $this->lang->line('xin_update');?>", value: "63", check: "<?php if(isset($_GET['role_id'])) { if(in_array('63',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_setup_modules');?>",  add_info: "<?php echo $this->lang->line('xin_update');?>", value: "93", check: "<?php if(isset($_GET['role_id'])) { if(in_array('93',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	
{ id: "", class: "role-checkbox-modal",text: "<?php echo $this->lang->line('xin_acc_accounts');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('71',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "",value: "71",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_account_list');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('72',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "72",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "72", check: "<?php if(isset($_GET['role_id'])) { if(in_array('72',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "352", check: "<?php if(isset($_GET['role_id'])) { if(in_array('352',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "353", check: "<?php if(isset($_GET['role_id'])) { if(in_array('353',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "354", check: "<?php if(isset($_GET['role_id'])) { if(in_array('354',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_account_balances');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('73',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "73",},
	]},
	{ id: "", class: "role-checkbox-modal",text: "<?php echo $this->lang->line('xin_acc_transactions');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('74',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "",value: "74",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_deposit');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('75',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "75",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "75", check: "<?php if(isset($_GET['role_id'])) { if(in_array('75',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "355", check: "<?php if(isset($_GET['role_id'])) { if(in_array('355',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "356", check: "<?php if(isset($_GET['role_id'])) { if(in_array('356',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "357", check: "<?php if(isset($_GET['role_id'])) { if(in_array('357',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_expense');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('76',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "76",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "76", check: "<?php if(isset($_GET['role_id'])) { if(in_array('76',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "358", check: "<?php if(isset($_GET['role_id'])) { if(in_array('358',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "359", check: "<?php if(isset($_GET['role_id'])) { if(in_array('359',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "360", check: "<?php if(isset($_GET['role_id'])) { if(in_array('360',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_transfer');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('77',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "77",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "77", check: "<?php if(isset($_GET['role_id'])) { if(in_array('77',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "361", check: "<?php if(isset($_GET['role_id'])) { if(in_array('361',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "362", check: "<?php if(isset($_GET['role_id'])) { if(in_array('362',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "363", check: "<?php if(isset($_GET['role_id'])) { if(in_array('363',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_view_transactions');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('78',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_view');?>", value: "78",},
	]},
	
	{ id: "", class: "role-checkbox-modal",text: "<?php echo $this->lang->line('xin_acc_payees_payers');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('79',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "",value: "79",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_payees');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('80',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "80",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "80", check: "<?php if(isset($_GET['role_id'])) { if(in_array('80',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "364", check: "<?php if(isset($_GET['role_id'])) { if(in_array('364',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "365", check: "<?php if(isset($_GET['role_id'])) { if(in_array('365',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "366", check: "<?php if(isset($_GET['role_id'])) { if(in_array('366',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_payers');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('81',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "81",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "81", check: "<?php if(isset($_GET['role_id'])) { if(in_array('81',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "367", check: "<?php if(isset($_GET['role_id'])) { if(in_array('367',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "368", check: "<?php if(isset($_GET['role_id'])) { if(in_array('368',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "369", check: "<?php if(isset($_GET['role_id'])) { if(in_array('369',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	]},
	
	{ id: "", class: "role-checkbox-modal",text: "<?php echo $this->lang->line('xin_acc_reports');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('82',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "",value: "82",  items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_account_statement');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('83',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_view');?>", value: "83"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_expense_reports');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('84',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_view');?>", value: "84",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_income_reports');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('85',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_view');?>", value: "85",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_transfer_report');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('86',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "86",},
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_quote_manager');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "87", check: "<?php if(isset($_GET['role_id'])) { if(in_array('87',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_project_clients');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "119", check: "<?php if(isset($_GET['role_id'])) { if(in_array('119',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "119", check: "<?php if(isset($_GET['role_id'])) { if(in_array('119',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "323", check: "<?php if(isset($_GET['role_id'])) { if(in_array('323',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "324", check: "<?php if(isset($_GET['role_id'])) { if(in_array('324',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "325", check: "<?php if(isset($_GET['role_id'])) { if(in_array('325',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_view');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "326", check: "<?php if(isset($_GET['role_id'])) { if(in_array('326',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_leads');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "410", check: "<?php if(isset($_GET['role_id'])) { if(in_array('410',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "411", check: "<?php if(isset($_GET['role_id'])) { if(in_array('411',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "412", check: "<?php if(isset($_GET['role_id'])) { if(in_array('412',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "413", check: "<?php if(isset($_GET['role_id'])) { if(in_array('413',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "414", check: "<?php if(isset($_GET['role_id'])) { if(in_array('414',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_view');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "420", check: "<?php if(isset($_GET['role_id'])) { if(in_array('420',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_estimates');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "415", check: "<?php if(isset($_GET['role_id'])) { if(in_array('415',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "416", check: "<?php if(isset($_GET['role_id'])) { if(in_array('416',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_create');?>",  add_info: "<?php echo $this->lang->line('xin_role_create');?>", value: "417", check: "<?php if(isset($_GET['role_id'])) { if(in_array('417',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "418", check: "<?php if(isset($_GET['role_id'])) { if(in_array('418',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "419", check: "<?php if(isset($_GET['role_id'])) { if(in_array('419',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_invoices_title');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "121", check: "<?php if(isset($_GET['role_id'])) { if(in_array('121',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "121", check: "<?php if(isset($_GET['role_id'])) { if(in_array('121',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_create');?>",  add_info: "<?php echo $this->lang->line('xin_role_create');?>", value: "120", check: "<?php if(isset($_GET['role_id'])) { if(in_array('120',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "328", check: "<?php if(isset($_GET['role_id'])) { if(in_array('328',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "329", check: "<?php if(isset($_GET['role_id'])) { if(in_array('329',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_acc_invoice_payments');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "330", check: "<?php if(isset($_GET['role_id'])) { if(in_array('330',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	{ id: "", class: "role-checkbox-modal", text: "Get Payment",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", value: "3320", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3320',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",},
	]},

	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_lang_settings');?>",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", value: "89", check: "<?php if(isset($_GET['role_id'])) { if(in_array('89',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",items: [
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>", value: "89", check: "<?php if(isset($_GET['role_id'])) { if(in_array('89',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "370", check: "<?php if(isset($_GET['role_id'])) { if(in_array('370',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", value: "371", check: "<?php if(isset($_GET['role_id'])) { if(in_array('371',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
	]},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_notify_top');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "90", check: "<?php if(isset($_GET['role_id'])) { if(in_array('90',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('header_apply_jobs');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "91", check: "<?php if(isset($_GET['role_id'])) { if(in_array('91',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_theme_settings');?>",  add_info: "<?php echo $this->lang->line('xin_theme_settings');?>", value: "94", check: "<?php if(isset($_GET['role_id'])) { if(in_array('94',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_calendar_title');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "95", check: "<?php if(isset($_GET['role_id'])) { if(in_array('95',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	{ id: "", class: "role-checkbox-modal",text: "<?php echo $this->lang->line('xin_hr_report_title');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('110',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "",value: "110", items: [

	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_reports_payslip');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "111", check: "<?php if(isset($_GET['role_id'])) { if(in_array('111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_reports_attendance_employee');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "112", check: "<?php if(isset($_GET['role_id'])) { if(in_array('112',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_reports_training');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "113", check: "<?php if(isset($_GET['role_id'])) { if(in_array('113',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_reports_projects');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "114", check: "<?php if(isset($_GET['role_id'])) { if(in_array('114',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "Store Report",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "1141", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1141',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	{ id: "", class: "role-checkbox-modal", text: "Leave Report",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "115", check: "<?php if(isset($_GET['role_id'])) { if(in_array('115',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_report_user_roles');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "116", check: "<?php if(isset($_GET['role_id'])) { if(in_array('116',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_hr_report_employees');?>",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "117", check: "<?php if(isset($_GET['role_id'])) { if(in_array('117',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	{ id: "", class: "role-checkbox-modal", text: "Lunch",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "409", check: "<?php if(isset($_GET['role_id'])) { if(in_array('409',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	{ id: "", class: "role-checkbox-modal", text: "Accounts",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "419", check: "<?php if(isset($_GET['role_id'])) { if(in_array('419',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	{ id: "", class: "role-checkbox-modal", text: "Employee  Issue",  add_info: "<?php echo $this->lang->line('xin_view');?>", value: "420", check: "<?php if(isset($_GET['role_id'])) { if(in_array('420',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	
	]},
]
});
		
// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
treeView = jQuery("#treeview").data("kendoTreeView"),
message;
//checkedNodeIds(treeView.dataSource.view(), checkedNodes);
jQuery("#result").html(message);
}
$(document).ready(function(){
	$("#role_access_modal").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox-modal').prop('checked', true);
		} else {
			$('.role-checkbox-modal').prop("checked", false);
		}
	});
});
</script>
<?php }
?>
