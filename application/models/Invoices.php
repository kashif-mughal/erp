<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Invoices extends CI_Model {



    public function __construct() {

        parent::__construct();

        $this->load->library('auth');

        $this->load->library('lcustomer');

        $this->load->library('session');

        $this->load->model('Customers');

        $this->auth->check_admin_auth();

    }



    //Count invoice

    public function count_invoice() {

        return $this->db->count_all("invoice");

    }

    //Count todays_sales_report

    public function todays_sales_report() {

        $today = date('Y-m-d');

        $this->db->select('b.customer_name, b.customer_id, a.invoice_id');

        $this->db->from('invoice a');

        $this->db->join('customer_information b','b.customer_id = a.customer_id');

        $this->db->where('a.date', $today);

        $query = $this->db->get()->result();

        return $query;

    }



//    ======= its for  best_sales_products ===========

    public function best_sales_products() {

        $this->db->select('b.product_id, b.product_name, sum(a.quantity) as quantity');

        $this->db->from('invoice_details a');

        $this->db->join('product_information b', 'b.product_id = a.product_id');

        $this->db->group_by('b.product_id');

        $this->db->order_by('quantity', 'desc');

        $query = $this->db->get();

        return $query->result();

    }



//    ======= its for  best_sales_products ===========

    public function best_saler_product_list() {

        $this->db->select('b.product_id, b.product_name, sum(a.quantity) as quantity');

        $this->db->from('invoice_details a');

        $this->db->join('product_information b', 'b.product_id = a.product_id');

        $this->db->group_by('b.product_id');

        $this->db->order_by('quantity', 'desc');

        $query = $this->db->get();

        return $query->result();

    }



//    ======= its for  todays_customer_receipt ===========

    public function todays_customer_receipt($today = null) {

        $this->db->select('a.customer_name, a.customer_id, SUM(b.amount) as total_amount');

        $this->db->from('customer_information a');

        $this->db->join('customer_ledger b', 'b.customer_id = a.customer_id');

        $this->db->where('b.date', $today);

        $this->db->where('b.receipt_from', 'receipt');

        $this->db->group_by('b.customer_id');

        $this->db->order_by('a.customer_name', 'Asc');

        $query = $this->db->get();

        return $query->result();

    }

//    ======= its for  todays_customer_receipt ===========

    public function filter_customer_wise_receipt($custome_id = null, $from_date = null) {

        $this->db->select('a.customer_name, a.customer_id, SUM(b.amount) as total_amount');

        $this->db->from('customer_information a');

        $this->db->join('customer_ledger b', 'b.customer_id = a.customer_id');

        $this->db->where('a.customer_id', $custome_id);

        $this->db->where('b.date', $from_date);

        $this->db->where('b.receipt_from', 'receipt');

//        $this->db->group_by('b.customer_id');

//        $this->db->order_by('a.customer_name', 'Asc');

        $query = $this->db->get();

        return $query->result();

    }



    //invoice List

    public function invoice_list($perpage, $page) {

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->order_by('a.invoice', 'desc');

        $this->db->limit($perpage, $page);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    // search invoice by customer id

    public function invoice_search($customer_id, $per_page, $page) {

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->where('a.customer_id', $customer_id);

        $this->db->order_by('a.invoice', 'desc');

        $this->db->limit($per_page, $page);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    // invoice search by invoice id

    public function invoice_list_invoice_id($invoice_no) {

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');



        $this->db->where('invoice', $invoice_no);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    // date to date invoice list

    public function invoice_list_date_to_date($from_date, $to_date, $perpage, $page, $flag=false) {
        
        // $queryText = "
        //     SELECT 
        //         DATE_FORMAT(date,'%M-%Y') as date,
        //         EXTRACT(MONTH FROM STR_TO_DATE(date,'%Y-%m-%d')) as month,
        //         IFNULL(sum(total_amount), 0) as gross_sales,
        //         IFNULL(sum(total_ret_amount), 0) as sale_return,
        //         sum(total_amount) - IFNULL(sum(total_ret_amount), 0) as net_sale

        //     FROM 
        //         invoice
        //     LEFT JOIN
        //         product_return
        //         on invoice.invoice_id = product_return.invoice_id
        //         ";
        //     $queryText .= $from_date == null || $to_date == null ? "
        //     WHERE 
        //         EXTRACT(YEAR FROM STR_TO_DATE(date,'%Y-%m-%d'))  >= EXTRACT(YEAR FROM NOW()) " : "
        //     WHERE 
        //         STR_TO_DATE(date,'%Y-%m-%d') >= STR_TO_DATE('$from_date','%Y-%m-%d') && 
        //         STR_TO_DATE(date,'%Y-%m-%d') <= STR_TO_DATE('$to_date','%Y-%m-%d')";
        //     $queryText .= "
        //     GROUP BY
        //         EXTRACT(YEAR_MONTH FROM STR_TO_DATE(date,'%Y-%m-%d'))
        //     ORDER BY
        //         month ASC
        // ";
        // $query1 = $this->db->query($queryText)->result_array();

        // // echo '<pre>';
        // // print_r($query1);die();
        // return $query1;


        if($flag){
            $myQr = "SELECT a.*, IFNULL(sum(pr.total_ret_amount), 0) as sale_return, TRUNCATE(sum(a.total_amount), 2) as total_amount_by_customer,b.customer_name from invoice a 
        LEFT JOIN
            product_return pr on a.invoice_id = pr.invoice_id
        join 
            customer_information b on b.customer_id = a.customer_id 
            where a.date BETWEEN '$from_date%' AND '$to_date%' group by b.customer_name order by a.invoice desc limit $page, $perpage";
            $query = $this->db->query($myQr);
            if ($query->num_rows() > 0) {
            $result = $query->result_array();
            for ($i=0; $i < count($result) ; $i++) { 
                $result[$i]["net_sales"] = $result[$i]["total_amount_by_customer"] - $result[$i]["sale_return"];
            }
            return $result;

            }
            return false;
        }
        else{

            $dateRange = "a.date BETWEEN '$from_date%' AND '$to_date%'";

            $this->db->select('a.*,b.customer_name');

            $this->db->from('invoice a');

            $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

            $this->db->where($dateRange, NULL, FALSE);

            $this->db->order_by('a.invoice', 'desc');

            $this->db->limit($perpage, $page);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {

                return $query->result_array();

            }

            return false;
        }

    }



    // Invoiec list date to date 

    public function invoice_list_date_to_date_count($from_date, $to_date) {

        $dateRange = "a.date BETWEEN '$from_date%' AND '$to_date%'";

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->where($dateRange, NULL, FALSE);

        $this->db->order_by('a.invoice', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->num_rows();

        }

        return false;

    }



    //invoice List

    public function invoice_list_count() {

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->order_by('a.invoice', 'desc');

        $this->db->limit('500');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->num_rows();

        }

        return false;

    }



// count invoice search by customer

    public function invoice_search_count($customer_id) {

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->where('a.customer_id', $customer_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->num_rows();

        }

        return false;

    }



    //invoice Search Item

    public function search_inovoice_item($customer_id) {

        $this->db->select('a.*,b.customer_name');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->where('b.customer_id', $customer_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }

    public function get_new_invoice_id(){
        $this->db->select('invoice');

        $this->db->from('invoice');

        $this->db->order_by('invoice', 'desc');

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;
    }


    //POS invoice entry

    public function pos_invoice_setup($product_id, $product_uuid = null) {

        $product_information = $this->db->select('*')

                ->from('product_information')

                //->join('supplier_product', 'product_information.product_id = supplier_product.product_id')
                
                ->where(is_null($product_uuid) ? 'product_id' : 'product_uuid', is_null($product_uuid) ? $product_id : $product_uuid)

                ->get()

                ->row();



        if ($product_information != null) {
            $CI = & get_instance();

            $CI->load->model('Web_settings');

            $currency_details = $CI->Web_settings->retrieve_setting_editdata();


            $data2 = (object) array(

                        'total_product' => $product_information->available_quantity,

                        'supplier_price' => $product_information->supplier_price,

                        'price' => $product_information->price,

                        'supplier_id' => $product_information->supplier_id,

                        'product_id' => $product_information->product_id,

                        'product_name' => $product_information->product_name,

                        'product_model' => $product_information->product_model,

                        'unit' => $product_information->unit,

                        'tax' => $product_information->tax,

                        'discount_type' => $currency_details[0]['discount_type']

            );



            return $data2;

        } else {

            return false;

        }

    }



    //POS customer setup

    public function pos_customer_setup() {

        $query = $this->db->select('*')

                ->from('customer_information')

                ->where('customer_name', 'Walking Customer')

                ->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    //Count invoice

    public function invoice_entry() {

        $invoice_id = $this->generator(10);

        $invoice_id = strtoupper($invoice_id);

        $quantity = $this->input->post('product_quantity');

        $available_quantity = $this->input->post('available_quantity');

        $sale_order_id = $this->input->post('sale_order_number');

        $cartoon = $this->input->post('cartoon');

        $delivery_challan = $this->input->post('delivery_challan');

        $result = array();

      /*  foreach ($available_quantity as $k => $v) {

            if ($v < $quantity[$k]) {

                $this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_qnty')));

                redirect('Cinvoice');

            }

        }
*/

        $product_id = $this->input->post('product_id');

        if ($product_id == null) {

            $this->session->set_userdata(array('error_message' => display('please_select_product')));

            redirect('Cinvoice/pos_invoice');

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





            //Previous balance adding -> Sending to customer model to adjust the data.

            $this->Customers->previous_balance_add(0, $customer_id);

        } else {
            $customer_id = $this->input->post('customer_id');

        }





        //Full or partial Payment record.

        $paid_amount = $this->input->post('paid_amount');

        if ($this->input->post('paid_amount') > 0) {



            $this->db->set('status', '1');

            $this->db->where('customer_id', $customer_id);



            $this->db->update('customer_information');



            $transection_id = $this->auth->generator(15);



            //Insert to customer_ledger Table 

            $data2 = array(

                'transaction_id' => $transection_id,

                'customer_id' => $customer_id,

                'receipt_no' => $this->auth->generator(10),

                'date' => $this->input->post('invoice_date'),

                'amount' => $this->input->post('paid_amount'),

                'payment_type' => 1,

                'description' => 'ITP',

                'status' => 1

            );

            $this->db->insert('customer_ledger', $data2);



            // Account table info

            $data3 = array(

                'transaction_id' => $transection_id,

                'relation_id' => $customer_id,

                'transection_type' => 2,

                'date_of_transection' => $this->input->post('invoice_date'),

                'transection_category' => 2,

                'amount' => $this->input->post('paid_amount'),

                'transection_mood' => 1,

                'description' => 'ITP'

            );



            $this->db->insert('transection', $data3);



            // Inserting for Accounts adjustment.

            ############ default table :: customer_payment :: inflow_92mizdldrv #################

        }



        //Data inserting into invoice table

        $datainv = array(

            'invoice_id' => $invoice_id,

            'customer_id' => $customer_id,

            'date' => $this->input->post('invoice_date'),

            'total_amount' => $this->input->post('grand_total_price'),

            'total_tax' => $this->input->post('total_tax'),

            'invoice' => $this->number_generator(),

            'invoice_details' => $this->input->post('inva_details'),

            'invoice_discount' => $this->input->post('invoice_discount'),

            'total_discount' => $this->input->post('total_discount'),

            'delivery_challan' => $delivery_challan,

            'vehicle' => $this->input->post('vehicle'),

            'status' => 1

        );

        //	print_r($datainv);exit;



        $this->db->insert('invoice', $datainv);



        // Insert to customer_ledger Table 

        $data4 = array(

            'transaction_id' => $this->generator(15),

            'customer_id' => $customer_id,

            'invoice_no' => $invoice_id,

            'date' => $this->input->post('invoice_date'),

            'amount' => $this->input->post('grand_total_price'),

            'status' => 1

        );

        $this->db->insert('customer_ledger', $data4);





        $rate = $this->input->post('product_rate');
        $p_id = $this->input->post('product_id');
        $total_amount = $this->input->post('total_price');
        $discount_rate = $this->input->post('discount_amount');
        $discount_per = $this->input->post('discount');
        $tax_amount = $this->input->post('tax');
        $product_uuid = $this->input->post('product_uuid');

        for ($i = 0, $n = count($p_id); $i < $n; $i++) {

            $cartoon_quantity = $cartoon[$i];

            $product_quantity = $quantity[$i];
            $product_rate = $rate[$i];
            $product_id = $p_id[$i];
            $product_uniqueId = $product_uuid[$i];
            $total_price = $total_amount[$i];
            $supplier_rate = $this->supplier_rate($product_id);
            $disper = $discount_per[$i];
            $discount = is_numeric($product_quantity) * is_numeric($product_rate) * is_numeric($disper) / 100;
            $tax = $tax_amount[$i];



            $data1 = array(

                'invoice_details_id' => $this->generator(15),
                'invoice_id' => $invoice_id,
                'product_id' => $product_id,
                'product_uuid' => $product_uniqueId,
                'quantity' => $product_quantity,
                'rate' => $product_rate,
                'discount' => $discount,
                'discount_per' => $disper,
                'tax' => $tax,
                'paid_amount' => $this->input->post('paid_amount'),
                'due_amount' => $this->input->post('due_amount'),
                'supplier_rate' => $supplier_rate[0]['supplier_price'],
                'total_price' => $total_price,
                'status' => 1

            );

            if (!empty($quantity)) {

                $this->db->insert('invoice_details', $data1);

            }
            if(!empty($sale_order_id)){
                $where = array('product_id' => $product_id, 'invoice_id' => $sale_order_id);
                $this->db->set('remaining_quantity','remaining_quantity - '.(int) $product_quantity, false);
                $this->db->where($where);
                $this->db->update('sale_order_details');
            }

        }

        return $invoice_id;

    }



    //Get Supplier rate of a product

    public function supplier_rate($product_id) {

        $this->db->select('supplier_price');

        $this->db->from('supplier_product');

        $this->db->where(array('product_id' => $product_id));

        $query = $this->db->get();

        return $query->result_array();

    }



    //Retrieve invoice Edit Data

    public function retrieve_invoice_editdata($invoice_id) {

        $this->db->select('a.*,a.total_tax as taxs,b.customer_name,c.*,c.tax as total_tax,c.product_id,d.product_name,d.product_model,d.tax,d.unit');

        $this->db->from('invoice a');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');

        $this->db->join('product_information d', 'd.product_uuid = c.product_uuid');

        $this->db->where('a.invoice_id', $invoice_id);

        $query = $this->db->get();



        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    //update_invoice

    public function update_invoice() {

        $invoice_id = $this->input->post('invoice_id');

        $ab = $this->db->select('transaction_id')->from('customer_ledger')->where('invoice_no', $invoice_id)->get()->result();

        foreach ($ab as $ab) {

            $tran = $ab->transaction_id;

        }



        $this->db->where('transaction_id', $tran);

        $this->db->delete('customer_ledger');



        $this->db->where('transaction_id', $tran);

        $this->db->delete('transection');



        $datarcpt = array(

            'transaction_id' => $tran,

            'customer_id' => $this->input->post('customer_id'),

            'receipt_no' => $this->auth->generator(10),

            'date' => $this->input->post('invoice_date'),

            'amount' => $this->input->post('paid_amount'),

            'payment_type' => 1,

            'description' => 'ITP',

            'status' => 1

        );


        $data = array(

            'invoice_id' => $invoice_id,

            'customer_id' => $this->input->post('customer_id'),

            'date' => $this->input->post('invoice_date'),

            'total_amount' => $this->input->post('grand_total_price'),

            'total_tax' => $this->input->post('total_tax'),

            'invoice_details' => $this->input->post('inva_details'),

            'invoice_discount' => $this->input->post('invoice_discount'),

            'total_discount' => $this->input->post('total_discount'),

            'delivery_challan' => $this->input->post('delivery_challan'),

            'vehicle' => $this->input->post('vehicle')

        );

        $data2 = array(

            'transaction_id' => $tran,

            'customer_id' => $this->input->post('customer_id'),

            'invoice_no' => $invoice_id,

            'date' => $this->input->post('invoice_date'),

            'amount' => $this->input->post('grand_total_price'),

            'payment_type' => 1,

            'description' => 'ITP',

            'status' => 1

        );



        $data3 = array(

            'transaction_id' => $tran,

            'relation_id' => $this->input->post('customer_id'),

            'transection_type' => 2,

            'date_of_transection' => $this->input->post('invoice_date'),

            'transection_category' => 2,

            'amount' => $this->input->post('paid_amount'),

            'transection_mood' => 1,

            'description' => 'ITP'

        );





        if ($invoice_id != '') {

            $this->db->where('invoice_id', $invoice_id);

            $this->db->update('invoice', $data);



            //Update Another table





            $this->db->insert('customer_ledger', $data2);

            $this->db->insert('customer_ledger', $datarcpt);

            $this->db->insert('transection', $data3);

        }



        // Inserting for Accounts adjustment.

        ############ default table :: customer_payment :: inflow_92mizdldrv #################

        //Insert to customer_ledger Table 

        //$this->db->insert($account_table,$account_adjustment);





        $invoice_d_id = $this->input->post('invoice_details_id');

        $cartoon = $this->input->post('cartoon');

        $quantity = $this->input->post('product_quantity');

        $rate = $this->input->post('product_rate');

        $p_id = $this->input->post('product_id');

        $total_amount = $this->input->post('total_price');

        $discount_rate = $this->input->post('discount_amount');

        $discount_per = $this->input->post('discount');

        $product_uuid = $this->input->post('product_uuid');

        //$tax_amount 	= $this->input->post('tax');

        $this->db->where('invoice_id', $invoice_id);

        $this->db->delete('invoice_details');



        for ($i = 0, $n = count($p_id); $i < $n; $i++) {

            $cartoon_quantity = $cartoon[$i];

            $product_quantity = $quantity[$i];

            $product_rate = $rate[$i];

            $product_id = $p_id[$i];

            $product_uniqueId = $product_uuid[$i];

            $total_price = $total_amount[$i];

            $supplier_rate = $this->supplier_rate($product_id);

            $discount = $discount_rate[$i];

            $dis_per = $discount_per[$i];







            if (!empty($tax_amount[$i])) {

                $tax = $tax_amount[$i];

            } else {

                $tax = $this->input->post('tax');

            }





            $data1 = array(

                'invoice_details_id' => $this->generator(15),

                'invoice_id' => $invoice_id,

                'product_id' => $product_id,

                'product_uuid' => $product_uniqueId,

                'quantity' => $product_quantity,

                'rate' => $product_rate,

                'discount' => $discount,

                'total_price' => $total_price,

                'discount_per' => $dis_per,

                'tax' => $this->input->post('total_tax'),

                'paid_amount' => $this->input->post('paid_amount'),

                'due_amount' => $this->input->post('due_amount'),

            );

            $this->db->insert('invoice_details', $data1);







            $tran = $this->db->select('*')->from('customer_ledger')->where('invoice_no', $invoice_id)->get()->result();





            foreach ($tran as $value) {

                

            }

            $transection_id = $value->transaction_id;





            $customer_id = $this->input->post('customer_id');

            $account_adjustment = array(

                'transection_id' => $transection_id,

                'tracing_id' => $customer_id,

                'date' => $this->input->post('invoice_date'),

                'amount' => $this->input->post('paid_amount'),

                'payment_type' => 1,

                'description' => 'ITP',

                'status' => 1

            );

        }



        return $invoice_id;

    }



    //Retrieve invoice_html_data

    public function retrieve_invoice_html_data($invoice_id) {

        // a join invoice_details c on c.invoice_id = a.invoice_id join customer_information b on b.customer_id = a.customer_id join product_information d on d.product_id = c.product_id join product_category pc on pc.category_id = d.category_id join employee_information ei on ei.employee_id = b.employee_id where a.invoice_id = '1917568531' and c.quantity > 0

        $this->db->select('a.total_tax,

						a.*,

                        ei.employee_name salesman,

                        ei.employee_vehicle,

                        pc.category_name,

						b.*,

						c.*,

						d.product_id,

						d.product_name,

						d.product_details,
                        d.unit,

						d.product_model');

        $this->db->from('invoice a');

        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');

        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');

        //$this->db->join('product_information d', 'd.product_id = c.product_id');

//         select a.total_tax, a.*, ei.employee_vehicle salesman, ei.employee_vehicle, pc.category_name, b.*, c.*, d.product_id,
// d.product_name, d.product_details, d.unit, d.product_model from invoice a join invoice_details c on
// c.invoice_id = a.invoice_id join customer_information b on b.customer_id = a.customer_id join
// product_information d on d.product_uuid = c.product_uuid join product_category pc on pc.category_id = d.category_id
// join employee_information ei on ei.employee_id = b.employee_id where a.invoice_id = '8176251517' and c.quantity > 0

        $this->db->join('product_information d', 'd.product_uuid = c.product_uuid');

        $this->db->join('product_category pc', 'pc.category_id = d.category_id');

        $this->db->join('employee_information ei', 'ei.employee_id = b.employee_id');

        $this->db->where('a.invoice_id', $invoice_id);

        $this->db->where('c.quantity >', 0);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();

        }

        return false;

    }



    // Delete invoice Item

    public function retrieve_product_data($product_id) {

        $this->db->select('supplier_price,price,supplier_id,tax');

        $this->db->from('product_information a');

        $this->db->join('supplier_product b', 'a.product_id=b.product.id');

        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {

                $data[] = $row;

            }

            return $data;

        }

        return false;

    }



    //Retrieve company Edit Data

    public function retrieve_company() {

        $this->db->select('*');

        $this->db->from('company_information');

        $this->db->limit('1');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    // Delete invoice Item

    public function delete_invoice($invoice_id) {

        //Delete Invoice table

        $this->db->where('invoice_id', $invoice_id);

        $this->db->delete('invoice');

        //Delete invoice_details table

        $this->db->where('invoice_id', $invoice_id);

        $this->db->delete('invoice_details');

        return true;

    }



    public function invoice_search_list($cat_id, $company_id) {

        $this->db->select('a.*,b.sub_category_name,c.category_name');

        $this->db->from('invoices a');

        $this->db->join('invoice_sub_category b', 'b.sub_category_id = a.sub_category_id');

        $this->db->join('invoice_category c', 'c.category_id = b.category_id');

        $this->db->where('a.sister_company_id', $company_id);

        $this->db->where('c.category_id', $cat_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    // GET TOTAL PURCHASE PRODUCT

    public function get_total_purchase_item($product_id) {

        $this->db->select('SUM(quantity) as total_purchase');

        $this->db->from('product_purchase_details');

        $this->db->where('product_id', $product_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    // GET TOTAL SALES PRODUCT

    public function get_total_sales_item($product_id) {

        $this->db->select('SUM(quantity) as total_sale');

        $this->db->from('invoice_details');

        $this->db->where('product_id', $product_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array();

        }

        return false;

    }



    //Get total product

    public function get_total_product($product_id, $supplier_id) {

        $this->db->select('SUM(a.quantity) as total_purchase,b.*');

        $this->db->from('product_purchase_details a');

        $this->db->join('supplier_product b', 'a.product_id=b.product_id');

        $this->db->where('a.product_id', $product_id);

        $this->db->where('b.supplier_id', $supplier_id);

        $total_purchase = $this->db->get()->row();



        $this->db->select('SUM(b.quantity) as total_sale');

        $this->db->from('invoice_details b');

        $this->db->where('b.product_id', $product_id);

        $total_sale = $this->db->get()->row();



        $this->db->select('a.*,b.*');

        $this->db->from('product_information a');

        $this->db->join('supplier_product b', 'a.product_id=b.product_id');

        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));

        $this->db->where('b.supplier_id', $supplier_id);

        $product_information = $this->db->get()->row();



        $available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);



        $CI = & get_instance();

        $CI->load->model('Web_settings');

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $data2 = array(

            'total_product' => $available_quantity,

            'supplier_price' => $product_information->supplier_price,

            'price' => $product_information->price,

            'supplier_id' => $product_information->supplier_id,

            'unit' => $product_information->unit,

            'tax' => $product_information->tax,

            'discount_type' => $currency_details[0]['discount_type'],

        );



        return $data2;

    }



// product information retrieve by product id

    public function get_total_product_invoic($product_id) {
        // $this->db->select('SUM(a.quantity) as total_purchase');
        
        // $this->db->from('product_purchase_details a');

        // $this->db->where('a.product_id', $product_id);

        // $total_purchase = $this->db->get()->row();

        // $this->db->select('SUM(b.quantity) as total_sale');

        // $this->db->from('invoice_details b');

        // $this->db->where('b.product_id', $product_id);

        // $total_sale = $this->db->get()->row();

        $this->db->select('a.*');

        $this->db->from('product_information a');

        //$this->db->join('supplier_product b', 'a.product_id=b.product_id');

        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));

        $product_information = $this->db->get()->row();



        //$available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);



        $CI = & get_instance();

        $CI->load->model('Web_settings');

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();



        $data2 = array(

            //'total_product' => $available_quantity,

            //'supplier_price' => $product_information->supplier_price,

            'price' => $product_information->price,

            //'supplier_id' => $product_information->supplier_id,

            'unit' => $product_information->unit,

            'tax' => $product_information->tax,

            'discount_type' => $currency_details[0]['discount_type'],

        );



        return $data2;

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



    //NUMBER GENERATOR

    public function number_generator() {

        $this->db->select_max('invoice', 'invoice_no');

        $query = $this->db->get('invoice');

        $result = $query->result_array();

        $invoice_no = $result[0]['invoice_no'];

        if ($invoice_no != '') {

            $invoice_no = $invoice_no + 1;

        } else {

            $invoice_no = 1000;

        }

        return $invoice_no;

    }



}

