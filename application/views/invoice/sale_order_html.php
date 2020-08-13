<?php
$CI = & get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>


<!-- Printable area start -->
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        // document.body.style.marginTop="-45px";
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<!-- Printable area end -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Sale Order Details</h1>
            <small>Sale order details</small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_details') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Alert Message -->
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
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <div class="panel-body">
                            <div class="row" style="padding-bottom: 10px;">

                                <div class="invoice-title"><span>Sale Order</span></div>
                                <div class="row" style="margin: 10px; padding: 5px; border-bottom: 2px solid;">
                                    <div class="col-sm-12 col-md-12 text-left" style="padding: 0px; width: 100%;display: inline-block;font-size: 11px;">
                                        <div class="col-sm-2 cl2">
                                            <b>Sale Order No:</b>
                                        </div>
                                        <div class="col-sm-4 cl4 field">
                                            <span><?=$invoice_no?></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b>Date:</b>
                                        </div>
                                        <div class="col-sm-4 cl4 field">
                                            <span><?=$final_date?></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b>Messers:</b>
                                        </div>
                                        <div class="col-sm-4 cl4 field">
                                            <span><b style="font-size: 12px;"><?=$customer_name?></b></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b>Address:</b>
                                        </div>
                                        <div class="col-sm-4 cl4 field">
                                            <span><?=$customer_address?></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b>&nbsp;</b>
                                        </div>
                                        <div class="col-sm-4 cl4">
                                            <span>&nbsp;</span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b>Sales Man:</b>
                                        </div>
                                        <div class="col-sm-4 cl4 field">
                                            <span><?=$salesman?></span>
                                        </div>
                                        <!-- <div class="col-sm-3 cl3">
                                            <b>Route:</b>
                                        </div>
                                        <div class="col-sm-3 cl3 field">
                                            <span><?=$route?></span>
                                        </div> -->
                                        <!-- <div class="col-sm-3 cl3">
                                            <b>C.Balance:</b>
                                        </div>
                                        <div class="col-sm-9 cl3 field">
                                            <span>test test</span>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive m-b-20" id="mainTable" style="font-size: 10px;">

                                <table class="table table-striped">
                                    <thead>
                                        <tr style="border: 1px solid darkgrey;">
                                            <th class="text-center no-border extra-bolder"><?php echo display('sl') ?></th>
                                            <th class="text-center no-border extra-bolder"><?php echo display('product_name') ?></th>
                                            <th class="text-center no-border extra-bolder">Drum</th>
                                            <th class="text-center no-border extra-bolder">Gallon</th>
                                            <th class="text-center no-border extra-bolder">Quarter</th>
                                            <th class="text-center no-border extra-bolder">Total Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? $i = 0;
                                        $total_amount = 0.00;
                                        $totalDiscount=0.00;
                                        foreach($invoice_all_data as $k => $v){ ?>
                                            <tr style="font-family: inherit; border-bottom: 2px solid black; border-top: none;">
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn catHeading">
                                                    <?=$k?>
                                                </td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                            </tr>
                                            <? 
                                            $v2 = $invoice_all_data[$k];
                                            if(!isset($v2))
                                                continue;
                                            foreach ($v2 as $k3 => $v3) {?>
                                                <?php $total_amount = $v3['total_amount'];?>
                                                <tr>
                                                    <td class="text-center"><?=++$i;?></td>
                                                    <td class="text-left header"><?=$k3?></td>
                                                    <td class="text-center"><?=$v3['dQuantity']?></td>
                                                    <td class="text-center"><?=$v3['gQuantity']?></td>
                                                    <td class="text-center"><?=$v3['qQuantity']?></td>
                                                    <td class="text-center"><?=$v3['dQuantity'] + $v3['gQuantity'] + $v3['qQuantity']?></td>
                                                </tr>
                                            <? } ?>
                                        <? } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" style="position: relative; font-size: 11px;">
                                <div class="col-md-8 inlineDiv" style="padding-right: 5px;width: 66.6666666666%;margin-top:60px;">
                                    &nbsp;
                                </div>
                                <div class="col-md-2 inlineDiv" style="padding-left: 0px;text-align: right;width: 16.6666666666%;position: absolute;bottom: 20px;">
                                    <div>
                                        <span class="header">OV :</span>
                                    </div>
                                    <div>
                                        <span class="header">CL :</span>
                                    </div>
                                    <div>
                                        <span class="header">OS :</span>
                                    </div>
                                    <div>
                                        <span class="header">RTM :</span>
                                    </div>
                                    <div>
                                        <span class="header">STM :</span>
                                    </div>
                                </div>
                                <div class="col-md-2 inlineDiv" style="padding-left: 0px;text-align: right;width: 13.6666666666%;position: absolute; bottom: 20px;right: 0px;">
                                    <div class="put-value">
                                        <span class="header"><?=number_format($total_amount,2)?></span>
                                    </div>
                                    <div class="put-value">
                                        <span class="header"><?=number_format($credit_limit,2)?></span>
                                    </div>
                                    <div class="put-value">
                                        <span class="header"><?=number_format($customer_balance, 2)?></span>
                                    </div>
                                    <div class="put-value">
                                        <span class="header">&nbsp;</span>
                                    </div>
                                    <div class="put-value">
                                        <span class="header">&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                            <style type="text/css">
                                .put-value{
                                    border-bottom: 1px solid black;
                                }
                                .table-striped>tbody>tr:nth-of-type(odd) {
                                    background-color: transparent;
                                }
                                .no-border{
                                    border: none !important;
                                }
                                .extra-bolder{
                                    font-weight: 800 !important;
                                }
                                .emptyColumn{
                                    border-right: none !important;
                                    border-left: none !important;
                                    background: white;
                                }
                                .eachCatCount{
                                    text-align: right;
                                }
                                .header{
                                    font-weight: bold;
                                }
                                .catHeading{
                                    font-size: 20px;
                                    font-style: italic;
                                }
                                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                                    /*border: none;*/
                                    /*#e4e5e7*/
                                    border:1px solid darkgrey;
                                    border-right: 1px solid #5d5d5d;
                                }
                                hr{
                                    border-top: 3px solid #e1e6ef;
                                }
                                .normalTd{
                                    text-align: center;
                                }
                                .inlineDiv{
                                    display: inline-block;
                                }
                                .topSolid{
                                    border-top: 2px solid;
                                }
                                .cl2{
                                    display: inline-block;
                                    width: 15.666666666666%;
                                    padding: 0px;
                                }
                                .cl3{
                                    display: inline-block;
                                    width: 24%;
                                    padding: 0px;
                                }
                                .cl4{
                                    display: inline-block;
                                    width: 32.33333333333%;
                                    padding: 0px;
                                }
                                .cl9{
                                    display: inline-block;
                                    width: 72%;
                                    padding: 0px;
                                }
                                .field{
                                    border-bottom: 1px solid black;
                                    /*margin: 0 10px;*/
                                    display: inline-block;
                                    padding: 0px;
                                }
                                .invoice-title{
                                    text-align: center;
                                }
                                .invoice-title span{
                                    border: 1px solid black;
                                    padding: 10px 30px;
                                    line-height: 83px;
                                    font-size: 24px;
                                    font-style: italic;
                                    font-family: cursive;
                                }
                                .sign-area{
                                    display: inline-block;width: 33.333333333%
                                }
                                .sign-area div{
                                    float: left;
                                    text-align: center;
                                    border-top: 1px solid darkgrey;
                                    margin-top: 110px;
                                    font-weight: bold;
                                }
                            </style>
                            <div class="row">
                                <div class="col-xs-4 sign-area">
                                    <div style="width: 65%;">
                                        <span>Generated By</span>
                                    </div>
                                </div>
                                <div class="col-xs-4 sign-area">
                                    <div style="width:70%;">
                                        <span>Checked By</span>
                                    </div>
                                </div>
                                <div class="col-xs-4 sign-area">
                                    <div style="width:65%;float: right;">
                                        <span>Customer Sign</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer text-left">
                            <a  class="btn btn-danger" href="<?php echo base_url('Cinvoice'); ?>"><?php echo display('cancel') ?></a>
                            <button  class="btn btn-info" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button>

                        </div>
                    </div>
                </div>
            </div>
        </section> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

    <script type="text/javascript">
        var monthNames = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
        ];
        var dateStr = '<?=$final_date?>';
        dateStr = dateStr.replace(/\s/g, "");
        var finalDate = new Date(dateStr);
        setTimeout(function(){
            var after30 = setDesiredDate(finalDate, 30, monthNames);
            $('#after30').html(after30);
            var after60 = setDesiredDate(finalDate, 60, monthNames);
            $('#after60').html(after60);
            var after90 = setDesiredDate(finalDate, 90, monthNames);
            $('#after90').html(after90);
            var after120 = setDesiredDate(finalDate, 120, monthNames);
            $('#after120').html(after120);
            var after150 = setDesiredDate(finalDate, 150, monthNames);
            $('#after150').html(after150);
        }, 1000);

        function setDesiredDate(date, add, monthNames){
            var desiredDate = new Date(date);
            desiredDate.setDate(desiredDate.getDate() + add);
            var desiredDateString = desiredDate.getDate() + ' - ' + monthNames[desiredDate.getMonth()] + ' - ' + desiredDate.getFullYear();
            return desiredDateString;
        }
    </script>



