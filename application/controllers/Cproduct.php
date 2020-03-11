<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Cproduct extends CI_Controller {



    public $product_id;



    function __construct() {

        parent::__construct();
        $this->load->database();

    }



    //Index page load

    public function index() {
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

        $CI->load->model('Categories');
        $CI->load->model('Units');
        $supplier = $CI->Suppliers->supplier_list("110", "0");
        $category_list = $CI->Categories->category_list_product();
        $unit_list = $CI->Units->unit_list();
        $tax_list = $CI->db->select('*')
                ->from('tax_information')
                ->get()
                ->result();
       
        $data = array(
            'logindata' => $logged_info,
            'mainmenu' => $top_menu,
            'content' => "product/add_item_form",
            'msg_content' => $message,
            'company_info' => $company_info,
            'supplier' => $supplier,
            'category_list' => $category_list,
            'unit_list' => $unit_list,
            'tax_list' => $tax_list,
            'title'=> "Add Product"
        );


        $CI->load->view("admin_template",$data);

    }



    //Insert Product and uload

    public function insert_product() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('lproduct');

        $product_id = $this->generator(8);

        $sup_price = $this->input->post('supplier_price');

        $s_id = $this->input->post('supplier_id');

        $product_model = $this->input->post('model');

        for ($i = 0, $n = count($s_id); $i < $n; $i++) {

            $supplier_price = $sup_price[$i];

            $supp_id = $s_id[$i];



            $supp_prd = array(

                'product_id' => $product_id,

                'supplier_id' => $supp_id,

                'supplier_price' => $supplier_price,

                'products_model' => $product_model = $this->input->post('model')

            );



            $this->db->insert('supplier_product', $supp_prd);

        }



        //Supplier check

        if ($this->input->post('supplier_id') == null) {

            $this->session->set_userdata(array('error_message' => display('please_select_supplier')));

            redirect(base_url('Cproduct'));

        }



        if ($_FILES['image']['name']) {

            //Chapter chapter add start

            $config['upload_path'] = './my-assets/image/product/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['max_size'] = "*";
            $config['max_width'] = "*";
            $config['max_height'] = "*";
            $config['encrypt_name'] = TRUE;


            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Cproduct'));
            } else {

                $image = $this->upload->data();
                $image_url = base_url() . "my-assets/image/product/" . $image['file_name'];

            }

        }



        $price = $this->input->post('price');

        $tax_percentage = $this->input->post('tax');

        $tax = $tax_percentage / 100;



        $data = array(

            'product_id' => $product_id,

            'product_name' => $this->input->post('product_name'),

            //'supplier_id' 			=> $this->input->post('supplier_id'),

            'category_id' => $this->input->post('category_id'),

            'unit' => $this->input->post('unit'),

            'tax' => $tax,

            'serial_no' => $this->input->post('serial_no'),

            'price' => $price,

            //'supplier_price' 		=> $this->input->post('supplier_price'),

            'product_model' => $this->input->post('model'),

            'product_details' => $this->input->post('description'),

            'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),

            'status' => 1

        );



        $result = $CI->lproduct->insert_product($data);



        if ($result == 1) {

            $this->session->set_userdata(array('message' => display('successfully_added')));

            if (isset($_POST['add-product'])) {

                redirect(base_url('Cproduct/manage_product'));

                exit;

            } elseif (isset($_POST['add-product-another'])) {

                redirect(base_url('Cproduct'));

                exit;

            }

        } else {

            $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));

            redirect(base_url('Cproduct'));

        }

    }



    //Product Update Form

    public function product_update_form($product_id) {

        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('sub_product', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            //$this->session->set_userdata(array('error_message' => "Product Exists with same Model Number"));

            redirect(base_url('Cproduct/item_update_form/'.$product_id));
        }

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('lproduct');

        $content = $CI->lproduct->product_edit_data($product_id);

        $this->template->full_admin_html_view($content);

    }



    // Product Update

    public function product_update() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->model('Products');



        $product_id = $this->input->post('product_id');

        $this->db->where('product_id', $product_id);

        $this->db->delete('supplier_product');

        $sup_price = $this->input->post('supplier_price');

        $s_id = $this->input->post('supplier_id');

        for ($i = 0, $n = count($s_id); $i < $n; $i++) {

            $supplier_price = $sup_price[$i];

            $supp_id = $s_id[$i];



            $supp_prd = array(

                'product_id' => $product_id,

                'supplier_id' => $supp_id,

                'supplier_price' => $supplier_price

            );



            $this->db->insert('supplier_product', $supp_prd);

        }

        // configure for upload 

        $config = array(

            'upload_path' => "./my-assets/image/product/",

            'allowed_types' => "png|jpg|jpeg|gif|bmp|tiff",

            'overwrite' => TRUE,

//                'file_name' => "IST" . time(),

            'max_size' => '0',

        );

        $image_data = array();

        $this->load->library('upload', $config);

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {

            $image_data = $this->upload->data();

//                print_r($image_data); die();

            $image_name = base_url() . "my-assets/image/product/" . $image_data['file_name'];

            $config['image_library'] = 'gd2';

            $config['source_image'] = $image_data['full_path']; //get original image

            $config['maintain_ratio'] = TRUE;

            $config['height'] = '*';

            $config['width'] = '*';

//                $config['quality'] = 50;

            $this->load->library('image_lib', $config);

            $this->image_lib->clear();

            $this->image_lib->initialize($config);

            if (!$this->image_lib->resize()) {

                echo $this->image_lib->display_errors();

            }

        } else {

            $image_name = $this->input->post('old_image');

        }




        $price = $this->input->post('price');

        $tax_percentage = $this->input->post('tax');

        $tax = $tax_percentage / 100;



        $data = array(

            'product_name' => $this->input->post('product_name'),

            //'supplier_id' 			=> $this->input->post('supplier_id'),

            'category_id' => $this->input->post('category_id'),

            'price' => $price,

            //'supplier_price' 		=> $this->input->post('supplier_price'),

            'serial_no' => $this->input->post('serial_no'),

            'product_model' => $this->input->post('model'),

            'product_details' => $this->input->post('description'),

            'unit' => $this->input->post('unit'),

            'tax' => $tax,

            'image' => $image_name,

//            'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),

        );

        $result = $CI->Products->update_product($data, $product_id);

        if ($result == true) {

            $this->session->set_userdata(array('message' => display('successfully_updated')));

            redirect(base_url('Cproduct/manage_product'));

        } else {

            $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));

            redirect(base_url('Cproduct/manage_product'));

        }

    }



    //Manage Product

    public function manage_product() {



        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('lproduct');

        $CI->load->model('Products');



        #

        #pagination starts

        #

        $config["base_url"] = base_url('Cproduct/manage_product/');

        $config["total_rows"] = $this->Products->product_list_count();

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

        $content = $this->lproduct->product_list($links, $config["per_page"], $page);



        $this->template->full_admin_html_view($content);

    }



    //Add Product CSV

    public function add_product_csv() {

        $CI = & get_instance();

        $data = array(

            'title' => display('add_product_csv')

        );

        $content = $CI->parser->parse('product/add_product_csv', $data, true);

        $this->template->full_admin_html_view($content);

    }



    //CSV Upload File

    function uploadCsv() {

        $product_id = $this->generator(8);

        $count = 0;

        $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");



        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {



            while ($csv_line = fgetcsv($fp, 1024)) {

                //keep this if condition if you want to remove the first row

                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {

                    $insert_csv = array();

                    $insert_csv = array();

                    $insert_csv['product_id'] = (!empty($csv_line[0]) ? $csv_line[0] : null);

                    $insert_csv['supplier_id'] = (!empty($csv_line[1]) ? $csv_line[1] : null);

                    $insert_csv['category_id'] = (!empty($csv_line[2]) ? $csv_line[2] : null);

                    $insert_csv['product_name'] = (!empty($csv_line[3]) ? $csv_line[3] : null);

                    $insert_csv['price'] = (!empty($csv_line[4]) ? $csv_line[4] : null);

                    $insert_csv['supplier_price'] = (!empty($csv_line[5]) ? $csv_line[5] : null);

                    $insert_csv['unit'] = (!empty($csv_line[6]) ? $csv_line[6] : null);

                    $insert_csv['tax'] = (!empty($csv_line[7]) ? $csv_line[7] : null);

                    $insert_csv['product_model'] = (!empty($csv_line[8]) ? $csv_line[8] : null);

                    $insert_csv['product_details'] = (!empty($csv_line[9]) ? $csv_line[9] : null);

                    $insert_csv['image'] = (!empty($csv_line[10]) ? $csv_line[10] : null);

                    $insert_csv['status'] = (!empty($csv_line[11]) ? $csv_line[11] : null);

                }





                $data = array(

                    'product_id' => $insert_csv['product_id'],

                    'category_id' => $insert_csv['category_id'],

                    'product_name' => $insert_csv['product_name'],

                    'price' => $insert_csv['price'],

                    'unit' => $insert_csv['unit'],

                    'tax' => $insert_csv['tax'],

                    'product_model' => $insert_csv['product_model'],

                    'product_details' => $insert_csv['product_details'],

                    'image' => $insert_csv['image'],

                    'status' => $insert_csv['status']

                );

                $supp_prd = array(

                    'product_id' => $insert_csv['product_id'],

                    'supplier_id' => $insert_csv['supplier_id'],

                    'supplier_price' => $insert_csv['supplier_price'],

                    'products_model' => $insert_csv['product_model'],

                );



                if ($count > 0) {



                    $splprd = $this->db->select('*')

                            ->from('supplier_product')

                            ->where('supplier_id', $supp_prd['supplier_id'])

                            ->where('products_model', $supp_prd['product_model'])

                            ->get()

                            ->num_rows();



                    if ($splprd == 0) {

                        $this->db->insert('supplier_product', $supp_prd);

                    } else {

                        $supp_prd = array(

                            'supplier_id' => $insert_csv['supplier_id'],

                            'supplier_price' => $insert_csv['supplier_price'],

                            'products_model' => $insert_csv['product_model']

                        );

                        $this->db->where('products_model', $supp_prd['product_model']);

                        $this->db->where('supplier_id', $supp_prd['supplier_id']);

                        $this->db->update('supplier_product', $supp_prd);

                    }

                    $result = $this->db->select('*')

                            ->from('product_information')

                            ->where('product_model', $data['product_model'])

                            ->get()

                            ->num_rows();

                    if ($result == 0 && !empty($data['product_model'])) {



                        $this->db->insert('product_information', $data);





                        $this->db->select('*');

                        $this->db->from('product_information');

                        $this->db->where('status', 1);

                        $query = $this->db->get();

                        foreach ($query->result() as $row) {

                            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);

                        }

                        $cache_file = './my-assets/js/admin_js/json/product.json';

                        $productList = json_encode($json_product);

                        file_put_contents($cache_file, $productList);

                    } else {



                        $data = array(

                            'category_id' => $insert_csv['category_id'],

                            'product_name' => $insert_csv['product_name'],

                            'price' => $insert_csv['price'],

                            'unit' => $insert_csv['unit'],

                            'tax' => $insert_csv['tax'],

                            'product_model' => $insert_csv['product_model'],

                            'product_details' => $insert_csv['product_details'],

                            'image' => (!empty($insert_csv['image']) ? $insert_csv['image'] : base_url('my-assets/image/product.png')),

                            'status' => $insert_csv['status']

                        );





                        $this->db->where('product_model', $data['product_model']);

                        $this->db->update('product_information', $data);







                        $this->db->select('*');

                        $this->db->from('product_information');

                        $this->db->where('status', 1);

                        $query = $this->db->get();

                        foreach ($query->result() as $row) {

                            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);

                        }

                        $cache_file = './my-assets/js/admin_js/json/product.json';

                        $productList = json_encode($json_product);

                        file_put_contents($cache_file, $productList);

                    }

                }

                $count++;

            }

        }



        fclose($fp) or die("can't close file");

        $this->session->set_userdata(array('message' => display('successfully_added')));

        redirect(base_url('Cproduct/manage_product'));

    }



    //Add supplier by ajax

    public function add_supplier() {

        $this->load->model('Suppliers');



        $data = array(

            'supplier_id' => $this->auth->generator(20),

            'supplier_name' => $this->input->post('supplier_name'),

            'address' => $this->input->post('address'),

            'mobile' => $this->input->post('mobile'),

            'details' => $this->input->post('details'),

            'status' => 1

        );



        $supplier = $this->Suppliers->supplier_entry($data);



        if ($supplier == TRUE) {

            $this->session->set_userdata(array('message' => display('successfully_added')));

            echo TRUE;

        } else {

            $this->session->set_userdata(array('error_message' => display('already_exists')));

            echo FALSE;

        }

    }



    // Insert category by ajax

    public function insert_category() {

        $this->load->model('Categories');

        $category_id = $this->auth->generator(15);



        //Customer  basic information adding.

        $data = array(

            'category_id' => $category_id,

            'category_name' => $this->input->post('category_name'),

            'status' => 1

        );



        $result = $this->Categories->category_entry($data);



        if ($result == TRUE) {

            $this->session->set_userdata(array('message' => display('successfully_added')));

            echo TRUE;

        } else {

            $this->session->set_userdata(array('error_message' => display('already_exists')));

            echo FALSE;

        }

    }



    // product_delete

    public function product_delete() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Products');

        $product_id = $_POST['product_id'];

        $result = $CI->Products->delete_product($product_id);

        return true;

    }



    //Retrieve Single Item  By Search

    public function product_by_search() {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('lproduct');

        $product_id = $this->input->post('product_id');



        $content = $CI->lproduct->product_search_list($product_id);

        $this->template->full_admin_html_view($content);

    }



    //Retrieve Single Item  By Search

    public function product_details($product_id) {

        $this->product_id = $product_id;

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('lproduct');

        $content = $CI->lproduct->product_details($product_id);

        $this->template->full_admin_html_view($content);

    }



    //Retrieve Single Item  By Search

    public function product_sales_supplier_rate($product_id = null, $startdate = null, $enddate = null) {

        if ($startdate == null) {

            $startdate = date('Y-m-d', strtotime('-30 days'));

        }

        if ($enddate == null) {

            $enddate = date('Y-m-d');

        }

        $product_id_input = $this->input->post('product_id');

        if (!empty($product_id_input)) {

            $product_id = $this->input->post('product_id');

            $startdate = $this->input->post('from_date');

            $enddate = $this->input->post('to_date');

        }



        $this->product_id = $product_id;



        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->library('lproduct');

        $content = $CI->lproduct->product_sales_supplier_rate($product_id, $startdate, $enddate);

        $this->template->full_admin_html_view($content);

    }



    //This function is used to Generate Key

    public function generator($lenth) {

        $CI = & get_instance();

        $this->auth->check_admin_auth();

        $CI->load->model('Products');



        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {

            $rand_value = rand(0, 8);

            $rand_number = $number["$rand_value"];



            if (empty($con)) {

                $con = $rand_number;

            } else {

                $con = "$con" . "$rand_number";

            }

        }



        $result = $this->Products->product_id_check($con);



        if ($result === true) {

            $this->generator(8);

        } else {

            return $con;

        }

    }



    //Export CSV

    public function exportCSV() {

        // file name 

        $this->load->model('Products');

        $filename = 'product_' . date('Ymd') . '.csv';

        header("Content-Description: File Transfer");

        header("Content-Disposition: attachment; filename=$filename");

        header("Content-Type: application/csv; ");



        // get data 

        $usersData = $this->Products->product_csv_file();



        // file creation 

        $file = fopen('php://output', 'w');



        $header = array('product_id', 'supplier_id', 'category_id', 'product_name', 'price', 'supplier_price', 'unit', 'tax', 'product_model', 'product_details', 'image', 'status');

        fputcsv($file, $header);

        foreach ($usersData as $line) {

            fputcsv($file, $line);

        }

        fclose($file);

        exit;

    }


    public function add_item(){
        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('lproduct');


        $content = $CI->lproduct->product_add_form();

        $this->template->full_admin_html_view($content);
        
    }


    public function insert_item(){
        $this->load->model('Products');
        
        $product_model = $this->input->post('model');
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $this->db->where('product_model', $product_model);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $this->session->set_userdata(array('error_message' => "Product Exists with same Model Number"));

            redirect(base_url('Cproduct'));
        }

        if ($_FILES['image']['name']) {

            //Chapter chapter add start

            $config['upload_path'] = './my-assets/image/product/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['max_size'] = "*";
            $config['max_width'] = "*";
            $config['max_height'] = "*";
            $config['encrypt_name'] = TRUE;


            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Cproduct'));
            } else {

                $image = $this->upload->data();
                $image_url = base_url() . "my-assets/image/product/" . $image['file_name'];

            }

        }

        $product_model = $this->input->post('model');
        $product_name = $this->input->post('product_name');
        $item_code = $this->input->post('item_code');
        $item_color = $this->input->post('item_color');
        $item_gallon = $this->input->post('item_gallon');
        $item_quarter = $this->input->post('item_quarter');
        $item_drum = $this->input->post('item_drum');
        $special = $this->input->post('special');
        $price = $this->input->post('price');

        $tax_percentage = $this->input->post('tax');
        $tax = $tax_percentage / 100;


        $data = array(
            'product_id' => $product_model,
            'product_name' => $this->input->post('product_name'),
            'category_id' => $this->input->post('category_id'),
            'unit' => $this->input->post('unit'),
            'tax' => $tax,
            'serial_no' => $this->input->post('serial_no'),
            'price' => (!empty($price) ? $price : "0"),
            'product_model' => $this->input->post('model'),
            'product_details' => $this->input->post('description'),
            'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
            'sub_product'=>'0',
            'status' => 1
        );
        $this->db->insert('product_information', $data);
        for ($i = 0; $i < count($item_code);  $i++) 
        {
            $code = $item_code[$i];
            $color = $item_color[$i];
            $drum = $item_drum[$i];
            $quarter = $item_quarter[$i];
            $gallon = $item_gallon[$i];       
            $each_special = $special[$i];
            $supp_prd = array(

                'product_id' => "G".$code,
                'product_name' => $product_name."-".$color." (".$code.")", 
                'category_id' => $this->input->post('category_id'),
                'unit' => "Gallon",
                'tax' => $tax,
                'serial_no' => $color,
                'price' => $gallon,
                'product_model' => "$code",
                'product_details' => $this->input->post('description'),
                'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
                'sub_product'=>$product_model,
                'status' => 1,
                'special' => $each_special == "1" ? true : false

            );
            $this->db->insert('product_information', $supp_prd);

          //  print_r($supp_prd);
            $supp_prd = array(

                'product_id' => "Q".$code,
                'product_name' => $product_name."-".$color." (".$code.")",
                'price' => $quarter,
                'unit' => "Quarter",
                'product_model' => "$code",
                'category_id' => $this->input->post('category_id'),
                'tax' => $tax,
                 'serial_no' => $color,                
                'product_details' => $this->input->post('description'),
                'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
                'sub_product'=>$product_model,
                'status' => 1,
                'special' => $each_special == "1" ? true : false

            );
            $this->db->insert('product_information', $supp_prd);

                     //   print_r($supp_prd);

                     //   die();
            $supp_prd = array(

                'product_id' => "D".$code,
                'product_name' => $product_name."-".$color." (".$code.")",
                'price' => $drum,
                'unit' => "Drum",
                'product_model' => "$code",
                'category_id' => $this->input->post('category_id'),
                'tax' => $tax,
                'serial_no' => $color,
                'product_details' => $this->input->post('description'),
                'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
                'sub_product' =>$product_model,
                'status' => 1,
                'special' => $each_special == "1" ? true : false

            );

            $this->db->insert('product_information', $supp_prd);

        }
        $this->Products->update_cache_file();
        redirect(base_url('Cproduct/manage_product'));

        
    }

    public function item_update_form($product_id){

        $CI = & get_instance();
        $CI->load->model('Products');
        $CI->load->model('Suppliers');
        $CI->load->model('Categories');
        $CI->load->model('Units');

        $product_detail = $CI->Products->retrieve_product_editdata($product_id);
        
        @$category_id = $product_detail[0]['category_id'];
        
        $category_list = $CI->Categories->category_list_product();
        $unit_list = $CI->Units->unit_list();
        $category_selected = $CI->Categories->category_search_item($category_id);

        $tax_list = $CI->db->select('*')
                ->from('tax_information')
                ->get()
                ->result();

        $subproduct_list = $CI->db->select('*')
                        ->from('product_information')->where('sub_product',$product_id)->get()->result();

           $subproducts = array();

        foreach ($subproduct_list as $products) {
            # code...
            //echo "<pre>";print_r($products);
            //echo $products->product_model;
            $subproducts[$products->product_model]['color'] = $products->serial_no;
            $subproducts[$products->product_model]['unit'][$products->unit] = $products->price; 
            $subproducts[$products->product_model]['special'] = $products->special;
        }
        $data = array(
            'title' => display('edit_your_product'),
            'product_id' => $product_detail[0]['product_id'],
            'product_name' => $product_detail[0]['product_name'],
            'price' => $product_detail[0]['price'],
            'serial_no' => $product_detail[0]['serial_no'],
            'product_model' => $product_detail[0]['product_model'],
            'product_details' => $product_detail[0]['product_details'],
            'image' => $product_detail[0]['image'],
            'unit' => $product_detail[0]['unit'],
            'supplier_list' => $supplier_list,
            'supplier_selected' => $supplier_selected,
            'unit_list' => $unit_list,
            'category_list' => $category_list,
            'selected_category_id' => $product_detail[0]['category_id'],
            'category_selected' => $category_selected,
            'tax_list' => $tax_list,
            'tax_selecete' => $product_detail[0]['tax'] * 100,
            'supplier_product_data' => $supplier_product_detail,
            'sub_products' => $subproducts,
            'content' => 'product/edit_item_form'
        );
        //$chapterList = $CI->parser->parse('product/edit_product_form', $data, true);
        //die(print_r($data));
         $CI->load->view("admin_template",$data);
        //return $chapterList;
    }



    // Product Update

    public function item_update() {
        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->model('Products');

        $product_id = $this->input->post('product_id');
        $this->db->where('sub_product', $product_id);
        $this->db->delete('product_information');

        

        // configure for upload 

        $config = array(
            'upload_path' => "./my-assets/image/product/",
            'allowed_types' => "png|jpg|jpeg|gif|bmp|tiff",
            'overwrite' => TRUE,
//                'file_name' => "IST" . time(),

            'max_size' => '0',
        );

        $image_data = array();

        $this->load->library('upload', $config);

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {

            $image_data = $this->upload->data();

//                print_r($image_data); die();

            $image_name = base_url() . "my-assets/image/product/" . $image_data['file_name'];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_data['full_path']; //get original image
            $config['maintain_ratio'] = TRUE;
            $config['height'] = '*';
            $config['width'] = '*';

//                $config['quality'] = 50;

            $this->load->library('image_lib', $config);

            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) {

                echo $this->image_lib->display_errors();

            }

        } else {

            $image_name = $this->input->post('old_image');

        }




        $price = $this->input->post('price');
        $tax_percentage = $this->input->post('tax');
        $tax = $tax_percentage / 100;



        $data = array(

            'product_name' => $this->input->post('product_name'),
            //'supplier_id'             => $this->input->post('supplier_id'),
            'category_id' => $this->input->post('category_id'),
            'price' => $price,
            //'supplier_price'      => $this->input->post('supplier_price'),
            'serial_no' => $this->input->post('serial_no'),
            'product_model' => $this->input->post('model'),
            'product_details' => $this->input->post('description'),
            'unit' => $this->input->post('unit'),
            'tax' => $tax,
            'image' => $image_name,
//            'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),

        );

        $result = $CI->Products->update_product($data, $product_id);

        $product_model = $this->input->post('model');
        $product_name = $this->input->post('product_name');
        $item_code = $this->input->post('item_code');
        $item_color = $this->input->post('item_color');
        $item_gallon = $this->input->post('item_gallon');
        $item_quarter = $this->input->post('item_quarter');
        $item_drum = $this->input->post('item_drum');
        $price = $this->input->post('price');
        $special = $this->input->post('special');

        for ($i = 0; $i < count($item_code);  $i++) 
        {

            $code = $item_code[$i];
            $color = $item_color[$i];
            $drum = $item_drum[$i];
            $quarter = $item_quarter[$i];
            $gallon = $item_gallon[$i];       
            $each_special = $special[$i];
            
            $supp_prd = array(

                'product_id' => "G".$code,
                'product_name' => $product_name."-".$color." (".$code.")", 
                'category_id' => $this->input->post('category_id'),
                'unit' => "Gallon",
                'tax' => $tax,
                'serial_no' => $color,
                'price' => $gallon,
                'product_model' => "$code",
                'product_details' => $this->input->post('description'),
                'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
                'sub_product'=>$product_model,
                'status' => 1,
                'special' => $each_special == "1" ? true : false

            );

            $this->db->insert('product_information', $supp_prd);

          //  print_r($supp_prd);


            $supp_prd = array(

                'product_id' => "Q".$code,
                'product_name' => $product_name."-".$color." (".$code.")",
                'price' => $quarter,
                'unit' => "Quarter",
                'product_model' => "$code",
                'category_id' => $this->input->post('category_id'),
                'tax' => $tax,
                 'serial_no' => $color,                
                'product_details' => $this->input->post('description'),
                'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
                'sub_product'=>$product_model,
                'status' => 1,
                'special' => $each_special == "1" ? true : false

            );

            $this->db->insert('product_information', $supp_prd);


            $supp_prd = array(

                'product_id' => "D".$code,
                'product_name' => $product_name."-".$color." (".$code.")",
                'price' => $drum,
                'unit' => "Drum",
                'product_model' => "$code",
                'category_id' => $this->input->post('category_id'),
                'tax' => $tax,
                 'serial_no' => $color,                
                'product_details' => $this->input->post('description'),
                'image' => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
                'sub_product' =>$product_model,
                'status' => 1,
                'special' => $each_special == "1" ? true : false

            );

            $this->db->insert('product_information', $supp_prd);

        }

        if ($result == true) {

            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Cproduct/manage_product'));

        } else {

            $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
            redirect(base_url('Cproduct/manage_product'));

        }

    }
    
    
    
    
    public function add_stock_data(){
         //  echo "<pre>";
         // print_r($_POST);
       // die();

         $productInfo = $this->input->post("product_id");
         $productQty = $this->input->post("product_quantity");


         for($x=0;$x<count($productInfo);$x++){

            $qty = $productQty[$x];
            $product_code = $productInfo[$x];

            $datainv = array(
 
            'product_code' => $product_code,
            'inserted_at' => $this->input->post('invoice_date'),
            'qty' => $qty);


           // print_r($datainv);
             $sqlquey = $this->db->insert('stock', $datainv);


             $query = "update product_information set available_quantity = available_quantity+$qty where product_id='$product_code' ";

             $this->db->query($query);

         }
 
         redirect('Cproduct/add_stock'); 
}
//Stock
public function add_stock() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');

        $customer_details = $CI->Invoices->pos_customer_setup();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data = array(

            'title' => display('add_new_pos_invoice'),
            'customer_name' => $customer_details[0]['customer_name'],
            'customer_id' => $customer_details[0]['customer_id'],
            'discount_type' => $currency_details[0]['discount_type'],
            'content' => 'product/add_stock'

        );

         $CI->load->view("admin_template",$data);

    }
    //Insert pos invoice

    public function insert_stock() {

        $CI = & get_instance();

        $CI->auth->check_admin_auth();

        $CI->load->model('Invoices');

        $CI->load->model('Web_settings');

        $product_id = $this->input->post('product_id');

        if(strstr($product_id,"/"))
        {
            $info = explode("/", $product_id);
            $product_id = $info[0];
            $quantity = $info[1];
        }
        else{
            $quantity = 1;
        }

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

                            <input type=\"number\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_id . "');\" onchange=\"quantity_calculate('" . $product_id . "');\" class=\"total_qntt_" . $product_id . " form-control text-right\" id=\"total_qntt_" . $product_id . "\" placeholder=\"0.00\" min=\"0\"  value='$quantity'/>

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

        public function manage_stock()
        {
        
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


        // $company_info = $CI->Products->retrieve_company();
        $data = array(
            'logindata' => $logged_info,
            'mainmenu' => $top_menu,
            'content' => "product/view_stock",
            'msg_content' => $message,
            'company_info' => $company_info
        );

       
        $startdate = $this->input->get('from_date');
        $enddate = $this->input->get('to_date');

        if($startdate == ""){

            $startdate = date("Y-m-01");
            $enddate = date("Y-m-t");
        }
        
        $chequeInfo = $this->db->query("SELECT `stock`.`id`,`stock`.`product_code`, `product_information`.`product_name`, `stock`.`inserted_at`, `stock`.`qty`
                                        FROM `product_information`INNER JOIN `stock` 
                                        ON (`product_information`.`product_id` = `stock`.`product_code`)  WHERE stock.inserted_at >='$startdate' && stock.inserted_at <='$enddate'
                                        ORDER BY id;")->result_array();

              
         $data['stock_detail'] = $chequeInfo;

        $data['start_date'] = $startdate;
        $data['end_date'] = $enddate;
        $CI->load->view("admin_template",$data);
        }

        public function manage_stock_delete(){
            // die("dfsdf");

     $product_qty = $this->db->query("SELECT `available_quantity` from product_information where product_id='".$_GET['pcd']."'")->result_array();
     $finalqty = $product_qty[0]['available_quantity'] - $_GET['qty'];
          $this->db->query("delete from `stock` where `id`=".$_GET['sid']);

          $this->db->query("UPDATE product_information set available_quantity = $finalqty WHERE product_id = '".$_GET['pcd']."'");

        
          redirect('Cproduct/manage_stock');
        }
    



}

