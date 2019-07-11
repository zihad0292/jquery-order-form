(function($){
    'use strict';

    var prod_01_variant_01_price = 17;
    var prod_01_variant_02_price = 31;
    var prod_01_variant_03_price = 45;
    var prod_02_variant_01_price = 50;
    var prod_03_variant_01_price = 16;
    var prod_03_variant_02_price = 50;
    var total1 = 0;
    var total2 = 0;
    var total3 = 0;
    var total4 = 0;
    var total5 = 0;
    var total6 = 0;
    var totalCartValue = 0;

    $('.product-summary-row').css({
        "display": "-webkit-box",
        "display": "-ms-flexbox",
        "display": "flex"
    });

    $('.product-quantity input').on('focus', function(){
        if($(this).val() == 0){
            $(this).val('');
        }
        $('.product-quantity input').on('blur', function(){
            if($(this).val() == ''){
                $(this).val(0);
            }
        });
    });
    $('.product-quantity input').on('keyup', function(){
        var $this = $(this);
        var elementID = $this.attr('id');
        var newValue = +$this.val();
        if(isNaN(newValue) || newValue < 0 || newValue >9){
            alert('Please enter a number between 1 and 9');
            $this.val(0);
            updateCart(elementID, product_id_total);
        }else{
            $this.blur();
            var product_id_total = eval(elementID + '_price') * newValue;
            updateCart(elementID, product_id_total);
        }  
    });

    function updateCart(elementID){

        var product_id_qty = +$('#' + elementID).val();
        var product_id_price = eval(elementID + '_price');
        var product_id_summary = $('#' + elementID + '_summary');
        var product_id_summary_qty = $('#' + elementID + '_summary .col-2 span');
        var product_id_summary_price = $('#' + elementID + '_summary .col-3 span');
        var product_id_total = product_id_qty * product_id_price;

        if(product_id_total>0){
            $(product_id_summary_qty).text('x'+ product_id_qty);
            $(product_id_summary_price).text(product_id_total + ' Euro');
            product_id_summary.css({
                "display": "-webkit-box",
                "display": "-ms-flexbox",
                "display": "flex"
            });
        }else{
            product_id_summary.hide();
        }       

        updateTotal(elementID, product_id_total);
        
    }

    function updateTotal(elementID, product_id_total){
        if(elementID == "prod_01_variant_01"){
            total1 = product_id_total;
        }else if(elementID == "prod_01_variant_02"){
            total2 = product_id_total;
        }else if(elementID == "prod_01_variant_03"){
            total3 = product_id_total;
        }else if(elementID == "prod_02_variant_01"){
            total4 = product_id_total;
        }else if(elementID == "prod_03_variant_01"){
            total5 = product_id_total;
        }else if(elementID == "prod_03_variant_02"){
            total6 = product_id_total;
        }

        totalCartValue = total1+total2+total3+total4+total5+total6;
        var vatValue = (totalCartValue * 7)/100;
        vatValue = Math.round(vatValue * 100) / 100;
        $('#total_amount span').text(totalCartValue + ' Euro');
        $('.vat_amount').text(vatValue);
    }


    var flag = 0;
    $('#order-form-id .form-submit-button .btn').on('click', function(event){

        event.preventDefault();       

        var all_products = $('.single-product.row');
        $(all_products).each(function(index,element){
            var $inputField = $(element).find('.product-quantity input');
            if($inputField.val()>0){
                flag = 1;
            }
        });

        if(flag == 1){
            var form = $('#order-form-id');

            if($('#email').val() == ''){
                $('#email').css('border', '1px solid red');
                alert('Please enter an email address');
            }
            else{
                form.submit();
            }
            
        }else{   
            alert('Please add products to the cart first.');    
        }
        
    });

    

})(jQuery);

