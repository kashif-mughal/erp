<?php


?>
<!-- Customer js php -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/customer.js.php" ></script>
<!-- loan js php -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/loan.js.php" ></script>
<!-- Product invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product_invoice.js.php" ></script>
<!-- Invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/bootstrap-toggle.css">
<script src="<?php echo base_url()?>assets/js/bootstrap-toggle.min.js" type="text/javascript"></script>


<!-- Customer type change by javascript start -->
<script type="text/javascript">

    //Bank Information Show
    function bank_info_show(payment_type)
    {
        if(payment_type.value=="1"){
            document.getElementById("bank_info_hide").style.display="none";
        }
        else{ 
            document.getElementById("bank_info_hide").style.display="block";  
        }
    }

    //Active/Inactive customer
    function active_customer(status)
    {
        if(status=="payment_from_2"){
            document.getElementById("payment_from_2").style.display="none";
            document.getElementById("payment_from_1").style.display="block";
            document.getElementById("myRadioButton_2").checked = false;
            document.getElementById("myRadioButton_1").checked = true;
        }
        else{
            document.getElementById("payment_from_1").style.display="none";
            document.getElementById("payment_from_2").style.display="block";
            document.getElementById("myRadioButton_2").checked = false;
            document.getElementById("myRadioButton_1").checked = true;
        }
    }

</script>

<style type="text/css">
    
    .hide { display: none; }
    .show { display: block; }

</style>
<?php echo form_open('Cpayment/update_voucher_entry', array('class' => 'form-vertical','id' => 'validate'))?>
<input type="hidden" id='relation_id' name='relation_id' value="<?=$person_info['id']?>" />
<input type="hidden" id='voucher_id' name='voucher_id' value="<?=$voucher_details[0]['voucher_id']?>" />
<input type="hidden" id='transection_category' name='transection_category' value="<?=$voucher_details[0]['transection_category']?>" />
                   
<!-- Add new employee start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="fa fa-money"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('add_receipt') ?></h1>
            <small><?php echo display('add_new_receipt') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('transaction') ?></a></li>
                <li class="active"><?php echo display('add_receipt') ?></li>
            </ol>
        </div>
    </section>


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
                <div class="column">
                
                  <a href="<?php echo base_url('Cpayment/edit_voucher')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i><?php echo display('manage_transaction') ?></a>


                </div>
            </div>
        </div>

        <!-- New employee -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_receipt') ?></h4>
                        </div>
                    </div>
                        <div class="panel-body">
                        <?php $today = date('Y-m-d'); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo display('choose_transaction') ?></label>
                            <div class="switch col-sm-9">
                                
            <input type="radio" name="transectio_type" id="weekSW-0" value="1" <?php if($voucher_details[0]['transection_type'] == "1") echo "checked=checked";?> />
            <label for="weekSW-0" id="yes"><i class="fa fa-credit-card" aria-hidden="true"></i>
            <strong><?php echo display('payment') ?></strong></label>
            <input type="radio" name="transectio_type" id="weekSW-1" value="2"  <?php if($voucher_details[0]['transection_type'] == "2") echo "checked=checked";?>  />
            <label for="weekSW-1" id="no"><i class="fa fa-credit-card" aria-hidden="true"></i>
            <strong><?php echo display('receipt') ?></strong></label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="first_name" class="col-sm-2 col-form-label"><?php echo display('date') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-4">
                                <input class="form-control datepicker" name ="date" id="first_name" type="text" placeholder="Date"  required="" value="<?php echo date('Y-m-d') ?>">
                            </div>
                           
                            <label for="description" class="col-sm-2 col-form-label"><?php echo display('description') ?></label>
                            <div class="col-sm-4">
                                <textarea name="description" class="form-control" id="description" placeholder="Please Enter some description"></textarea> 
                            </div>
                        </div> 
                        <div class="form-group row">
                              <label for="first_name" class="col-sm-2 col-form-label">Name<i class="text-danger">*</i></label>
                             <div class="col-sm-4">
                              <?=$person_info['name']?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="Transection_mood" class="col-sm-2 col-form-label"><?php echo display('transaction_mood') ?></label>
                            <div class="col-sm-4">
                               <select onchange="bank_info_show(this)" name="payment_type" id="payment_type" class="form-control">
                                    <option value="1"> <?php echo display('cash') ?> </option>
                                    <option value="2"> <?php echo display('cheque') ?> </option>
                                    <option value="3"> <?php echo display('pay_order') ?> </option>
                                </select>
                            </div>

                            <!-- Payment amount div -->
                            <div style="<?=($voucher_details[0]['transection_type'] == '1')?'':'display:none'?>" id="PaymentContainer1"> 
                                <label for="first_name" class="col-sm-2 col-form-label"><?php echo display('payment_amount') ?></label>
                                <div class="col-sm-4">
                                <input class="none form-control " name ="pay_amount" id="payment" class="none" type="text" placeholder="Payment Amount" ></div>
                            </div>

                            <!-- Receipt amount div -->
                            <div style="<?=($voucher_details[0]['transection_type'] == '2')?'':'display:none'?>" id="PaymentContainer2">
                                <label for="first_name" class="col-sm-2 col-form-label"><?php echo display('receipt_amount') ?></label>
                                <div class="col-sm-4">
                                    <input class="none form-control " name ="amount" id="receipt" class="none" type="text" placeholder="<?php echo display('receipt_amount') ?>">
                                </div>
                            </div>
                        </div>

                        <div id="bank_info_hide">
                            <div class="form-group row">
                                <label for="cheque_or_pay_order_no" class="col-sm-2 col-form-label"><?php echo display('cheque_or_pay_order_no') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-4">
                                    <input type="test" id="cheque_or_pay_order_no" class="form-control"  name="cheque_no" placeholder="<?php echo display('cheque_or_pay_order_no') ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="date" class="col-sm-2 col-form-label"><?php echo display('date') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-4">
                                    <input type="text" name="cheque_mature_date" value="<?php echo $today; ?>" id="date"  class="datepicker form-control"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bank_name" class="col-sm-2 col-form-label"><?php echo display('bank_name') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-4">
                                    <select name="bank_name" id="bank_name"  class="form-control" style="width: 100%">
                                        <?php foreach($get_bank_list as $bank){  ?>
                                <option value="<?= $bank['bank_id']?>"><?= $bank['bank_name']?></option>
                            <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-10 col-form-label"></label>
                            <div class="col-sm-2" style="margin-top:5px ">
                              <!--   <input type="submit" id="add-customer" class="btn btn-success btn-large text-right" name="add-employee" value="<?php echo display('submit') ?>" /> -->
                                <input type="button" id="add-customer" onclick="MultiReceiptField('addRecieptItem')" class="btn btn-info btn-large text-right" name="add-employee" value="<?php echo display('add_new_item') ?>" />
                            </div>
                        </div>

                    </div>
                   
                </div>
            </div>
        </div>
    </section>
<section class="content">
    
   <div class="table-responsive panel panel-bd lobidrag" style="margin-top: 10px;">
    <div class="table-responsive">
                                <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo display('transaction_mood') ?></th>
                                            <th class="text-center"><?php echo display('description') ?></th>
                                            <th class="text-center"><?php echo display('receipt_amount') ?></th>
                                           
                                            <th class="text-center"><?php echo display('action') ?></th>
                                        </tr>
                                    </thead>

                                    <tbody id="addRecieptItem"></tbody>

                                          <?php
                                          $totalamount = 0;
                                        
                                          foreach ($voucher_details as $voucher) {
                                            $totalamount += $voucher['pay_amount'];
                                            # code...
                                            // echo "<pre>";
                                            // print_r($voucher);
                                            ?>

        <tr>
            <td class="span3 supplier">- </td>
              <td class="wt">

                <input type="hidden" name="amount_mode[]" id="amount_mode[]" value="<?=$voucher['transection_mood']?>" />
                <?php
                if($voucher['transection_mood'] == "1")
                    echo "Cash";
                else
                    echo "Cheque";

                ?>

            </td>
              <td class="text-right">
                <input type="hidden" name="amount_cheque_no[]" id="amount_cheque_no[]" value="">
                <input type="hidden" name="amount_cheque_date[]" id="amount_cheque_date[]" value="">
                <input type="hidden" name="amount_bank_name[]" id="amount_bank_name[]" value="">
                <input type="hidden" name="amount_description[]" id="amount_description[]" value="<?=$voucher['description']?>">
                <?=$voucher['description']?>
            </td>
              <td class="text-right">
                <input class="form-control total_price text-right total_price_1" type="text" name="total_price[]" id="total_price_1" value="<?=$voucher['pay_amount']?> " readonly="readonly">

            </td>
              <td> 
                <button style="text-align: right;" class="btn btn-danger red" type="button" value="Delete" onclick="deleteRow(this)" tabindex="8">Delete</button>
            </td>
          </tr>
                                         
                                             <?php
                                        
                                        }                         
                                            ?> 
                                           
                                     <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <!-- <input type="button" id="add_invoice_item" class="btn btn-info" name="add-invoice-item"  onClick="addPurchaseOrderField1('addPurchaseItem');" value="<?php echo display('add_new_item') ?>"  tabindex="9"/> -->

                                            <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url();?>"/>
                                        </td>
                                        <td style="text-align:right;" colspan="1"><b><?php echo display('grand_total') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" class="text-right form-control" name="grand_total_price" value="<?= $totalamount ?>" readonly="readonly" />
                                        </td>
                                    </tr>
                                </tfoot>
                                </table>

                            </div>


                           
                        </div>

                          <div class="form-group row pull-right" style=" ">
                            <div class="col-sm-12">
                                <input type="submit" id="add_purchase" class="btn btn-primary btn-large" name="add-purchase" value="<?php echo display('submit') ?>" />
                           <!--      <input type="submit" value="<?php echo display('submit_and_add_another') ?>" name="add-purchase-another" class="btn btn-large btn-success" id="add_purchase_another" > -->
                            </div>



                            </div>

                            
                        </section>
</div>
<!-- Add new customer end -->
 <?php echo form_close()?>

<script>

$(function() {
    $("#selId").on("change",function() {
       var selectId = $(this).children("option").filter(":selected").text();
       $(".hidid").hide();
       if(selectId=="Supplier")
       {
            $("#test1").show();
       }
       else if(selectId=="Customer")
       {
            $("#test2").show();
       }
       else if(selectId=="Office")
       {
            $("#test3").show();
       }
       
        else if(selectId=="salary")
       {
            $("#test3").show();
       }
     else if(selectId=="Loan")
       {
            $("#test4").show();
       }
    }).change();
});

 
</script>
<script type="text/javascript">
      $(document).change(function () {
    if ($('#weekSW-0').prop('checked')) {
        $('#PaymentContainer1').show();
    } else {
        $('#PaymentContainer1').hide();
    }

    if ($('#weekSW-1').prop('checked')) {
        $('#PaymentContainer2').show();
    } else {
        $('#PaymentContainer2').hide();
    }
});

</script>
<script type="text/javascript">
    
$('#weekSW-0').click(function () {
     $('#receipt').val($('#receipt').attr('value'));
 });

$('#weekSW-1').click(function () {
     $('#payment').val($('#payment').attr('value'));
 });
</script>

<script>
function myFunction() {
    var x = document.getElementById("selId").value;
    if(x==1){x='Supplier'}
        if(x==2){x='Customer'}
             if(x==3){x='Office'}
                 if(x==4){x='Loan'}
    document.getElementById("demo").innerHTML = "Select: " + x +" Name";
}

  var count = 1;
  var limits = 500;


function MultiReceiptField(divName){

  
        if (count == limits)  {
            alert("You have reached the limit of adding " + count + " inputs");
        }
        else{
            var newdiv = document.createElement('tr');
            var tabin="product_name_"+count;
             tabindex = count * 4 ,
           newdiv = document.createElement("tr");
            tab1 = tabindex + 1;
            
            tab2 = tabindex + 2;
            tab3 = tabindex + 3;
            tab4 = tabindex + 4;
            tab5 = tabindex + 5;
            tab6 = tab5 + 1;
            tab7 = tab6 +1;


        var amount =  0 ;
        if($("input[name='transectio_type']:checked"). val() == "1")
            amount = document.getElementById('payment').value;
        else
            amount = document.getElementById('receipt').value;
        
        var tMode = document.getElementById('payment_type').options[document.getElementById('payment_type').selectedIndex].text;

            //if(transectio_type)
            var description = "";
            if(tMode != "Cash"){

                var chequeNo = document.getElementById('cheque_or_pay_order_no').value;
                var chequeDate = document.getElementById('date').value;
                var bankName = document.getElementById('bank_name').options[document.getElementById('bank_name').selectedIndex].text;

                description  = "CHQ # " + chequeNo + ", CHQ Date: " + chequeDate   + " ( " + bankName   + " ) ;" ;


            }
            else
            {
                chequeNo = "";
                chequeDate = "";
                bankName = "";
            }

            description += " "+$('#description').val();


            newdiv.innerHTML ='<td class="span3 supplier">- </td>  <td class="wt"><input type="hidden" name="amount_mode[]" id="amount_mode[]" value="'+tMode+'" />'+tMode+' </td><td class="text-right"><input type="hidden" name="amount_cheque_no[]" id="amount_cheque_no[]" value="'+chequeNo+'" /><input type="hidden" name="amount_cheque_date[]" id="amount_cheque_date[]" value="'+chequeDate+'" /><input type="hidden" name="amount_bank_name[]" id="amount_bank_name[]" value="'+bankName+'" /><input type="hidden" name="amount_description[]" id="amount_description[]" value="'+description+'" />'+description+' </td><td class="text-right"><input class="form-control total_price text-right total_price_'+ count +'" type="text" name="total_price[]" id="total_price_'+ count +'" value="'+amount+'" readonly="readonly" /> </td><td> <button style="text-align: right;" class="btn btn-danger red" type="button" value="<?php echo display('delete')?>" onclick="deleteRow(this)" tabindex="8"><?php echo display('delete')?></button></td>';
            document.getElementById(divName).appendChild(newdiv);
               
            count++;

            grandTotal = 0;
            $('.total_price').each(function(){grandTotal += parseInt(this.value);})

            $('#grandTotal').val(grandTotal);
        }
    }
</script>