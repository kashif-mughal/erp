<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Linvoice {


    public function monthly_sales_and_recovery($links, $perpage, $page, $pageText, $pageTitle, $from_date = null, $to_date = null) {
        $CI = & get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->model('Reports');

        $monthly_sales_report = $CI->Reports->monthly_sales_report_mk($from_date, $to_date);

        if($from_date == null || $to_date == null)
        {
            $openingMonth = date("Y-01-01");
        }
        else{
            $openingMonth = date("Y-m-d",strtotime($from_date));   
        }

        $openingAmount = $CI->Reports->GetOpeningSales($openingMonth);
        

        //$months = ["January","February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        //$invoices_list = $CI->Invoices->invoice_list($perpage, $page);
        //if (!empty($monthly_sales_report)) {
            // foreach ($monthly_sales_report as $k => $v) {
            //     $monthly_sales_report[$k]['date'] = $CI->occational->dateConvert($monthly_sales_report[$k]['date']);
            // }
        
            $i = 0;
            if (!empty($monthly_sales_report)) {
                foreach ($monthly_sales_report as $k => $v) {
                    $i++;
                    $monthly_sales_report[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        //}

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => $pageTitle,
            'sales_list' => $monthly_sales_report,
            'links' => $links,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency'],
            'position' => $currency_details[0]['currency_position'],
            'openingAmount' => $openingAmount
        );
        //print_r($data);die;

        $salesList = $CI->parser->parse($pageText, $data, true);
        return $salesList;
    }

    //Retrieve  Invoice List

    public function invoice_list($links, $perpage, $page, $pageText, $pageTitle, $type=0) {


        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $CI->load->model('Reports');


        $invoices_list = $CI->Invoices->invoice_list($perpage, $page);

//        echo '<pre>';        print_r($invoices_list);die();

        if (!empty($invoices_list)) {

            foreach ($invoices_list as $k => $v) {

                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);

            }

            $i = 0;

            if (!empty($invoices_list)) {

                foreach ($invoices_list as $k => $v) {

                    $i++;

                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);

                }

            }

        }
        // echo '<pre>';
        // print_r($invoices_list);die;

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
//manage_invoice
        //Sales Report (Bill Wise)
        $data = array(

            'title' => $type == 0 ? display($pageTitle) : $pageTitle,

            'invoices_list' => $invoices_list,

            'links' => $links,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

        );
//invoice/invoice
        //report/sales_report_bill_wise
        $invoiceList = $type == 0 ? $CI->parser->parse($pageText, $data, true) : $CI->parser->parse($pageText, $data, true);

        return $invoiceList;

    }

    public function invoice_search_customer_wise($customer_id, $links, $per_page, $page, $pageText, $pageTitle) {



        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $invoices_list = $CI->Invoices->invoice_search($customer_id, $per_page, $page);

        if (!empty($invoices_list)) {

            foreach ($invoices_list as $k => $v) {

                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);

            }

            $i = 0;

            if (!empty($invoices_list)) {

                foreach ($invoices_list as $k => $v) {

                    $i++;

                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);

                }

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();

        $data = array(

            'title' => $pageTitle,

            'invoices_list' => $invoices_list,

            'links' => $links,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

        );
//$invoiceList = $type == 0 ? $CI->parser->parse('invoice/invoice', $data, true) : $CI->parser->parse('report/sales_report_bill_wise', $data, true);
        $invoiceList = $CI->parser->parse($pageText, $data, true);

        return $invoiceList;

    }

    // Search invoice by customer id

    public function invoice_search($customer_id, $links, $per_page, $page, $pageText, $pageTitle, $type=0) {


        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $invoices_list = $CI->Invoices->invoice_search($customer_id, $per_page, $page);

        if (!empty($invoices_list)) {

            foreach ($invoices_list as $k => $v) {

                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);

            }

            $i = 0;

            if (!empty($invoices_list)) {

                foreach ($invoices_list as $k => $v) {

                    $i++;

                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);

                }

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $company_info = $CI->Reports->retrieve_company();
        //print_r("expression2");die();

//'title' => $type == 0 ? display('manage_invoice') : "Sales Report (Bill Wise)",

        $data = array(

            'title' => $type == 0 ? display($pageTitle) : $pageTitle,

            'invoices_list' => $invoices_list,

            'links' => $links,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

        );

//$invoiceList = $type == 0 ? $CI->parser->parse('invoice/invoice', $data, true) : $CI->parser->parse('report/sales_report_bill_wise', $data, true);
        $invoiceList = $type == 0 ? $CI->parser->parse($pageText, $data, true) : $CI->parser->parse($pageText, $data, true);

        return $invoiceList;

    }



    //inovie_manage search by invoice id

    public function invoice_list_invoice_no($invoice_no, $type=0) {



        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $invoices_list = $CI->Invoices->invoice_list_invoice_id($invoice_no);

        if (!empty($invoices_list)) {

            foreach ($invoices_list as $k => $v) {

                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);

            }

            $i = 0;

            if (!empty($invoices_list)) {

                foreach ($invoices_list as $k => $v) {

                    $i++;

                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);

                }

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();

        $data = array(

            'title' => $type == 0 ? display('manage_invoice') : "Sales Report (Bill Wise)",

            'invoices_list' => $invoices_list,

            'links' => '',

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

        );

        $invoiceList = $type == 0 ? $CI->parser->parse('invoice/invoice', $data, true) : $CI->parser->parse('report/sales_report_bill_wise', $data, true);

        return $invoiceList;

    }

    public function date_to_date_monthly_sales_and_recovery($from_date, $to_date, $links, $perpage, $page) {
        $CI = & get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $invoices_list = $CI->Invoices->invoice_list_date_to_date($from_date, $to_date, $perpage, $page);
        if (!empty($invoices_list)) {
            foreach ($invoices_list as $k => $v) {
                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);
            }
            $i = 0;
            if (!empty($invoices_list)) {
                foreach ($invoices_list as $k => $v) {
                    $i++;
                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => "Monthly Sales & Recovery",
            'invoices_list' => $invoices_list,
            'links' => $links,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency'],
            'position' => $currency_details[0]['currency_position'],
        );
        $invoiceList = $CI->parser->parse('report/monthly_sales_and_recovery', $data, true);
        return $invoiceList;
    }

    public function invoice_list_date_to_date_customer_wise($from_date, $to_date, $links, $perpage, $page) {

        $CI = & get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $invoices_list = $CI->Invoices->invoice_list_date_to_date($from_date, $to_date, $perpage, $page, true);
        if (!empty($invoices_list)) {
            foreach ($invoices_list as $k => $v) {
                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);
            }
            $i = 0;
            if (!empty($invoices_list)) {
                foreach ($invoices_list as $k => $v) {
                    $i++;
                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => "Sales Report (Customer Wise)",
            'invoices_list' => $invoices_list,
            'links' => $links,
            'company_info' => $company_info,
            'currency' => $currency_details[0]['currency'],
            'position' => $currency_details[0]['currency_position'],
        );
        $invoiceList = $CI->parser->parse('report/sales_report_customer_wise', $data, true);
        return $invoiceList;
    }

    // date to date invoice list 

    public function invoice_list_date_to_date($from_date, $to_date, $links, $perpage, $page, $type=0) {


        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');



        $invoices_list = $CI->Invoices->invoice_list_date_to_date($from_date, $to_date, $perpage, $page);

        if (!empty($invoices_list)) {

            foreach ($invoices_list as $k => $v) {

                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);

            }

            $i = 0;

            if (!empty($invoices_list)) {

                foreach ($invoices_list as $k => $v) {

                    $i++;

                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);

                }

            }

        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();

        $data = array(

            'title' => $type == 0 ? display('manage_invoice') : "Sales Report (Bill Wise)",

            'invoices_list' => $invoices_list,

            'links' => $links,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

        );

        $invoiceList = $type == 0 ? $CI->parser->parse('invoice/invoice', $data, true) : $CI->parser->parse('report/sales_report_bill_wise', $data, true);

        return $invoiceList;

    }



    //Pos invoice add form

    public function pos_invoice_add_form($customer_list = null) {
        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $customer_details = $CI->Invoices->pos_customer_setup();

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $last_invoice_id = $CI->Invoices->get_new_invoice_id();
        $last_invoice_id = $last_invoice_id[0]['invoice'];

        $data = array(

            'title' => display('add_new_pos_invoice'),

            'customer_name' => $customer_details[0]['customer_name'],

            'customer_id' => $customer_details[0]['customer_id'],

            'discount_type' => $currency_details[0]['discount_type'],

            'customer_list' => $customer_list,

            'new_invoice_no' => $last_invoice_id + 1

        );

        $invoiceForm = $CI->parser->parse('invoice/add_pos_invoice_form', $data, true);

        return $invoiceForm;

    }



    //Retrieve  Invoice List

    public function search_inovoice_item($customer_id) {

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->library('occational');

        $invoices_list = $CI->Invoices->search_inovoice_item($customer_id);

        if (!empty($invoices_list)) {

            foreach ($invoices_list as $k => $v) {

                $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);

            }

            $i = 0;

            if (!empty($invoices_list)) {

                foreach ($invoices_list as $k => $v) {

                    $i++;

                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);

                }

            }

        }

        $data = array(

            'title' => display('manage_invoice'),

            'invoices_list' => $invoices_list

        );

        $invoiceList = $CI->parser->parse('invoice/invoice', $data, true);

        return $invoiceList;

    }



    //Invoice add form

    public function invoice_add_form($customer_list = null) {

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $last_invoice_id = $CI->Invoices->get_new_invoice_id();
        $last_invoice_id = $last_invoice_id[0]['invoice'];

        $data = array(

            'title' => display('add_new_invoice'),

            'discount_type' => $currency_details[0]['discount_type'],

            'customer_list' => $customer_list,

            'new_invoice_no' => $last_invoice_id + 1

        );
        $invoiceForm = $CI->parser->parse('invoice/add_invoice_form', $data, true);

        return $invoiceForm;

    }



    //Insert invoice

    public function insert_invoice($data) {

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->Invoices->invoice_entry($data);

        return true;

    }



    //Invoice Edit Data

    public function invoice_edit_data($invoice_id, $customer_list = null) {

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $invoice_detail = $CI->Invoices->retrieve_invoice_editdata($invoice_id);

        $i = 0;

        if (!empty($invoice_detail)) {

            foreach ($invoice_detail as $k => $v) {

                $i++;

                $invoice_detail[$k]['sl'] = $i;

            }

        }



        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(

            'title' => display('invoice_edit'),

            'invoice_id' => $invoice_detail[0]['invoice_id'],

            'customer_id' => $invoice_detail[0]['customer_id'],

            'customer_name' => $invoice_detail[0]['customer_name'],

            'date' => $invoice_detail[0]['date'],

            'invoice_details' => $invoice_detail[0]['invoice_details'],

            'total_amount' => $invoice_detail[0]['total_amount'],

            'paid_amount' => $invoice_detail[0]['paid_amount'],

            'due_amount' => $invoice_detail[0]['due_amount'],

            'invoice_discount' => $invoice_detail[0]['invoice_discount'],

            'total_discount' => $invoice_detail[0]['total_discount'],

            'unit' => $invoice_detail[0]['unit'],

            'tax' => $invoice_detail[0]['tax'],

            'total_tax' => $invoice_detail[0]['taxs'],

            'invoice_all_data' => $invoice_detail,

            'discount_type' => $currency_details[0]['discount_type'],

            'delivery_challan' => $invoice_detail[0]['delivery_challan'],

            'vehicle' => $invoice_detail[0]['vehicle'],

            'customer_list' =>  $customer_list

        );
        $chapterList = $CI->parser->parse('invoice/edit_invoice_form', $data, true);

        return $chapterList;

    }



    //Invoice html Data

    public function invoice_html_data($invoice_id) {

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);





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

        $data = array(

            'title' => display('invoice_details'),

            'invoice_id' => $invoice_detail[0]['invoice_id'],

            'invoice_no' => $invoice_detail[0]['invoice'],

            'customer_name' => $invoice_detail[0]['customer_name'],

            'customer_address' => $invoice_detail[0]['customer_address'],

            'customer_mobile' => $invoice_detail[0]['customer_mobile'],

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

            'invoice_all_data' => $invoice_detail,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

            'discount_type' => $currency_details[0]['discount_type'],

        );

        $chapterList = $CI->parser->parse('invoice/invoice_html', $data, true);

        return $chapterList;

    }



    //POS invoice html Data

    public function pos_invoice_html_data($invoice_id) {

        $CI = & get_instance();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $CI->load->library('occational');

        $invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);



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

        $data = array(

            'ttile' => display('invoice_details'),

            'invoice_id' => $invoice_detail[0]['invoice_id'],

            'invoice_no' => $invoice_detail[0]['invoice'],

            'customer_name' => $invoice_detail[0]['customer_name'],

            'customer_address' => $invoice_detail[0]['customer_address'],

            'customer_mobile' => $invoice_detail[0]['customer_mobile'],

            'customer_email' => $invoice_detail[0]['customer_email'],

            'final_date' => $invoice_detail[0]['final_date'],

            'invoice_details' => $invoice_detail[0]['invoice_details'],

            'total_amount' => number_format($invoice_detail[0]['total_amount'], 2, '.', ','),

            'subTotal_cartoon' => $subTotal_cartoon,

            'subTotal_quantity' => $subTotal_quantity,

            'invoice_discount' => number_format($invoice_detail[0]['invoice_discount'], 2, '.', ','),

            'total_discount' => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),

            'total_tax' => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),

            'subTotal_ammount' => number_format($subTotal_ammount, 2, '.', ','),

            'paid_amount' => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),

            'due_amount' => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),

            'invoice_all_data' => $invoice_detail,

            'company_info' => $company_info,

            'currency' => $currency_details[0]['currency'],

            'position' => $currency_details[0]['currency_position'],

        );



        $chapterList = $CI->parser->parse('invoice/pos_invoice_html', $data, true);

        return $chapterList;

    }



}



?>