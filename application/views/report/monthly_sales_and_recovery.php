<!-- Customer js php -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/customer.js.php" ></script>
<!-- Product invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product_invoice.js.php" ></script>
<!-- Invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>
<script type="text/javascript">
	$.urlParam = function (name) {
		var results = new RegExp('[\?&]' + name + '=([^&#]*)')
		.exec(window.location.search);
		return (results !== null) ? results[1] || 0 : false;
	}
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		document.body.style.marginTop="0px";
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
<!-- Manage Invoice Start -->
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1>Monthly sales & recovery</h1>
			<small>Monthly sales & recovery</small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('report') ?></a></li>
				<li class="active">Monthly sales & recovery</li>
			</ol>
		</div>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="column">
					<a href="<?php echo base_url('Admin_dashboard/all_report')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('todays_report')?> </a>
					<a href="<?php echo base_url('Admin_dashboard/todays_purchase_report')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('purchase_report')?> </a>
					<a href="<?php echo base_url('Admin_dashboard/product_sales_reports_date_wise')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('sales_report_product_wise')?> </a>
					<a href="<?php echo base_url('Admin_dashboard/total_profit_report')?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('profit_report')?> </a>
				</div>
			</div>
		</div>
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
		<!-- date between search -->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body"> 
						<?php echo form_open('Admin_dashboard/date_to_date_monthly_sales_and_recovery',array('class' => 'form-inline','method' => 'get'))?>
						<?php date_default_timezone_set("Asia/Dhaka"); $today = date('Y-m-d'); ?>
						<div class="form-group">
							<label class="" for="from_date"><?php echo display('start_date') ?></label>
							<input type="text" name="from_date" class="form-control datepicker" id="from_date" value="<?php echo $today?>" placeholder="<?php echo display('start_date') ?>" >
						</div> 
						<div class="form-group">
							<label class="" for="to_date"><?php echo display('end_date') ?></label>
							<input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $today?>">
						</div>  

						<button type="submit" class="btn btn-success" style="float: right;"><?php echo display('search') ?></button>
						<a class="btn btn-warning" href="#" onclick="printDiv('printDiv')"  style="float: right;">Print</a>

						<?php echo form_close()?>
					</div>
				</div>
			</div>
		</div>
		<div class="row"> 
		</div>
		<!-- Manage Invoice report -->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-bd lobidrag">
					<div class="panel-heading">
						<div class="panel-title">
							<h4>Monthly sales & recovery</h4>
						</div>
					</div>
					<div class="panel-body" id="printDiv">
						<div class="text-center">
							{company_info}
							<h3> {company_name} </h3>
							<h4 >{address} </h4>
							{/company_info}
							<script>
								var dtFrom = $.urlParam('from_date');
								var dtUpto = $.urlParam('to_date');
								if(dtFrom != dtUpto){
									document.write('<p><b>From</b> ' + dtFrom + '  <b>To</b> ' + dtUpto + "</p>");
								}
							</script>
							<h4> <?php echo display('print_date') ?>: <?php echo date("Y-m-d h:i:s"); ?> </h4>
						</div>
						<div class="table-responsive">
							<table id="dataTableExample" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('date') ?></th>
										<th>Opening</th>
										<th>Gr.Sales</th>
										<th>S.Return</th>
										<th>Net Sales</th>
										<th>Dep.Slip</th>
										<th>Cash</th>
										<th>Pay Order</th>
										<th>Less</th>
										<th>Chq</th>
										<th>M_DB</th>
									</tr>
								</thead>
								<tbody id="sales-table">
									<?php
									if ($sales_list) {
										?>
										{sales_list}
										<tr class="each-row">
											<td><span>{sl}</span></td>

											<td><span>{date}</span></td>
											<td>
												<span><?php echo "$openingAmount"; ?></span>
											</td>
											<td>
												<span>
													<?php echo (($position==0)?"$currency {total_amount}":"{gross_sales} $currency") ?>
												</span>
											</td>
											<td>
												<span>
													<?php echo (($position==0)?"$currency {total_amount}":"{sale_return} $currency") ?>
												</span>
											</td>
											<td>
												<span>
													<?php echo (($position==0)?"$currency {total_amount}":"{net_sale} $currency") ?>
												</span>
											</td>
											<td>
												<span></span>
											</td>
											<td>
												<span></span>
											</td>
											<td>
												<span></span>
											</td>
											<td>
												<span></span>
											</td>
											<td>
												<span></span>
											</td>
											<td>
												<span></span>
											</td>

										</tr>
										{/sales_list}
										<?php
									}
									?>
								</tbody>
							</table>
							<div class="text-right"><?php echo $links?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Manage Invoice End -->

<!-- Delete Invoice ajax code -->

<script type="text/javascript">
	$(document).ready(function(){
		var allTrs = $('.each-row');
		var sumArray = [];
		for (var i = 1; i < 13; i++) {
			sumArray[i] = 0;
		}
		for (var i = 0; i < allTrs.length; i++) {
			for (var j = 4; j < 13; j++) {
				var temp = parseInt($($('.each-row')[i]).find(`td:nth-child(${j}) span`).text().replace(/[^0-9\.]/g, ''));
				sumArray[j] += !isNaN(temp) ? temp : 0;
				if(isNaN(sumArray[j])){
					debugger;
				}
			}
		}
		var appendHtml = "";
		appendHtml += "<tr>";
		for (var i = 1; i < 13; i++) {
			appendHtml += `<td><span></span></td>`;
		}
		appendHtml += "</tr><tr>";
		for (var i = 1; i < 13; i++) {
			if(i == 1 || i == 2 || i == 3)
				appendHtml += `<td><span></span></td>`;
			else
				appendHtml += `<td><span>${sumArray[i]}</span></td>`;
		}
		appendHtml += "</tr>";
		$("#sales-table").append(appendHtml);
	});
	
</script>
<script type="text/javascript">
	//Delete Invoice Item 
	$(".deleteInvoice").click(function()
	{	
		var invoice_id=$(this).attr('name');
		var csrf_test_name=  $("[name=csrf_test_name]").val();
		var x = confirm("Are You Sure,Want to Delete ?");
		if (x==true){
			$.ajax
			({
				type: "POST",
				url: '<?php echo base_url('Cinvoice/invoice_delete')?>',
				data: {invoice_id:invoice_id,csrf_test_name:csrf_test_name},
				cache: false,
				success: function(datas)
				{
					window.reload();
				} 
			});
		}
	});
</script>