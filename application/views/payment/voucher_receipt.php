<!-- Purchase Payment Ledger Start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	document.body.style.marginTop="0px";
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
	        <h1><?php echo display('purchase_ledger') ?></h1>
	        <small><?php echo display('purchase_ledger') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('purchase') ?></a></li>
	            <li class="active"><?php echo display('purchase_ledger') ?></li>
	        </ol>
	    </div>
	</section>

	<!-- Invoice information -->
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
                
                  <a href="<?php echo base_url('Cpurchase')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_purchase')?> </a>

                  <a href="<?php echo base_url('Cpurchase/manage_purchase')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_purchase')?> </a>

                </div>
            </div>
        </div>

		

		<!-- Purchase Ledger -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		           
		                <div class="panel-title">
		                    <h4><?php echo display('voucher_information') ?></h4>
		                </div>
		               <!--  
		            -->
		            <div class="panel-body">
		            	<div class="text-right">
		                    <a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
		                </div>
		                <div id="printableArea" style="margin-left:2px;">
		                
		                	
		                <div class="table-responsive">
		                	<table width="100%"  class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th></th>
										<th colspan="3" class="text-center">Payment Voucher</th>
							
										<th colspan="1">&nbsp;</th>
									</tr>
									
									<tr>
										<th><?php echo display('date') ?></th>
										<th class="text-right"><?=date("d-m-Y",strtotime($final_date))?></th>
											<th  class="text-center"><?=$person_info['name']?></th>
										<th  class="text-right">V #</th>
										<th>{voucher_no}</th>
									</tr>
									<tr><td colspan="4"></td></tr>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('name') ?></th>
										<th colspan='2'><?php echo display('description') ?></th>
										<th><?php echo display('total_ammount') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($voucher_details) {

										for($i=0;$i<count($voucher_details);$i++){
								?>

								
									<tr>
										<td><?=$voucher_details[$i]['sl'];?></td>
										<td>
											<a href="#">
											<?=$voucher_details[$i]['transection_mode']?>
											</a>
										</td>
										<td style="text-align: right" colspan='2'><?=$voucher_details[$i]['description']?></td>
										<td style="text-align: right;"><?=number_format($voucher_details[$i]['amount'], 2, '.', ',');?></td>
										
									</tr>
								  
								<?php
									}
									}
								?>
								</tbody>
								<tfoot>
									<tr>
										<td style="text-align:right" colspan="4"><b><?php echo display('grand_total') ?>:</b></td>
										<td  style="text-align:right;padding-right:20px !important;"><b><?php echo number_format($total_amount, 2, '.', ','); ?></b></td>
									</tr>

									<tr>
										<td colspan='5'>Amount in words: <b><?php echo ucwords(getPKRCurrency($total_amount));?></b> </td>
										
									</tr>
									<tr>
										<td colspan='2'>Account Officer</td>
										<td colspan='2' class="text-center">Admin Officer</td>
										<td colspan='1'>Reciver</td>
										
									</tr>
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
<!-- Purchase ledger End  -->
<?php

function getPKRCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
}


?>