<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/API_Controller.php';

class Employee_list extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api_helper');
        $this->load->model("Salary_model");
        $this->load->model("Attendance_model");

    }
    /**
     * demo method
     *
     * @link [api/user/demo]
     * @method POST
     * @return Response|void
     */
    public function test()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            echo json_encode($user_info);
            exit();




        } else {
            echo json_encode($user_info);
            exit();
        }
    }
    public function index(){
            $this->db->select('xin_employees.first_name,xin_employees.last_name,xin_employees.email,xin_employees.contact_no,xin_departments.department_name,xin_designations.designation_name');
            $this->db->from('xin_employees');
            $this->db->from('xin_departments');
            $this->db->from('xin_designations');
            $this->db->where('xin_employees.designation_id = xin_designations.designation_id');
            $this->db->where('xin_employees.department_id = xin_departments.department_id');
            $this->db->where('xin_employees.user_role_id',3);
            $this->db->where('xin_employees.status',1);
            $this->db->order_by('xin_employees.basic_salary','DESC');
            $data['Employee']=$this->db->get()->result();
            $this->api_return([
                'success' => true,
                'massage'=>'Success',
                'data' => $data,
            ],200);
    }
}
