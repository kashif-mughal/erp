
<!-- Manage Payment start -->
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        document.body.style.marginTop = "0px";
        $('table tr').find('td:eq(7)').hide();
        $('table tr').find('th:eq(7)').hide();
        $('table tr').find('th:eq(5)').hide();
        $('table tr').find('td:eq(5)').hide();
        $('.dataTables_length').hide();
        $('.dt-buttons').hide();
        $('.dataTables_filter').hide();
        $('.dataTables_paginate').hide();
        $('table tr').find('tfoot:eq(6)').hide();

        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Manage Voucher</h1>
            <small>Voucher List</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo display('manage_transaction') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <?php
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
                    <a href="<?php echo base_url('Cpayment') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('payment') ?> </a>
                    <a href="<?php echo base_url('Cpayment/receipt_transaction') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('receipt') ?> </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body"> 
                        <form action="<?php echo base_url('Cpayment/manage_voucher') ?>" class="form-inline" method="get" accept-charset="utf-8">
                            <?php date_default_timezone_set("Asia/karachi");
                            $today = date('Y-m-d'); ?>
                            <div class="form-group">
                                <label class="" for="from_date"><?php echo display('start_date') ?></label>
                                <input type="text" name="from_date" class="form-control datepicker" id="from_date" value="<?php echo $_GET['from_date'] ?>" placeholder="<?php echo display('start_date') ?>" >
                            </div> 

                            <div class="form-group"> 
                                <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $_GET['to_date'] ?>">
                            </div>  

                            <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>

                        </form> 
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage transaction report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_transaction') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="text-right">
                            <a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                        </div>
                        <div id="printableArea" style="margin-left:2px;">
                            <div class="text-center">
                                {company_info}
                                <h3> {company_name} </h3>
                                <h4 >{address} </h4>
                                {/company_info}
                                <h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                            </div>

                            <div class="table-responsive" style="margin-top: 10px;">
                                <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo display('name') ?></th>
                                            <th class="text-center"><?php echo display('account_name') ?></th>
                                            <th class="text-center"><?php echo display('receipt_amount') ?></th>
                                            <th class="text-center"><?php echo display('paid_amount') ?></th>
                                            <th class="text-center"><?php echo display('action') ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
//                                        echo '<pre>';    print_r($ledger); //die();
                                        if ($ledger) {
                                            ?>
                                            <?php
                                            $sl = 0;
                                            $debit = $credit = $balance = 0;
                                            foreach ($ledger as $single) {
                                                $sl++
                                                ?>
                                                <tr>
                                                    <td><?php echo $sl; ?></td>
                                                    <td><?php
                                                        if ($single['supplier_name']) {
                                                            echo $single['supplier_name'];
                                                        } else {
                                                            echo $single['customer_name'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $tran_cat = $single['transection_category'];
                                                        if ($tran_cat == 1) {
                                                            echo "supplier";
                                                        } elseif ($tran_cat == 2) {
                                                            echo "customer";
                                                        } elseif ($tran_cat == 3) {
                                                            echo "Office";
                                                        } elseif ($tran_cat == 5) {
                                                            echo "Salary";
                                                        } else {
                                                            echo "Loan";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        if ($single['debit']) {
                                                            echo (($position == 0) ? "$currency " : " $currency");
                                                            echo number_format($single['debit'], '2', '.', ',');
                                                            $debit += $single['debit'];
                                                        } else {
                                                            $debit += '0.00';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        if ($single['credit']) {
                                                            echo (($position == 0) ? "$currency " : " $currency");
                                                            echo number_format($single['credit'], '2', '.', ',');
                                                            $credit += $single['credit'];
                                                        } else {
                                                            $credit += '0.00';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td align="center">
                                                         <a href="<?php echo base_url() . 'Cpayment/receipt_voucher/' . $single['voucher_id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Detail"><i class="fa fa-info" aria-hidden="true"></i></a>
                                                        
                                                        
                                                        <a href="<?php echo base_url() . 'Cpayment/edit_voucher/' . $single['voucher_id']; ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>

                                    <tfoot>
                                        <tr  align="right">
                                            <td colspan="3"  align="right"><b>Total:</b></td>
                                            <td align="right"><b>
                                                    <?php
                                                    echo (($position == 0) ? "$currency " : "$currency");
                                                    echo number_format(@$debit, '2', '.', ',');
                                                    ?></b>
                                            </td>
                                            <td align="right"><b>
                                                    <?php
                                                    echo (($position == 0) ? "$currency " : "$currency");
                                                    echo number_format(@$credit, '2', '.', ',');
                                                    ?></b>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!--<div class="text-right"><?php echo $links ?></div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage Payment End -->


<script type="text/javascript">
    $(".deletePayment").click(function () {

        return true;
        var transaction_id = $(this).attr('name');
        var csrf_test_name = $("[name=csrf_test_name]").val();
        var x = confirm("Are You Sure,Want to Delete ?");
        if (x == true) {
            $.ajax
                    ({
                        type: "POST",
                        url: '<?php echo base_url('Cpayment/payment_delete') ?>',
                        data: {transaction_id: transaction_id, csrf_test_name: csrf_test_name},
                        cache: false,
                        success: function (datas)
                        {
                            location.reload();
                        }
                    });
        }
    });
</script>