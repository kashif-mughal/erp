<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/supplier.js.php" ></script>
<!-- Manage Supplier Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('manage_suppiler') ?></h1>
            <small><?php echo display('manage_your_supplier') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('supplier') ?></a></li>
                <li class="active"><?php echo display('manage_suppiler') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Message -->
        <?php

        //print_r($employee_list);
        $message = $this->session->userdata('message');
        if (isset($message)) {
            ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>                    
            </div>
            <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>                    
            </div>
            <?php
            $this->session->unset_userdata('error_message');
        }
        ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="column">

                    <a href="<?php echo base_url('Csupplier') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_supplier') ?> </a>

                    <a href="<?php echo base_url('Csupplier/supplier_ledger_report') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('supplier_ledger') ?> </a>

                    <a href="<?php echo base_url('Csupplier/supplier_sales_details_all') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('supplier_sales_details') ?> </a>

                </div>
            </div>
        </div>


        <!-- Manage Supplier -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>Employee Detail</h4>
                        </div>
                        <span class="text-right">
                            <!-- <?php echo form_open('Csupplier/search_supplier', array('class' => 'form-inline', 'method' => 'post')) ?>

                            <div class="form-group">
                                <input type="text"   name="supplier_name" class="supplierSelection form-control" placeholder='<?php echo display('supplier_name') ?>' id="supplier_name" tabindex="1" />

                                <input id="SchoolHiddenId" class="supplier_hidden_value abc" type="hidden" name="supplier_id">
                            </div>  

                            <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>

                            <?php echo form_close() ?> -->
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTableExample-supplier" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th><?php echo display('name') ?></th>
                                        <th><?php echo display('address') ?></th>
                                        <th><?php echo display('mobile') ?></th>
                                        <th><?php echo display('email') ?></th>
                                       
                                        <th><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($employee_list) {
                                        ?>
                                        <? for($x=0;$x<count($employee_list);$x++){ ?>
                                        <tr>
                                            <td><?=$x+1;?></td>
                                            <td>
                                                <a href="#">
                                                   <?=$employee_list[$x]['employee_name']?>
                                                </a>
                                            </td>
                                            <td><?=$employee_list[$x]['employee_address']?></td>
                                            <td><?=$employee_list[$x]['employee_mobile']?></td>
                                            <td><?=$employee_list[$x]['employee_email']?></td>
                                           
                                            <td>
                                    <center>
                                        <?php echo form_open() ?>
                                        <a href="<?php echo base_url() . 'Cemployee/employee_update_form/'.$employee_list[$x]['employee_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                        <a href="<?=base_url('Cemployee/employee_delete/'.$employee_list[$x]['employee_id'])?>" class="deleteSupplier btn btn-danger btn-sm" name="<?php echo $employee_list[$x]['employee_id'];?>" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                         

                                        <?php echo form_close() ?>
                                    </center>
                                    </td>
                                    </tr>
                                    <? } ?>
                                    <?php
                                }
                                ?>
                                    
                                </tbody>

                            </table>
                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage Product End -->

<!-- Delete Product ajax code -->
<script type="text/javascript">
    //Delete Supplier 
    $(".deleteSupplier").click(function ()
    {
        var supplier_id = $(this).attr('name');
        var csrf_test_name = $("[name=csrf_test_name]").val();
        var x = confirm("Are You Sure,Want to Delete ?");
        if (x == true) {
            $.ajax
                    ({
                        type: "POST",
                        url: '<?php echo base_url('Csupplier/supplier_delete') ?>',
                        data: {supplier_id: supplier_id, csrf_test_name: csrf_test_name},
                        cache: false,
                        success: function (datas)
                        {

                        }
                    });
        }
    });
$(document).ready(function() {
    $("#dataTableExample-supplier").DataTable( {
        dom:"<'row'<'col-sm-4'><'col-sm-4 text-center'B><'col-sm-4'f>>", lengthMenu:[[ -1], [ "All"]], buttons:[ {
            extend: "copy", className: "btn-sm"
        }
        , {
            extend: "csv", title: "ExampleFile", className: "btn-sm"
        }
        , {
            extend: "excel", title: "ExampleFile", className: "btn-sm"
        }
        , {
            extend: "pdf", title: "ExampleFile", className: "btn-sm"
        }
        , {
            extend: "print", className: "btn-sm",exportOptions: {
                    columns: [ 0, 1,  4 ]
                },
            customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '8pt' )
                        
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
            title: ""
        }
        ]
    }
    ) });
</script>