<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Cinvoice extends CI_Controller {



    function __construct() {

        parent::__construct();
        $this->load->database();


    }



    public function index() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');
       // 
        $customer_list = $this->db->query("select customer_id, customer_name, credit_limit, discount from customer_information")->result_array();

        $content = $CI->linvoice->invoice_add_form($customer_list);

        $this->template->full_admin_html_view($content);

    }



    //Insert invoice

    public function insert_invoice() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $invoice_id = $CI->Invoices->invoice_entry();

        $this->session->set_userdata(array('message' => display('successfully_added')));

        redirect('Cinvoice/delivery_challan/'.$invoice_id);

        //$this->invoice_inserted_data($invoice_id);

    }



    //invoice Update Form

    public function invoice_update_form($invoice_id) {
        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $customer_list = $this->db->query("select customer_id, customer_name, credit_limit, discount from customer_information")->result_array();

        $content = $CI->linvoice->invoice_edit_data($invoice_id, $customer_list);

        $this->template->full_admin_html_view($content);

    }



    // invoice Update

    public function invoice_update() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $invoice_id = $CI->Invoices->update_invoice();

        $this->session->set_userdata(array('message' => display('successfully_updated')));

        redirect('Cinvoice/delivery_challan/'.$invoice_id);
        //$this->invoice_inserted_data($invoice_id);

    }



    //Search Inovoice Item

    public function search_inovoice_item() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('linvoice');



        $customer_id = $this->input->post('customer_id');

        $content = $CI->linvoice->search_inovoice_item($customer_id);

        $this->template->full_admin_html_view($content);

    }



    //Manage invoice list

    public function manage_invoice() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $CI->load->model('Invoices');


        $config["base_url"] = base_url('Cinvoice/manage_invoice/');

        $config["total_rows"] = $this->Invoices->invoice_list_count();

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

        $content = $this->linvoice->invoice_list($links, $config["per_page"], $page, "invoice/invoice","manage_invoice");

        $this->template->full_admin_html_view($content);

    }



    // search invoice by customer id

    public function invoice_search() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('Linvoice');
        $CI->load->model('Invoices');

        $customer_id = $this->input->get('customer_id');

        #

        #pagination starts

        #

        $config["base_url"] = base_url('Cinvoice/invoice_search/');

        $config["total_rows"] = $this->Invoices->invoice_search_count($customer_id);

        $config["per_page"] = 10;

        $config["uri_segment"] = 3;

        $config["num_links"] = 5;

        $config['suffix'] = '?' . http_build_query($_GET);

        $config['first_url'] = $config["base_url"] . $config['suffix'];

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
        $content = $this->linvoice->invoice_search($customer_id, $links, $config["per_page"], $page, "invoice/invoice", "Manage Invoice");

        $this->template->full_admin_html_view($content);

    }



    // search invoice by invoice id

    public function manage_invoice_invoice_id() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $CI->load->model('Invoices');

        $invoice_no = $this->input->post('invoice_no');

        $content = $this->linvoice->invoice_list_invoice_no($invoice_no);

        $this->template->full_admin_html_view($content);

    }



    // invoice list date to date 

    public function date_to_date_invoice() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $CI->load->model('Invoices');

        $from_date = $this->input->get('from_date');

        $to_date = $this->input->get('to_date');



        #

        #pagination starts

        #
        $config["base_url"] = base_url('Cinvoice/date_to_date_invoice/');

        $config["total_rows"] = $this->Invoices->invoice_list_date_to_date_count($from_date, $to_date);

        $config["per_page"] = 10;

        $config["uri_segment"] = 3;

        $config["num_links"] = 5;

        $config['suffix'] = '?' . http_build_query($_GET, '', '&');

        $config['first_url'] = $config["base_url"] . $config['suffix'];

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
        

        $content = $this->linvoice->invoice_list_date_to_date($from_date, $to_date, $links, $config["per_page"], $page);

        $this->template->full_admin_html_view($content);

    }



    //POS invoice page load

    public function pos_invoice() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $customer_list = $this->db->query("select customer_id, customer_name, credit_limit, discount from customer_information")->result_array();
        $content = $CI->linvoice->pos_invoice_add_form($customer_list);

        $this->template->full_admin_html_view($content);

    }



    //Insert pos invoice

    public function insert_pos_invoice() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $product_id = $this->input->post('product_id');



        $product_details = $CI->Invoices->pos_invoice_setup($product_id);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $tr = " ";

        if (!empty($product_details)) {

            $product_id = $this->generator(5);

            

            $tr .= "<tr id=\"row_" . $product_id . "\">

                        <td class=\"\" style=\"width:220px\">

                            

                            <input type=\"text\" name=\"product_name\" onkeypress=\"invoice_productList('" . $product_id . "');\" class=\"form-control productSelection \" value='" . $product_details->product_name . "- (" . $product_details->product_model . ")" . "' placeholder='" . display('product_name') . "' required=\"\" id=\"product_name\" tabindex=\"\" readonly>



                            <input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId\" value = \"$product_details->product_id\" id=\"product_id\"/>

                            

                        </td>



                        <td>

                            <input type=\"text\" name=\"available_quantity[]\" class=\"form-control text-right available_quantity_'" . $product_details->product_id . "'\" value='" . $product_details->total_product . "' readonly=\"\"/>

                        </td>



                        <td>

                            <input class=\"form-control text-right unit_'" . $product_details->product_id . "' valid\" value=\"$product_details->unit\" readonly=\"\" aria-invalid=\"false\" type=\"text\">

                        </td>

                    

                        <td>

                            <input type=\"number\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_id . "');\" onchange=\"quantity_calculate('" . $product_id . "');\" class=\"total_qntt_" . $product_id . " form-control text-right\" id=\"total_qntt_" . $product_id . "\" placeholder=\"0.00\" min=\"0\"/>

                        </td>



                        <td style=\"width:85px\">

                            <input type=\"number\" name=\"product_rate[]\" onkeyup=\"quantity_calculate('" . $product_id . "');\" onchange=\"quantity_calculate('" . $product_id . "');\" value='" . $product_details->price . "' id=\"price_item_" . $product_id . "\" class=\"price_item1 form-control text-right\" required placeholder=\"0.00\" min=\"0\"/>

                        </td>



                        <td class=\"\">

                            <input type=\"text\" name=\"discount[]\" onkeyup=\"quantity_calculate('" . $product_id . "');\" onchange=\"quantity_calculate('" . $product_id . "');\" id=\"discount_" . $product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>



                            <input type=\"hidden\" value=" . $currency_details[0]['discount_type'] . " name=\"discount_type\" id=\"discount_type_" . $product_id . "\">

                        </td>



                        <td class=\"text-right\" style=\"width:100px\">

                            <input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_id . "\" value='" . $product_details->price . "' tabindex=\"-1\" readonly=\"readonly\"/>

                        </td>



                        <td>

                            <input type=\"hidden\" id=\"total_tax_" . $product_id . "\" class=\"total_tax_1\" value='" . $product_details->tax . "'/>

                            <input type=\"hidden\" id=\"all_tax_" . $product_id . "\" class=\" total_tax\" value=\"\" name=\"tax[]\"/>

                            <input type=\"hidden\" id=\"total_discount_" . $product_id . "\" />

                            <input type=\"hidden\" id=\"all_discount_" . $product_id . "\" class=\"total_discount\"/>

                            <button style=\"text-align: right;\" class=\"btn btn-danger\" type=\"button\" value='" . display('delete') . "' onclick=\"deleteRow(this)\">" . display('delete') . "</button>

                        </td>

                    </tr>";

            echo $tr;

        } else {

            return false;

        }

    }



    //Retrive right now inserted data to cretae html

    public function invoice_inserted_data($invoice_id) {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $content = $CI->linvoice->invoice_html_data($invoice_id);

        $this->template->full_admin_html_view($content);

    }




    //Retrive right now inserted data to cretae html

    public function pos_invoice_inserted_data($invoice_id) {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $content = $CI->linvoice->pos_invoice_html_data($invoice_id);

        $this->template->full_admin_html_view($content);

    }



    // Retrieve_product_data

    public function retrieve_product_data() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $product_id = $this->input->post('product_id');

        $supplier_id = $this->input->post('supplier_id');



        $product_info = $CI->Invoices->get_total_product($product_id, $supplier_id);



        echo json_encode($product_info);

    }



    //product info retrive by product id for invoice

    public function retrieve_product_data_inv() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $product_id = $this->input->post('product_id');
        $product_uuid = null;
        $product_uuid = $this->input->post('product_uuid');
        $product_info = $CI->Invoices->pos_invoice_setup($product_id, $product_uuid);
        //$product_info = $CI->Invoices->get_total_product_invoic($product_id);



        echo json_encode($product_info);

    }



    // Invoice delete

    public function invoice_delete() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $invoice_id = $_POST['invoice_id'];

        $result = $CI->Invoices->delete_invoice($invoice_id);

        if ($result) {

            $this->session->set_userdata(array('message' => display('successfully_delete')));

            return true;

        }

    }    // Invoice delete

    public function sale_order_delete() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $invoice_id = $_POST['invoice_id'];

        $this->db->select("invoice");
        $this->db->from("sale_order");
        $this->db->where("invoice_id", $invoice_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $invoice_id =  $query->result_array();
        }


        $invoice_id = $invoice_id[0]["invoice"];

        $data = array(
            'status' => 0
        );
        $this->db->where('invoice', $invoice_id);
        $this->db->update('sale_order',$data);

        $this->db->where('invoice_id', $invoice_id);
        $this->db->update('sale_order_details',$data);


        // $this->db->where('invoice', $invoice_id);
        // $this->db->delete('sale_order');
        // $this->db->where('invoice_id', $invoice_id);
        // $this->db->delete('sale_order_details');
    }



    //AJAX INVOICE STOCKs

    public function product_stock_check($product_id) {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Invoices');

        //$product_id =  $this->input->post('product_id');



        $purchase_stocks = $CI->Invoices->get_total_purchase_item($product_id);

        $total_purchase = 0;

        if (!empty($purchase_stocks)) {

            foreach ($purchase_stocks as $k => $v) {

                $total_purchase = ($total_purchase + $purchase_stocks[$k]['quantity']);

            }

        }

        $sales_stocks = $CI->Invoices->get_total_sales_item($product_id);

        $total_sales = 0;

        if (!empty($sales_stocks)) {

            foreach ($sales_stocks as $k => $v) {

                $total_sales = ($total_sales + $sales_stocks[$k]['quantity']);

            }

        }



        $final_total = ($total_purchase - $total_sales);

        return $final_total;

    }



//    =========== its for 1 increment =============

    function randomChange($myValue) {

        $random = rand(0, 1);

        if ($random > 0)

            return $myValue + 1;



        return $myValue - 1;

    }



    //This function is used to Generate Key

    public function generator($lenth) {

        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");



        for ($i = 0; $i < $lenth; $i++) {

            $rand_value = rand(0, 8);

            $rand_number = $number["$rand_value"];



            if (empty($con)) {

                $con = $rand_number;

            } else {

                $con = "$con" . "$rand_number";

            }

        }

        return $con;

    }


    public function sale_order() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $customer_details = $CI->Invoices->pos_customer_setup();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $customer_list = $this->db->query("select customer_id, customer_name, credit_limit, discount from customer_information")->result_array();
        $data = array(
            'title' => display('add_new_pos_invoice'),
            'customer_name' => $customer_details[0]['customer_name'],
            'customer_id' => $customer_details[0]['customer_id'],
            'discount_type' => $currency_details[0]['discount_type'],
            'content' => 'invoice/add_sale_order_form',
            'nested_date' => array(
                'customer_list' => $customer_list
            )
        );
        //die("here..");
      //  $invoiceForm = $CI->parser->parse('invoice/add_sale_order_form', $data, true);
         $CI->load->view("admin_template", $data);

    }

    public function sale_order_remaining_items(){
        $saleOrderId = $this->input->get('saleOrderId');
        $query = "SELECT so.invoice_id so_invoice_id, so.customer_id so_customer_id, sod.* FROM sale_order so JOIN sale_order_details sod ON sod.invoice_id = so.invoice WHERE so.invoice = ".$saleOrderId;
        $customer_list = $this->db->query($query)->result_array();
        print_r(json_encode($customer_list));
    }

    public function insert_sale_order(){
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('Linvoice');
        $CI->load->model('Invoices');
        $CI->load->model('Customers');
        
        $invoice_id = $CI->Invoices->generator(10);
        $invoice_id = strtoupper($invoice_id);

      //  echo $invoice_id;

        $quantity = $this->input->post('product_quantity');
       // $available_quantity = $this->input->post('available_quantity');
        $cartoon = $this->input->post('cartoon');



        $product_id = $this->input->post('product_id');

        if ($product_id == null) {

            $this->session->set_userdata(array('error_message' => display('please_select_product')));

            redirect('Cinvoice/sale_order');

        }



        if (($this->input->post('customer_name_others') == null) && ($this->input->post('customer_id') == null ) && ($this->input->post('customer_name') == null )) {

            $this->session->set_userdata(array('error_message' => display('please_select_customer')));

            redirect(base_url() . 'Cinvoice');

        }



        if (($this->input->post('customer_id') == null ) && ($this->input->post('customer_name') == null )) {

            $customer_id = $this->auth->generator(15);

            //Customer  basic information adding.

            $data = array(

                'customer_id' => $customer_id,

                'customer_name' => $this->input->post('customer_name_others'),

                'customer_address' => $this->input->post('customer_name_others_address'),

                'customer_mobile' => "",

                'customer_email' => "",

                'status' => 2

            );


            $this->db->insert('customer_information', $data);
            $this->db->select('*');
            $this->db->from('customer_information');
            $query = $this->db->get();

            foreach ($query->result() as $row) {

                $json_customer[] = array('label' => $row->customer_name, 'value' => $row->customer_id);

            }

            $cache_file = './my-assets/js/admin_js/json/customer.json';

            $customerList = json_encode($json_customer);

            file_put_contents($cache_file, $customerList);

            $this->Customers->previous_balance_add(0, $customer_id);

        } else {

            $customer_id = $this->input->post('customer_id');

        }


        //Full or partial Payment record.

        $paid_amount = $this->input->post('paid_amount');


        //Data inserting into invoice table
//die('here...');
        $invoice_number = $this->sale_order_number_generator();
        $datainv = array(

            'invoice_id' => $invoice_id,
            'customer_id' => $customer_id,
            'date' => $this->input->post('invoice_date'),
            'total_amount' => $this->input->post('grand_total_price'),
            'total_tax' => $this->input->post('total_tax'),
            'invoice' => $invoice_number,
            'invoice_details' => $this->input->post('inva_details'),
            'invoice_discount' => $this->input->post('invoice_discount'),
            'total_discount' => $this->input->post('total_discount'),
            'status' => 1

        );

//die('here...');
        
//print_r($datainv);
        $this->db->insert('sale_order', $datainv);
        $rate = $this->input->post('product_rate');
        $p_id = $this->input->post('product_id');
        $total_amount = $this->input->post('total_price');
        $discount_rate = $this->input->post('discount_amount');
        $discount_per = $this->input->post('discount');
        $tax_amount = $this->input->post('tax');
        $all_product_ids = $this->input->post('product_uuid');
//die("here....");
        for ($i = 0, $n = count($p_id); $i < $n; $i++) {

            $cartoon_quantity = $cartoon[$i];

            $product_quantity = $quantity[$i];
            $product_rate = $rate[$i];
            $product_id = $p_id[$i];
            $total_price = $total_amount[$i];
            //$supplier_rate = $this->supplier_rate($product_id);
            $disper = $discount_per[$i];
            $discount = is_numeric($product_quantity) * is_numeric($product_rate) * is_numeric($disper) / 100;
            $tax = $tax_amount[$i];
            $product_uuid = $all_product_ids[$i];


            $data1 = array(

                'invoice_details_id' => $this->generator(15),
                'invoice_id' => $invoice_number,
                'product_id' => $product_id,
                'quantity' => $product_quantity,
                'remaining_quantity' => $product_quantity,
                'rate' => $product_rate,
                'discount' => $discount,
                'discount_per' => $disper,
                'tax' => $tax,
                'paid_amount' => $this->input->post('paid_amount'),
                'due_amount' => $this->input->post('due_amount'),
                'supplier_rate' => "0",
                'total_price' => $total_price,
                'status' => 1,
                'product_uuid' => $product_uuid
            );

            if (!empty($quantity)) {

                $this->db->insert('sale_order_details', $data1);

            }

        }

         redirect('Cinvoice/sale_order');
        //return $invoice_id;
    }


    public function sale_order_number_generator() {

        $this->db->select_max('invoice', 'invoice_no');

        $query = $this->db->get('sale_order');

        $result = $query->result_array();

        $invoice_no = $result[0]['invoice_no'];

        if ($invoice_no != '') {

            $invoice_no = $invoice_no + 1;

        } else {

            $invoice_no = 1000;

        }

        return $invoice_no;

    }


    public function delivery_challan($invoice_id) {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);

        if(!$invoice_detail)
        {
            redirect('Cinvoice/manage_invoice/');
        }

        $subTotal_quantity = 0;

        $subTotal_cartoon = 0;

        $subTotal_discount = 0;

        $subTotal_ammount = 0;

        if (!empty($invoice_detail)) {

            foreach ($invoice_detail as $k => $v) {

                $invoice_detail[$k]['final_date'] = $CI->occational->dateConvert($invoice_detail[$k]['date']);

                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];

                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];

            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {

                $i++;

                $invoice_detail[$k]['sl'] = $i;

            }

        }



        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $company_info = $CI->Invoices->retrieve_company();
        $categoriesGroup = array();

        // foreach ($invoice_detail as $k => $v) {
        //     if(is_null($categoriesGroup[$invoice_detail[$k]['category_name']])){
        //         $categoriesGroup[$invoice_detail[$k]['category_name']] = array();
        //     }
        //     if(is_null($categoriesGroup[$invoice_detail[$k]['category_name']][$v['unit']])){
        //         $categoriesGroup[$invoice_detail[$k]['category_name']][$v['unit']] = array();
        //     }
        //     array_push($categoriesGroup[$invoice_detail[$k]['category_name']][$v['unit']], $v);
        // }
        foreach ($invoice_detail as $k => $v) {
            if($invoice_detail[$k]['special']){
                $product_parts = explode("-", $invoice_detail[$k]['product_name']);
                $product_name =  $product_parts[0];
                $product_name .= " Special Shade";
                if(is_null($categoriesGroup[$product_name])){
                    $categoriesGroup[$product_name] = array();
                }
                if(is_null($categoriesGroup[$product_name][$v['unit']])){
                    $categoriesGroup[$product_name][$v['unit']] = array();
                }
                array_push($categoriesGroup[$product_name][$v['unit']], $v);
            }
            else{
                $product_parts = explode("-", $invoice_detail[$k]['product_name']);
                $product_name =  $product_parts[0];
                if(is_null($categoriesGroup[$product_name])){
                    $categoriesGroup[$product_name] = array();
                }
                if(is_null($categoriesGroup[$product_name][$v['unit']])){
                    $categoriesGroup[$product_name][$v['unit']] = array();
                }
                array_push($categoriesGroup[$product_name][$v['unit']], $v);
            }
        }
// echo '<pre>';
// print_r($invoice_detail[0]['customer_id']);die;
        
        $data = array(

            'title' => display('invoice_details'),

            'invoice_id' => $invoice_detail[0]['invoice_id'],

            'invoice_no' => $invoice_detail[0]['invoice'],

            'customer_name' => $invoice_detail[0]['customer_name'],

            'customer_address' => $invoice_detail[0]['customer_address'],

            'customer_mobile' => empty($invoice_detail[0]['customer_mobile']) ? "N/A" : $invoice_detail[0]['customer_mobile'],

            'customer_email' => $invoice_detail[0]['customer_email'],

            'final_date' => $invoice_detail[0]['final_date'],

            'invoice_details' => $invoice_detail[0]['invoice_details'],

            'total_amount' => number_format($invoice_detail[0]['total_amount'], 2, '.', ','),

            'subTotal_quantity' => $subTotal_quantity,

            'total_discount' => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),

            'total_tax' => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),

            'subTotal_ammount' => number_format($subTotal_ammount, 2, '.', ','),

            'paid_amount' => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),

            'due_amount' => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),

            'invoice_all_data' => $categoriesGroup,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

            'discount_type' => $currency_details[0]['discount_type'],

            'route' => empty($invoice_detail[0]['route']) ? "N/A" : $invoice_detail[0]['route'],

            'salesman' => empty($invoice_detail[0]['salesman']) ? "N/A" : $invoice_detail[0]['salesman'],
            
            'content' => "invoice/delivery_html",
            
            'vehicle' => empty($invoice_detail[0]['vehicle']) ? "N/A" : $invoice_detail[0]['vehicle']

        );
    // echo '<pre>';
    // print_r($categoriesGroup);die;
        //$chapterList = $CI->parser->parse('invoice/delivery_html', $data, true);

         $CI->load->view("admin_template",$data);
       // return $chapterList;
      //  $this->template->full_admin_html_view($content);

    }


        public function manage_sale_order() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('linvoice');

        $CI->load->model('Invoices');


      

        $CI->load->model('Web_settings');

        $CI->load->library('occational');


        $startdate = $this->input->get('from_date');
        $enddate = $this->input->get('to_date');

        if($startdate == ""){

            $startdate = date("Y-m-01");
            $enddate = date("Y-m-t");
        }

        
       

        $this->db->select('a.*,b.customer_name');

        $this->db->from('sale_order a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->order_by('a.invoice', 'desc');

        if($_POST['customer_id'] != ""){

        $this->db->where('a.customer_id',$_POST['customer_id']);}
        else if($_POST['invoice_no'] != ""){
             $this->db->where('a.invoice =',$_POST['invoice_no']);
        }
        else{
        $this->db->where('a.date >=',$startdate);
        $this->db->where('a.date <=',$enddate);
        $this->db->where('a.status !=',0);
    }
         $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $invoices_list =  $query->result_array();

        }
        else
        {
            $invoices_list = array();
        }
       //// print_r($_POST);
       // print_r($invoices_list);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title' => display('manage_invoice'),

            'invoices_list' => $invoices_list,
            'startdate' => $startdate,
            'enddate'=>$enddate,
          

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],
            'content' => 'invoice/manage_sale_order'

        );

        //print_r($invoices_list);
         $CI->load->view("admin_template",$data);

//        die("here...");
    }

    public function sale_order_detail($invoice){


        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $CI->load->library('Customers');

        //$invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);

        $this->db->select('a.total_tax,

                        (a.total_amount + a.total_discount) sub_total_amount,

                        a.*,

                        ei.employee_name salesman,

                        pc.category_name,

                        b.*,

                        c.*,

                        d.special,

                        d.product_id,

                        d.product_name,

                        d.product_details,
                        d.unit,

                        d.product_model');

        $this->db->from('sale_order a');


        $this->db->join('sale_order_details c', 'c.invoice_id = a.invoice');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->join('product_information d', 'd.product_uuid = c.product_uuid');

        $this->db->join('product_category pc', 'pc.category_id = d.category_id');

        $this->db->join('employee_information ei', 'ei.employee_id = b.employee_id');

        $this->db->where('a.invoice_id', $invoice);

        $this->db->where('a.status', 1);

        $this->db->where('c.quantity >', 0);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $invoice_detail =  $query->result_array();

        }
        else{
            redirect('Cinvoice/manage_sale_order/');
        }        
        $subTotal_quantity = 0;

        $subTotal_cartoon = 0;

        $subTotal_discount = 0;

        $subTotal_ammount = 0;

        if (!empty($invoice_detail)) {

            foreach ($invoice_detail as $k => $v) {

                $invoice_detail[$k]['final_date'] = $CI->occational->dateConvert($invoice_detail[$k]['date']);

                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];

                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];

            }



            $i = 0;

            foreach ($invoice_detail as $k => $v) {

                $i++;

                $invoice_detail[$k]['sl'] = $i;

            }

        }



        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $company_info = $CI->Invoices->retrieve_company();
        $categoriesGroup = array();


        // foreach ($invoice_detail as $k => $v) {
        //     if($invoice_detail[$k]['special']){
        //         $product_parts = explode("-", $invoice_detail[$k]['product_name']);
        //         $product_name =  $product_parts[0];
        //         $product_name .= " Special Shade";
        //         if(is_null($categoriesGroup[$product_name])){
        //             $categoriesGroup[$product_name] = array();
        //         }
        //         if(is_null($categoriesGroup[$product_name][$v['unit']])){
        //             $categoriesGroup[$product_name][$v['unit']] = array();
        //         }
        //         array_push($categoriesGroup[$product_name][$v['unit']], $v);
        //     }
        //     else{
        //         $product_parts = explode("-", $invoice_detail[$k]['product_name']);
        //         $product_name =  $product_parts[0];
        //         if(is_null($categoriesGroup[$product_name])){
        //             $categoriesGroup[$product_name] = array();
        //         }
        //         if(is_null($categoriesGroup[$product_name][$v['unit']])){
        //             $categoriesGroup[$product_name][$v['unit']] = array();
        //         }
        //         array_push($categoriesGroup[$product_name][$v['unit']], $v);
        //     }
        // }



        foreach ($invoice_detail as $k => $v) {
            $product_parts = explode("-", $invoice_detail[$k]['product_name']);
            if($invoice_detail[$k]['special']){
                $product_name =  $product_parts[0];
                $product_name .= " Special Shade";
                if(is_null($categoriesGroup[$product_name])){
                    $categoriesGroup[$product_name] = array();
                }

                $product_shade = substr($invoice_detail[$k]['product_id'], 1, strlen($invoice_detail[$k]['product_id']));
                $product_shade .= " ";
                $product_shade .= substr($product_parts[1], 0, strpos($product_parts[1], '('));

                if(is_null($categoriesGroup[$product_name][$product_shade])){
                    $categoriesGroup[$product_name][$product_shade] = $v;
                    $categoriesGroup[$product_name][$product_shade]['dQuantity'] = 0;
                    $categoriesGroup[$product_name][$product_shade]['gQuantity'] = 0;
                    $categoriesGroup[$product_name][$product_shade]['qQuantity'] = 0;
                }
                if($v['unit'] == 'Drum')
                    $categoriesGroup[$product_name][$product_shade]['dQuantity'] += $v['quantity'];
                if($v['unit'] == 'Gallon')
                    $categoriesGroup[$product_name][$product_shade]['gQuantity'] += $v['quantity'];
                if($v['unit'] == 'Quarter')
                    $categoriesGroup[$product_name][$product_shade]['qQuantity'] += $v['quantity'];
            }
            else{
                $product_name =  $product_parts[0];
                if(is_null($categoriesGroup[$product_name])){
                    $categoriesGroup[$product_name] = array();
                }
                $product_shade = substr($invoice_detail[$k]['product_id'], 1, strlen($invoice_detail[$k]['product_id']));
                $product_shade .= " ";
                $product_shade .= substr($product_parts[1], 0, strpos($product_parts[1], '('));

                if(is_null($categoriesGroup[$product_name][$product_shade])){
                    $categoriesGroup[$product_name][$product_shade] = $v;
                    $categoriesGroup[$product_name][$product_shade]['dQuantity'] = 0;
                    $categoriesGroup[$product_name][$product_shade]['gQuantity'] = 0;
                    $categoriesGroup[$product_name][$product_shade]['qQuantity'] = 0;
                }
                if($v['unit'] == 'Drum')
                    $categoriesGroup[$product_name][$product_shade]['dQuantity'] += $v['quantity'];
                if($v['unit'] == 'Gallon')
                    $categoriesGroup[$product_name][$product_shade]['gQuantity'] += $v['quantity'];
                if($v['unit'] == 'Quarter')
                    $categoriesGroup[$product_name][$product_shade]['qQuantity'] += $v['quantity'];
            }
        }

        $customers_balance = $CI->Customers->customer_balance($invoice_detail[0]['customer_id']);
        $customers_balance = $customers_balance[0]["customer_balance"];
        $data = array(

            'title' => display('invoice_details'),

            'invoice_id' => $invoice_detail[0]['invoice_id'],

            'invoice_no' => $invoice_detail[0]['invoice'],

            'credit_limit' => empty($invoice_detail[0]['credit_limit']) ? 0 : $invoice_detail[0]['credit_limit'],

            'customer_name' => $invoice_detail[0]['customer_name'],

            'customer_address' => $invoice_detail[0]['customer_address'],

            'customer_balance' => $customers_balance,

            'customer_mobile' => empty($invoice_detail[0]['customer_mobile']) ? "N/A" : $invoice_detail[0]['customer_mobile'],

            'customer_email' => $invoice_detail[0]['customer_email'],

            'final_date' => $invoice_detail[0]['final_date'],

            'invoice_details' => $invoice_detail[0]['invoice_details'],

            'route' => $invoice_detail[0]['route'],

            'total_amount' => number_format($invoice_detail[0]['total_amount'], 2, '.', ','),

            'subTotal_quantity' => $subTotal_quantity,

            'total_discount' => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),

            'total_tax' => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),

            'subTotal_ammount' => number_format($subTotal_ammount, 2, '.', ','),

            'paid_amount' => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),

            'due_amount' => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),

            //'invoice_all_data' => $invoice_detail,
            
            'invoice_all_data' => $categoriesGroup,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

            'discount_type' => $currency_details[0]['discount_type'],

            'content' => 'invoice/sale_order_html',
            
            'salesman' => empty($invoice_detail[0]['salesman']) ? "N/A" : $invoice_detail[0]['salesman']

        );
        // echo '<pre>';
        // die(print_r($data));


         $CI->load->view("admin_template",$data);

      //  $chapterList = $CI->parser->parse('invoice/sale_order_html', $data, true);
    }

}

