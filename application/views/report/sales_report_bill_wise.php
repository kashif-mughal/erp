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

			<h1>Bill wise sales report</h1>

			<small>Bill wise sales report</small>

			<ol class="breadcrumb">

				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>

				<li><a href="#"><?php echo display('report') ?></a></li>

				<li class="active">Sales Report (Bill Wise)</li>

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

			<div class="col-sm-8">

				<div class="panel panel-default">

					<div class="panel-body"> 

						<?php echo form_open('Admin_dashboard/date_to_date_invoice',array('class' => 'form-inline','method' => 'get'))?>

						<?php date_default_timezone_set("Asia/Dhaka"); $today = date('Y-m-d'); ?>

						<div class="form-group">

							<label class="" for="from_date"><?php echo display('start_date') ?></label>

							<input type="text" name="from_date" class="form-control datepicker" id="from_date" value="<?php echo $today?>" placeholder="<?php echo display('start_date') ?>" >

						</div> 

						<div class="form-group">

							<label class="" for="to_date"><?php echo display('end_date') ?></label>

							<input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $today?>">

						</div>  


						<span style="float: right;">
							<button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
							<a class="btn btn-warning" href="#" onclick="printDiv('printDiv')">Print</a>
						</span>	                  

						<?php echo form_close()?>

					</div>

				</div>

			</div>
			<div class="col-sm-4">

		        <div class="panel panel-default">

		            <div class="panel-body"> 



						<form action="<?php echo base_url('Admin_dashboard/sales_report_bill_wise_search_by_invoice_id')?>" class="form-inline" method="post" accept-charset="utf-8">

		                    <input type="text" class="form-control" name="invoice_no" placeholder="<?php echo display('invoice_no') ?>">

							<button type="submit" class="btn btn-primary btn-sm"><?php echo display('search')?></button>     

			            </form>		



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

							<h4>Bill wise sales report</h4>

						</div>

						<span class="text-right">

							<?php echo form_open('Admin_dashboard/sales_report_bill_wise_search_by_user_name',array('class' => 'form-inline','method' => 'get'))?>

							

							<div class="form-group">

								

								<input type="text"   name="customer_name" class="customerSelection form-control" placeholder='<?php echo display('customer_name') ?>' id="customer_name" tabindex="1" />



								<input id="SchoolHiddenId" class="customer_hidden_value abc" type="hidden" name="customer_id">

							</div>  



							<button type="submit" class="btn btn-success"><?php echo display('search') ?></button>

							

							<?php echo form_close()?>

						</span>

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

										<th>Inv. No</th>

										<th><?php echo display('date') ?></th>

										<th>Buyer Name</th>

										<th><?php echo display('total_amount') ?></th>

									</tr>

								</thead>

								<tbody>

									<?php

									if ($invoices_list) {

										?>

										{invoices_list}

										<tr>

											<td>{sl}</td>

											<td>

												{invoice}

											</td>


											<td>{final_date}</td>

											<td>

												{customer_name}

											</td>

											<td style="text-align: right;"><?php echo (($position==0)?"$currency {total_amount}":"{total_amount} $currency") ?></td>


										</tr>

										{/invoices_list}

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