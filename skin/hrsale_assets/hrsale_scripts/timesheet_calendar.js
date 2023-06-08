$(document).ready(function() {	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
});

$(document).ready(function() { 
	jQuery("#aj_company").change(function(){
		jQuery.get(base_url+"/get_update_employees/"+jQuery(this).val(), function(data, status){
			jQuery('#employee_ajax').html(data);
		});
	});
	$('.d_month_year').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat:'yy-mm',
		yearRange: '2019:' + new Date().getFullYear(),
		beforeShow: function(input) {
			$(input).datepicker("widget").addClass('hide-calendar');
		},
			onClose: function(dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
			$(this).datepicker('widget').removeClass('hide-calendar');
			$(this).datepicker('widget').hide();
		}
	});
});