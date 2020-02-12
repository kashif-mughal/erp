<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cpayment extends CI_Controller {

    public $menu;

    function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lpayment');
        $this->load->library('lsettings');
        $this->load->library('session');
        $this->load->model('Payment');
        $this->load->library('linvoice');
        $this->load->library('laccounts');
        $this->load->model('Settings');
        $this->load->model('Accounts');
        $this->load->model('Web_settings');
        $this->auth->check_admin_auth();
        $this->template->current_menu = 'Payment';
    }

    public function index() {
        //die("Why?");
        $content = $this->lpayment->payement_form();
        $this->template->full_admin_html_view($content);
    }

    public function receipt_transaction() {
        $content = $this->lpayment->receipt_form();
        $this->template->full_admin_html_view($content);
    }

    public function multi_payment() {
        //die('here');
        $content = $this->lpayment->payment_multi_form();
        $this->template->full_admin_html_view($content);
    }
    
    
    
    public function edit_voucher ($voucherid){
          


            $CI = & get_instance();
            $CI->load->model('Suppliers');
            $CI->load->model('Customers');
            $CI->load->model('Settings');
            
            
            $voucher_details = $this->db->query("SELECT * FROM transection WHERE voucher_id ='$voucherid'")->result_array();
            //echo "<pre>";
            //print_r($voucherInfo);
            //die();
            if($voucher_details[0]['transection_category'] == "1"){
            $supplier_info = $CI->Suppliers->supplier_search($voucher_details[0]['relation_id']);
            $person_info['id'] = $supplier_info[0]['supplier_id'];
            $person_info['name'] = $supplier_info[0]['supplier_name'];
            $person_info['address'] = $supplier_info[0]['address'];
            $person_info['mobile'] = $supplier_info[0]['mobile'];
          //  print_r($supplier_info);
        }
        else{
            $customer_info = $CI->Customers->customer_search_item($voucher_details[0]['relation_id']);
              $person_info['id'] = $customer_info[0]['customer_id'];
              $person_info['name'] = $customer_info[0]['customer_name'];
              $person_info['address'] = $customer_info[0]['address'];
              $person_info['mobile'] = $customer_info[0]['mobile'];
        }
           $get_bank_list =$CI->Settings->get_bank_list();

         $data = array(

            // 'title' => display('add_new_pos_invoice'),
            'get_bank_list' => $get_bank_list,
            'voucher_details' => $voucher_details,
            'person_info' => $person_info,
            'content' => 'payment/edit_voucher'

        );
    
         $CI->load->view("admin_template",$data);
       

    }



        public function update_voucher_entry(){


        $todays_date = $todays_date = $this->input->post('date'); // date("m-d-Y");
        $tran_category = $this->input->post('transection_category');
        $relation_id = $this->input->post('relation_id');       
        $description = $this->input->post('description');
       
        $tran_type = $this->input->post('transectio_type');
        $groupid =  $this->input->post('voucher_id');

        if($tran_category == '1')
                  $this->db->query("DELETE  s.*, t.* FROM supplier_ledger s, transection t WHERE t.transaction_id   = s.transaction_id AND t.voucher_id = '$groupid';");
        if($tran_category == '2')
                    $this->db->query("DELETE  s.*, t.* FROM customer_ledger s, transection t WHERE t.transaction_id   = s.transaction_id AND t.voucher_id = '$groupid';");

  
        $paymentType['cash'] = "1";
        $paymentType['1'] = "1";
        $paymentType['2'] = "2";
        $paymentType['cheque'] = "2";

        for($i = 0;$i <  count($_POST['amount_mode']);$i++){
                $payment_type = $paymentType[strtolower($_POST['amount_mode'][$i])];
                $cheque_no = $_POST['amount_cheque_no'][$i];
                $cheque_mature_date = $_POST['amount_cheque_date'][$i];
                $bank_name = $_POST['amount_bank_name'][$i];
                $account_table = $this->input->post('account_table');
                $amount = $_POST['total_price'][$i];
                $description = $_POST['amount_description'][$i];
                
                $transaction_id = $this->auth->generator(15);
                if($tran_type ==2)
                {
                    $amount = $_POST['total_price'][$i];$pay_amount = 0;
                }else{
                    $amount = 0;$pay_amount = $_POST['total_price'][$i];
                }

                //print_R($paymentType);
                $dataP = array(
                    'transaction_id' => $transaction_id,
                    'date_of_transection' => $this->input->post('date'),
                    'transection_category' => $this->input->post('transection_category'),
                    'transection_type' => $tran_type,
                    'relation_id' => $relation_id,
                    'amount' => $amount,
                    'pay_amount' => $pay_amount,
                    'transection_mood' => $payment_type,
                    'description' => $description,
                    'voucher_id' => $groupid,
                    'cheque_no' => $cheque_no,
                    'bank_name' => $bank_name,
                    'cheque_date' => $cheque_mature_date,
                );
        //echo '<pre>';        print_r($dataP);die();


                 $this->db->insert('transection', $dataP);


               
                $status = 1;

                $receipt_no = $this->auth->generator(10);
                $trans_type = $this->input->post('transectio_type');

                $customer_id = $this->input->post('relation_id');
                if ($tran_category == '2' AND $trans_type == 1) {

                    //customer_ledger
                    //Data ready for customer ledger
                    $data = array(
                        'transaction_id' => $transaction_id,
                        'customer_id' => $customer_id,
                        'invoice_no' => NULL,
                        'receipt_no' => $receipt_no,
                        'amount' => $pay_amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'status' => 1
                    );

                    $this->Accounts->customer_ledger($data);
                }

                if ($tran_category == '2' AND $trans_type == 2) {
                    //Data ready for customer ledger
                    $datarcv = array(
                        'transaction_id' => $transaction_id,
                        'customer_id' => $customer_id,
                        'invoice_no' => $receipt_no,
                        'receipt_no' => NULL,
                        'description' => "Accounts",
                        'amount' => $amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'receipt_from' => 'receipt',
                        'status' => 1
                    );

                    $this->Accounts->customer_ledger($datarcv);
                }
        


                $supplier_id = $this->input->post('relation_id');
                if ($tran_category =='1' AND $trans_type == 2) {
                    $deposit_no = $this->generator(10);
                    $data = array(
                        'transaction_id' => $transaction_id,
                        'supplier_id' => $supplier_id,
                        'chalan_no' => $deposit_no,
                        'deposit_no' => NULL,
                        'amount' => $amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'status' => $status
                    );
                    $this->Accounts->supplier_ledger($data);
                }

                if ($tran_category == '1' AND $trans_type == 1) {
                    $deposit_no = $this->generator(10);
                    $datapay = array(
                        'transaction_id' => $transaction_id,
                        'supplier_id' => $supplier_id,
                        'chalan_no' => NULL,
                        'deposit_no' => $deposit_no,
                        'amount' => $pay_amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'status' => $status
                    );
                    $this->Accounts->supplier_ledger($datapay);
                }
                

            }
      
        $this->session->set_userdata(array('message' => display('successfully_saved')));
        redirect(base_url("Cpayment/receipt_voucher/$groupid"));
         

    }  


    /* transection method add start */

    public function transection_entry() {
        $todays_date = $todays_date = $this->input->post('date'); // date("m-d-Y");

        $payment_type = $this->input->post('payment_type');
        $cheque_no = $this->input->post('cheque_no');
        $cheque_mature_date = $this->input->post('cheque_mature_date');
        $bank_name = $this->input->post('bank_name');
        $account_table = $this->input->post('account_table');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $tran_category = $this->input->post('transection_category');
        $transaction_id = $this->auth->generator(15);
        
        if ($tran_category == 1) {
            $relation_id = $this->input->post('supplier_id');
        } elseif ($tran_category == 2) {
            $relation_id = $this->input->post('customer_id');
        } elseif ($tran_category == 3) {
            $relation_id = $this->input->post('office');
        } else {
            $relation_id = $this->input->post('loan_id');
        }



        $dataP = array(
            'transaction_id' => $transaction_id,
            'date_of_transection' => $this->input->post('date'),
            'transection_category' => $this->input->post('transection_category'),
            'transection_type' => $this->input->post('transectio_type'),
            'relation_id' => $relation_id,
            'amount' => $this->input->post('amount'),
            'pay_amount' => $this->input->post('pay_amount'),
            'transection_mood' => $this->input->post('payment_type'),
            'description' => $this->input->post('description'),
        );
//        echo '<pre>';        print_r($dataP);die();


        $this->db->insert('transection', $dataP);


        /*if ($payment_type == 1) {
            $status = 1;
        } else {
            $status = 0;
        }*/

        $status = 1;

        $receipt_no = $this->auth->generator(10);
        //$transaction_id=$this->auth->generator(15);
        $trans_type = $this->input->post('transectio_type');

        $customer_id = $this->input->post('customer_id');
        if ($customer_id AND $trans_type == 1) {
            //Data ready for customer ledger
            $data = array(
                'transaction_id' => $transaction_id,
                'customer_id' => $customer_id,
                'invoice_no' => NULL,
                'receipt_no' => $receipt_no,
                'amount' => $this->input->post('pay_amount'),
                'description' => $description,
                'payment_type' => $payment_type,
                'cheque_no' => $cheque_no,
                'date' => $todays_date,
                'status' => 1
            );

            $this->Accounts->customer_ledger($data);
        }

        if ($customer_id AND $trans_type == 2) {
            //Data ready for customer ledger
            $datarcv = array(
                'transaction_id' => $transaction_id,
                'customer_id' => $customer_id,
                'invoice_no' => $receipt_no,
                'receipt_no' => NULL,
                'description' => "Accunts",
                'amount' => $this->input->post('amount'),
                'description' => $description,
                'payment_type' => $payment_type,
                'cheque_no' => $cheque_no,
                'date' => $todays_date,
                'receipt_from' => 'receipt',
                'status' => 1
            );

            $this->Accounts->customer_ledger($datarcv);
        }
        $loan_id = $this->input->post('loan_id');
        if ($loan_id AND $trans_type == 1) {
            $data14 = array(
                'transaction_id' => $transaction_id,
                'person_id' => $relation_id,
                'credit' => $this->input->post('pay_amount'),
                'date' => $this->input->post('date'),
                'details' => $this->input->post('description'),
                'status' => 2
            );
            $result = $this->Settings->submit_payment($data14);
        }
        $loan_id = $this->input->post('loan_id');
        if ($loan_id AND $trans_type == 2) {
            $data15 = array(
                'transaction_id' => $transaction_id,
                'person_id' => $relation_id,
                'debit' => $this->input->post('amount'),
                'date' => $this->input->post('date'),
                'details' => $this->input->post('description'),
                'status' => 1
            );
            $result = $this->Settings->submit_payment($data15);
        }


        $supplier_id = $this->input->post('supplier_id');
        if ($supplier_id AND $trans_type == 2) {
            $deposit_no = $this->generator(10);
            $data = array(
                'transaction_id' => $transaction_id,
                'supplier_id' => $supplier_id,
                'chalan_no' => $deposit_no,
                'deposit_no' => NULL,
                'amount' => $this->input->post('amount'),
                'description' => $description,
                'payment_type' => $payment_type,
                'cheque_no' => $cheque_no,
                'date' => $todays_date,
                'status' => $status
            );
            $this->Accounts->supplier_ledger($data);
        }
        if ($supplier_id AND $trans_type == 1) {
            $deposit_no = $this->generator(10);
            $datapay = array(
                'transaction_id' => $transaction_id,
                'supplier_id' => $supplier_id,
                'chalan_no' => NULL,
                'deposit_no' => $deposit_no,
                'amount' => $this->input->post('pay_amount'),
                'description' => $description,
                'payment_type' => $payment_type,
                'cheque_no' => $cheque_no,
                'date' => $todays_date,
                'status' => $status
            );
            $this->Accounts->supplier_ledger($datapay);
        }
        $customer_id = $this->input->post('customer_id');
        if ($customer_id AND $trans_type == 2) {
            
        }
//Invoice sending data from transection
// $invoice=rand(10,30);
//  if($customer_id AND $trans_type==2){
// $datain=array(
//             'invoice_id'        =>  $invoice,
//             'customer_id'       =>  $customer_id,
//             'date'              =>  $this->input->post('date'),
//             'total_amount'      =>  $this->input->post('amount'),
//             'invoice'           => $this->auth->generator(10),
//             'total_discount'    => $this->input->post('total_discount'),
//             'status'            =>  1
//         );
//         $this->db->insert('invoice',$datain);
//          }       
        $this->session->set_userdata(array('message' => display('successfully_saved')));
        if ($trans_type == 1) {
            redirect(base_url('Cpayment'));
        } else {
            redirect(base_url('Cpayment/receipt_transaction'));
            exit;
        }
    }

    /* transection method add End */



    public function multi_transaction_entry(){

        $todays_date = $todays_date = $this->input->post('date'); // date("m-d-Y");
        $tran_category = $this->input->post('transection_category');
        if ($tran_category == 1) {
            $relation_id = $this->input->post('supplier_id');
        } elseif ($tran_category == 2) {
            $relation_id = $this->input->post('customer_id');
        } elseif ($tran_category == 3) {
            $relation_id = $this->input->post('office');
        } else {
            $relation_id = $this->input->post('loan_id');
        }
        $description = $this->input->post('description');
       
        $tran_type = $this->input->post('transectio_type');
        $maxid = $this->db->query("SELECT MAX(voucher_id)+1  AS max_transaction FROM transection")->row()->max_transaction;
        $groupid = $maxid;




        $paymentType['cash'] = "1";
        $paymentType['cheque'] = "2";

        for($i = 0;$i <  count($_POST['amount_mode']);$i++){
                $payment_type = $paymentType[strtolower($_POST['amount_mode'][$i])];
                $cheque_no = $_POST['amount_cheque_no'][$i];
                $cheque_mature_date = $_POST['amount_cheque_date'][$i];
                $bank_name = $_POST['amount_bank_name'][$i];
                $account_table = $this->input->post('account_table');
                $amount = $_POST['total_price'][$i];
                $description = $_POST['amount_description'][$i];
                
                $transaction_id = $this->auth->generator(15);
                if($tran_type ==2)
                {
                    $amount = $_POST['total_price'][$i];$pay_amount = 0;
                }else{
                    $amount = 0;$pay_amount = $_POST['total_price'][$i];
                }

                $dataP = array(
                    'transaction_id' => $transaction_id,
                    'date_of_transection' => $this->input->post('date'),
                    'transection_category' => $this->input->post('transection_category'),
                    'transection_type' => $tran_type,
                    'relation_id' => $relation_id,
                    'amount' => $amount,
                    'pay_amount' => $pay_amount,
                    'transection_mood' => $payment_type,
                    'description' => $description,
                    'voucher_id' => $groupid,
                    'cheque_no' => $cheque_no,
                    'bank_name' => $bank_name,
                    'cheque_date' => $cheque_mature_date,
                );
//        echo '<pre>';        print_r($dataP);die();


                 $this->db->insert('transection', $dataP);


               
                $status = 1;

                $receipt_no = $this->auth->generator(10);
                $trans_type = $this->input->post('transectio_type');

                $customer_id = $this->input->post('customer_id');
                if ($customer_id AND $trans_type == 1) {
                    //Data ready for customer ledger
                    $data = array(
                        'transaction_id' => $transaction_id,
                        'customer_id' => $customer_id,
                        'invoice_no' => NULL,
                        'receipt_no' => $receipt_no,
                        'amount' => $pay_amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'status' => 1
                    );

                    $this->Accounts->customer_ledger($data);
                }

                if ($customer_id AND $trans_type == 2) {
                    //Data ready for customer ledger
                    $datarcv = array(
                        'transaction_id' => $transaction_id,
                        'customer_id' => $customer_id,
                        'invoice_no' => $receipt_no,
                        'receipt_no' => NULL,
                        'description' => "Accounts",
                        'amount' => $amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'receipt_from' => 'receipt',
                        'status' => 1
                    );

                    $this->Accounts->customer_ledger($datarcv);
                }
        


                $supplier_id = $this->input->post('supplier_id');
                if ($supplier_id AND $trans_type == 2) {
                    $deposit_no = $this->generator(10);
                    $data = array(
                        'transaction_id' => $transaction_id,
                        'supplier_id' => $supplier_id,
                        'chalan_no' => $deposit_no,
                        'deposit_no' => NULL,
                        'amount' => $amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'status' => $status
                    );
                    $this->Accounts->supplier_ledger($data);
                }

                if ($supplier_id AND $trans_type == 1) {
                    $deposit_no = $this->generator(10);
                    $datapay = array(
                        'transaction_id' => $transaction_id,
                        'supplier_id' => $supplier_id,
                        'chalan_no' => NULL,
                        'deposit_no' => $deposit_no,
                        'amount' => $pay_amount,
                        'description' => $description,
                        'payment_type' => $payment_type,
                        'cheque_no' => $cheque_no,
                        'date' => $todays_date,
                        'status' => $status
                    );
                    $this->Accounts->supplier_ledger($datapay);
                }
                

            }
      
        $this->session->set_userdata(array('message' => display('successfully_saved')));
        
        redirect(base_url("Cpayment/receipt_voucher/$groupid"));
         

    }
    /* Multi transection method t End */


    public function manage_payment() {

        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpayment');
        $CI->load->model('Payment');
// pagination start 
        $config["base_url"] = base_url('Cpayment/manage_payment/');
        $config["total_rows"] = $this->Payment->count_transection();
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
        $content = $this->lpayment->payment_list($links, $config["per_page"], $page);

        $this->template->full_admin_html_view($content);
    }

    public function payment_delete() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Payment');
        $id = $_POST['transaction_id'];
        $result = $CI->Payment->delete_payment($id);
        $as = $CI->Payment->delete_customer_ledger($id);
        $asd = $CI->Payment->delete_supplier_ledger($id);
        $df = $CI->Payment->delete_person_ledger($id);

        //$result=$CI->Payment->delete_invoice($id);
        return true;
    }

    public function payment_update_form() {
        $trans = $this->uri->segment(3);
        $content = $this->lpayment->payment_up_data($trans);
        $this->menu = array('label' => 'Edit Data', 'url' => 'Cpayment');
        $this->template->full_admin_html_view($content);
    }

    public function payment_update() {
        $trans = $this->input->post('transaction_id');


        $payment_type = $this->input->post('payment_type');
        $cheque_no = $this->input->post('cheque_no');
        $cheque_mature_date = $this->input->post('cheque_mature_date');
        $bank_name = $this->input->post('bank_name');
        $account_table = $this->input->post('account_table');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $tran_category = $this->input->post('transection_category');

        
        if ($tran_category == 1) {
            $relation_id = $this->input->post('supplier_id');
        } elseif ($tran_category == 2) {
            $relation_id = $this->input->post('customer_id');
        } elseif ($tran_category == 3) {
            $relation_id = $this->input->post('office');
            ;
        } else {
            $relation_id = $this->input->post('loan_id');
        }

        $trans_type = $this->input->post('transection_type');


        if (!empty($this->input->post('transection_category'))) {

            // print_r($_POST);
            $data = array(
                'transaction_id' => $trans,
                'date_of_transection' => $this->input->post('date'),
                'transection_category' => $this->input->post('transection_category'),
                'transection_type' => $this->input->post('transection_type'),
                'relation_id' => $relation_id,
                'amount' => $this->input->post('amount'),
                'pay_amount' => $this->input->post('pay_amount'),
                'bank_name' => $bank_name,
                'cheque_no' => $cheque_no,
                'cheque_date' => $cheque_mature_date,
                'transection_mood' => $this->input->post('payment_type'),
                'description' => $this->input->post('description'),
            );
             
            $this->Payment->update_payment($data, $trans);
        }


        /*if ($payment_type == 1) {
            $status = 1;
        } else {
            $status = 0;
        }*/

        $status =1 ;

        $customer_id = $this->input->post('customer_id');
        if ($customer_id AND $trans_type == 2) {
            //Data ready for customer ledger
            $datarcv = array(
                'transaction_id' => $trans,
                'customer_id' => $customer_id,
                'invoice_no' => NULL,
                'receipt_no' => '1233',
                'amount' => $this->input->post('amount'),
                'description' => "CHQ # ".$this->input->post('cheque_no').", CHQ Date: ".$cheque_mature_date." ( ".$bank_name." ) ; ",
                'payment_type' => $payment_type,
                'cheque_no' => $cheque_no,
                'date' => $this->input->post('date'),
            );


            $this->Payment->customer_leder_updata($datarcv, $trans);
        }

        $customer_id = $this->input->post('customer_id');
        if ($customer_id AND $trans_type == 1) {
            //Data ready for customer ledger
            $datacd = array(
                'transaction_id' => $trans,
                'customer_id' => $customer_id,
                'invoice_no' => NULL,
                'receipt_no' => '',
                'amount' => $this->input->post('pay_amount'),
                'description' => $description,
                'payment_type' => $payment_type,
                'cheque_no' => $cheque_no,
                'date' => $this->input->post('date'),
            );

            $this->Payment->customer_leder_updata($datacd, $trans);
        }

        $supplier_id = $this->input->post('supplier_id');
        if ($supplier_id AND $trans_type == 1) {
            $deposit_no = $this->generator(10);
            $datapay = array(
                'transaction_id' => $trans,
                'supplier_id' => $supplier_id,
                'chalan_no' => NULL,
                // 'deposit_no'    =>  $deposit_no,
                'amount' => $this->input->post('pay_amount'),
                'description' => $description,
                'payment_type' => $payment_type,
                //'cheque_no'     =>  $cheque_no,
                'date' => $this->input->post('date'),
            );
            $this->Payment->supplier_leder_updata($datapay, $trans);
        }

        $supplier_id = $this->input->post('supplier_id');
        if ($supplier_id AND $trans_type == 2) {
            $deposit_no = $this->generator(10);
            $datarec = array(
                'transaction_id' => $trans,
                'supplier_id' => $supplier_id,
                'chalan_no' => NULL,
                // 'deposit_no'    =>  $deposit_no,
                'amount' => $this->input->post('amount'),
                'description' => "CHQ # ".$this->input->post('cheque_no').", CHQ Date: ".$cheque_mature_date." ( ".$bank_name." ) ; ",
                'payment_type' => $payment_type,
                //'cheque_no'     =>  $cheque_no,
                'date' => $this->input->post('date'),
            );
            $this->Payment->supplier_leder_updata($datarec, $trans);
        }


        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cpayment/manage_payment'));
        exit;
    }

    // trans report details
    public function trans_details() {
        $content = $this->lpayment->transection_report_details();
        $this->template->full_admin_html_view($content);
    }

    public function tran_det_id($id) {
        $content = $this->lpayment->transection_data($id);
        $this->template->full_admin_html_view($content);
    }

    #==============Closing form==========#

    public function closing() {
        $data = array('title' => "Accounts | Daily Closing");
        $data = $this->Accounts->accounts_closing_data();
//        echo '<pre>';        print_r($data); die();
        $content = $this->parser->parse('accounts/closing_form', $data, true);
        $this->template->full_admin_html_view($content);
    }

    #====== Cash closing report ============#

    public function closing_report() {

        $content = $this->laccounts->daily_closing_list();
        $this->template->full_admin_html_view($content);
    }

    public function summary_single($start, $end, $account) {
        $data = array('title' => display('accounts_details_data'));

        //Getting all tables name.   
        $data['table_inflow'] = $this->Payment->table_name(2);
        $data['table_outflow'] = $this->Payment->table_name(1);

        $data['accounts'] = $this->Payment->trans_summary_details($start, $end, $account);
        //$data['total_inflow']=$this->accounts->sub_total;

        $content = $this->parser->parse('payment/summary_single', $data, true);
        $this->template->full_admin_html_view($content);
    }

    // transection report

    public function summaryy() {
        $content = $this->lpayment->trans_data();
        $this->template->full_admin_html_view($content);
    }

    public function getreport_details() {
        $transection_category = $_POST['transection_category'];
        $data['marks'] = $this->Payment->get_details($transection_category);
        $this->load->view("payment/modal", $data);
    }

    public function today_details() {
        $transection_category = $_POST['transection_category'];
        $data['marks'] = $this->Payment->today_details($transection_category);
        $this->load->view("payment/modal", $data);
    }

//date wise report details
    public function date_summary() {
        $config["base_url"] = base_url('Cpayment/date_summary/');
        $config["total_rows"] = $this->Payment->count_transection();
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
        $content = $this->lpayment->trans_datewise_data($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    // search date between controller 
    public function search_datewise() {
        $start = $this->input->post('from_date');
        $end = $this->input->post('to_date');

        $content = $this->lpayment->result_datewise_data($start, $end);
        $this->template->full_admin_html_view($content);
    }

    //custom report transection

    public function custom_report() {
        $config["base_url"] = base_url('Cpayment/custom_report/');
        $config["total_rows"] = $this->Payment->count_transection();
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
        $content = $this->lpayment->trans_custom_report_data($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

// custom report search

    public function custom_search_datewise() {
        $start = $this->input->post('from_date');
        $end = $this->input->post('to_date');
        $account = $this->input->post('accounts');
        if ($account == "All") {
            $url = "Cpayment/custom_report";
            redirect(base_url($url));
            exit;
        }
        $content = $this->lpayment->custom_result_datewise_data($start, $end, $account);
        $this->template->full_admin_html_view($content);
    }

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

    public function transaction_date_to_date() {
        $star_date = $this->input->get('from_date');
        $end_date = $this->input->get('to_date');
        $config["base_url"] = base_url('Cpayment/transaction_date_to_date/');
        $config["total_rows"] = $this->Payment->count_date_summary_date_to_date($star_date, $end_date);
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
        $content = $this->lpayment->payment_date_date_info($star_date, $end_date, $links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    public function receipt_voucher($voucherid){

        $content = $this->lpayment->receipt_voucher($voucherid);
        $this->template->full_admin_html_view($content);
    }

      public function manage_voucher() {

        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpayment');
        $CI->load->model('Payment');
// pagination start 
        $config["base_url"] = base_url('Cpayment/manage_voucher/');
        $config["total_rows"] = $this->Payment->count_voucher();
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
        //$content = $this->lpayment->payment_list($links, $config["per_page"], $page);
        $content = $this->lpayment->voucher_list($links, $config["per_page"], $page);

        $this->template->full_admin_html_view($content);
    }


    function cheque_status(){
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
            'content' => "payment/cheque_list",
            'msg_content' => $message,
            'company_info' => $company_info
        );

        $startdate = $this->input->get('from_date');
        $enddate = $this->input->get('to_date');

        if($startdate == ""){

            $startdate = date("Y-m-01");
            $enddate = date("Y-m-t");
        }

        $sqlExtra = "";
        $cheque_no  = $this->input->get("cheque_no");
        if($cheque_no!= "")
        {
            $sqlExtra .= " and cheque_no like '%$cheque_no%'";
        }
        $bank_name  = $this->input->get("bank_name");
        if($bank_name!= "" && $bank_name != "ALL")
        {
            $sqlExtra .= " and bank_name = '$bank_name'";
        }

        $chequeInfo = $this->db->query("select * from transection where cheque_date >='$startdate' and cheque_date <='$enddate' $sqlExtra")->result_array();
        $bankInfo = $this->db->query("select * from bank_add ")->result_array();

        $data['cheque_detail'] = $chequeInfo;
        $data['bank_detail'] = $bankInfo;
        $data['start_date'] = $startdate;
        $data['end_date'] = $enddate;
        $CI->load->view("admin_template",$data);

    }

}
