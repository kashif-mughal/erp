<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cemployee extends CI_Controller {

    public $employee_id;

    function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lsupplier');
        $this->load->library('session');
        $this->load->model('Suppliers');
        $this->load->database();
        $this->auth->check_admin_auth();
    }

    public function index() {
        //$content = $this->lsupplier->supplier_add_form();
        //$this->template->full_admin_html_view($content);
        $CI =& get_instance();
        $CI->load->model('Purchases');
        $CI->load->model('Suppliers');
        $CI->load->model('Products');

        if ($CI->auth->is_admin()) {
            $menu_template = 'include/top_menu';
            $logged_data = 'include/admin_loggedin_info';

            $log_info = array(
                'email' => $CI->session->userdata('user_name'),
                'logout' => base_url() . 'Admin_dashboard/logout'
            );
            $top_menu = $CI->parser->parse($menu_template, true);
            $logged_info = $CI->parser->parse($logged_data, $log_info, true);
        }


        $company_info = $CI->Products->retrieve_company();
        $data = array(
            'logindata' => $logged_info,
            'mainmenu' => $top_menu,
            'content' => "employee/add_employee",
            'msg_content' => $message,
            'company_info' => $company_info
        );


        $CI->load->view("admin_template",$data);
    }
    //Insert supplier
    public function insert_employee() {
        //$supplier_id = $this->auth->generator(20);
        $data = array(
            'employee_name' => $this->input->post('employee_name'),
            'employee_address' => $this->input->post('address'),
            'employee_mobile' => $this->input->post('mobile'),
            'employee_email' => $this->input->post('email'),
            'employee_vehicle' => $this->input->post('vehicle'),
            'status' => 1
        );

        $employee = $this->db->insert("employee_information",$data);
        
        if ($employee == TRUE) {
            //Previous balance adding -> Sending to supplier model to adjust the data.
           // $this->Suppliers->previous_balance_add($this->input->post('previous_balance'), $supplier_id,$supplierName);
            
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-employee'])) {
                redirect(base_url('Cemployee/manage_employee'));
                exit;
            } elseif (isset($_POST['add-employee-another'])) {
                redirect(base_url('Cemployee'));
                exit;
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            if (isset($_POST['add-employee'])) {
                redirect(base_url('Cemployee/manage_employee'));
                exit;
            } elseif (isset($_POST['add-employee-another'])) {
                redirect(base_url('Cemployee'));
                exit;
            }
        }
    }

    //Manage supplier
    public function manage_employee() {
        $CI =& get_instance();
        $CI->load->model('Purchases');
        $CI->load->model('Suppliers');
        $CI->load->model('Products');

       // die("here...");
        if ($CI->auth->is_admin()) {
            $menu_template = 'include/top_menu';
            $logged_data = 'include/admin_loggedin_info';

            $log_info = array(
                'email' => $CI->session->userdata('user_name'),
                'logout' => base_url() . 'Admin_dashboard/logout'
            );
            $top_menu = $CI->parser->parse($menu_template, true);
            $logged_info = $CI->parser->parse($logged_data, $log_info, true);
        }
        //die("here,,,");
        $employee_list = $this->db->query("select * from employee_information where status ='1'")->result_array();

        $company_info = $CI->Products->retrieve_company();
        $data = array(
            'logindata' => $logged_info,
            'mainmenu' => $top_menu,
            'content' => "employee/employee",
            'employee_list' => $employee_list,
            'msg_content' => $message,
            'company_info' => $company_info
        );


        $CI->load->view("admin_template",$data);

    }

    // search supplier 
    public function search_supplier() {
        $supplier_id = $this->input->post('supplier_id');
        $content = $this->lsupplier->supplier_search($supplier_id);
        $this->template->full_admin_html_view($content);
    }

    //Supplier Update Form
    public function employee_update_form($employee_id) {
        $CI =& get_instance();
        $CI->load->model('Purchases');
        $CI->load->model('Suppliers');
        $CI->load->model('Products');

        if ($CI->auth->is_admin()) {
            $menu_template = 'include/top_menu';
            $logged_data = 'include/admin_loggedin_info';

            $log_info = array(
                'email' => $CI->session->userdata('user_name'),
                'logout' => base_url() . 'Admin_dashboard/logout'
            );
            $top_menu = $CI->parser->parse($menu_template, true);
            $logged_info = $CI->parser->parse($logged_data, $log_info, true);
        }

        $employee_list = $this->db->query("select * from employee_information where employee_id ='$employee_id' and status='1'")->result_array();

        $company_info = $CI->Products->retrieve_company();
        $data = array(
            'logindata' => $logged_info,
            'mainmenu' => $top_menu,
            'content' => "employee/edit_employee_form",
            'employee_list' => $employee_list,
            'msg_content' => $message,
            'company_info' => $company_info
        );


        $CI->load->view("admin_template",$data);
    }

    // Supplier Update
    public function employee_update() {
        $employee_id = $this->input->post('employee_id');
        $data = array(
            'employee_name' => $this->input->post('employee_name'),
            'employee_address' => $this->input->post('address'),
            'employee_mobile' => $this->input->post('mobile'),
            'employee_email' => $this->input->post('email'),
            'employee_vehicle' => $this->input->post('vehicle'),
        );
        $this->db->where('employee_id', $employee_id);
        $this->db->update('employee_information',$data);

        //print_r($data);
        //die();
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cemployee/manage_employee'));
        exit;
    }

    //Supplier Search Item
    public function supplier_search_item() {
        $supplier_id = $this->input->post('supplier_id');
        $content = $this->lsupplier->supplier_search_item($supplier_id);
        $this->template->full_admin_html_view($content);
    }

    // Supplier Delete from System
    public function employee_delete($employee_id) {
       // $employee_id = $_REQUEST['employee_id'];

        $data = array('status' => '-1');
        $this->db->where('employee_id',$employee_id);
        $this->db->update('employee_information',$data);
        redirect(base_url('Cemployee/manage_employee'));
        
        return true;
    }

    // Supplier details findings !!!!!!!!!!!!!! Inactive Now !!!!!!!!!!!!
    public function supplier_details($supplier_id) {
        $content = $this->lsupplier->supplier_detail_data($supplier_id);
        $this->supplier_id = $supplier_id;
        $this->template->full_admin_html_view($content);
    }

    //Supplier Ledger Book
    public function supplier_ledger() {
        $start = $this->input->post('from_date');
        $end = $this->input->post('to_date');

        $supplier_id = $this->input->post('supplier_id');
        $cat = $this->input->post('rep_cat');

        if ($cat == "all") {
            $url = "Csupplier/supplier_ledger_report";
            redirect(base_url($url));
            exit;
        }
        $sup_sale = $this->input->post('cat');


        if ($sup_sale == "2") {
            $url = "Csupplier/supplier_sales_details" . '/' . $supplier_id . '/' . $start . '/' . $end;
            redirect(base_url($url));
            exit;
        }
        $sup_sale_summary = $this->input->post('cat');

        if ($sup_sale_summary == "3") {
            $url = "Csupplier/supplier_sales_summary" . '/' . $supplier_id . '/' . $start . '/' . $end;
            redirect(base_url($url));
            exit;
        }
        $sup_sale_summary = $this->input->post('cat');

        if ($sup_sale_summary == "4") {
            $url = "Csupplier/sales_payment_actual" . '/' . $supplier_id . '/' . $start . '/' . $end;
            redirect(base_url($url));
            exit;
        }

        $content = $this->lsupplier->supplier_ledger($supplier_id, $start, $end);

        $this->template->full_admin_html_view($content);
    }

    public function supplier_ledger_report() {

        $config["base_url"] = base_url('Csupplier/supplier_ledger_report/');
        $config["total_rows"] = $this->Suppliers->count_supplier_product_info();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $links = $this->pagination->create_links();
        $content = $this->lsupplier->supplier_ledger_report($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    // Supplier wise sales report details
    public function supplier_sales_details() {
        $start = $this->input->post('from_date');
        $end = $this->input->post('to_date');
        $supplier_id = $this->uri->segment(3);

        $content = $this->lsupplier->supplier_sales_details($supplier_id, $start, $end);
        $this->template->full_admin_html_view($content);
    }

    // Supplier wise sales report summary
    public function supplier_sales_summary() {
        #
        #pagination starts
        #
        $supplier_id = $this->uri->segment(4);
        $config["base_url"] = base_url('Csupplier/supplier_sales_summary/' . $supplier_id . "/");
        $config["total_rows"] = $this->Suppliers->supplier_sales_summary_count($supplier_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 4;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content = $this->lsupplier->supplier_sales_summary($supplier_id, $links, $config["per_page"], $page);

        $this->supplier_id = $supplier_id;
        $this->template->full_admin_html_view($content);
    }

    // Actual Ledger based on sales & deposited amount
    public function sales_payment_actual() {
        #
        #pagination starts
        $supplier_id = $this->uri->segment(3);

        $config["base_url"] = base_url('Csupplier/sales_payment_actual/' . $supplier_id . "/");
        $config["total_rows"] = $this->Suppliers->sales_payment_actual_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 6;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content = $this->lsupplier->sales_payment_actual($supplier_id, $links, $config["per_page"], $page);

        $this->supplier_id = $supplier_id;
        $this->template->full_admin_html_view($content);
    }

    // search report 
    public function search_supplier_report() {
        $start = $this->input->post('from_date');
        $end = $this->input->post('to_date');

        $content = $this->lpayment->result_datewise_data($start, $end);
        $this->template->full_admin_html_view($content);
    }

    //Supplier sales details all from menu
    public function supplier_sales_details_all() {
        $config["base_url"] = base_url('Csupplier/supplier_sales_details_all/');
        $config["total_rows"] = $this->Suppliers->supplier_sales_details_count_all();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content = $this->lsupplier->supplier_sales_details_allm($links, $config["per_page"], $page);

        $this->template->full_admin_html_view($content);
    }

    // supplier ledger for supplier information 
    public function supplier_ledger_info($supplier_id) {
        $content = $this->lsupplier->supplier_ledger_info($supplier_id);
        $this->supplier_id = $supplier_id;
        $this->template->full_admin_html_view($content);
    }

       public function supplier_detail_ledger($supplier_id){

        $CI =& get_instance();
        $CI->load->model('Purchases');
        $CI->load->model('Suppliers');
        $CI->load->model('Products');

        if ($CI->auth->is_admin()) {
            $menu_template = 'include/top_menu';
            $logged_data = 'include/admin_loggedin_info';

            $log_info = array(
                'email' => $CI->session->userdata('user_name'),
                'logout' => base_url() . 'Admin_dashboard/logout'
            );
            $top_menu = $CI->parser->parse($menu_template, true);
            $logged_info = $CI->parser->parse($logged_data, $log_info, true);
        }


        $company_info = $CI->Products->retrieve_company();
        $data = array(
            'logindata' => $logged_info,
            'mainmenu' => $top_menu,
            'content' => "supplier/detail_ledger",
            'msg_content' => $message,
            'company_info' => $company_info
        );

        $startdate = $this->input->get('from_date');
        $enddate = $this->input->get('to_date');

        if($startdate == ""){

            $startdate = date("Y-m-01");
            $enddate = date("Y-m-t");
        }

        $supplierInfo = $this->db->query("select * from supplier_information where supplier_id = '$supplier_id'")->row();        
        

        $queryRS = $this->db->query("select amount from supplier_ledger where supplier_id = '$supplier_id' and payment_type='NA'");

        $queryData  = $queryRS->row();
        $openingbalance = $queryData->amount;

        $previouscredit = $this->db->query("SELECT SUM(amount) as credit_amount FROM supplier_ledger WHERE supplier_id = '$supplier_id' AND DATE < '$startdate' AND ISNULL(chalan_no) ")->row()->credit_amount;

        $previousdebit = $this->db->query("SELECT SUM(amount) as debit_amount FROM supplier_ledger WHERE supplier_id = '$supplier_id' AND DATE < '$startdate' AND ISNULL(deposit_no)  AND payment_type !='NA'")->row()->debit_amount;
       

        $currentQuery  = $this->db->query("SELECT * FROM supplier_ledger WHERE supplier_id = '$supplier_id' AND `date` >= '$startdate' and `date` <='$enddate'  AND payment_type !='NA' order by `date` asc");

        $currentData = $currentQuery->result_array();
        
       
        $forwardcredit = $this->db->query("SELECT SUM(amount) as credit_amount FROM supplier_ledger WHERE supplier_id = '$supplier_id' AND DATE > '$enddate' AND ISNULL(chalan_no) ")->row()->credit_amount;

        $forwarddebit = $this->db->query("SELECT SUM(amount) as debit_amount FROM supplier_ledger WHERE supplier_id = '$supplier_id' AND DATE > '$enddate' AND ISNULL(deposit_no)  AND payment_type !='NA'")->row()->debit_amount;
        $data['supplier_id']= $supplier_id;
        $data['openingbalance']= $openingbalance - $previouscredit + $previousdebit;
        $data['current_transaction']= $currentData;
        $data['forward_transaction']= $forwarddebit - $forwardcredit;
        $data['start_date']= $startdate;
        $data['end_date']= $enddate;
        $data['supplier_info']= $supplierInfo;

        
        $CI->load->view("admin_template",$data);
       
    }


}
