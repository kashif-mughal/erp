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
            <h1><?php echo display('invoice_details') ?></h1>
            <small><?php echo display('invoice_details') ?></small>
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
                                <? //rint_r($company_info)?>
                                <!-- 
                                <div class="col-sm-8" style="display: inline-block;width: 64%">
                                    <img src="<?php
                                    if (isset($Web_settings[0]['invoice_logo'])) {
                                        echo $Web_settings[0]['invoice_logo'];
                                    }
                                    ?>" class="" alt="" style="margin-bottom:20px">
                                    <br>
                                    <span class="label label-success-outline m-r-15 p-10" ><?php echo display('billing_from') ?></span>
                                    <address style="margin-top:10px">
                                        <strong style="font-size: 20px; "><?=$company_info[0]['company_name']?></strong><br>
                                        {address}<br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr> <?=$company_info[0]['mobile']?><br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        <?=$company_info[0]['email']?><br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        <?=$company_info[0]['website']?>
                                    </address>
                                </div>
                                <div class="col-sm-4 text-left" style="display: inline-block;margin-left: 5px;">
                                    <h2 class="m-t-0"><?php echo display('invoice') ?></h2>
                                    <div><?php echo display('invoice_no') ?>: <?=$invoice_no?></div>
                                    <div class="m-b-15"><?php echo display('billing_date') ?>: <?=$final_date?></div>

                                    <span class="label label-success-outline m-r-15"><?php echo display('billing_to') ?></span>

                                    <address style="margin-top:10px;width: 200px">  
                                        <strong style="font-size: 20px; "><?=$customer_name?> </strong><br>
                                        <?php if ($customer_address) { ?>
                                            <?=$customer_address?>
                                        <?php } ?>
                                        <br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr>
                                        <?php if ($customer_mobile) { ?>
                                            <?=$customer_mobile?>
                                        <?php }if ($customer_email) {
                                            ?>
                                            <br>
                                            <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                            <?=$customer_email?>
                                        <?php } ?>
                                    </address>
                                </div> -->

                                <div class="invoice-title"><span>Invoice / Cash Memo</span></div>
                                <div class="col-sm-6 text-left" style="width: 60%;display: inline-block;font-size: 11px;">
                                    <div class="col-sm-3 cl3">
                                        <b><?php echo display('invoice_no') ?>:</b>
                                    </div>
                                    <div class="col-sm-3 cl3 field">
                                        <span><?=$invoice_no?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b><?php echo display('billing_date') ?>:</b>
                                    </div>
                                    <div class="col-sm-3 cl3 field">
                                        <span><?=$final_date?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>Messers:</b>
                                    </div>
                                    <div class="col-sm-9 cl9 field">
                                        <span><b style="font-size: 12px;"><?=$customer_name?></b></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>Address:</b>
                                    </div>
                                    <div class="col-sm-9 cl9 field">
                                        <span><?=$customer_address?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>Cell #:</b>
                                    </div>
                                    <div class="col-sm-3 cl3 field">
                                        <span><?=$customer_mobile?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>Vehicle:</b>
                                    </div>
                                    <div class="col-sm-3 cl3 field">
                                        <span><?=$vehicle?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>Sales Man:</b>
                                    </div>
                                    <div class="col-sm-3 cl3 field">
                                        <span><?=$salesman?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>Route:</b>
                                    </div>
                                    <div class="col-sm-3 cl3 field">
                                        <span><?=$route?></span>
                                    </div>
                                    <div class="col-sm-3 cl3">
                                        <b>C.Balance:</b>
                                    </div>
                                    <div class="col-sm-9 cl9 field">
                                        <span><?=$customer_balance?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-left" style="padding: 0px;width: 38%;display: inline-block; font-size: 9px;">
                                    <div class="text-center" style="margin-bottom: 13px;"><b>DUE DATES OF INVOICE</b></div>
                                    <div class="col-sm-12" style="bottom: 13px; border: 1px solid black; padding: 5px; line-height: 22px;">
                                        <div class="col-sm-2 cl2">
                                            <b><span>30 Days</span></b>
                                        </div>
                                        <div class="col-sm-4 cl4">
                                            <span id="after30"></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b><span>60 Days</span></b>
                                        </div>
                                        <div class="col-sm-4 cl4">
                                            <span id="after60"></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b><span>90 Days</span></b>
                                        </div>
                                        <div class="col-sm-4 cl4">
                                            <span id="after90"></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b><span>120 Days</span></b>
                                        </div>
                                        <div class="col-sm-4 cl4">
                                            <span id="after120"></span>
                                        </div>
                                        <div class="col-sm-2 cl2">
                                            <b><span>150 Days</span></b>
                                        </div>
                                        <div class="col-sm-4 cl4">
                                            <span id="after150"></span>
                                        </div>
                                        <div class="col-sm-3 cl3">
                                            <b><span>Above</span></b>
                                        </div>
                                        <div class="col-sm-3 cl3">
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            </div> <hr>

                            <div class="table-responsive m-b-20" id="mainTable" style="font-size: 10px;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo display('product_name') ?></th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-center">Gr. Amount</th>
                                            <th class="text-center">Disc. %</th>
                                            <th class="text-center">Tot. Disc</th>
                                            <th class="text-center">Net Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? $i = 0;
                                        $totalDrum=0.00;
                                        $totalGallon=0.00;
                                        $totalQuarter=0.00;
                                        $totalDozen=0.00;
                                        $netAmount=0.00;
                                        $totalDiscount=0.00;
                                        foreach($invoice_all_data as $k => $v){ ?>
                                            <tr style="font-family: inherit;">
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn catHeading">
                                                    <?=$k?>
                                                </td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                                <td class="emptyColumn"></td>
                                            </tr>
                                            <? 
                                                for($counter = 0; $counter < 3; $counter++){

                                                //foreach ($invoice_all_data[$k] as $k2 => $v2) {
                                                $totalQty = 0;
                                                $unitToDisplay = "Drum";
                                                if($counter == 0)
                                                    $unitToDisplay = "Drum";
                                                else if($counter == 1)
                                                    $unitToDisplay = "Gallon";
                                                else if($counter == 2)
                                                    $unitToDisplay = "Quarter";
                                                $k2 = $unitToDisplay;
                                                $v2 = $invoice_all_data[$k][$unitToDisplay];
                                                if(!isset($v2))
                                                    continue;
                                                //echo '<pre>kashif';print_r($v2);die;
                                                    foreach ($v2 as $k3 => $v3) {
                                                        $totalQty+= $v3['quantity'];
                                                        $product_parts = explode("-", $v3['product_name']);
                                                        $product_shade = substr($v3['product_id'], 1, strlen($v3['product_id']));
                                                        $product_shade .= " ";
                                                        $product_shade .= explode(" ", $product_parts[1])[0];
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?=++$i;?></td>
                                                        <td class="text-left header"><?=$product_shade?></td>
                                                        <td class="text-center"><?=$v3['quantity']?> <?=$v3['unit']?></td>
                                                        <td class="text-right"><?=$v3['rate']?></td>
                                                        <td class="text-right"><?=$v3['total_price']?></td>
                                                        <td class="text-right"><?php if($v3['discount_per'] == '')
                                                        {
                                                            echo '0%';
                                                        } 
                                                        else
                                                        {
                                                            echo $v3['discount_per'];
                                                        }?></td>
                                                        <td class="text-right"><?=($v3['total_price'] / 100) * $v3['discount_per']?></td>
                                                        <td class="text-right"><?=$v3['total_price'] - (($v3['total_price'] / 100) * $v3['discount_per'])?></td>
                                                        <?php 
                                                        $netAmount += ($v3['total_price'] - (($v3['total_price'] / 100) * $v3['discount_per']));
                                                        $totalDiscount += (($v3['total_price'] / 100) * $v3['discount_per']);
                                                        ?>
                                                    </tr>
                                            <? } //}?>
                                            <tr style="font-family: inherit;">
                                                <td class="emptyColumn eachCatCount"></td>
                                                <td class="emptyColumn eachCatCount header">
                                                    <?=$totalQty."   ".$k2?>
                                                </td>
                                                <td class="emptyColumn eachCatCount"></td>
                                                <td class="emptyColumn eachCatCount"></td>
                                                <td class="emptyColumn eachCatCount"></td>
                                                <td class="emptyColumn eachCatCount"></td>
                                                <td class="emptyColumn eachCatCount"></td>
                                                <td class="emptyColumn eachCatCount"></td>
                                            </tr>
                                            <?php 
                                            if(strtolower($k2) == "drum"){
                                                $totalDrum += $totalQty;
                                            }
                                            if(strtolower($k2) == "gallon"){
                                                $totalGallon += $totalQty;
                                            }
                                            if(strtolower($k2) == "quarter"){
                                                $totalQuarter += $totalQty;
                                            }
                                            if(strtolower($k2) == "dozen"){
                                                $totalDozen += $totalQty;
                                            }
                                            ?>
                                        <? } ?>
                                    <? } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row" style="position: relative; font-size: 11px;">
                            <div class="col-md-5 inlineDiv" style="padding-right: 5px;width: 41.6666666666%;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="normalTd">Drum</th>
                                            <th class="normalTd">Gallon</th>
                                            <th class="normalTd">Quarter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="normalTd"><?=number_format((float)$totalDrum, 2, '.', '')?></td>
                                            <td class="normalTd"><?=number_format((float)$totalGallon, 2, '.', '')?></td>
                                            <td class="normalTd"><?=number_format((float)$totalQuarter, 2, '.', '')?></td>
                                        </tr>
                                        <tr>
                                            <td class="normalTd"><?=number_format((float)$totalDrum * 14.56, 2, '.', '')?></td>
                                            <td class="normalTd"><?=number_format((float)$totalGallon * 3.64, 2, '.', '')?></td>
                                            <td class="normalTd"><?=number_format((float)$totalQuarter * 0.9, 2, '.', '')?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-3 inlineDiv" style="padding-left: 0px;width: 25%;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center normalTd">Total Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="normalTd normalTd"><?=number_format((float)(number_format((float)$totalDrum, 2, '.', '') + number_format((float)$totalGallon, 2, '.', '') + number_format((float)$totalQuarter, 2, '.', '')), 2, '.', '')?></td>
                                        </tr>
                                        <tr>
                                            <td class="normalTd normalTd"><?=number_format((float)(number_format((float)$totalDrum * 14.56, 2, '.', '') + number_format((float)$totalGallon * 3.64, 2, '.', '') + number_format((float)$totalQuarter * 0.9, 2, '.', '')), 2, '.', '')?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-2 inlineDiv" style="padding-left: 0px;text-align: right;width: 16.6666666666%;position: absolute;bottom: 20px;">
                                <div>
                                    <span class="header">Total :</span>
                                </div>
                                <div>
                                    <span class="header">Less Disc :</span>
                                </div>
                                <div>
                                    <span class="header">Invoice Value :</span>
                                </div>
                            </div>
                            <div class="col-md-2 inlineDiv" style="padding-left: 0px;text-align: right;width: 13.6666666666%;position: absolute; bottom: 20px;right: 0px;">
                                <div>
                                    <span class="header"><?=$subTotal_ammount?></span>
                                </div>
                                <div>
                                    <span class="header"><?=number_format($totalDiscount,2)?></span>
                                </div>
                                <div>
                                    <span class="header"><?=number_format($netAmount,2)?></span>
                                </div>
                            </div>
                        </div>

                        <style type="text/css">
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
                            }
                            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                                border: none;
                                border-right: 1px solid #e4e5e7;
                            }
                            hr{
                                border-top: 3px solid #e1e6ef;
                            }
                            .normalTd{
                                border: 1px solid #e4e5e7 !important;
                                text-align: center;
                            }
                            .inlineDiv{
                                display: inline-block;
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
                            }
                        </style>
                        <div class="row">

                            <div class="col-xs-4" style="display: inline-block;width: 33.333333333%">


                                <div  style="float:left;width:65%;text-align:center;border-top:1px solid #e4e5e7;margin-top: 110px;font-weight: bold;">
                                    <span>Generated By</span>
                                </div>
                            </div>
                            <div class="col-xs-4" style="display: inline-block;width: 33.333333333%">


                                <div  style="float:left;width:70%;text-align:center;border-top:1px solid #e4e5e7;margin-top: 110px;font-weight: bold;">
                                    <span>Checked By</span>
                                </div>
                            </div>
                            <div class="col-xs-4" style="display: inline-block;width: 33.3333333333%;">


                                <div  style="float:right;width:65%;text-align:center;border-top:1px solid #e4e5e7;margin-top: 110px;font-weight: bold;">
                                    <span>Customer Sign</span>
                                </div>

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
