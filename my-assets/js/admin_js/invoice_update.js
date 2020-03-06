//Add Input Field Of Row
var currentCustomerInformation;
var tableName = "normalinvoice";
var isAllowDiscountChange = false;
var isAllowRateChange = false;
var isUserInfoLoaded = false;
var credit_limit_text = `<span class="col-sm-8 col-form-label" id="limit-exced-text" style="color:#f1c40f;padding: 5px 30px; text-shadow: 2px 9px 18px #f1c40f, 0px -5px 35px rgba(255,255,255,0.3);">Grand total exceeding the credit limit. <i>*</i></span>`;
if(window.location.href.indexOf('Cinvoice/sale_order') != -1){
    tableName = "addinvoice";
}
function addInputField(t, rowData = null) {
    var row = parseInt($("#" + tableName + " tbody tr").last().attr("rowid"));
    if(!row)
        row=1;
    if(window.location.href.indexOf('Cinvoice/sale_order') != -1){
        row = $("#" + tableName + " tbody tr").length;
    }
    var count = row + 1;
    var limits = 500;
    if (count == limits)
        alert("You have reached the limit of adding " + count + " inputs");
    else {
        var a = "product_name" + count,
                tabindex = count * 5,
                e = document.createElement("tr");
        e.setAttribute("rowid", count);
        tab1 = tabindex + 1;
        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tabindex + 6;
        if(rowData)
            e.classList.add('row-from-sale-order');
        var innerHTMLValue = "<td><input type='text' name='product_name' onkeypress='invoice_productList(" + count + ");' class='form-control productSelection' placeholder='Product Name' id='" + a + "' required tabindex='" + tab1 + "'";
        var productName="";
        if(rowData && rowData.product_id)
            var desiredProduct = productList.filter(function(each){return each.value == rowData.product_id});
        if(desiredProduct && desiredProduct.length > 0)
            productName = desiredProduct[0].label;
        if(rowData)
            innerHTMLValue += ` value='${productName}'`;
        innerHTMLValue += "><input type='hidden' class='product_uuid' name='product_uuid[]' id='product_uuid[]'><input type='hidden' class='autocomplete_hidden_value  product_id_" + count + "' name='product_id[]' id='SchoolHiddenId'"
        if(rowData && rowData.product_id)
            innerHTMLValue += ` value='${rowData.product_id}'`;
        innerHTMLValue += "/></td>   <td><input type='text' name='available_quantity[]' id='' class='form-control text-right available_quantity_" + count + "' value='0' readonly='readonly' /></td><td><input class='form-control text-right unit_" + count + " valid' value='None' readonly='' aria-invalid='false' type='text'></td><td> <input type='text' name='product_quantity[]' required='required' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='total_qntt_" + count + "' class='total_qntt_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab2 + "'";
        if(rowData && rowData.remaining_quantity)
            innerHTMLValue += ` value='${rowData.remaining_quantity}'`;
        innerHTMLValue += "/></td><td><input type='text' name='product_rate[]' onkeyup='quantity_calculate(" + count + ");'  onkeydown='change_rate(event);' isallowchangeprice=\"false\" onchange='quantity_calculate(" + count + ");' id='price_item_" + count + "' class='price_item" + count + " form-control text-right' required placeholder='0.00' min='0'";
        if(rowData && rowData.rate)
            innerHTMLValue += ` value='${rowData.rate}'`;
        innerHTMLValue += "/></td><td><input type='text' name='discount[]'";
        if(currentCustomerInformation && currentCustomerInformation[0]){
            var customerDiscount = parseInt(currentCustomerInformation[0].discount);
            if(currentCustomerInformation[0].discount && !isNaN(customerDiscount)){
                innerHTMLValue +=  ` value= "${customerDiscount}"`;
            }
        }
        innerHTMLValue += " onkeyup='quantity_calculate(" + count + ");' onkeydown='change_discount(event);' onchange='quantity_calculate(" + count + ");' id='discount_" + count + "' class='form-control text-right' placeholder='0.00' min='0' ";
        if(rowData && rowData.discount_per)
            innerHTMLValue += ` value='${rowData.discount_per}'`;
        innerHTMLValue += "/><input type='hidden' value='' name='discount_type' id='discount_type_" + count + "'></td><td class='text-right'><input class='total_price form-control text-right' type='text' name='total_price[]' id='total_price_" + count + "' value='0.00' readonly='readonly'/></td><td><input type='hidden' id='total_tax_" + count + "' class='total_tax_" + count + "' /><input type='hidden' id='all_tax_" + count + "' class=' total_tax' name='tax[]'/><input type='hidden'  id='total_discount_" + count + "' class='total_tax_" + count + "' /><input type='hidden' id='all_discount_" + count + "' class='total_discount' name='discount_amount[]'/><button style='text-align: right;' class='btn btn-danger' type='button' value='Delete' onclick='deleteRow(this)'>Delete</button></td>";
        e.innerHTML = innerHTMLValue;
                document.getElementById(t).appendChild(e),
                document.getElementById(a).focus(),
                document.getElementById("add_invoice_item").setAttribute("tabindex", tab3);
        document.getElementById("paidAmount").setAttribute("tabindex", tab4);
        document.getElementById("full_paid_tab").setAttribute("tabindex", tab5);
        document.getElementById("add_invoice").setAttribute("tabindex", tab6);
        if(rowData && rowData.product_id){
            triggerProductSelectionFirstTime(count, rowData.product_id);
        }
        count++
    }
}
function change_discount(e){
    if(e.keyCode != 9 && !isAllowDiscountChange){
        if(confirm("are you sure you want to change the discount amount?")){
            isAllowDiscountChange = true;
            return true;
        }
        else
            e.preventDefault();
    }
}
function change_rate(e){
    if(e.keyCode != 9 && !isAllowRateChange){
        if(confirm("are you sure you want to change the rate?")){
            isAllowRateChange = true;
            return true;
        }
        else
            e.preventDefault();
    }
}

// function change_price(e){
//     if(e.currentTarget.getAttribute("isallowchangeprice") == "false"){
//         if(confirm("Are you sure you want to change the price?")){
//             e.currentTarget.setAttribute("isallowchangeprice", "true");
//             return true;
//         }
//         else
//             e.preventDefault();
//     }
    
// }
function triggerProductSelectionFirstTime(cName, productId){
    var priceClass = 'price_item' + cName;

    var available_quantity = 'available_quantity_' + cName;

    var unit = 'unit_' + cName;

    var tax = 'total_tax_' + cName;

    var discount_type = 'discount_type_' + cName;
    var id = productId;

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
                var obj = jQuery.parseJSON(data);
                $('.'+priceClass).val(obj.price);
                //$('.'+available_quantity).val(obj.total_product.tofixed());
                $('.'+unit).val(obj.unit);
                $('.'+tax).val(obj.tax);
                $('#'+discount_type).val(obj.discount_type);
                quantity_calculate(cName);
            } 
        });
}
//Quantity calculat
function quantity_calculate(item) {
    if(!isUserInfoLoaded)
    {
        get_curent_customer_information();
        if(currentCustomerInformation && currentCustomerInformation[0]){
            var customerDiscount = parseInt(currentCustomerInformation[0].discount);
            if(currentCustomerInformation[0].discount && !isNaN(customerDiscount)){
                $("#discount_" + item).val(customerDiscount);
            }
        }
        isUserInfoLoaded = true;
    }
    var quantity = $("#total_qntt_" + item).val();
    var price_item = $("#price_item_" + item).val();
    var invoice_discount = $("#invoice_discount").val();
    var discount = $("#discount_" + item).val();
    var total_tax = $("#total_tax_" + item).val();
    var total_discount = $("#total_discount_" + item).val();
    var dis_type = $("#discount_type_" + item).val();

    if (quantity > 0 || discount > 0 || invoice_discount > 0) {
        if (dis_type == 1) {
            var price = quantity * price_item;
            
            // Discount cal per product
            var dis = +( price * discount / 100 )  +  +invoice_discount;
//            var dis = price * discount / 100;
            

            $("#all_discount_" + item).val(dis);

            //Total price calculate per product
            var temp = price - dis;
            $("#total_price_" + item).val(price);

            //Tax cal per product
            var tax = temp * total_tax;
            $("#all_tax_" + item).val(tax);
        } else if (dis_type == 2) {

            var price = quantity * price_item;

            // Discount cal per product
            var dis = discount * quantity;

            $("#all_discount_" + item).val(dis);

            //Total price calculate per product
            var temp = price - dis;
            $("#total_price_" + item).val(price);

            //Tax cal per product
            var tax = temp * total_tax;
            $("#all_tax_" + item).val(tax);
        } else if (dis_type == 3) {
            var total_price = quantity * price_item;

            // Discount cal per product
            $("#all_discount_" + item).val(discount);

            //Total price calculate per product
            var price = total_price - discount;
            $("#total_price_" + item).val(total_price);

            //Tax cal per product
            var tax = price * total_tax;
            $("#all_tax_" + item).val(tax);
        }
    } else {
        var n = quantity * price_item;
        var c = quantity * price_item * total_tax;
        $("#total_price_" + item).val(n),
                $("#all_tax_" + item).val(c)
    }
    calculateSum();
    invoice_paidamount();
}
//Calculate Sum
function calculateSum() {
    var t = 0,
            a = 0,
            e = 0,
            o = 0,
            p = 0;

    //Total Tax
    $(".total_tax").each(function () {
        isNaN(this.value) || 0 == this.value.length || (a += parseFloat(this.value))
    });debugger;
    $("#total_tax_ammount").val(Math.round(a));
    //Total Discount
    $(".total_discount").each(function () {
        isNaN(this.value) || 0 == this.value.length || (p += parseFloat(this.value))
    });
    $("#total_discount_ammount").val(Math.round(p));
    //Total Price
    $(".total_price").each(function () {
        isNaN(this.value) || 0 == this.value.length || (t += parseFloat(this.value))
    });
    o = Math.round(a);
    e = Math.round(t);
    f = Math.round(p);

    var test = +o + +e + -f;
    var grandTotalValue = Math.round(test);
    $("#grandTotal").val(grandTotalValue);
    get_curent_customer_information();
    if(currentCustomerInformation && currentCustomerInformation[0] && currentCustomerInformation[0].credit_limit){
        if(parseInt(grandTotalValue) > parseInt(currentCustomerInformation[0].credit_limit)){
            var parent = $("#" + tableName).parent();
            parent.attr('style','box-shadow: 1px -1px 14px #f1c40f;');
            if(!$("#limit-exced-text").length)
                parent.prepend(credit_limit_text);
            //$('#add_invoice').attr("disabled", true);
        }else{
            $("#" + tableName).parent().removeAttr('style');
            if($("#limit-exced-text").length)
                $('#limit-exced-text').remove();
            //$('#add_invoice').attr("disabled", false);
        }
    }
}
function get_curent_customer_information(){
    var currentCustomerId = $('#customer_id').val();
    if(typeof(customerInformation) != "undefined"){
    currentCustomerInformation = customerInformation.filter(function(each){
            return each.customer_id == currentCustomerId;
        });
    }
}

$(document).ready(function(){
    $('#customer_id').on("change", function(){
        isAllowDiscountChange = false;
        isAllowRateChange = false;
        get_curent_customer_information();
        if(currentCustomerInformation && currentCustomerInformation[0]){
            var customerDiscount = parseInt(currentCustomerInformation[0].discount);
            if(currentCustomerInformation[0].discount && !isNaN(customerDiscount)){
                var discountElems = $('input[name="discount[]"]');
                for(var i = 0; i < discountElems.length; i++){
                    var discountElem = $(discountElems[i]);
                    var discountId = discountElem.attr("id").split('_')[1];
                    discountElem.val(customerDiscount);
                    quantity_calculate(discountId);
                }
            }else{
                var discountElems = $('input[name="discount[]"]');
                for(var i = 0; i < discountElems.length; i++){
                    var discountElem = $(discountElems[i]);
                    var discountId = discountElem.attr("id").split('_')[1];
                    discountElem.val("");
                    quantity_calculate(discountId);
                }
            }
            change_current_balance(currentCustomerInformation[0]['customer_id']);
        }
    });
})
function change_current_balance(customer_id){
    $.ajax
       ({
            type: "POST",
            url: $('.baseUrl').val()+"Ccustomer/customer_balance",
            data: {customer_id:customer_id},
            cache: false,
            success: function(data)
            {
                $('#customer_balance').html(data);
            } 
        });    
}
//Invoice Paid Amount
function invoice_paidamount() {
    var t = $("#grandTotal").val(),
            a = $("#paidAmount").val(),
            e = t - a;
    $("#dueAmmount").val(Math.round(e))
}
//Stock Limit
function stockLimit(t) {
    var a = $("#total_qntt_" + t).val(),
            e = $(".product_id_" + t).val(),
            o = $(".baseUrl").val();
    $.ajax({
        type: "POST",
        url: o + "Cinvoice/product_stock_check",
        data: {
            product_id: e
        },
        cache: !1,
        success: function (e) {
            if (a > Number(e)) {
                var o = "You can purchase maximum " + e + " Items";
                alert(o), $("#qty_item_" + t).val("0"), $("#total_qntt_" + t).val("0"), $("#total_price_" + t).val("0")
            }
        }
    })
}

function stockLimitAjax(t) {
    var a = $("#total_qntt_" + t).val(),
            e = $(".product_id_" + t).val(),
            o = $(".baseUrl").val();
    $.ajax({
        type: "POST",
        url: o + "Cinvoice/product_stock_check",
        data: {
            product_id: e
        },
        cache: !1,
        success: function (e) {
            if (a > Number(e)) {
                var o = "You can purchase maximum " + e + " Items";
                alert(o), $("#qty_item_" + t).val("0"), $("#total_qntt_" + t).val("0"), $("#total_price_" + t).val("0.00"), calculateSum()
            }
        }
    })
}

//Invoice full paid
function full_paid() {
    var grandTotal = $("#grandTotal").val();
    $("#paidAmount").val(grandTotal);
    invoice_paidamount();
    calculateSum();
}

//Delete a row of table
function deleteRow(t) {
    var a = $("#normalinvoice > tbody > tr").length;
    if (1 == a)
        alert("There only one row you can't delete.");
    else {
        var e = t.parentNode.parentNode;
        e.parentNode.removeChild(e),
                calculateSum();
        invoice_paidamount();
    }
}
var count = 2,
        limits = 500;
//$('body').on('keyup', '#invoice_discount', function () {
//    var invoiceDis = $("#invoice_discount").val();
//    var ttlDis = $("#total_discount_ammount").val();
//    var xx = invoiceDis * ttlDis;
//    alert(xx);
//})