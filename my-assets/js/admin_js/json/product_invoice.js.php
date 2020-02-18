<?php

$cache_file = "product.json";

    header('Content-Type: text/javascript; charset=utf8');

?>

var productList = <?php echo file_get_contents($cache_file); ?> ; 


APchange = function(event, ui){

	$(this).data("autocomplete").menu.activeMenu.children(":first-child").trigger("click");

}

    function invoice_productList(cName) {

		var priceClass = 'price_item'+cName;

		var available_quantity = 'available_quantity_'+cName;

		var unit = 'unit_'+cName;

		var tax = 'total_tax_'+cName;

		var discount_type = 'discount_type_'+cName;



        $( ".productSelection" ).autocomplete(

		{

            source: productList,

			delay:300,

			focus: function(event, ui) {

				$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);

				$(this).val(ui.item.label);

				return false;

			},

			select: function(event, ui) {

				var elems = $('.product_uuid');
				for(counter = 0; counter < elems.length; counter++){
					if(elems[counter].value == ui.item.product_uuid)
					{
						event.preventDefault();
						$(this).val("");
						alert('This product already exists');
						return false;
					}
				}

				$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);

				$(this).parent().find(".product_uuid").val(ui.item.product_uuid);

				$(this).val(ui.item.label);
				
				var id=ui.item.value;

				var dataString = 'product_id='+ id;

				var base_url = $('.baseUrl').val();



				

				$.ajax

				   ({

						type: "POST",

						url: base_url+"Cinvoice/retrieve_product_data_inv",

						data: dataString,

						cache: false,

						success: function(data)

						{
debugger;
							var obj = jQuery.parseJSON(data);

							$('.'+priceClass).val(obj.price);

							//$('.'+available_quantity).val(obj.total_product.toFixed(2,2));

							$('.'+unit).val(obj.unit);

							$('.'+tax).val(obj.tax);

							$('#'+discount_type).val(obj.discount_type);

							

							//This Function Stay on others.js page

							quantity_calculate(cName);

							

						} 

					});

				

				$(this).unbind("change");

				return false;

			}

		});

		$( ".productSelection" ).focus(function(){

			$(this).change(APchange);

		

		});

    }





