<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function add_payment($data = array()) {
        return $this->db->insert('transection', $data);
    }

    public function payment_list($per_page, $page) {
        $this->db->select('
   i.*, i.amount as debit,
    i.pay_amount as credit,i.transaction_id,i.relation_id,f.customer_name,h.supplier_name,k.person_name,m.parent_id');
        $this->db->from('transection i');
        $this->db->join('customer_information f', 'i.relation_id=f.customer_id', 'left');
        $this->db->join('supplier_information h', 'i.relation_id=h.supplier_id', 'left');
        $this->db->join('person_information k', 'i.relation_id=k.person_id', 'left');
        $this->db->join('account_2 m', 'i.transection_category=m.parent_id');
        $this->db->group_by('i.transaction_id');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function delete_payment($id) {
        $this->db->where('transaction_id', $id);
        $this->db->delete('transection');
        return true;
    }

    // customer ledger delete
    public function delete_customer_ledger($id) {
        $this->db->where('transaction_id', $id);
        $this->db->delete('customer_ledger');
        return true;
    }

    // supplier ledger delete

    public function delete_supplier_ledger($id) {
        $this->db->where('transaction_id', $id);
        $this->db->delete('supplier_ledger');
        return true;
    }

    // loan or person ledger delete

    public function delete_person_ledger($id) {
        $this->db->where('transaction_id', $id);
        $this->db->delete('person_ledger');
        return true;
    }

//invoice inflow delege
    public function delete_inflow($id) {
        $this->db->where('transection_id', $id);
        $this->db->delete('inflow_92mizdldrv');
        return true;
    }

    public function update_payment($data, $trans) {
        $this->db->where('transaction_id', $trans);
        $this->db->update('transection', $data);
        return true;
    }

    public function payment_updata($trans) {
        $this->db->select('*');
        $this->db->from('transection');
        $this->db->where('transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //customer_inflow_updata
    public function customer_inflow_updata($data, $trans) {
        $this->db->where('transection_id', $trans);
        $this->db->update('inflow_92mizdldrv', $data);
        return true;
    }

    // update customer ledger from transaciton update form
    public function customer_leder_updata($data, $trans) {
        $this->db->where('transaction_id', $trans);
        $this->db->update('customer_ledger', $data);
        return true;
    }

    public function supplier_leder_updata($data, $trans) {


        $this->db->where('transaction_id', $trans);
        $this->db->update('supplier_ledger', $data);
        return true;
    }

    public function person_leder_updata($trans) {
        $this->db->select('*');
        $this->db->from('person_ledger');
        $this->db->where('transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function selected_transection($category_id) {
        $this->db->select('*');
        $this->db->from('account_2');
        $this->db->where('account_id', $category_id);
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //customer select in transection update
    public function selected_customer($trans) {

        $this->db->select('f.*,j.customer_name');
        $this->db->from('customer_ledger f');
        $this->db->join('customer_information j', 'f.customer_id=j.customer_id', 'left');
        $this->db->where('f.transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

//loan selecete in transection update
    public function selected_loan($trans) {

        $this->db->select('f.*,j.person_name');
        $this->db->from('person_ledger f');
        $this->db->join('person_information j', 'f.person_id=j.person_id', 'left');
        $this->db->where('f.transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Supplier update selected info
    public function selected_supplier($trans) {

        $this->db->select('f.*,j.supplier_name');
        $this->db->from('supplier_ledger f');
        $this->db->join('supplier_information j', 'f.supplier_id=j.supplier_id', 'left');
        $this->db->where('f.transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //office accunt update sele
    public function selected_office_trns($trans) {

        $this->db->select('a.*,b.*');
        $this->db->from('transection a');
        $this->db->join('account_2  b', 'a.relation_id=b.account_name');
        $this->db->where('a.transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //transectio payment type seleceted
    public function selected_transection_type($trans) {

        $this->db->select('*');
        $this->db->from('transection');

        $this->db->where('transaction_id', $trans);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //

    public function transection_report() {
        $query = $this->db->select('transection.*,
      count(transection.transaction_id) as Total_trans,
      sum(transection.amount) as amount
      ')
                ->from('transection')
                ->group_by("transection.relation_id")
                ->limit('500')
                ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // admin dashboard data




    public function table_name($type) {
        $this->db->select(' * ');
        $this->db->from('account_2');
        $this->db->where('parent_id', $type);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function payment_summary() {
        $todays_date = date('Y-m-d');
        $this->db->select('transection.transection_category,account_2.*');
        $this->db->from('transection');
        $this->db->join('account_2', 'transection.transection_category=account_2.parent_id');
        $this->db->where('transection.transection_type', 1);
        $this->db->where('transection.date_of_transection', $todays_date);
        $this->db->group_by('transection.transection_category');
        $query = $this->db->get();
        $accounts = array();
        $serial = 1;
        $total = 0;
        foreach ($query->result_array() as $tran_cat) {

            $table = 'transection';
            $todays_date = date('Y-m-d');
            $this->db->select("count(amount) as no_trans,sum(amount) as 'total',transection_category");
            $this->db->from($table);
            $this->db->where('date_of_transection', $todays_date);
            $this->db->where('transection_type', 1);
            $this->db->where('transection_category', $tran_cat['transection_category']);
            // $this->db->group_by('transection_category');
            $result_account = $this->db->get();
            $account = $result_account->result_array();
            //$tran_cat=$account[0]['transection_category'];
            $no_trans = $account[0]['no_trans'];
            $total += $account[0]['total'];
            // foreach ($tran_cat as $tran_cat) {
            // }
            if ($no_trans > 0) {
                $gmdate = date('Y-m-d');


                $link = base_url() . "Payment/summary_single/" . $gmdate . "/" . $todays_date . "/" . $table;

                $account_name = "<a href='" . $link . "' >" . $tran_cat["account_name"] . "</a>";

                $accounts[] = array("sl" => $serial, "table" => $account_name, "no_transection" => $no_trans, "sub_total" => $account[0]['total']);
                $serial++;
            }
        }
        $this->sub_total = $total;

        return $accounts;
    }

    public function receipt_summary_datewise($start, $end) {
        $this->db->select('transection.transection_category,account_2.*');
        $this->db->from('transection');
        $this->db->join('account_2', 'transection.transection_category=account_2.parent_id');
        $this->db->where('transection.transection_type', 2);
        $this->db->group_by('transection.transection_category');
        $query = $this->db->get();
        $accounts = array();
        $serial = 1;
        $total = 0;
        foreach ($query->result_array() as $tran_cat) {

            $table = 'transection';
            $todays_date = date('Y-m-d');
            $this->db->select("count(amount) as no_trans,sum(amount) as 'total',transection_category");
            $this->db->from($table);
            $this->db->where(array('date_of_transection >=' => $start, 'date_of_transection <=' => $end));
            $this->db->where('transection_type', 2);
            $this->db->where('transection_category', $tran_cat['transection_category']);
            // $this->db->group_by('transection_category');
            $result_account = $this->db->get();
            $account = $result_account->result_array();
            //$tran_cat=$account[0]['transection_category'];
            $no_trans = $account[0]['no_trans'];
            $total += $account[0]['total'];
            // foreach ($tran_cat as $tran_cat) {
            // }
            if ($no_trans > 0) {
                $gmdate = date('Y-m-d');


                $link = base_url() . "Payment/summary_single/" . $gmdate . "/" . $todays_date . "/" . $table;

                $account_name = "<a href='" . $link . "' >" . $tran_cat["account_name"] . "</a>";

                $accounts[] = array("sl" => $serial, "table" => $account_name, "no_transection" => $no_trans, "sub_total" => $account[0]['total']);
                $serial++;
            }
        }
        $this->sub_total = $total;

        return $accounts;
    }

// transection details
    public function trans_summary_details($start, $end) {
        $this->db->select(" * ");
        $this->db->from('payment_trans');
        $this->db->where(array('date >=' => $start, 'date <=' => $end));
        // $this->db->where("status",1);
        $result_account = $this->db->get();
        $account = $result_account->result_array();
        $serial = 1;
        $data = array();
        foreach ($account as $account) {
            $date = substr($account['date'], 0, 10);

            if ($account['payment_type'] == 1) {
                $payment_type = "Cash";
            } elseif ($account['payment_type'] == 2) {
                $payment_type = "Cheque";
            } else {
                $payment_type = "Pay Order";
            }

            $data[] = array("sl" => $serial++, "table" => $table, "date" => $date, "transection_id" => $account['transection_id'], "tracing_id" => $this->idtracker($account['tracing_id']), "description" => $account['description'], "amount" => $account['amount'], "payment_type" => $payment_type);
        }

        return $data;
    }

    // transaction Daily details report 
    public function tran_rep_query() {
        $datse = date('Y-m-d');

        $this->db->select('
      transection.*,
      sum(transection.amount) as debit,
      sum(transection.pay_amount) as credit,count(transection.transaction_id) as tran_no
      ');
        $this->db->from('transection');

        $this->db->where('transection.date_of_transection', $datse);
        $this->db->group_by('transection.transection_category');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

// report popup query
    public function get_details($transection_category) {

        $date = date('Y-m-d');
        $this->db->select('
   i.*, i.amount as debit,
    i.pay_amount as credit,i.transaction_id,i.relation_id,f.customer_name,h.supplier_name,k.person_name');
        $this->db->from('transection i');
        $this->db->join('customer_information f', 'i.relation_id=f.customer_id', 'left');
        //$this->db->join('account_2 m','i.transection_category=m.parent_id','left');
        $this->db->join('supplier_information h', 'i.relation_id=h.supplier_id', 'left');
        $this->db->join('person_information k', 'i.relation_id=k.person_id', 'left');
        $this->db->where("i.date_of_transection", $date);
        $this->db->where("i.transection_category", $transection_category);

        $query = $this->db->get();

        return $query->result_array();
    }

// todays report pop up query
    public function today_details($transection_category) {

        $date = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('transection');
        $this->db->where("date_of_transection", $date);
        $this->db->where("transection_category", $transection_category);
        $query = $this->db->get();

        return $query->result_array();
    }

    //invoic number search 
    public function invoice($transaction_id) {

        $this->db->select('*');
        $this->db->from('customer_v_transection');

        $this->db->where('transaction_id', $transaction_id);
        //$this->db->where("customer_ledger.customer_id",$customer_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    // date to date query 
    public function date_summary_query($per_page, $page) {
        $startdate = $this->input->get("from_date");
        $enddate = $this->input->get("to_date");
        $userType = $this->input->get("u_type");
        $userName = $this->input->get("u_name");
        
        if($startdate =="")
        {
            $startdate = date("Y-m-01");
            $enddate = date("Y-m-t");
        }
        $sql = "SELECT si.supplier_name, ci.customer_name, t.* FROM transection t 
	LEFT JOIN supplier_ledger sl ON sl.transaction_id = t.transaction_id
    LEFT JOIN supplier_information si ON si.supplier_id = sl.supplier_id
	LEFT JOIN customer_ledger cl ON cl.transaction_id = t.transaction_id 
    LEFT JOIN customer_information ci ON ci.customer_id = cl.customer_id  where date_of_transection >='$startdate' and date_of_transection <='$enddate'";

        if($userName){
            $sql .= " AND (ci.customer_name = '$userName' OR si.supplier_name = '$userName')";
        }
        if($userType > 0 && $userType < 6){
            // transection_category == 1 =>  "supplier";
            // transection_category == 2 =>  "customer";
            // transection_category == 3 =>  "Office";
            // transection_category == 4 =>  "Salary";
            // transection_category == 5 =>  "Loan";

            $sql .= " AND transection_category = $userType";
        }
//        echo $sql;        die();
//print_r($sql);die;
        $query = $this->db->query($sql);
        //echo '<pre>';print_r($query->result_array());die;
        return $query->result_array();
    }

    public function date_summary_query11($per_page, $page) {
        $today = date('Y-m-d');
        $sql = 'SELECT si.supplier_name, ci.customer_name, t.* FROM transection t 
	LEFT JOIN supplier_ledger sl ON sl.transaction_id = t.transaction_id
    LEFT JOIN supplier_information si ON si.supplier_id = sl.supplier_id
	LEFT JOIN customer_ledger cl ON cl.transaction_id = t.transaction_id 
    LEFT JOIN customer_information ci ON ci.customer_id = cl.customer_id 
	WHERE t.date_of_transection = "' . $today . '" GROUP BY t.transaction_id ORDER BY create_date DESC';
//        echo $sql;        die();
        $query = $this->db->query($sql);
        return $query->result_array();
//    $this->db->select('
//     i.*, i.amount as debit,
//      i.pay_amount as credit,q.*,r.*,s.*,a.*');
//    $this->db->from('transection i');
//    $this->db->join('view_customer_transection q','i.transaction_id=q.transaction_id','left');
//    $this->db->join('view_supplier_transection r','i.transaction_id=r.transaction_id','left');
//     $this->db->join('view_person_transection s','i.transaction_id=s.transaction_id','left');
//     $this->db->join('account_2 a','i.transection_category=a.account_id');
//   
//    $this->db->limit($per_page,$page);
//  $this->db->group_by('i.transaction_id');
// 
//    $query = $this->db->get();
//    if ($query->num_rows() > 0) {
//      return $query->result_array();  
//    }
//    return false;
    }

// search query date between search by date
    public function search_query($start, $end) {
        $this->db->select('
      i.*, i.amount as debit,
      i.pay_amount as credit,i.transaction_id,i.relation_id,f.customer_name,h.supplier_name,k.person_name,m.parent_id');
        $this->db->from('transection i');
        $this->db->join('customer_information f', 'i.relation_id=f.customer_id', 'left');
        $this->db->join('account_2 m', 'i.transection_category=m.parent_id', 'left');
        $this->db->join('supplier_information h', 'i.relation_id=h.supplier_id', 'left');
        $this->db->join('person_information k', 'i.relation_id=k.person_id', 'left');
        $this->db->where(array('i.date_of_transection >=' => $start, 'i.date_of_transection <=' => $end));
        $this->db->group_by('i.relation_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // Account2 list for transection
    public function account_list() {
        $this->db->select('*');
        $this->db->from('account_2');
        $this->db->where('parent_id', 3);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Transection category list
    public function account_list_category() {
        $this->db->select('*');
        $this->db->from('account_2');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Custom repor search query result
    public function custom_search_query($start, $end, $account) {
        $this->db->select('
      i.*, i.amount as debit,
      i.pay_amount as credit,i.transaction_id,i.relation_id,f.customer_name,h.supplier_name,k.person_name,m.parent_id');
        $this->db->from('transection i');
        $this->db->join('customer_information f', 'i.relation_id=f.customer_id', 'left');
        $this->db->join('account_2 m', 'i.transection_category=m.parent_id', 'left');
        $this->db->join('supplier_information h', 'i.relation_id=h.supplier_id', 'left');
        $this->db->join('person_information k', 'i.relation_id=k.person_id', 'left');
        $this->db->where(array('i.date_of_transection >=' => $start, 'i.date_of_transection <=' => $end));
        $this->db->where('i.transection_category', $account);
        //$this->db->group_by('i.relation_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // transection category list
    public function tran_cat_list() {
        $this->db->select('*');
        $this->db->from('account_2');
        $this->db->group_by('parent_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // dashboard admin panel data
    public function dashboard_info() {
        return $last = $this->db->order_by('transaction_id', "desc")->limit(1)->get('transection')->row();
    }

    public function count_transection() {
        return $this->db->count_all("transection");
    }

    public function date_summary_date_to_date($star_date, $end_date, $per_page, $page) {

        $this->db->select('
     i.*, i.amount as debit,
      i.pay_amount as credit,q.*,r.*,s.*');
        $this->db->from('transection i');
        $this->db->join('view_customer_transection q', 'i.transaction_id=q.transaction_id', 'left');
        $this->db->join('view_supplier_transection r', 'i.transaction_id=r.transaction_id', 'left');
        $this->db->join('view_person_transection s', 'i.transaction_id=s.transaction_id', 'left');
//        $this->db->join('account_2 a', 'i.transection_category=a.parent_id');

        $this->db->where('i.date_of_transection >=', $star_date);
        $this->db->where('i.date_of_transection <=', $end_date);
        $this->db->order_by('i.date_of_transection,i.relation_id', "asc");
       // $this->db->limit($per_page, $page);
        $this->db->group_by('i.transaction_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //count date to date payment row
    public function count_date_summary_date_to_date($star_date, $end_date) {

        $this->db->select('
     i.*, i.amount as debit,
      i.pay_amount as credit,q.*,r.*,s.*,a.*');
        $this->db->from('transection i');
        $this->db->join('view_customer_transection q', 'i.transaction_id=q.transaction_id', 'left');
        $this->db->join('view_supplier_transection r', 'i.transaction_id=r.transaction_id', 'left');
        $this->db->join('view_person_transection s', 'i.transaction_id=s.transaction_id', 'left');
        $this->db->join('account_2 a', 'i.transection_category=a.parent_id');

        $this->db->where('i.date_of_transection >=', $star_date);
        $this->db->where('i.date_of_transection <=', $end_date);
        $this->db->group_by('i.transaction_id');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function supplier_product_sale_previous_debit($start) {
        $query = $this->db->select('sum(amount) as pre_debit,sum(pay_amount) as pre_credit')
                ->from('transection')
                ->where("date_of_transection <", $start)
                ->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function voucher_receipt($voucherId){

        $query = $this->db->select("*")->from('transection')->where("voucher_id","$voucherId")->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }

    }

    public function get_voucher_list(){

        // SELECT date_of_transection, SUM(amount) AS amount,voucher_id, relation_id,s.supplier_name FROM transection t, supplier_information s WHERE s.supplier_id = t.relation_id ORDER BY date_of_transection;

    }

    public function count_voucher() {
        $this->db->where('voucher_id !=', '');
        $this->db->from('transection');
        $this->db->group_by('voucher_id');
        return $this->db->count_all_results();
    }

    public function voucher_list($per_page, $page) {

        $startdate = $this->input->get("from_date");
        $enddate = $this->input->get("to_date");
        $userType = $this->input->get("u_type");
        $userName = $this->input->get("u_name");
        if($startdate =="")
        {
            $startdate = date("Y-m-01");
            $enddate = date("Y-m-t");
        }

        
        $this->db->select('i.*, sum(i.amount) as debit,
    sum(i.pay_amount) as credit,i.transaction_id,i.relation_id,f.customer_name,h.supplier_name,k.person_name');
        $this->db->from('transection i');
        $this->db->join('customer_information f', 'i.relation_id=f.customer_id', 'left');
        $this->db->join('supplier_information h', 'i.relation_id=h.supplier_id', 'left');
        $this->db->join('person_information k', 'i.relation_id=k.person_id', 'left');
        
        $this->db->where('i.date_of_transection >=',"$startdate");
        $this->db->where('i.date_of_transection <=',"$enddate");
        $this->db->where('i.voucher_id !=', '');
        if($userName){
            $this->db->where("(f.customer_name = '$userName' OR h.supplier_name = '$userName')");
        }
        if($userType > 0 && $userType < 6){
            // transection_category == 1 =>  "supplier";
            // transection_category == 2 =>  "customer";
            // transection_category == 3 =>  "Office";
            // transection_category == 4 =>  "Salary";
            // transection_category == 5 =>  "Loan";

            $this->db->where('transection_category =', $userType);
        }
        $this->db->group_by('i.voucher_id');


       // $this->db->limit($per_page, $page);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;


    }

}
