<!-- Stock report start -->
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        document.body.style.marginTop = "0px";
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
           <!--  <h1><?php echo display('supplier_ledger') ?></h1>
            <small><?php echo display('manage_supplier_ledger') ?></small> -->
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('payment') ?></a></li>
                <li class="active"><?php echo display('cheque') ?></li>
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
                        <form action="<?=base_url('Cpayment/cheque_status')?>" class="form-inline" method="get" accept-charset="utf-8">
                                                        <div class="form-group">
                                <label class="" for="from_date">Start Date</label>
                                <input type="date" name="from_date" class="form-control datepicker hasDatepicker" id="from_date" value="<?=$start_date?>" placeholder="Start Date">
                            </div> 

                            <div class="form-group">
                                <label class="" for="to_date">End Date</label>
                                <input type="date" name="to_date" class="form-control datepicker hasDatepicker" id="to_date" placeholder="End Date" value="<?=$end_date?>">
                            </div>  
                            <div class="form-group">
                                <label class="" for="bank_detail">Bank Name</label>
                                <select class="form-control" id='bank_name' name="bank_name">
                                    <option value="ALL">ALL</option>
                                    <? for($i=0;$i<count($bank_detail);$i++){
                                        echo "<option value='".$bank_detail[$i]['bank_name']."'>".$bank_detail[$i]['bank_name']."</option>";
                                    }?>
                                </select>
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
                            <h4><?php echo "Cheque Payment" ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="printableArea" style="margin-left:2px;">

                            

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo "Cheque Date"; ?></th>
                                            <th class="text-center"><?php echo display('cheque_no') ?></th>
                                            <th class="text-center"><?php echo display('bank_name') ?></th>
                                          
                                            <th class="text-center"><?php echo display('debit') ?></th>
                                            <th class="text-center"><?php echo display('credit') ?></th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    		
                                        <?php

                                       // print_r($cheque_detail);
                                        $SubTotal_debit = 0;
                                        $SubTotal_credit = 0;
                                        $x=0;

                                        if ($cheque_detail) {
                                            foreach ($cheque_detail as $ledger) {

                                            	$debit = 0;$credit=0;

                                            	if($ledger['pay_amount'])
                                            		$credit = $ledger['pay_amount'];
                                            	if($ledger['amount'])
                                            		$debit = $ledger['amount'];

                                                ?>
                                                <tr>
                                                    <td><?php echo $x+1;$x++; ?></td>
                                                    <td><?php echo $ledger['cheque_date'] ?></td>
                                                    <td><?php echo $ledger['cheque_no'] ?></td>
                                                    <td><?php echo $ledger['bank_name'] ?></td>
                                                   
                                                    <td>
                                                        <?php
                                                        if ($debit) {
                                                            echo  $ledger['amount'] ;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        if ($credit) {
                                                            echo  $ledger['pay_amount'] ;
                                                        }
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
                                        
                                        
                                    </tfoot>
                                </table>
                               
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