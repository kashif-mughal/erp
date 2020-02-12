<!-- Stock report start -->
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        //printContents.style.fontSize = "10px";
        
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        document.body.style.marginTop = "0px";
        document.body.style.fontSize = "10px";
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<!-- Supplier Ledger Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('supplier_ledger') ?></h1>
            <small><?php echo display('manage_supplier_ledger') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('supplier') ?></a></li>
                <li class="active"><?php echo display('supplier_ledger') ?></li>
            </ol>
        </div>
    </section>

    <!-- Supplier information -->
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
                <div class="panel panel-default">
                    <div class="panel-body"> 
                        <form action="<?=base_url('Csupplier/supplier_detail_ledger/'.$supplier_id)?>" class="form-inline" method="get" accept-charset="utf-8">
                                                        <div class="form-group">
                                <label class="" for="from_date">Start Date</label>
                                <input type="date" name="from_date" class="form-control datepicker hasDatepicker" id="from_date" value="<?=$start_date?>" placeholder="Start Date">
                            </div> 

                            <div class="form-group">
                                <label class="" for="to_date">End Date</label>
                                <input type="date" name="to_date" class="form-control datepicker hasDatepicker" id="to_date" placeholder="End Date" value="<?=$end_date?>">
                            </div>  

                            <button type="submit" class="btn btn-success">Search</button>

                        </form>	
                    </div>
                </div>
            </div>
        </div>

      
        

        <!-- Supplier ledger -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('supplier_ledger') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="text-right">
                            <a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                        </div>
                        <div id="printableArea" style="margin-left:2px;">

                            <?php if ($supplier_name) { ?>
                                <div class="text-center">
                                    <h3> {supplier_name} </h3>
                                    <h4><?php echo display('address') ?> : {address} </h4>
                                    <h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                                </div>
                            <?php } ?>

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                           
                                            <th class="text-center" colspan='5'><?=$supplier_info->supplier_name; ?> From (<?=date("d-m-Y",strtotime($start_date))?>) - To (<?=date("d-m-Y",strtotime($end_date))?>)</th>
                                          
                                           
                                           
                                        </tr>
                                        <tr>
                                            <th class="text-center"><?php echo display('date') ?></th>
                                            <th class="text-center"><?php echo display('description') ?></th>
                                          
                                            <th class="text-center"><?php echo display('debit') ?></th>
                                            <th class="text-center"><?php echo display('credit') ?></th>
                                            <th class="text-center"><?php echo display('balance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    		<tr>
                                    			<td>-</td>
                                    			<td>Opening Balance</td>
                                    			<td>-</td>
                                    			
                                    			<td>-</td>
                                    			<td><?=$openingbalance?></td>
                                    		</tr>
                                        <?php

                                        $SubTotal_debit = 0;
                                        $SubTotal_credit = 0;
                                        if ($current_transaction) {
                                            foreach ($current_transaction as $ledger) {

                                            	$debit = 0;$credit=0;

                                            	if($ledger['deposit_no'])
                                            		$credit = $ledger['amount'];
                                            	if($ledger['chalan_no'])
                                            		$debit = $ledger['amount'];

                                                ?>
                                                <tr>
                                                    <td><?php echo date("d-m-Y",strtotime($ledger['date'])) ?></td>
                                                    <td><?php echo $ledger['description'] ?>
                                                    	<?php if($ledger['deposit_no']) echo " - ".$ledger['deposit_no']; ?>
                                                    	<?php if($ledger['chalan_no']) echo " Purchase - ".$ledger['chalan_no']; ?>
                                                    </td>
                                                   
                                                    <td>
                                                        <?php
                                                        if ($debit) {
                                                            echo (($position == 0) ? $currency . " " . $ledger['amount'] : $ledger['amount'] . " " . $currency);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        if ($credit) {
                                                            echo (($position == 0) ? $currency . " " . $ledger['amount'] : $ledger['amount'] . " " . $currency);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        echo $openingbalance = $openingbalance + $debit -  $credit;

                                                        $SubTotal_debit += $debit; 
                                                        $SubTotal_credit += $credit;


                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }

                                                        
                                        $total_amount = $openingbalance + $forward_transaction;
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" align="right"><b><?php echo "SubTotal" ?>:</b></td>
                                            <td align="right"><b><?php echo (($position == 0) ? "$currency $SubTotal_debit" : "{SubTotal_debit} $currency") ?></b></td>
                                            <td align="right"><b><?php echo (($position == 0) ? "$currency $SubTotal_credit" : "{SubTotal_credit} $currency") ?></b></td>
                                            <td align="right"><b>-</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="right"><b><?php echo display('grand_total') ?>:</b></td>
                                            <td align="right"><b>-</td>
                                            <td align="right"><b>-</b></td>
                                            <td align="right"><b><?php echo (($position == 0) ? "$currency $total_amount" : "{total_amount} $currency") ?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="text-right"><?php echo $links ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Supplier Ledger End  -->
<script type="text/javascript">
    $('#purpose').on('change', function () {
        var x = 0;
        if (this.value > x) {
            $("#datebetween").show();
        } else {
            $("#datebetween").hide();
        }
    });
</script>