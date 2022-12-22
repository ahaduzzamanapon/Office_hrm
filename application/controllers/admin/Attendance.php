<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the HRSALE License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.hrsale.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hrsalesoft@gmail.com so we can send you a copy immediately.
 *
 * @author   HRSALE
 * @author-email  hrsalesoft@gmail.com
 * @copyright  Copyright © hrsale.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Controller {

	 public function __construct() {
        parent::__construct();
        $session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		//load the model
		$this->load->model('Attendance_model');
		$this->load->model("Xin_model");


		// $this->load->model("Timesheet_model");
		// $this->load->model("Employees_model");
		// $this->load->library('email');
		// $this->load->model("Department_model");
		// $this->load->model("Designation_model");
		// $this->load->model("Roles_model");
		// $this->load->model("Project_model");
		// $this->load->model("Location_model");
	}

	public function index()
    {
		$data['title'] = $this->lang->line('dashboard_attendance').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('dashboard_attendance');
		$data['path_url'] = 'attendance';
		// $data['all_office_shifts'] = $this->Location_model->all_office_locations();
		$data['subview'] = $this->load->view("admin/attendance/index", $data, TRUE);
		$this->load->view('admin/layout/layout_main', $data); //page load
			  
    }

	public function attendance_process($process_date, $status)
    {
    	$process_date = date("Y-m-d", strtotime($process_date));
		$this->Attendance_model->attn_process($process_date, $status);
		$this->db->trans_complete();
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo "Process failed";
		}
		else
		{
			echo "Process completed sucessfully";
		}

    }

    // status wise daily report
    public function daily_present_report($attendance_date, $status)
    {
    	$attendance_date = date("Y-m-d", strtotime($attendance_date));
    	$data["values"] = $this->Attendance_model->daily_present_report($attendance_date, $status);


        $data["attendance_date"] = $attendance_date;
        if(is_string($data["values"]))
        {
            echo $data["values"];
        }
        else
        {
            $this->load->view('admin/attendance/daily_present_report',$data);
        }
    }



    public function get_employee_ajax_request()
    {
    	$status = $this->input->get('status');
    	$data["employees"] = $this->Attendance_model->get_employee_ajax_request($status);
        echo json_encode($data);
    }

	
}