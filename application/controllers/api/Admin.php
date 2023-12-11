<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/API_Controller.php';

class Admin extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api_helper');
        $this->load->model("Timesheet_model");
        $this->load->model("Attendance_model");
        $this->load->model("Inventory_model");
        $this->load->model("Reports_model");
        $this->load->model("Lunch_model");
        $this->load->model("Xin_model");
        $this->load->library('upload');
    }

    // leave
    public function leave_list()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $offset = $this->input->post('offset');
                $limit = $this->input->post('limit');
                $status = $this->input->post('status');

                $result = $this->Timesheet_model->get_leaves_with_info_with_pagi($offset, $limit, $status);
                if ($result) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $result,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data not found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    public function single_leave_status()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $id = $this->input->post('leave_id');
                $data['result'] = $this->Timesheet_model->get_leaves_leave_id_with_info($id);
                if (empty($data['result'])) {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data not found',
                        'data' => [],
                    ], 200);
                }
                $data['leave_calel'] = 12 - get_cal_leave($data['result']->employee_id, 1);
                $data['leave_calel_percent'] = $data['leave_calel'] * 100 / 12;
                $data['leave_calsl'] = 4 - get_cal_leave($data['result']->employee_id, 2);
                $data['leave_calsl_percent'] = $data['leave_calsl'] * 100 / 4;
                if ($data) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Unauthorized User',
                        'data' => [],
                    ], 401);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    public function single_leave_status_change()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');
                $total_days = $this->input->post('total_days');
                $status = $this->input->post('status');
                $remark = $this->input->post('remark');
                $leave_id = $this->input->post('leave_id');

                $hulfday = 0;
                if ($this->input->post('Half_Day')) {
                    $hulfday = 1;
                    $total_days = 0.5;
                }
                $qt_remarks = htmlspecialchars(addslashes($remark), ENT_QUOTES);
                $stutuss = $this->input->post('status');
                if ($stutuss == 4 || $stutuss == 3 || $stutuss == 2) {
                    $notyfi_data = 3;
                } else {
                    $notyfi_data = 1;
                };
                $qnty = $total_days;

                $data = array(
                    'status' => $status,
                    'remarks' => $qt_remarks,
                    'notify_leave' => $notyfi_data,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'qty' => $qnty,
                    'is_half_day' => $hulfday,
                );
                $id = $this->input->post('leave_id');
                $result = $this->Timesheet_model->update_leave_record($data, $id);
                if ($result == true) {
                    if ($data['qty'] > 0) {
                        for ($i = 0; $i < $data['qty']; $i++) {
                            $process_date = date("Y-m-d", strtotime("+$i day", strtotime($data['from_date'])));
                            $this->Attendance_model->attn_process($process_date, array($_POST['emp_id']));
                        }
                    }
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'request failed',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    // levae end

    // stock in start
    public function stock_in_list()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $offset = $this->input->post('offset');
                $limit = $this->input->post('limit');
                $status = $this->input->post('status');
                $data['products'] = $this->Inventory_model->purchase_products_requisition_api($offset, $limit, $status);
                if ($data) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    public function single_stock_in_details()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $id = $this->input->post('id');
                $data['results'] = $this->Inventory_model->product_requisition_details($id);
                if (!empty($data['results'])) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    public function single_stock_in_status_change()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $id = $this->input->post('id');
                $quantity = $this->input->post('approved_quantity');
                $status = $this->input->post('status');
                if ($status == 1 || $status == 2 || $status == 4) {
                    $update = $this->db->where('id', $id)->update('products_purches_details', ['status' => $status, 'ap_quantity' => $quantity]);
                    if ($update) {
                        $this->api_return([
                            'status' => true,
                            'message' => 'Update Successful',
                            'data' => [],
                        ], 200);
                    } else {
                        $this->api_return([
                            'status' => false,
                            'message' => 'Update Failed',
                            'data' => [],
                        ], 200);
                    }
                } elseif ($status == 3) {

                    $results = $this->db->where('id', $id)->get('products_purches_details')->result();
                    foreach ($results as $key => $row) {
                        $product = $this->db->where('id', $row->product_id)->get('products')->row();
                        $quantity = $product->quantity + $row->ap_quantity;
                        $this->db->where('id', $row->product_id)->update('products', array('quantity' => $quantity));
                        $this->db->where('id', $row->id)->update('products_purches_details', array('status' => 3));
                    }
                    $deliver = $this->db->where('id', $id)->update('products_purches_details', ['status' => 3]);
                    if ($deliver) {
                        $this->api_return([
                            'status' => true,
                            'message' => 'Received Successful',
                            'data' => [],
                        ], 200);
                    } else {
                        $this->api_return([
                            'status' => false,
                            'message' => 'Update Failed',
                            'data' => [],
                        ], 200);
                    }
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Please provide valid status',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    // stock in end

    // stock out start

    public function stock_out_list()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $offset = $this->input->post('offset');
                $limit = $this->input->post('limit');
                $status = $this->input->post('status');
                $data['products'] = $this->Inventory_model->product_requisition_api($offset, $limit, $status);
                if ($data) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    public function single_stock_out_details()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $id = $this->input->post('id');
                $data['results'] = $this->Inventory_model->requisition_details($id);
                if (!empty($data['results'])) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }
    public function single_stock_out_status_change()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $id = $this->input->post('id');
                $quantity = $this->input->post('approved_quantity');
                $status = $this->input->post('status');
                $permission = $this->input->post('p_permission');

                $session = [
                    'role_id' => $user_info['user_info']->user_role_id,
                    'user_id' => $user_info['user_info']->user_id,
                ];
                if ($status == 4) {
                    $this->db->where('id', $id)->update('products_requisition_details', ['updated_by' => $user_info['user_info']->user_id]);
                    $approved = $this->db->where('id', $id)->update('products_requisition_details', ['status' => 4]);
                    if ($approved) {
                        $this->db->where('id', $id)->update('products_requisition_details', ['status' => 4]);
                        $this->api_return([
                            'status' => true,
                            'message' => 'successful',
                            'data' => [],
                        ], 200);
                    }
                } else {
                    $status = 1;
                    $approved_qty = $quantity;
                    $permission = $this->input->post('p_permission');
                    $r_id = $id;
                    //  manage permission to user wise
                    if ($session['role_id'] == 2) {
                        if ($permission == '0') {
                            $status = 2;
                        } else {
                            $status = 6;
                        }
                    } elseif ($session['role_id'] == 1) {
                        $status = 2;
                    } elseif ($session['role_id'] == 4) {
                        $status = 5;
                    } else {
                        $this->api_return([
                            'status' => false,
                            'message' => 'Please Give Valid status',
                            'data' => [],
                        ], 200);
                    }
                    $data = array(
                        'approved_qty' => $approved_qty,
                        'status' => $status,
                        'updated_by' => $session['user_id'],
                    );

                    $approved = $this->db->where('id', $r_id)->update('products_requisition_details', $data);
                    if ($approved) {
                        $this->api_return([
                            'status' => false,
                            'message' => 'Success',
                            'data' => [],
                        ], 200);
                    }
                }

            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        }
    }

    public function late_approved_list()
    {

        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $salary_month = $this->db->select('*')->from('xin_salary_payslips')->order_by('payslip_id', 'DESC')->limit(1)->get()->row()->salary_month;
                $data = $this->Xin_model->modify_salary($salary_month);
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function late_approved_add()
    {
        
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $json_data = file_get_contents('php://input');

                // Decode the JSON data
                $data = json_decode($json_data, true);
                $date=$data['date'];
                $modify_data=$data['modify_data'];
               
                foreach ($modify_data as $key => $value) {
                    $user_id = $value['modify_user_id'];
                    $salary = $value['modify_salary'];
                    $date = $date;
                    $m_day = $value['modify_day'];
                    $result = $this->Xin_model->update_salaryall($user_id, $salary, $date, $m_day);
                }

             
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => [],
                    ], 200);
              
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function employee_report()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $data = $this->Reports_model->show_report();
            
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function device_report()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $data = $this->Reports_model->get_product_reports_info();
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function device_report_movement()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $data = $this->Reports_model->show_move_report( );
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function daily_payment_in()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $this->db->select('xin_project_invoice.*,xin_clients.name as client_name,xin_projects.title as project_name');
                $this->db->from('xin_project_invoice');
                $this->db->join('xin_clients', 'xin_clients.client_id = xin_project_invoice.clint_id');
                $this->db->join('xin_projects', 'xin_projects.project_id = xin_project_invoice.project_id');
                $this->db->where('xin_project_invoice.date',date('Y-m-d'));
                $data=$this->db->get()->result();
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function monthly_payment_in()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $this->db->select('xin_project_invoice.*,xin_clients.name as client_name,xin_projects.title as project_name');
                $this->db->from('xin_project_invoice');
                $this->db->join('xin_clients', 'xin_clients.client_id = xin_project_invoice.clint_id');
                $this->db->join('xin_projects', 'xin_projects.project_id = xin_project_invoice.project_id');
                $this->db->where('xin_project_invoice.date >=',date('Y-m-1'));
                $this->db->where('xin_project_invoice.date <=',date('Y-m-t'));
                $data=$this->db->get()->result();
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function daily_payment_out()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
                $this->db->select('xin_payment_out_invoice.*,xin_payment_out_purpose.*');
                $this->db->from('xin_payment_out_invoice');
                $this->db->join('xin_payment_out_purpose', 'xin_payment_out_purpose.id = xin_payment_out_invoice.purposes');
                $this->db->where('xin_payment_out_invoice.date ',date('Y-m-d'));
                $data=$this->db->get()->result();
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
    public function lunch_payment_report()
    {
        $authorization = $this->input->get_request_header('Authorization');
        $user_info = api_auth($authorization);
        if ($user_info['status'] == true) {
            if ($user_info['user_info']->user_role_id != 3) {
               
                $data = $this->Lunch_model->paymentreport();
                if (!empty($data)) {
                    $this->api_return([
                        'status' => true,
                        'message' => 'successful',
                        'data' => $data,
                    ], 200);
                } else {
                    $this->api_return([
                        'status' => false,
                        'message' => 'Data Not Found',
                        'data' => [],
                    ], 200);
                }
            } else {
                $this->api_return([
                    'status' => false,
                    'message' => 'Unauthorized User',
                    'data' => [],
                ], 401);
            };
        } else {
            $this->api_return([
                'status' => false,
                'message' => 'Unauthorized User',
                'data' => [],
            ], 401);
        };
    }
}
